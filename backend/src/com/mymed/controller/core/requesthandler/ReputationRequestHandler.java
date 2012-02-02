/*
 * Copyright 2012 INRIA 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
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
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedAppUserId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.ReputationRole;
import com.mymed.controller.core.manager.reputation.api.recommendation_manager.ReputationManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.reputation.MReputationBean;
import com.mymed.utils.MLogger;

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
		this.reputationManager = new ReputationManager();
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
			
			// accessToken
			if (!parameters.containsKey("accessToken")) {
				throw new InternalBackEndException("accessToken argument is missing!");
			} else {
				tokenValidation(parameters.get("accessToken")); // Security Validation
			}
			
			switch (code) {
			case READ:
				if ((application = parameters.get("application")) == null) {
					throw new InternalBackEndException("missing application argument!");
				} else if ((producer = parameters.get("producer")) == null) {
					throw new InternalBackEndException("missing producer argument!");
				} else if ((consumer = parameters.get("consumer")) == null) {
					throw new InternalBackEndException("missing consumer argument!");
				}
                                MymedAppUserId user = new MymedAppUserId(application, producer, ReputationRole.Producer);
				MReputationBean reputation = reputationManager.read(user);
				message.addData("reputation", reputation.getReputation() + "");
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

			// accessToken
			if (!parameters.containsKey("accessToken")) {
				throw new InternalBackEndException("accessToken argument is missing!");
			} else {
				tokenValidation(parameters.get("accessToken")); // Security Validation
			}
			
			switch (code) {
			case CREATE:
			case UPDATE:
				break;
			default:
				throw new InternalBackEndException("ReputationRequestHandler.doPost(" + code + ") not exist!");
			}
		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doGet operation");
			MLogger.getDebugLog().debug("Error in doGet operation", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 
		
		printJSonResponse(message, response);
	}
}
