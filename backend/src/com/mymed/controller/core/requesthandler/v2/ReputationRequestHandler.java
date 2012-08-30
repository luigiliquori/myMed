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
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.ReputationManager;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedAppUserId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.ReputationRole;
import com.mymed.controller.core.requesthandler.message.JsonMessageOut;
import com.mymed.model.data.reputation.MReputationEntity;
/**
 * Servlet implementation class ReputationRequestHandler
 */
@WebServlet("/v2/ReputationRequestHandler")
public class ReputationRequestHandler extends AbstractRequestHandler {
    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = -1758781146254746346L;

    /**
     * The JSON 'reputation' attribute.
     */
    private static final String JSON_REPUTATION = JSON.get("json.reputation");

    /**
     * The JSON 'producer' attribute.
     */
    private static final String JSON_PRODUCER = JSON.get("json.producer");
    private static final String JSON_CONSUMER = JSON.get("json.consumer");

    private final ReputationManager reputationManager;

    public ReputationRequestHandler() throws ServletException {
        super();
        reputationManager = new ReputationManager();
    }

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.controller.core.requesthandler.AbstractRequestHandler#doGet
     * (javax.servlet.http.HttpServletRequest,
     * javax.servlet.http.HttpServletResponse)
     */
    @Override
    protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessageOut<Object> message = new JsonMessageOut<Object>(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);
            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String 
            	application = parameters.get(JSON_APPLICATION), 
            	producer = parameters.get(JSON_PRODUCER),
            	producers = parameters.get("producers"),
            	consumer = parameters.get(JSON_CONSUMER);

            switch (code) {
                case READ :
                    if (application == null)
                        throw new InternalBackEndException("missing application argument!");
                    

                    if (producer != null){
                    	final MymedAppUserId user = new MymedAppUserId(application, producer, ReputationRole.Producer);
                        final MReputationEntity reputation = reputationManager.read(user, consumer);

                        message.addDataObject(JSON_REPUTATION, reputation);
                    } else if(producers != null){
                    	List<String> prods = new ArrayList<String>();
                    	try {
                    		prods = gson.fromJson(producers, listType);
            			} catch (final JsonSyntaxException e) {
            				LOGGER.debug("Error in Json format", e);
            				throw new InternalBackEndException("jSon format is not valid");
            			} catch (final JsonParseException e) {
            				LOGGER.debug("Error in parsing Json", e);
            				throw new InternalBackEndException(e.getMessage());
            			}
                    	List<MymedAppUserId> repIds = new ArrayList<MymedAppUserId>();
                    	for (String p : prods)
                    		repIds.add(new MymedAppUserId(application, p, ReputationRole.Producer));
                    	
                        final Map<String, MReputationEntity> reputationMap = reputationManager.read(repIds, consumer);

                        message.addDataObject(JSON_REPUTATION, reputationMap);
                    	
                    } else {
                    	throw new InternalBackEndException("missing producer(s) argument!");
                    }
                        

                    break;
                case DELETE :
                    break;
                default :
                    throw new InternalBackEndException("ReputationRequestHandler.doGet(" + code + ") not exist!");
            }
        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doGet operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.controller.core.requesthandler.AbstractRequestHandler#doPost
     * (javax.servlet.http.HttpServletRequest,
     * javax.servlet.http.HttpServletResponse)
     */
    @Override
    protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessageOut<Object> message = new JsonMessageOut<Object>(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);
            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));

            switch (code) {
                case CREATE :
                case UPDATE :
                    break;
                default :
                    throw new InternalBackEndException("ReputationRequestHandler.doPost(" + code + ") not exist!");
            }
        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }
}
