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

public abstract class AbstractRequestHandler extends HttpServlet {

	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	/** Google library to handle jSon request */
	private Gson gson;
	
	/** WPF1 - INRIA - Overlay Networks and Pub/Sub Paradigm */
	private ServiceManager serviceManager;
	
	/** The response/feedback printed */
	private String response = "null";
	
	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
	protected AbstractRequestHandler(){
		this.gson = new Gson();
		this.serviceManager = new ServiceManager();
	}
	
	/* --------------------------------------------------------- */
	/*                      protected methods       	         */
	/* --------------------------------------------------------- */
	/**
	 * @return the parameters of an HttpServletRequest
	 */
	protected Map<String, String> getParameters(HttpServletRequest request){
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
	
	/**
	 * Print the feedback to the frontend
	 * @param response
	 * @throws IOException
	 */
	protected void printResponse(HttpServletResponse response) throws IOException {
		/** Init response */
		response.setContentType("text/html;charset=UTF-8");
		/** send the response */
		PrintWriter out = response.getWriter();
		out.println(this.response);
		out.close();
		this.response = "null";
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		System.out.println("\nGET request received :\n" + request.getQueryString());
		printResponse(response);
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		System.out.println("\nPOST request received :\n" + request.getQueryString());
		printResponse(response);
	}

	/* --------------------------------------------------------- */
	/*                      GETTER&SETTER		       	         */
	/* --------------------------------------------------------- */
	public Gson getGson() {
		return gson;
	}

	public void setGson(Gson gson) {
		this.gson = gson;
	}
	
	public ServiceManager getServiceManager() {
		return serviceManager;
	}

	public void setServiceManager(ServiceManager serviceManager) {
		this.serviceManager = serviceManager;
	}	
	public String getResponse() {
		return response;
	}

	public void setResponse(String response) {
		this.response = response;
	}
}
