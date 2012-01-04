package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.position.PositionManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.user.MPositionBean;
import com.mymed.utils.MLogger;

/**
 * Servlet implementation class PositionRequestHandler
 */
@WebServlet("/PositionRequestHandler")
public class PositionRequestHandler extends AbstractRequestHandler {
	private static final long serialVersionUID = 1L;

	private PositionManager positionManager;

	/**
	 * @see HttpServlet#HttpServlet()
	 */
	public PositionRequestHandler() throws ServletException {
		super();

		try {
			positionManager = new PositionManager();
		} catch (final InternalBackEndException e) {
			throw new ServletException(
					"PositionManager is not accessible because: "
							+ e.getMessage());
		}
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));
			String userID = parameters.get("userID");

			if (userID == null) {
				throw new InternalBackEndException("userID argument missing!");
			}

			switch (code) {
			case READ:
				message.setMethod("READ");
				System.out.println("USER: " + userID);
				MPositionBean position = positionManager.read(userID);
				message.addData("position", getGson().toJson(position));
				break;
			default:
				throw new InternalBackEndException(
						"PositionRequestHandler.doGet(" + code + ") not exist!");
			}

		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doGet");
			MLogger.getDebugLog().debug("Error in doGet", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		}

		printJSonResponse(message, response);
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));
			final String position = parameters.get("position");
			if (position == null) {
				throw new InternalBackEndException("missing position argument!");
			}

			switch (code) {

			case UPDATE :
				message.setMethod("UPDATE");
				try {
					MPositionBean positionBean = getGson().fromJson(position, MPositionBean.class);
					MLogger.getLog().info("Trying to update position:\n {}", positionBean.toString());
					positionManager.update(positionBean);
					message.setDescription("Position updated!");
					MLogger.getLog().info("Position updated!");
				} catch (final JsonSyntaxException e) {
					throw new InternalBackEndException("position jSon format is not valid");
				}
				break;
			default :
				throw new InternalBackEndException("ProfileRequestHandler.doPost(" + code + ") not exist!");
			}

		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doRequest operation");
			MLogger.getDebugLog().debug("Error in doRequest operation", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 

		printJSonResponse(message, response);
	}
}

