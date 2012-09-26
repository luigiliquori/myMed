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
import static com.mymed.utils.MatchMakingv2.prefix;

import java.io.IOException;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.mailtemplates.MailDispatcher;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.publish.PublishManager;
import com.mymed.controller.core.requesthandler.message.JsonMessageIn;
import com.mymed.controller.core.requesthandler.message.JsonMessageOut;
import com.mymed.utils.CombiLine;
import com.mymed.utils.GsonUtils;
import com.mymed.utils.HashFunction;
import com.mymed.utils.MatchMakingv2;
import com.mymed.utils.MiscUtils;

/**
 * Servlet implementation class PublishRequestHandler
 */

@WebServlet("/v2/PublishRequestHandler")
public class PublishRequestHandler extends AbstractRequestHandler {

	/**
	 * 
	 */
	private static final long serialVersionUID = -2988915549079597L;

	/**
	 * JSON 'results' attribute.
	 */
	protected static final String JSON_RESULTS = JSON.get("json.results");

	// private int INDEX_ONLY = 1; // to delete only indexes, not data

	protected PublishManager dataManager;
	protected ProfileManager profileManager;

	private final int MAIL_EXECUTOR_SIZE = 10;
	private ExecutorService mail_executor;

	/**
	 * JSON 'predicate' attribute.
	 */

	protected static final String JSON_DETAILS = JSON.get("json.details");

	public PublishRequestHandler() throws InternalBackEndException {
		super();
		profileManager = new ProfileManager();
		dataManager = new PublishManager();
		mail_executor = Executors.newFixedThreadPool(MAIL_EXECUTOR_SIZE);
	}

