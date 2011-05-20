package com.mymed.controller.core.services.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.services.manager.pubsub.ProfileManager;
import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;
import com.mymed.model.data.MUserBean;

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
			this.profileManager = new ProfileManager();
		} catch (InternalBackEndException e) {
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
	protected void doGet(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		try {
			/** Get the parameters */
			Map<String, String> parameters = getParameters(request);

			/** handle the request */
			RequestCode code = requestCodeMap.get(parameters.get("code"));

			String id;
			if((id = parameters.get("id"))== null){
				throw new ServletException("missing id argument!"); 
			}
			switch (code) {
			case READ:
				MUserBean userBean = profileManager.read(id);
				setResponseText(getGson().toJson(userBean));
				break;
			case DELETE:
				profileManager.delete(id);
				System.out.println("\nINFO: User " + id
						+ " deleted!");
				break;
			default:
				throw new ServletException("ProfileRequestHandler.doGet(" + code + ") not exist!");
			}
			super.doGet(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			throw new ServletException(e.getMessage());
		} catch (IOBackEndException e) {
			e.printStackTrace();
			throw new IOException(e.getMessage());
		}
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	protected void doPost(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		try {
			/** Get the parameters */
			Map<String, String> parameters = getParameters(request);

			/** handle the request */
			RequestCode code = requestCodeMap.get(parameters.get("code"));

			String user;
			if((user = parameters.get("user"))== null){
				throw new ServletException("missing user argument!"); 
			}
			switch (code) {
			case CREATE:
				MUserBean userBean = null;
				try {
					userBean = getGson().fromJson(user,
							MUserBean.class);
				} catch (JsonSyntaxException e) {
					e.printStackTrace();
					throw new ServletException("user jSon format is not valid");
				}
				System.out.println("\nINFO: trying to create a new user:\n" + userBean);
				userBean = profileManager.create(userBean);
				System.out.println("\nINFO: User created!");
				
				setResponseText(getGson().toJson(userBean));
				break;
			case UPDATE:
				try {
					userBean = getGson().fromJson(user,
							MUserBean.class);
				} catch (JsonSyntaxException e) {
					e.printStackTrace();
					throw new ServletException("jSon format is not valid");
				}
				System.out.println("\nINFO: trying to update user:\n" + userBean);
				profileManager.update(userBean);
				System.out.println("\nINFO: User updated!");
				break;
			default:
				throw new ServletException("ProfileRequestHandler.doPost(" + code + ") not exist!");
			}
			super.doPost(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			throw new ServletException(e.getMessage());
		} catch (IOBackEndException e) {
			e.printStackTrace();
			throw new IOException(e.getMessage());
		}
	}
}
