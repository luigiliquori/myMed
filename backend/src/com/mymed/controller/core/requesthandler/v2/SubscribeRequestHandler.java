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

import java.io.IOException;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.subscribe.SubscribeManager;
import com.mymed.controller.core.requesthandler.message.JsonMessageIn;
import com.mymed.controller.core.requesthandler.message.JsonMessageOut;
import com.mymed.utils.CombiLine;
import com.mymed.utils.GsonUtils;
import com.mymed.utils.MatchMakingv2;
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

	protected final SubscribeManager subscriptionManager;

	public SubscribeRequestHandler() throws InternalBackEndException {
		super();
		subscriptionManager = new SubscribeManager();
	}


	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response) Create a subscription
	 */
	@Override
	protected void doPost(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException, JsonSyntaxException, JsonParseException, IOException {

		final JsonMessageOut<Object> out = new JsonMessageOut<Object>(200, this
				.getClass().getName());
		
		try{
			JsonMessageIn in = gson.fromJson(request.getReader(), JsonMessageIn.class);
			
			validateToken(in.getAccessToken());
			
			final RequestCode code = REQUEST_CODE_MAP.get(in.getCode());
	
			if (in.getUser() == null)
				throw new InternalBackEndException("missing user argument!");
	
			final String application = in.getApplication()!=null?
					in.getApplication()
					:"";
			String id = in.getId();
			
			switch (code) {
	
			case CREATE:
				out.setMethod(JSON_CODE_CREATE);
	
				
				if (id != null) {
					/* we want to subscribe to a data changes */
					LinkedHashMap<String, List<String>> keywords = new LinkedHashMap<String, List<String>>();
					keywords.put(id, MiscUtils.singleton(id));
					
					subscriptionManager.create(application, id, in.getUser(), gson.toJson(keywords), in.getMailTemplate());
	
					LOGGER.info(" created subscriptions for {}: {} ", in.getUser(), id);
					out.setDescription("subscription created: " + id
							+ " for user: " + in.getUser()+" with template "+ in.getMailTemplate());
	
				} else if (in.getPredicates() != null){
					/* we want to subscribe to keywords/ categories ... */
	
					LOGGER.info("in ." + in.getPredicates().size());
	
					LinkedHashMap<String, List<String>> keywords = MatchMakingv2.format(in.getPredicates());
	
					LOGGER.info("indexes: {} ", keywords);
					List<String> rows = new CombiLine(keywords).expand();
	
					String v = gson.toJson(keywords);
	
					for (String i : rows) {
						subscriptionManager.create(application, i, in.getUser(), v, in.getMailTemplate());
					}
	
					LOGGER.info(" created subscriptions for {}: {} ", in.getUser(), in.getPredicates());
					out.setDescription("subscription created: " + application + in.getPredicates()
							+ " for user: " + in.getUser()+" with template "+ in.getMailTemplate());
				}
	
				break;
			case READ:
				out.setMethod(JSON_CODE_READ);
				/*
				 * read subs of this user
				 */
				if (id != null) {
					String sub = subscriptionManager.read(application + in.getUser(), id);
					out.setDescription("Subscriptions found for Application: "
							+ application + " User: " + in.getUser());
					LOGGER.info("Subscriptions found for Application: "
							+ application + " User: " + in.getUser());
					out.addDataObject(JSON_SUBSCRIPTIONS, sub);
				} else {
					final Map<String, String> predicates = subscriptionManager.read(application + in.getUser());
	
					out.setDescription("Subscriptions found for Application: "
							+ application + " User: " + in.getUser());
					LOGGER.info("Subscriptions found for Application: "
							+ application + " User: " + in.getUser());
					out.addDataObject(JSON_SUBSCRIPTIONS, predicates);
				}
	
				break;
	
			case DELETE:
				out.setMethod(JSON_CODE_DELETE);
	
				if (id == null){
					id = "ALL";
				}
	
				LOGGER.info("in ." + application + in.getUser() +" . "  +id);
				String keywordsStr = subscriptionManager.read(application + in.getUser(), id);
				LinkedHashMap<String, List<String>> keywords = new LinkedHashMap<String, List<String>>();
	
				if (keywordsStr != null){
					keywords = GsonUtils.json_to_keywords(keywordsStr);
				}
	
				LOGGER.info("in ." + keywords.size() + " . "+application+" "+in.getUser());
				
				for (Entry<String, List<String>> entry : keywords.entrySet()){
					for (String i : entry.getValue()){
						subscriptionManager.delete(application, i, in.getUser());
					}
				}
	
				LOGGER.info("  deleted subscriptions for {}: {} ", in.getUser()+id, keywords);
				out.setDescription("subscription deleted: " + keywords +" id "+ id
						+ " for user: " + in.getUser());
	
				break;
	
			default:
				throw new InternalBackEndException(
						"SubscribeRequestHandler.doPost(" + code
								+ ") not exist!");
	
			}
		
		} catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost", e);
            out.setStatus(e.getStatus());
            out.setDescription(e.getMessage());
        }

		printJSonResponse(out, response);
	}
	
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doGet(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException {
		throw new InternalBackEndException("SubscribeRequestHandler.doGet() not exist!");
	}
}
