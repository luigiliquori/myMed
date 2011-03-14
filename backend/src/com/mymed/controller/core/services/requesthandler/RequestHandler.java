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
import com.mymed.model.core.wrapper.Wrapper;
import com.mymed.model.datastructure.User;

/**
 * Handle all the request from the frontend
 * @author lvanni
 *
 */
public class RequestHandler extends HttpServlet {
	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	/** Google library to handle jSon request */
	private Gson gson;
	
	/** WPF1 - INRIA - Overlay Networks and Pub/Sub Paradigm */
	private ServiceManager serviceManager;
	
	/** Requests code */ 
	protected Map<String, RequestCode> requestCodeMap = new HashMap<String, RequestCode>();
	private enum RequestCode {
		// low level API
		CONNECT  ("0"),
		SETKEYSPACE ("1"),
		SETCOLUMNFAMILY ("2"),
		SETKEYUSERID ("3"),
		INSERTKEY ("4"),
		GETKEY ("5"),
		
		// hight level API
		SETPROFILE ("10"), 	// SET AN USER PROFILE INTO THE BACKBONE
		GETPROFILE ("11"), 	// GET AN USER PROFILE FROM THE BACKBONE
		LOGIN ("12"),		// MYMED AUTHENTICATION
		
		// DHTs API
		PUT ("20"),
		GET ("21");
		
		public final String code;
		
		RequestCode(String code){
			this.code = code;
		}
	}
	
	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#HttpServlet()
	 */
	public RequestHandler() {
		super();
		/** Google Gson
		 * see http://code.google.com/p/google-gson/
		 */
		this.gson = new Gson();
		
		// initialise the CodeMapping
		this.serviceManager = new ServiceManager();
		for(RequestCode r : RequestCode.values()){
			requestCodeMap.put(r.code, r);
		}
	}
	
	/* --------------------------------------------------------- */
	/*                      private methods       		         */
	/* --------------------------------------------------------- */
	/**
	 * @return the parameters of an HttpServletRequest
	 */
	private Map<String, String> getParameters(HttpServletRequest request){
		Map<String, String> parameters = new HashMap<String, String>();
		Enumeration<String> paramNames = request.getParameterNames();
		while (paramNames.hasMoreElements()) {
			String paramName = (String) paramNames.nextElement();
			String[] paramValues = request.getParameterValues(paramName);
			if (paramValues.length >= 1) { // all the params should be atomic
				parameters.put(paramName, paramValues[0]);
			}
		}
		return parameters;
	}

	/* --------------------------------------------------------- */
	/*                      extends HttpServlet                  */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		/** Init response */
		response.setContentType("text/html;charset=UTF-8");
		String result = "NULL"; // the feedBack for a bad request on the frontend is NULL

		/** Get the parameters */
		Map<String, String> parameters = getParameters(request);
		
		/** handle the request */
		if (parameters.containsKey("act")){
			RequestCode code = requestCodeMap.get(parameters.get("act"));
			
			switch(code){
			case SETPROFILE : 
				User user = gson.fromJson(parameters.get("user"), User.class);
				System.out.println("\nreceived:\n" + user);
				serviceManager.setProfile(user);
				break;
			case GETPROFILE : 
				String id = parameters.get("id");
				user = serviceManager.getProfile(id);
				result = gson.toJson(user);
				break;
			case LOGIN : 
				String email = parameters.get("email"); // email == id for mymed users
				String password = parameters.get("password");
				user = serviceManager.getProfile(email);
				result = user.getPassword().equals(password) ? gson.toJson(user) : "false";
				break;
			case PUT : 
				String key = parameters.get("key");
				String value = parameters.get("value");
				System.out.println("key to publish: " + key);
				System.out.println("value to publish: " + value);
				new Wrapper().put(key, value);
				break;
			case GET : 
				key = parameters.get("key"); 
				System.out.println("key to search: " + key);
				result = new Wrapper().get(key);
				break;
			default : break;
			}
		}
		
		/** send the response */
		PrintWriter out = response.getWriter();
		out.println(result);
		out.close();
	}
	
	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {
		// TODO 
	}
}