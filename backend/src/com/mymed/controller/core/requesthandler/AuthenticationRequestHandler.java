package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.authentication.AuthenticationManager;
import com.mymed.controller.core.manager.authentication.IAuthenticationManager;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MLogger;

/**
 * Servlet implementation class AuthenticationRequestHandler
 */
public class AuthenticationRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	private IAuthenticationManager authenticationManager;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#HttpServlet()
	 */
	public AuthenticationRequestHandler() throws ServletException {
		super();
		try {
			authenticationManager = new AuthenticationManager();
		} catch (final InternalBackEndException e) {
			throw new ServletException("AuthenticationManager is not accessible because: " + e.getMessage());
		}
	}

	/* --------------------------------------------------------- */
	/* extends HttpServlet */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
	        IOException {
		try {
			/** Get the parameters */
			final Map<String, String> parameters = getParameters(request);

			/** Get the method code */
			final RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			String login, password;
			if ((login = parameters.get("login")) == null || (password = parameters.get("password")) == null) {
				handleError(new InternalBackEndException("missing argument!"), response);
				return;
			}

			if (code == RequestCode.READ) {
				MUserBean userBean;
				try {
					userBean = authenticationManager.read(login, password);
					setResponseText(getGson().toJson(userBean));
				} catch (final IOBackEndException e) {
					handleError(e, response);
					return;
				}
			} else {
				handleError(new InternalBackEndException("ProfileRequestHandler.doGet(" + code + ") not exist!"),
				        response);
				return;
			}

			// TODO test if it works
			// switch (code) {
			// case READ:
			// MUserBean userBean;
			// try {
			// userBean = authenticationManager.read(login, password);
			// setResponseText(getGson().toJson(userBean));
			// } catch (IOBackEndException e) {
			// handleError(e, response);
			// return;
			// }
			// break;
			// default:
			// handleError(new
			// InternalBackEndException("ProfileRequestHandler.doGet(" + code +
			// ") not exist!"), response);
			// return;
			// }

			super.doGet(request, response);
		} catch (final InternalBackEndException e) {
			e.printStackTrace();
			handleError(e, response);
			return;
		}
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doPost(final HttpServletRequest request, final HttpServletResponse response)
	        throws ServletException, IOException {
		try {
			/** Get the parameters */
			final Map<String, String> parameters = getParameters(request);

			/** Get the method code */
			final RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			String authentication;
			if ((authentication = parameters.get("authentication")) == null) {
				handleError(new InternalBackEndException("missing authentication argument!"), response);
				return;
			}
			switch (code) {
				case CREATE :
					String user;
					if ((user = parameters.get("user")) == null) {
						handleError(new InternalBackEndException("missing user argument!"), response);
						return;
					}

					MUserBean userBean = null;
					try {
						userBean = getGson().fromJson(user, MUserBean.class);
						userBean.setSocialNetworkID("MYMED");
						userBean.setSocialNetworkName("myMed");
					} catch (final JsonSyntaxException e) {
						handleError(new InternalBackEndException("user jSon format is not valid"), response);
						return;
					}

					MAuthenticationBean authenticationBean = null;
					try {
						authenticationBean = getGson().fromJson(authentication, MAuthenticationBean.class);
					} catch (final JsonSyntaxException e) {
						handleError(new InternalBackEndException("authentication jSon format is not valid"), response);
						return;
					}

					MLogger.getLog().info("Trying to create a new user:\n {}", userBean.toString());

					userBean = authenticationManager.create(userBean, authenticationBean);

					MLogger.getLog().info("User created");

					setResponseText(getGson().toJson(userBean));
					break;
				case UPDATE :
					try {
						authenticationBean = getGson().fromJson(authentication, MAuthenticationBean.class);
					} catch (final JsonSyntaxException e) {
						handleError(new InternalBackEndException("authentication jSon format is not valid"), response);
						return;
					}

					String id;
					if ((id = parameters.get("id")) == null) {
						handleError(new InternalBackEndException("missing user argument!"), response);
						return;
					}

					MLogger.getLog().info("Trying to update authentication:\n {}", authenticationBean.toString());

					authenticationManager.update(id, authenticationBean);

					MLogger.getLog().info("Authentication updated!");

					break;
				default :
					handleError(new InternalBackEndException("ProfileRequestHandler.doPost(" + code + ") not exist!"),
					        response);
					return;
			}
			super.doPost(request, response);
		} catch (final InternalBackEndException e) {
			MLogger.getLog().info("Error in doPost operation");
			MLogger.getDebugLog().debug("Error in doPost operation", e.getCause());
			handleError(e, response);
			return;
		} catch (final IOBackEndException e) {
			MLogger.getLog().info("Error in doPost operation");
			MLogger.getDebugLog().debug("Error in doPost operation", e.getCause());
			handleError(e, response);
		}
	}
}
