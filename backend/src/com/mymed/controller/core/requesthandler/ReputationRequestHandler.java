package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.reputation_manager.ReputationManager;
import com.mymed.controller.core.manager.storage.StorageManager;

/**
 * Servlet implementation class ReputationRequestHandler
 */
@WebServlet("/ReputationRequestHandler")
public class ReputationRequestHandler extends AbstractRequestHandler {
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
	public ReputationRequestHandler() throws ServletException {
		super();
		try {
			this.reputationManager = new ReputationManager(new StorageManager().getWrapper());
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
		try {
			/** Get the parameters */
			Map<String, String> parameters = getParameters(request);

			/** Get the method code */
			RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			String application, producer, consumer;
			switch (code) {
			case READ:
				if ((application = parameters.get("application")) == null) {
					handleError(new InternalBackEndException(
							"missing application argument!"), response);
					return;
				} else if ((producer = parameters.get("producer")) == null) {
					handleError(new InternalBackEndException(
							"missing producer argument!"), response);
					return;
				} else if ((consumer = parameters.get("consumer")) == null) {
					handleError(new InternalBackEndException(
							"missing consumer argument!"), response);
					return;
				}
				ReputationManager.ReputationObject reputation = reputationManager.readReputation(producer, consumer, application, true);
				setResponseText(reputation.getReputation() + "");
				break;
			case DELETE:
				break;
			default:
				handleError(new InternalBackEndException("ReputationRequestHandler.doGet(" + code + ") not exist!"), response);
				return;
			}
			super.doGet(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			handleError(e, response);
			return;
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

			/** Get the method code */
			RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			switch (code) {
			case UPDATE:
				break;
			default:
				handleError(new InternalBackEndException("ReputationRequestHandler.doPost(" + code + ") not exist!"), response);
				return;
			}
			super.doPost(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			handleError(e, response);
		}
	}
}
