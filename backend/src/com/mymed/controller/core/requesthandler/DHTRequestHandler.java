package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.dht.DHTManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.utils.MLogger;

/**
 * Handle all the request from the frontend
 * 
 * @author lvanni
 * 
 */
public class DHTRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	private DHTManager dhtManager;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * @throws ServletException
	 * @see HttpServlet#HttpServlet()
	 */
	public DHTRequestHandler() throws ServletException {
		super();

		try {
			dhtManager = new DHTManager();
		} catch (final InternalBackEndException e) {
			throw new ServletException("DHTManager is not accessible because: " + e.getMessage());
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
		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));
			String key;

			switch (code) {
			case READ : // GET
				if ((key = parameters.get("key")) == null) {
					throw new InternalBackEndException("missing key argument!");
				}
				message.setDescription("Get(" + key + ")");
				message.addData("value", (dhtManager.get(key)));
				break;
			default :
				throw new InternalBackEndException("DHTRequestHandler.doGet(" + code + ") not exist!");
			}

		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doGet operation");
			MLogger.getDebugLog().debug("Error in doGet operation", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 
		
		printJSonResponse(message, response);
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doPost(final HttpServletRequest request, final HttpServletResponse response)
			throws ServletException, IOException {
		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));
			String key, value;

			 switch (code) {
			 case CREATE : // PUT
				 if ((key = parameters.get("key")) == null) {
					 throw new InternalBackEndException("missing key argument!");
				 } else if ((value = parameters.get("value")) == null) {
					 throw new InternalBackEndException("missing value argument!");
				 }
				 dhtManager.put(key, value);
				 message.setDescription("key published");
				 break;
			 default :
				 throw new InternalBackEndException("DHTRequestHandler.doPost(" + code + ") not exist!");
			 }

		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doPost operation");
			MLogger.getDebugLog().debug("Error in doPost operation", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 
		
		printJSonResponse(message, response);
	}
}