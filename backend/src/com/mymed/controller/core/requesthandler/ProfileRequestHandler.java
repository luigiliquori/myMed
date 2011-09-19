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
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MLogger;

/**
 * Servlet implementation class UsersRequestHandler
 */
public class ProfileRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	private ProfileManager profileManager;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * @throws ServletException
	 * @see HttpServlet#HttpServlet()
	 */
	public ProfileRequestHandler() throws ServletException {
		super();

		try {
			profileManager = new ProfileManager();
		} catch (final InternalBackEndException e) {
			throw new ServletException("ProfileManager is not accessible because: " + e.getMessage());
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
			String id;
			if ((id = parameters.get("id")) == null) {
				handleError(new InternalBackEndException("missing id argument!"), response);
				return;
			}

			switch (code) {
				case READ :
					MUserBean userBean;
					try {
						userBean = profileManager.read(id);
						setResponseText(getGson().toJson(userBean));
					} catch (final IOBackEndException e) {
						handleError(e, response);
						return;
					}
					break;
				case DELETE :
					profileManager.delete(id);
					MLogger.getLog().info("User '{}' deleted", id);
					break;
				default :
					handleError(new InternalBackEndException("ProfileRequestHandler.doGet(" + code + ") not exist!"),
					        response);
					return;
			}

			super.doGet(request, response);
		} catch (final InternalBackEndException e) {
			MLogger.getLog().info("Error in doGet");
			MLogger.getDebugLog().debug("Error in doGet", e.getCause());
			handleError(e, response);
			return;
		} catch (final IOBackEndException e) {
			MLogger.getLog().info("Error in doGet");
			MLogger.getDebugLog().debug("Error in doGet", e.getCause());
			handleError(e, response);
		}
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
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
			String user;
			if ((user = parameters.get("user")) == null) {
				handleError(new InternalBackEndException("missing user argument!"), response);
				return;
			}
			switch (code) {
				case CREATE :
					MUserBean userBean = null;
					try {
						MLogger.getLog().info("User:\n", user);
						userBean = getGson().fromJson(user, MUserBean.class);
					} catch (final JsonSyntaxException e) {
						handleError(new InternalBackEndException("user jSon format is not valid"), response);
						return;
					}

					MLogger.getLog().info("Trying to create a new user:\n {}", userBean.toString());

					userBean = profileManager.create(userBean);

					MLogger.getLog().info("User created!");

					setResponseText(getGson().toJson(userBean));
					break;
				case UPDATE :
					try {
						userBean = getGson().fromJson(user, MUserBean.class);
					} catch (final JsonSyntaxException e) {
						handleError(new InternalBackEndException("user jSon format is not valid"), response);
						return;
					}

					MLogger.getLog().info("Trying to update user:\n {}", userBean.toString());

					userBean = profileManager.update(userBean);
					setResponseText(getGson().toJson(userBean));

					MLogger.getLog().info("User updated!");

					break;
				default :
					handleError(new InternalBackEndException("ProfileRequestHandler.doPost(" + code + ") not exist!"),
					        response);
					return;
			}

			super.doPost(request, response);
		} catch (final InternalBackEndException e) {
			MLogger.getLog().info("Error in doPost");
			MLogger.getDebugLog().debug("Error in doPost", e.getCause());
			handleError(e, response);
			return;
		} catch (final IOBackEndException e) {
			MLogger.getLog().info("Error in doPost");
			MLogger.getDebugLog().debug("Error in doPost", e.getCause());
			handleError(e, response);
		}
	}
}
