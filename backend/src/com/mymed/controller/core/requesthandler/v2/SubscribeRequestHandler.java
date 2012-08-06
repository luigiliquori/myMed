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
import static com.mymed.utils.MatchMaking.makePrefix;
import static com.mymed.utils.MatchMaking.parseInt;

import java.util.ArrayList;
import java.util.Collections;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.MultipartConfig;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.IndexBean;
import com.mymed.utils.MatchMaking;
import com.mymed.utils.MatchMaking.IndexRow;

/**
 * Servlet implementation class PubSubRequestHandler
 */

@MultipartConfig
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

    protected final PubSubManager pubsubManager;

    public SubscribeRequestHandler() throws InternalBackEndException {
        super();
        pubsubManager = new PubSubManager();
    }

    /**
     * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
     *      response)
     */
    @Override
    protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessage<Object> message = new JsonMessage<Object>(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);

            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            String 
            application = parameters.get(JSON_APPLICATION),
            dataId = parameters.get("id"), 
            user = parameters.get(JSON_USER),
            namespace = parameters.get(JSON_NAMESPACE);
            
            if (application == null)
        		throw new InternalBackEndException("missing application argument!");
        	else if(user == null)
        		throw new InternalBackEndException("missing user argument!");
            
            String prefix = makePrefix(application, namespace);
            
			switch (code) {
			case READ:
				message.setMethod(JSON_CODE_READ);
				/*
				 * read subs of this user
				 */
				if (dataId != null){
					String sub = pubsubManager.readSubEntry(prefix + user, dataId);
					message.setDescription("Subscriptions found for Application: " 
							+ application + " User: " + user);
					LOGGER.info("Subscriptions found for Application: "+ application + " User: " + user);
					message.addDataObject(JSON_SUBSCRIPTIONS, sub);
				} else {
					final Map<String, String> predicates = pubsubManager.read(prefix + user);
					
					message.setDescription("Subscriptions found for Application: " 
							+ application + " User: " + user);
					LOGGER.info("Subscriptions found for Application: "+ application + " User: " + user);
					message.addDataObject(JSON_SUBSCRIPTIONS, predicates);
				}
				
				break;
				
			case DELETE:
            	message.setMethod(JSON_CODE_DELETE);
            	
            	if (dataId == null)
            		throw new InternalBackEndException("missing id argument!");
            
            	String subJson = pubsubManager.readSubEntry(prefix + user, dataId);
            	List<IndexBean> query = new ArrayList<IndexBean>();
            	
            	if (subJson != null)
            		query = gson.fromJson(subJson, indexType);
            	
            	LOGGER.info("in ."+query.size());
            	LinkedHashMap<String, List<String>> indexes = MatchMaking.formatIndexes(query);	
            	List<IndexRow> combi = MatchMaking.getPredicate(
    					indexes, 0, query.size());
				for (IndexRow i : combi) {
					pubsubManager.delete(prefix, i.vals(), user);
				}
                    
				LOGGER.info("  deleted subscriptions for {}: {} ", user, subJson);
                message.setDescription("subscription deleted: " + subJson +" for user: " + user);

            	break;

			default:
				throw new InternalBackEndException(
						"SubscribeRequestHandler.doGet(" + code + ") not exist!");
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
     *      response)
     * Create a subscription
     */
    @Override
    protected void doPost(
            final HttpServletRequest request, 
            final HttpServletResponse response) throws ServletException {
        
        final JsonMessage<Object> message = new JsonMessage<Object>(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);

            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String 
            	application = parameters.get(JSON_APPLICATION),
        		user = parameters.get(JSON_USER),
        		dataId = parameters.get("id"), 
        		/*
        		 * subscribe to a specific data, (notified for its updates)
        		 */
        		index = parameters.get("index"),
        		/*
        		 * subscribe to keywords
        		 * NOTE for DELETE it's id paramter that is used either for data and indexes subscriotions
        		 * in order to have short unsubscribe links ...
        		 */
        		namespace = parameters.get(JSON_NAMESPACE),
        		min = parameters.get("min"),
        		max = parameters.get("max");
        
            // @TODO if 'id' is given it overrides 'index', only index treated below
        
			List<IndexBean> query = new ArrayList<IndexBean>();
			String prefix = makePrefix(application, namespace);
            
            if (application == null)
                throw new InternalBackEndException("missing application argument!");
        	else if (user == null)
                throw new InternalBackEndException("missing user argument!");
            
            try {
            	if (index != null)
            		query = gson.fromJson(index, indexType);

            } catch (final JsonSyntaxException e) {
                LOGGER.debug("Error in Json format", e);
                throw new InternalBackEndException("jSon format is not valid");
            } catch (final JsonParseException e) {
                LOGGER.debug("Error in parsing Json", e);
                throw new InternalBackEndException(e.getMessage());
            }
            
            switch (code) {
            
            case CREATE:
            	message.setMethod(JSON_CODE_CREATE);
            	
            	if (dataId != null){
            		/* we want to subscribe to a data changes */
            		pubsubManager.create(
            				prefix, dataId, user, "_");
            		
            		LOGGER.info(" created subscriptions for {}: {} ", user, dataId);
                	message.setDescription("subscription created: " + dataId +" for user: " + user);
            		
            	} else {
            		/* we want to subscribe to keywords/ categories ... */
            		Collections.sort(query);

                	LOGGER.info("in ."+query.size());
                	
                	LinkedHashMap<String, List<String>> indexes = MatchMaking.formatIndexes(query);
        			
                	List<IndexRow> combi = MatchMaking.getPredicate(
        					indexes, 
        					min!=null?parseInt(min):0,
        					max!=null?parseInt(max):query.size());
					for (IndexRow i : combi) {

						pubsubManager.create(
								prefix,
								i.vals(), user,
								gson.toJson(i.getIndexes(query)));
					}
            		
            		LOGGER.info(" created subscriptions for {}: {} ", user, index);
                	message.setDescription("subscription created: " + index +" for user: " + user);
            	}

            	break;
            		

			default:
				throw new InternalBackEndException("SubscribeRequestHandler.doPost(" + code + ") not exist!");
            	
            }

        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }
}
