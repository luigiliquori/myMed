package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.old.ReputationManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.reputation.old.MReputationBean;
import com.mymed.utils.MLogger;

/**
 * Servlet implementation class ReputationRequestHandler
 */
@WebServlet("/ReputationRequestHandlerOld")
public class ReputationRequestHandlerOld extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	private ReputationManager reputationManager;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * @throws ServletException 
	 * @see HttpServlet#HttpServlet()
	 */
	public ReputationRequestHandlerOld() throws ServletException {
		super();
		try {
			this.reputationManager = new ReputationManager();
		} catch (InternalBackEndException e) {
			throw new ServletException("ReputationManager is not accessible because: " + e.getMessage());
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
		
		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			Map<String, String> parameters = getParameters(request);
			RequestCode code = requestCodeMap.get(parameters.get("code"));
			String application, producer, consumer;
		
			switch (code) {
			case READ:
				if ((application = parameters.get("application")) == null) {
					throw new InternalBackEndException("missing application argument!");
				} else if ((producer = parameters.get("producer")) == null) {
					throw new InternalBackEndException("missing producer argument!");
				} else if ((consumer = parameters.get("consumer")) == null) {
					throw new InternalBackEndException("missing consumer argument!");
				}
				MReputationBean reputation = reputationManager.read(producer, consumer, application);
				message.addData("reputation", reputation.getValue() + "");
				break;
			case DELETE:
				break;
			default:
				throw new InternalBackEndException("ReputationRequestHandler.doGet(" + code + ") not exist!");
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
	protected void doPost(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			Map<String, String> parameters = getParameters(request);
			RequestCode code = requestCodeMap.get(parameters.get("code"));

			switch (code) {
			case CREATE:
			case UPDATE:
				break;
			default:
				throw new InternalBackEndException("ReputationRequestHandler.doPost(" + code + ") not exist!");
			}
			super.doPost(request, response);
		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doPost operation");
			MLogger.getDebugLog().debug("Error in doPost operation", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 

		printJSonResponse(message, response);
	}
}
