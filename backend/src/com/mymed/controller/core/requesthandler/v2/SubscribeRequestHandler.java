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
import static com.mymed.utils.PubSub.makePrefix;

import java.lang.reflect.Type;
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
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.IndexBean;
import com.mymed.utils.PubSub;
import com.mymed.utils.PubSub.Index;

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
    
    private static final String JSON_SUBSCRIPTIONS = JSON.get("json.subscriptions");

    private final PubSubManager pubsubManager;

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
            String application, user, namespace = parameters.get(JSON_NAMESPACE);
            
            if ((application = parameters.get(JSON_APPLICATION)) == null) {
        		throw new InternalBackEndException("missing application argument!");
        	} else if((user = parameters.get(JSON_USER)) == null){
        		throw new InternalBackEndException("missing user argument!");
        	}
            
			switch (code) {
			case READ:
				message.setMethod(JSON_CODE_READ);

				final Map<String, String> predicates = pubsubManager.read(makePrefix(application, namespace) 
						+ user);
				message.setDescription("Subscriptions found for Application: " 
						+ application + " User: " + user);
				LOGGER.info("Subscriptions found for Application: "+ application + " User: " + user);
				message.addDataObject(JSON_SUBSCRIPTIONS, predicates);
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
            String application, data, user, namespace = parameters.get(JSON_NAMESPACE);
            final List<IndexBean> query;
            
            if ((application = parameters.get(JSON_APPLICATION)) == null) {
                throw new InternalBackEndException("missing application argument!");
        	} else if ((data = parameters.get(JSON_DATA)) == null ) {
				throw new InternalBackEndException("missing data argument!");
            } else if ((user = parameters.get(JSON_USER)) == null) {
                throw new InternalBackEndException("missing user argument!");
            }
            
            try {
                final Type dataType = new TypeToken<List<IndexBean>>() {}.getType();
                query = gson.fromJson(data, dataType);

            } catch (final JsonSyntaxException e) {
                LOGGER.debug("Error in Json format", e);
                throw new InternalBackEndException("jSon format is not valid");
            } catch (final JsonParseException e) {
                LOGGER.debug("Error in parsing Json", e);
                throw new InternalBackEndException(e.getMessage());
            }
        	
        	Collections.sort(query);

        	LOGGER.info("in ."+query.size());
        	
        	LinkedHashMap<String, List<Index>> indexes = PubSub.formatIndexes(query);
			
			List<Index> combi = PubSub.getPredicate(indexes, query.size(), query.size());
        	
        	List<String> rows = PubSub.Index.getRows(combi);
        	 
        	LOGGER.info("sub rows: "+rows.size()+" initial: "+rows.get(0));
            
            switch (code) {
            
            case CREATE:
            	message.setMethod(JSON_CODE_CREATE);
            	
            	for (String key : rows) {
            		if (key.equals("")){
            			LOGGER.info("-------- key is empty: using _ as key for a general sub in this application+namespace");
            			key = "_";
            		}
            		pubsubManager.create(makePrefix(application, namespace), key, user);
            	}

            	LOGGER.info(" created subscriptions for {}: {} ", user, rows);
            	message.setDescription("subscription created: " + rows +" for user: " + user);
            	break;
            	
            case DELETE:
            	message.setMethod(JSON_CODE_DELETE);

            	for (String key : rows) {
            		if (key.equals("")){
            			LOGGER.info("-------- key is empty using: _ as key for stating you are sub to all in this applicationnamespace");
            			key = "_";
            		}
            		pubsubManager.delete(makePrefix(application, namespace), key, user);
            	}

            	LOGGER.info(" deleted subscriptions for {}: {} ", user, rows);
            	message.setDescription("subscription deleted: " + rows +" for user: " + user);
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
