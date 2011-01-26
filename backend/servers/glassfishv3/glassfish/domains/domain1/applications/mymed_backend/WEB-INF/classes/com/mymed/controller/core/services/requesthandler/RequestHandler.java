package com.mymed.controller.core.services.requesthandler;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.Gson;
import com.mymed.controller.core.services.ServiceManager;
import com.mymed.model.datastructure.User;

/**
 * Handle all the request from the frontend
 * @author lvanni
 *
 */
public class RequestHandler extends HttpServlet implements IRequestHandler{
	private static final long serialVersionUID = 1L;

	private Gson gson;
	private ServiceManager serviceManager;
	
	/**
	 * @see HttpServlet#HttpServlet()
	 */
	public RequestHandler() {
		super();
		/** Google Gson
		 * see http://code.google.com/p/google-gson/
		 */
		this.gson = new Gson();
		this.serviceManager = new ServiceManager();
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		/** Init response */
		response.setContentType("text/html;charset=UTF-8");
		String result = "NULL"; // the feedBack for a bad request on the frontend is NULL

		/** Get the request parameters */
		Map<String, String> parameters = new HashMap<String, String>();
		Enumeration<String> paramNames = request.getParameterNames();
		while (paramNames.hasMoreElements()) {
			String paramName = (String) paramNames.nextElement();
			String[] paramValues = request.getParameterValues(paramName);
			if (paramValues.length >= 1) { // all the params should be atomic
				parameters.put(paramName, paramValues[0]);
			}
		}

		/** handle the request */
		if (parameters.containsKey("act")){
			int chx = Integer.parseInt(parameters.get("act"));
			switch(chx){
			case SETPROFILE : // SET AN USER PROFILE INTO THE BACKBONE
				User user = gson.fromJson(parameters.get("user"), User.class);
				System.out.println("\n***New user registration received!\n" + user);
				serviceManager.setProfile(user);
				break;
			case GETPROFILE : // GET AN USER PROFILE FROM THE BACKBONE
				String id = parameters.get("id");
				user = serviceManager.getProfile(id);
				System.out.println("\n***User profile found!\n" + user);
				result = gson.toJson(user);
			default : break;
			}
		}
		
		/** send the response */
		PrintWriter out = response.getWriter();
		out.println(result);
		out.close();
	}
}