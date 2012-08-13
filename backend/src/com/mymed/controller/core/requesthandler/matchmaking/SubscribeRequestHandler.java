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

import static com.mymed.utils.GsonUtils.gson;
import static com.mymed.utils.MiscUtils.makePrefix;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.pubsub.PubSubManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;

/**
 * Servlet implementation class PubSubRequestHandler
 */
public class SubscribeRequestHandler extends AbstractMatchMaking {
    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = -3497628036243410706L;

    /**
     * JSON 'predicate' attribute.
     */
    private static final String JSON_PREDICATE = JSON.get("json.predicate");
    
    private static final String JSON_SUBSCRIPTIONS = JSON.get("json.subscriptions");

    private final PubSubManager pubsubManager;
    private ProfileManager profileManager;

    public SubscribeRequestHandler() throws InternalBackEndException {
        super();
        profileManager = new ProfileManager();
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
            String application, predicate, predicateList = null, user, namespace = parameters.get(JSON_NAMESPACE);
            
            switch (code) {
                case READ :
                	message.setMethod(JSON_CODE_READ);
                	if ((application = parameters.get(JSON_APPLICATION)) == null) {
                		throw new InternalBackEndException("missing application argument!");
                	} else if((user = parameters.get(JSON_USERID)) == null){
                		throw new InternalBackEndException("missing userID argument!");
                	}
                	final Map<String, String> predicates = pubsubManager.read(makePrefix(application, namespace) + user);
                 	message.setDescription("Subscriptions found for Application: " + application + " User: " + user);
 		            LOGGER.info("Subscriptions found for Application: " + application + " User: " + user);
 		            message.addDataObject(JSON_SUBSCRIPTIONS, predicates);
                    break;
                case DELETE :
                	
                	message.setMethod(JSON_CODE_DELETE);
                	if ((application = parameters.get(JSON_APPLICATION)) == null) {
                        throw new InternalBackEndException("missing application argument!");
                	} else if ((predicate = parameters.get(JSON_PREDICATE)) == null && (predicateList = parameters.get(JSON_PREDICATE_LIST)) == null) {
    					throw new InternalBackEndException("missing predicate or predicateList argument!");
                    } else if ((user = parameters.get(JSON_USERID)) == null) {
                        throw new InternalBackEndException("missing userID argument!");
                    }
                	
                	 if (predicateList != null) {
     					final Type dataType = new TypeToken<List<MDataBean>>() {}.getType();
     					final List<MDataBean> predicateListObject = gson.fromJson(predicateList, dataType);
     					List<String> predList = getPredicate(
     					        predicateListObject, 
     					        predicateListObject.size(),
     					        predicateListObject.size());
     					
     					// Sub predicate
     					predicate = getSubPredicate(predicateListObject);
     				}

                	pubsubManager.delete(makePrefix(application, namespace), user, predicate);
                	LOGGER.info("subscription deleted: " + predicate +" for user: " + user);
                	message.setDescription("subscription deleted: " + predicate +" for user: " + user);
                        
                    break;
                default :
                    throw new InternalBackEndException("SubscribeRequestHandler.doGet(" + code + ") not exist!");
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
            String application, singlePredicate, predicateList = null, user, namespace = parameters.get(JSON_NAMESPACE);
            user = parameters.get(JSON_USERID) != null ? parameters.get(JSON_USERID) : parameters.get(JSON_USER);

            if (code.equals(RequestCode.CREATE)) {
                if ((application = parameters.get(JSON_APPLICATION)) == null) {
                    throw new InternalBackEndException("missing application argument!");
                } else if ((singlePredicate = parameters.get(JSON_PREDICATE)) == null && (predicateList = parameters.get(JSON_PREDICATE_LIST)) == null) {
					throw new InternalBackEndException("missing predicate or predicateList argument!");
                } else if (user == null) {
                    throw new InternalBackEndException("missing userID argument!");
                }
                
                
                // If predicates are given as a list, there might several predicates generated.
                // For example in case of ENUM values
                List<String> predicates;
                
                // List of predicates
                if (predicateList != null) {
					final Type dataType = new TypeToken<List<MDataBean>>() {}.getType();
					final List<MDataBean> predicateListObject = gson.fromJson(predicateList, dataType);
					predicates = getPredicate(
					        predicateListObject, 
					        predicateListObject.size(),
					        predicateListObject.size());
					
				} else {			    
				    // Single predicate
				    predicates = new ArrayList<String>();
				    predicates.add(singlePredicate);
				}

                MUserBean userBean;
                try {
                	userBean = gson.fromJson(user, MUserBean.class);
                } catch (final JsonSyntaxException e) {
                	userBean = profileManager.read(user);
                }
                try {

                    // Subscribe to all predicates
                    StringBuffer description = new StringBuffer();
                    for (String predicate : predicates) {
                        pubsubManager.create(
                                makePrefix(application, namespace), 
                                predicate, 
                                userBean);
                        description.append(predicate + ", ");
                    }
                    message.setDescription("predicates subscribed: " + description.toString());
                } catch (final JsonSyntaxException e) {
                    throw new InternalBackEndException("jSon format is not valid");
                }
            } else {
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
