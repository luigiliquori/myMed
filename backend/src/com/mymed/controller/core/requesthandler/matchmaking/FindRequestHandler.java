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
package com.mymed.controller.core.requesthandler.matchmaking;

import java.io.UnsupportedEncodingException;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.pubsub.PubSubManager;
import com.mymed.controller.core.requesthandler.AbstractRequestHandler;
import com.mymed.controller.core.requesthandler.message.JsonMessage;

/**
 * Servlet implementation class PubSubRequestHandler
 */
public class FindRequestHandler extends AbstractRequestHandler {

    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = 4295832798531448329L;

    /**
     * JSON 'results' attribute.
     */
    private static final String JSON_RESULTS = JSON.get("json.results");

    /**
     * JSON 'predicate' attribute.
     */
    private static final String JSON_PREDICATE = JSON.get("json.predicate");

    /**
     * JSON 'details' attribute.
     */
    private static final String JSON_DETAILS = JSON.get("json.details");

    private final PubSubManager pubsubManager;

    public FindRequestHandler() {
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
            final String application, predicate, user;

            if (code == RequestCode.READ) {
              
            	message.setMethod(JSON_CODE_READ);
                if ((application = parameters.get(JSON_APPLICATION)) == null) {
                    throw new InternalBackEndException("missing application argument!");
                } else if ((predicate = parameters.get(JSON_PREDICATE)) == null) {
                	throw new InternalBackEndException("missing predicate argument!");
                }
               
                user = parameters.get(JSON_USERID) != null ? parameters.get(JSON_USERID) : parameters.get(JSON_USER);
               
                if (user != null) { // GET DETAILS
                    final List<Map<String, String>> details = pubsubManager.read(application, predicate, user);
                    if (details.isEmpty()) {
                        throw new IOBackEndException("no results found!", 404);
                    }
                    message.setDescription("Details found for Application: " + application + " User: " + user
                                    + " Predicate: " + predicate);
                    LOGGER.info("Details found for Application: " + application + " User: " + user + " Predicate: "
                                    + predicate);
                    message.addData(JSON_DETAILS, getGson().toJson(details));
                    message.addDataObject(JSON_DETAILS, details);
               
                } else { // GET RESULTS
                	
					String start = parameters.get("start") != null ? parameters.get("start") : "";
					int count = parameters.get("count") != null ? Integer.parseInt(parameters.get("count")) : 100;
                    final List<Map<String, String>> resList = pubsubManager.read(application, predicate, start, count, false);
                    if (resList.isEmpty()) {
                        throw new IOBackEndException("No reslult found for Application: " + application
                                        + " Predicate: " + predicate, 404);
                    }
                    message.setDescription("Results found for Application: " + application + " Predicate: " + predicate);
                    LOGGER.info("Results found for Application: " + application + " Predicate: " + predicate
                    		+ " start: " + start + " count: " + count );
                    message.addData(JSON_RESULTS, getGson().toJson(resList));
                    message.addDataObject(JSON_RESULTS, resList);
                    
                }
            } else {
                throw new InternalBackEndException("FindRequestHandler(" + code + ") not exist!");
            }
        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doGet operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        } catch (UnsupportedEncodingException e) {
        	LOGGER.debug("Error in doGet operation", e);
            message.setStatus(404);
            message.setDescription(e.getMessage());
		}

        printJSonResponse(message, response);
    }

    /**
     * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
     *      response)
     */
    @Override
    protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessage<Object> message = new JsonMessage<Object>(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);

            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));

            if (code != RequestCode.CREATE) {
                throw new InternalBackEndException("FindRequestHandler(" + code + ") not exist!");
            }
        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }
}