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
import com.mymed.controller.core.manager.session.SessionManager;
import com.mymed.model.data.session.MSessionBean;
import com.mymed.utils.MLogger;

/**
 * Servlet implementation class SessionRequestHandler
 */
public class SessionRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	private SessionManager sessionManager;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * @throws ServletException
	 * @see HttpServlet#HttpServlet()
	 */
	public SessionRequestHandler() throws ServletException {
		super();

		try {
			sessionManager = new SessionManager();
		} catch (final InternalBackEndException e) {
			throw new ServletException("SessionManager is not accessible because: " + e.getMessage());
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
			String userID;
			if ((userID = parameters.get("userID")) == null) {
				handleError(new InternalBackEndException("missing id argument!"), response);
				return;
			}
			switch (code) {
				case READ :
					MSessionBean sessionBean;
					try {
						sessionBean = sessionManager.read(userID);
						setResponseText(getGson().toJson(sessionBean));
					} catch (final IOBackEndException e) {
						handleError(e, response);
						return;
					}
					break;
				case DELETE :
					sessionManager.delete(userID);
					MLogger.getLog().info("Session {} deleted -> LOGOUT", userID);
					break;
				default :
					handleError(new InternalBackEndException("SessionRequestHandler.doGet(" + code + ") not exist!"),
					        response);
					return;
			}

			super.doGet(request, response);
		} catch (final InternalBackEndException e) {
			MLogger.getLog().info("Error in doGet");
			MLogger.getDebugLog().debug("Error in doGet", e.getCause());
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
			switch (code) {
				case CREATE :
					sessionManager.create(parameters.get("userID"), parameters.get("ip"));
					MLogger.getLog().info("Session {} created -> LOGIN", parameters.get("userID"));
					break;
				case UPDATE :
					MSessionBean sessionBean;
					try {
						sessionBean = getGson().fromJson(parameters.get("session"), MSessionBean.class);
					} catch (final JsonSyntaxException e) {
						handleError(new InternalBackEndException("user jSon format is not valid"), response);
						return;
					}
					sessionManager.update(sessionBean);
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