	@Override
	protected void doPost(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException, JsonSyntaxException, JsonParseException, IOException {
		final JsonMessageOut<Object> out = new JsonMessageOut<Object>(200, this
				.getClass().getName());

		try{
			JsonMessageIn in = gson.fromJson(request.getReader(), JsonMessageIn.class);
			
			validateToken(in.getAccessToken());
			
			final RequestCode code = REQUEST_CODE_MAP.get(in.getCode());
	
			// some vars
			LinkedHashMap<String, List<String>> keywords; // predicates once formatted
			List<String> rows; //corresponding  rows ^
			Map<String, String> publisher = null; // publishing user in case it exists
			
			String id = in.getId();
				
			switch (code) {
	
			case CREATE:
				out.setMethod(JSON_CODE_CREATE);
	
				in.init();
				LOGGER.info("predicates: {} ", in.getPredicates());
				/* generate the ROWS to search */
				keywords = MatchMakingv2.format(in.getPredicates());
				
				LOGGER.info("keywords: {} ", keywords);
	
				rows = new CombiLine(keywords, in.getLengthMax()).expand();
				MatchMakingv2.prefix(in.getApplication(), rows);
	
				/* make sure to put an id pointer */
				if (id == null) {
					final HashFunction h = new HashFunction(SOCIAL_NET_NAME);
					id = h.SHA1ToString(System.currentTimeMillis() + in.getMetadata().toString());
				}
				in.getMetadata().put("id", id);
	
				LOGGER.info("in " + id + "." + in.getData().size() + "."
						+ keywords.size());
				
				dataManager.create(rows, id, in.getMetadata());
	
				in.getData().put("keywords", gson.toJson(keywords));
	
				/* creates data */
				dataManager.create(in.getApplication() + id, in.getData());
				
				if ("noNotification".equals(in.getUser())){ // don't use subscriptions
					LOGGER.info(">>>>>>>>>  no Notification ");
					break;
				} else if (in.getUser() != null){ // look if the content is signed
					in.getData().put("user", in.getUser());
					publisher = profileManager.readSimple(in.getUser());
				}
				in.getData().put("id", id); // just for having publication link in mails
				
				mail_executor.execute(new MailDispatcher(
						in.getApplication(),
						rows,
						in.getData(),
						publisher));
	
				break;
				
			case READ:
				out.setMethod(JSON_CODE_READ);
				
				if (id != null) {
					// Get DATA (details)
					if (id.startsWith("[")){ //we've a list of ids coming
						List<String> ids = GsonUtils.json_to_list(id);
						ids = prefix(in.getApplication(), ids);
						final Map<String, Map<String, String>> details = dataManager.read(ids); 
						
						out.setDescription("Details found for predicate: " + id);
						LOGGER.info("Details found for predicate: " + id);
	
						out.addDataObject(JSON_DETAILS, details);
						
					} else { //read a simple row id
						final Map<String, String> details = dataManager.read(in.getApplication() + id);
						if (details.isEmpty()){
							out.setStatus(404);
							out.setDescription("No results found!");
							break;
						}
						out.setDescription("Details found for predicate: " + id);
						LOGGER.info("Details found for predicate: " + id);
	
						out.addDataObject(JSON_DETAILS, details);
					}
	
				} else if (in.getPredicates() != null) {
					LOGGER.info("preds: {} ", in.getPredicates());
					/* generate the ROWS to search */
					keywords = MatchMakingv2.format(in.getPredicates());
	
					LOGGER.info("keywords: {} ", keywords);
					
					rows = new CombiLine(keywords).expand();
					prefix(in.getApplication(), rows);
					
					Map<String, Map<String, String>> resMap;
	
					resMap = dataManager.read(rows, "", "");
	
					if (resMap.isEmpty()) {
						out.setStatus(404);
						out.setDescription("No results found for predicate:"+ in.getPredicates());
						break;
					}
					
					out.setDescription("Results found for predicate: " + in.getPredicates());
					LOGGER.info("Results found predicate: " + in.getPredicates());
	
					out.addDataObject(JSON_RESULTS, resMap.values());
	
				} else {
					throw new InternalBackEndException(
							"missing id or index argument!");
				}
	
				break;
	
			
			case UPDATE:
				out.setMethod(JSON_CODE_UPDATE);
	
				in.init();
				LOGGER.info("predicates: {} ", in.getPredicates());
				
				if (id == null)
					throw new InternalBackEndException("missing id argument!");
				
				if (in.getMetadata().size()>0){
					/* generate the ROWS to search */
					keywords = MatchMakingv2.format(in.getPredicates());
	
					LOGGER.info("keywords: {} ", keywords);
		
					rows = new CombiLine(keywords).expand();
					MatchMakingv2.prefix(in.getApplication(), rows);
					LOGGER.info("in " + id + "." + in.getMetadata().size() + "."
						+ keywords.size()+ "." + rows);
					
					dataManager.create(rows, id, in.getMetadata());
				}
				
		
				LOGGER.info("updating data " + id + " size "
						+ in.getData().size());
	
				/* creates data */
				dataManager.create(in.getApplication() + id, in.getData());
				
				
				if ("noNotification".equals(in.getUser())){ // don't use subscriptions
					LOGGER.info(">>>>>>>>>  no Notification ");
					break;
				} else if (in.getUser() != null) // look if the content is signed
					publisher = profileManager.readSimple(in.getUser());
	
				LOGGER.info(">>>>>>>>>  notifying subscribers ");
					
				mail_executor.execute(new MailDispatcher(
					in.getApplication(),
					MiscUtils.singleton(in.getApplication() + id),
					in.getData(),
					publisher));
				
				break;
				
			case DELETE:
	
				out.setMethod(JSON_CODE_DELETE);
				// final String mode = parameters.get("mode");
	
				if (id == null)
					throw new InternalBackEndException("missing id argument!");
	
				
				LOGGER.info("deleting " + in.getApplication() + id);
				
				if (in.getField() != null){
					dataManager.delete(in.getApplication() + id, in.getField());
					break;
				}
				
				String keywordsStr = "[]";
				try{
					keywordsStr = dataManager.read(in.getApplication() + id, "keywords");
				}catch(IOBackEndException e){}
				
	
				LOGGER.info(" trying to delete  " + id + "." + keywordsStr);
	
				keywords = new LinkedHashMap<String, List<String>>();
	
				if (keywordsStr != null) {
					keywords = GsonUtils.json_to_keywords(keywordsStr);
					LOGGER.info(" get index : {} ", keywords);
				}
				
				LOGGER.info(" deleting  " + id + "." + keywords.size());
	
				rows = new CombiLine(keywords).expand();
				prefix(in.getApplication(), rows);
	
				for (String i : rows) {
					dataManager.delete("", i, id);
				}
	
				/* deletes data */
				dataManager.delete(in.getApplication() + id);
	
				break;
	
			default:
				throw new InternalBackEndException("PublishRequestHandler("
						+ code + ") not exist!");
			}
		
		} catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost", e);
            out.setStatus(e.getStatus());
            out.setDescription(e.getMessage());
        }

		printJSonResponse(out, response);
	}
	
	protected void doGet(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException {
		throw new InternalBackEndException("PublishRequestHandler.doGet() not exist!");
	}
}