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
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));
			final String login = parameters.get("login");
			final String password = parameters.get("password");

			if (login == null || password == null) {
				handleError(new InternalBackEndException("missing argument!"), response);
			} else if (code == RequestCode.READ) {
				try {
					final MUserBean userBean = authenticationManager.read(login, password);
					setResponseText(getGson().toJson(userBean));

					super.doGet(request, response);
				} catch (final IOBackEndException e) {
					handleError(e, response);
				}
			} else {
				handleError(new InternalBackEndException("ProfileRequestHandler.doGet(" + code + ") not exist!"),
				        response);
			}
		} catch (final InternalBackEndException e) {
			MLogger.getLog().info("Error in doGet operation");
			MLogger.getDebugLog().debug("Error in doGet operation", e.getCause());
			handleError(e, response);
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
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));
			final String authentication = parameters.get("authentication");

			if (authentication == null) {
				handleError(new InternalBackEndException("Missing authentication argument!"), response);
			} else if (code == RequestCode.CREATE) {
				final String user = parameters.get("user");

				if (user == null) {
					handleError(new InternalBackEndException("Missing user argument!"), response);
				} else {
					try {
						MUserBean userBean = getGson().fromJson(user, MUserBean.class);
						userBean.setSocialNetworkID("MYMED");
						userBean.setSocialNetworkName("myMed");

						final MAuthenticationBean authenticationBean = getGson().fromJson(authentication,
						        MAuthenticationBean.class);

						MLogger.getLog().info("Trying to create a new user:\n {}", userBean.toString());
						userBean = authenticationManager.create(userBean, authenticationBean);
						MLogger.getLog().info("User created");

						setResponseText(getGson().toJson(userBean));

						super.doPost(request, response);
					} catch (final JsonSyntaxException e) {
						handleError(new InternalBackEndException("User/Authentication jSon format is not valid"),
						        response);
					}
				}
			} else if (code == RequestCode.UPDATE) {
				final String id = parameters.get("id");
				if (id == null) {
					handleError(new InternalBackEndException("Missing user argument!"), response);
				} else {
					try {
						final MAuthenticationBean authenticationBean = getGson().fromJson(authentication,
						        MAuthenticationBean.class);

						MLogger.getLog().info("Trying to update authentication:\n {}", authenticationBean.toString());
						authenticationManager.update(id, authenticationBean);
						MLogger.getLog().info("Authentication updated!");

						super.doPost(request, response);
					} catch (final JsonSyntaxException e) {
						handleError(new InternalBackEndException("Authentication jSon format is not valid"), response);
					}
				}
			} else {
				handleError(new InternalBackEndException("ProfileRequestHandler.doPost(" + code + ") not exist!"),
				        response);
			}
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
