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
package com.mymed.controller.core.requesthandler.v2;

import static com.mymed.utils.GsonUtils.gson;

import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
 
import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.subscription.SubscriptionManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.DataBean;
import com.mymed.utils.MatchMakingv2;
import com.mymed.utils.CombiLine;
import com.mymed.utils.MiscUtils;

/**
 * Servlet implementation class PubSubRequestHandler
 */

@WebServlet("/v2/SubscribeRequestHandler")
public class SubscribeRequestHandler extends AbstractRequestHandler {
	/**
	 * Generated serial ID.
	 */
	private static final long serialVersionUID = -3497628036243410706L;

	/**
	 * JSON 'predicate' attribute.
	 */

	protected static final String JSON_SUBSCRIPTIONS = JSON.get("json.subscriptions");

	protected final SubscriptionManager subscriptionManager;

	public SubscribeRequestHandler() throws InternalBackEndException {
		super();
		subscriptionManager = new SubscriptionManager();
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doGet(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException {
		final JsonMessage<Object> message = new JsonMessage<Object>(200, this
				.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			// Check the access token
			checkToken(parameters);

			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
			String 
				application = parameters.containsKey(JSON_APPLICATION)?
					parameters.get(JSON_APPLICATION)
					:"",
				id = parameters.get("id"),
				user = parameters.get(JSON_USER);

			if (user == null)
				throw new InternalBackEndException("missing user argument!");

			switch (code) {
			case READ:
				message.setMethod(JSON_CODE_READ);
				/*
				 * read subs of this user
				 */
				if (id != null) {
					String sub = subscriptionManager.read(application + user, id);
					message.setDescription("Subscriptions found for Application: "
							+ application + " User: " + user);
					LOGGER.info("Subscriptions found for Application: "
							+ application + " User: " + user);
					message.addDataObject(JSON_SUBSCRIPTIONS, sub);
				} else {
					final Map<String, String> predicates = subscriptionManager.read(application + user);

					message.setDescription("Subscriptions found for Application: "
							+ application + " User: " + user);
					LOGGER.info("Subscriptions found for Application: "
							+ application + " User: " + user);
					message.addDataObject(JSON_SUBSCRIPTIONS, predicates);
				}

				break;

			case DELETE:
				message.setMethod(JSON_CODE_DELETE);

				if (id == null)
					throw new InternalBackEndException("missing id argument!");

				String xpredicates = subscriptionManager.read(application + user, id);
				LinkedHashMap<String, List<String>> xpreds = new LinkedHashMap<String, List<String>>();

				if (xpredicates != null){
					try {
						xpreds = gson.fromJson(xpredicates, xType);
					} catch (final JsonSyntaxException e) {
						LOGGER.debug("Error in Json format", e);
						throw new InternalBackEndException("jSon format is not valid");
					} catch (final JsonParseException e) {
						LOGGER.debug("Error in parsing Json", e);
						throw new InternalBackEndException(e.getMessage());
					}
				}

				LOGGER.info("in ." + xpreds.size());

				List<String> rows = new CombiLine(xpreds).expand();

				for (String i : rows) {
					subscriptionManager.delete(application, i, user);
				}

				LOGGER.info("  deleted subscriptions for {}: {} ", user, xpredicates);
				message.setDescription("subscription deleted: " + xpredicates
						+ " for user: " + user);

				break;

			default:
				throw new InternalBackEndException(
						"SubscribeRequestHandler.doGet(" + code
								+ ") not exist!");
			}
		} catch (final AbstractMymedException e) {
			LOGGER.debug("Error in doGet operation", e);
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		}

		printJSonResponse(message, response);
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response) Create a subscription
	 */
	@Override
	protected void doPost(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException {

		final JsonMessage<Object> message = new JsonMessage<Object>(200, this
				.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			// Check the access token
			checkToken(parameters);

			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
			
			final String 
				application = parameters.containsKey(JSON_APPLICATION)?
					parameters.get(JSON_APPLICATION)
					:"",
				user = parameters.get(JSON_USER),
				id = parameters.get("id"),
				predicates = parameters.get("predicates"),
				mailTemplate = parameters.get("mailTemplate");

			if (user == null)
				throw new InternalBackEndException("missing user argument!");

			List<DataBean> preds = new ArrayList<DataBean>();

			
			
			switch (code) {

			case CREATE:
				message.setMethod(JSON_CODE_CREATE);

				if (id != null) {
					/* we want to subscribe to a data changes */
					LinkedHashMap<String, List<String>> xpreds = new LinkedHashMap<String, List<String>>();
					xpreds.put(id, MiscUtils.singleton(id));
					
					subscriptionManager.create(application, id, user, gson.toJson(xpreds), mailTemplate);

					LOGGER.info(" created subscriptions for {}: {} ", user, id);
					message.setDescription("subscription created: " + id
							+ " for user: " + user);

				} else if (predicates != null){
					/* we want to subscribe to keywords/ categories ... */
					
					// retrieve query params
					try {
						preds = gson.fromJson(predicates, predicateType);
					} catch (final JsonSyntaxException e) {
						LOGGER.debug("Error in Json format", e);
						throw new InternalBackEndException("jSon format is not valid");
					} catch (final JsonParseException e) {
						LOGGER.debug("Error in parsing Json", e);
						throw new InternalBackEndException(e.getMessage());
					}

					LOGGER.info("in ." + preds.size());

					LinkedHashMap<String, List<String>> xpreds = MatchMakingv2.format(preds);

					LOGGER.info("indexes: {} ", xpreds);
					List<String> rows = new CombiLine(xpreds).expand();
					
					String v = gson.toJson(xpreds);

					for (String i : rows) {
						subscriptionManager.create(application, i, user, v, mailTemplate);
					}

					LOGGER.info(" created subscriptions for {}: {} ", user, preds);
					message.setDescription("subscription created: " + application + predicates
							+ " for user: " + user);
				}

				break;

			default:
				throw new InternalBackEndException(
						"SubscribeRequestHandler.doPost(" + code
								+ ") not exist!");

			}

		} catch (final AbstractMymedException e) {
			LOGGER.debug("Error in doPost operation", e);
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		}

		printJSonResponse(message, response);
	}
}
