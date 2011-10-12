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
import com.mymed.utils.MLogger;

public abstract class AbstractRequestHandler extends HttpServlet {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	/** Google library to handle jSon request */
	private Gson gson;

	/** The response/feedback printed */
	private String responseText = null;

	/** Request code Map */
	protected Map<String, RequestCode> requestCodeMap = new HashMap<String, RequestCode>();

	/** Request codes */
	protected enum RequestCode {
		// C.R.U.D
		CREATE("0"),
		READ("1"),
		UPDATE("2"),
		DELETE("3");

		public final String code;

		RequestCode(final String code) {
			this.code = code;
		}
	}

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	protected AbstractRequestHandler() {
		super();

		gson = new Gson();
		for (final RequestCode r : RequestCode.values()) {
			requestCodeMap.put(r.code, r);
		}
	}

	/* --------------------------------------------------------- */
	/* protected methods */
	/* --------------------------------------------------------- */
	/**
	 * @return the parameters of an HttpServletRequest
	 */
	protected Map<String, String> getParameters(final HttpServletRequest request) throws InternalBackEndException {
		final Map<String, String> parameters = new HashMap<String, String>();
		final Enumeration<String> paramNames = request.getParameterNames();

		while (paramNames.hasMoreElements()) {

			final String paramName = paramNames.nextElement();
			final String[] paramValues = request.getParameterValues(paramName);

			if (paramValues.length >= 1) { // all the params should be atomic
				parameters.put(paramName, paramValues[0]);
				System.out.println(paramName + " : " +  paramValues[0]);
			}


			MLogger.getLog().info("{}: {}", paramName, paramValues[0]);

		}

		if (!parameters.containsKey("code")) {
			throw new InternalBackEndException("code argument is missing!");
		}

		return parameters;
	}

	/**
	 * Handle a server error, and send a feedback to the frontend
	 * 
	 * @param message
	 * @param response
	 */
	protected void handleError(final AbstractMymedException e, final HttpServletResponse response) {
		response.setStatus(e.getStatus());
		responseText = e.getJsonException();
		printResponse(response);
	}

	/**
	 * Print the feedback to the frontend
	 * 
	 * @param response
	 * @throws IOException
	 */
	protected void printResponse(final HttpServletResponse response) {
		/** Init response */
		if (responseText != null) {
			response.setContentType("text/plain;charset=UTF-8");
			/** send the response */
			PrintWriter out;
			try {
				out = response.getWriter();

				MLogger.getLog().info("Response sent:\n {}", responseText);

				out.print(responseText);
				out.close();
				responseText = null; // NOPMD to avoid code check warnings
			} catch (final IOException e) {
				MLogger.getLog().info("IOException: {}", e.getMessage());
				MLogger.getDebugLog().debug("Errore in printResponse()", e.getCause());
			}
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
		printResponse(response);
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doPost(final HttpServletRequest request, final HttpServletResponse response)
	        throws ServletException, IOException {
		printResponse(response);
	}
	
	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doDelete(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		printResponse(response);
	}

	/* --------------------------------------------------------- */
	/* GETTER&SETTER */
	/* --------------------------------------------------------- */
	public Gson getGson() {
		return gson;
	}

	public void setGson(final Gson gson) {
		this.gson = gson;
	}

	public String getResponseText() {
		return responseText;
	}

	public void setResponseText(final String responseText) {
		this.responseText = responseText;
	}
}
