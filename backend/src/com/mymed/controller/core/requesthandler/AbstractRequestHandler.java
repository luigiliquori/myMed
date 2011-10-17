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
import javax.servlet.http.Part;

import com.google.gson.Gson;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
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

		// see multipart/form-data Request
		if(request.getContentType() != null){
			try {
				if(request.getContentType().matches("multipart/form-data")){
					MLogger.getLog().info("multipart/form-data REQUEST");
					for(Part part : request.getParts()){
						MLogger.getLog().info("PART {} ", part);
					}
					throw new InternalBackEndException("multi-part is not yet implemented...");
				}
			} catch (IOException e) {
				throw new InternalBackEndException(e);
			} catch (ServletException e) {
				throw new InternalBackEndException(e);
			}
		}

		final Map<String, String> parameters = new HashMap<String, String>();
		final Enumeration<String> paramNames = request.getParameterNames();

		while (paramNames.hasMoreElements()) {
			final String paramName = paramNames.nextElement();
			final String[] paramValues = request.getParameterValues(paramName);

			if (paramValues.length >= 1) { // all the params should be atomic
				parameters.put(paramName, paramValues[0]);
			}

			MLogger.getLog().info("{}: {}", paramName, paramValues[0]);
		}

		if (!parameters.containsKey("code")) {
			throw new InternalBackEndException("code argument is missing!");
		}
		
		if (requestCodeMap.get(parameters.get("code")) == null) {
			throw new InternalBackEndException("code argument is not well formated");
		}

		return parameters;
	}

	/**
	 * Print the server response in a jSon format
	 * @param message
	 * @param response
	 */
	protected void printJSonResponse(final JsonMessage message, final HttpServletResponse response) {
		response.setStatus(message.getStatus());
		responseText = message.toString();
		printResponse(response);
	}

	/**
	 * Print the server response
	 * @param response
	 * @throws IOException
	 */
	private void printResponse(final HttpServletResponse response) {
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
}
