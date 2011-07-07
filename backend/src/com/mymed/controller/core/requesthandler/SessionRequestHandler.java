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
import com.mymed.model.data.MSessionBean;

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
			this.sessionManager = new SessionManager();
		} catch (InternalBackEndException e) {
			throw new ServletException("SessionManager is not accessible because: " + e.getMessage());
		}
	}

	/* --------------------------------------------------------- */
	/* extends HttpServlet */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		try {
			/** Get the parameters */
			Map<String, String> parameters = getParameters(request);

			/** Get the method code */
			RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			String userID;
			if((userID = parameters.get("userID"))== null){
				handleInternalError(new InternalBackEndException("missing id argument!"), response);
				return;
			}
			switch (code) {
			case READ:
				MSessionBean sessionBean;
				try {
					sessionBean = sessionManager.read(userID);
					setResponseText(getGson().toJson(sessionBean));
				} catch (IOBackEndException e) {
					handleNotFoundError(e, response);
					return;
				}
				break;
			case DELETE:
				sessionManager.delete(userID);
				System.out.println("\nINFO: User " + userID
						+ " deleted!");
				break;
			default:
				handleInternalError(new InternalBackEndException("SessionRequestHandler.doGet(" + code + ") not exist!"), response);
				return;
			}
			super.doGet(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			handleInternalError(e, response);
			return;
		}
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		try {
			/** Get the parameters */
			Map<String, String> parameters = getParameters(request);

			/** Get the method code */
			RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			switch (code) {
			case CREATE:
				sessionManager.create(parameters.get("userID"), parameters.get("ip"));
				break;
			case UPDATE:
				MSessionBean sessionBean;
				try {
					sessionBean = getGson().fromJson(parameters.get("session"),
							MSessionBean.class);
				} catch (JsonSyntaxException e) {
					handleInternalError(new InternalBackEndException("user jSon format is not valid"), response);
					return;
				}
				sessionManager.update(sessionBean);
				break;
			default:
				handleInternalError(new InternalBackEndException("ProfileRequestHandler.doPost(" + code + ") not exist!"), response);
				return;
			}
			super.doPost(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			handleInternalError(e, response);
			return;
		} catch (IOBackEndException e) {
			e.printStackTrace();
			handleNotFoundError(e, response);
		}
	}

}
