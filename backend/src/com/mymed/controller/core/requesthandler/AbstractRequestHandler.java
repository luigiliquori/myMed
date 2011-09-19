package com.mymed.controller.core.requesthandler;

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
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;

public abstract class AbstractRequestHandler extends HttpServlet {

	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	/** Google library to handle jSon request */
	private Gson gson;

	/** The response/feedback printed */
	private String responseText = null;

	/** Request code Map*/ 
	protected Map<String, RequestCode> requestCodeMap = new HashMap<String, RequestCode>();

	/** Request codes*/ 
	protected enum RequestCode {
		// C.R.U.D 
		CREATE ("0"), 	
		READ ("1"), 
		UPDATE ("2"),
		DELETE ("3");

		public final String code;

		RequestCode(String code){
			this.code = code;
		}
	}

	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
	protected AbstractRequestHandler(){
		this.gson = new Gson();
		for(RequestCode r : RequestCode.values()){
			requestCodeMap.put(r.code, r);
		}
	}

	/* --------------------------------------------------------- */
	/*                      protected methods       	         */
	/* --------------------------------------------------------- */
	/**
	 * @return the parameters of an HttpServletRequest
	 */
	protected Map<String, String> getParameters(HttpServletRequest request) throws InternalBackEndException{
		Map<String, String> parameters = new HashMap<String, String>();
		Enumeration<String> paramNames = request.getParameterNames();
		while (paramNames.hasMoreElements()) {
			String paramName = (String) paramNames.nextElement();
			String[] paramValues = request.getParameterValues(paramName);
			if (paramValues.length == 1) { // all the params should be atomic
				parameters.put(paramName, paramValues[0]);
				System.out.println(paramName + " : " +  paramValues[0]);
			}
		}
		if (!parameters.containsKey("code")){
			throw new InternalBackEndException("code argument is missing!");
		}
		return parameters;
	}
	
	protected Map<String, String[]> getParameterArrays(HttpServletRequest request) throws InternalBackEndException{
		Map<String, String[]> parameters = new HashMap<String, String[]>();
		Enumeration<String> paramNames = request.getParameterNames();
		while (paramNames.hasMoreElements()) {
			String paramName = (String) paramNames.nextElement();
			String[] paramValues = request.getParameterValues(paramName);
			if (paramValues.length >= 1) { // all the params should be atomic
				parameters.put(paramName, paramValues);
				System.out.println(paramName + " : " +  paramValues.toString());
			}
		}
		return parameters;
	}
	
	/**
	 * Handle a server error, and send a feedback to the frontend
	 * @param message
	 * @param response
	 */
	protected void handleError(AbstractMymedException e, HttpServletResponse response){
		response.setStatus(e.getStatus());
		this.responseText = e.getJsonException();
		printResponse(response);
	}
	
	/**
	 * Print the feedback to the frontend
	 * @param response
	 * @throws IOException
	 */
	protected void printResponse(HttpServletResponse response) {
		/** Init response */
		if(responseText != null) {
			response.setContentType("text/plain;charset=UTF-8");
			/** send the response */
			PrintWriter out;
			try {
				out = response.getWriter();
				System.out.println("\nResponse sent:\n" + this.responseText);
				out.print(this.responseText);
				out.close();
				this.responseText = null;
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
	}

	/* --------------------------------------------------------- */
	/*                      extends HttpServlet                  */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		printResponse(response);
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		printResponse(response);
	}
	
	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doDelete(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
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

	public String getResponseText() {
		return responseText;
	}

	public void setResponseText(String responseText) {
		this.responseText = responseText;
	}
}
