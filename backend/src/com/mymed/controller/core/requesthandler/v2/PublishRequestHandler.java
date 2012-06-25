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

import java.lang.reflect.Type;
import java.net.URLDecoder;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Scanner;

import javax.servlet.ServletException;
import javax.servlet.annotation.MultipartConfig;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.Part;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.pubsub.PubSubManager;
import com.mymed.controller.core.requesthandler.AbstractRequestHandler;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.PubSub;

/**
 * Servlet implementation class PubSubRequestHandler
 */
@MultipartConfig
@WebServlet("/v2/PublishRequestHandler")
public class PublishRequestHandler extends AbstractRequestHandler {

    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = 7612306539244045439L;

    /**
     * JSON 'predicate' attribute.
     */
    private static final String JSON_PREDICATE = JSON.get("json.predicate");

    private final PubSubManager pubsubManager;
    private final ProfileManager profileManager;

    public PublishRequestHandler() throws InternalBackEndException {
        super();
        profileManager = new ProfileManager();
        pubsubManager = new PubSubManager();
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.controller.core.requesthandler.AbstractRequestHandler#getParameters
     * (javax.servlet.http.HttpServletRequest)
     */
    @Override
    protected Map<String, String> getParameters(final HttpServletRequest request) throws AbstractMymedException {

        if ((request.getContentType() != null) && !request.getContentType().matches("multipart/form-data.*")) {
            return super.getParameters(request);
        }

        final Map<String, String> parameters = new HashMap<String, String>();

        try {
            for (final Part part : request.getParts()) {
                final String key = part.getName();
                final Scanner s = new Scanner(part.getInputStream());
                final String value = URLDecoder.decode(s.nextLine(), ENCODING);
                parameters.put(key, value);
            }
        } catch (final Exception e) {
            LOGGER.debug("Error retrieving arguments", e);
            throw new InternalBackEndException("Error in getting arguments");
        }

        if (!parameters.containsKey(JSON_CODE)) {
            throw new InternalBackEndException("code argument is missing!");
        }

        return parameters;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.controller.core.requesthandler.AbstractRequestHandler#doGet
     * (javax.servlet.http.HttpServletRequest, javax.servlet.http.HttpServletResponse)
     */
    @Override
    protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessage<Object> message = new JsonMessage<Object>(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);

            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String application, predicateListJson, dataId, userId;
            
            if ((application = parameters.get(JSON_APPLICATION)) == null) {
                throw new InternalBackEndException("missing application argument!");
            } else if ((predicateListJson = parameters.get(JSON_PREDICATE)) == null) {
                throw new InternalBackEndException("missing predicate argument!");
            } else if ((userId = parameters.get(JSON_USERID)) == null) {
                throw new InternalBackEndException("missing user argument!");
            } else if ((dataId = parameters.get("id")) == null) {
                throw new InternalBackEndException("missing id argument!");
            }
            
            switch (code) {
                case READ :
                    break;
                case DELETE :
                    message.setMethod(JSON_CODE_DELETE);
                    
                    try {
                        final Type dataType = new TypeToken<List<MDataBean>>() {
                        }.getType();
                        final List<MDataBean> predicateList = getGson().fromJson(predicateListJson, dataType);
                        Collections.sort(predicateList);
                        		
                        int level = 3;
                        if (parameters.get("level") != null){
                        	level = Integer.parseInt(parameters.get("level"));
                        }
                        
                        LOGGER.info("deleting "+dataId+" with level: "+level);
                        for (int i = 1; i <= level; i++) {
                        	List<List<PubSub.Index>> predicates = PubSub.getPredicate(predicateList, i);
    						/* store indexes for this data */
    	                    //LOGGER.info("indexing "+data_id+" with level: "+i);
    	                    for(List<PubSub.Index> predicate : predicates) {
    	                		pubsubManager.delete(application, PubSub.Index.toRowString(predicate), PubSub.Index.toColString(predicate)+dataId, userId);
    	                    }
                        }
                        
                    } catch (final JsonSyntaxException e) {
                        throw new InternalBackEndException("jSon format is not valid");
                    } catch (final JsonParseException e) {
                        throw new InternalBackEndException(e.getMessage());
                    }
                    break;
                default :
                    throw new InternalBackEndException("PublishRequestHandler(" + code + ") not exist!");
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
     * @see com.mymed.controller.core.requesthandler.AbstractRequestHandler#doPost
     * (javax.servlet.http.HttpServletRequest, javax.servlet.http.HttpServletResponse)
     */
    @Override
    protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessage<Object> message = new JsonMessage<Object>(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);

            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String application, userId, data, dataId;
            
            if ((application = parameters.get(JSON_APPLICATION)) == null) {
                throw new InternalBackEndException("missing application argument!");
            } else if ((userId = parameters.get(JSON_USERID)) == null) {
                throw new InternalBackEndException("missing userID argument!");
            } else if ((data = parameters.get(JSON_DATA)) == null) {
                throw new InternalBackEndException("missing data argument!");
            }
            
            MUserBean userBean = profileManager.read(userId);
            
			switch (code) {

			case CREATE:
				message.setMethod(JSON_CODE_CREATE);
                

                try {
                    final Type dataType = new TypeToken<List<MDataBean>>() {}.getType();
                    final List<MDataBean> dataList = getGson().fromJson(data, dataType);
  
					dataId = parameters.get("id") != null ? parameters.get("id") : Long.toString(System.currentTimeMillis());
				
                    /* construct indexes */
                    int level = 3;
                    if (parameters.get("level") != null){
                    	level = Integer.parseInt(parameters.get("level"));
                    }
                    
                    /* split dataMap between indexable data ("predicates in v1") and other data*/
                    final List<MDataBean> predicateList = new ArrayList<MDataBean>();
                    
                    for (MDataBean d : dataList){
                    	//int ontID = PubSub.parseInt(d.getOntologyID());
                    	if (d.getOntologyID() < PubSub.TEXT) {
                    		predicateList.add(d);
                    	}
                    }
                    Collections.sort(predicateList); 
                    
					for (int i = 1; i <= level; i++) {
						List<List<PubSub.Index>> predicates = PubSub.getPredicate(predicateList, i);
						/* store indexes for this data */
	                    LOGGER.info("indexing "+dataId+" with level: "+i+"."+predicates.size());
	                    for(List<PubSub.Index> p : predicates) {
	                    	String s1 = PubSub.Index.toRowString(p);
	                    	String s2 = PubSub.Index.toColString(p);
	                		pubsubManager.createIndex(application, s1, s2, dataId, userBean, dataList);
	                		pubsubManager.sendMail(application, s1, userBean, dataList);
	                    }
                    }

                } catch (final JsonSyntaxException e) {
                    LOGGER.debug("Error in Json format", e);
                    throw new InternalBackEndException("jSon format is not valid");
                } catch (final JsonParseException e) {
                    LOGGER.debug("Error in parsing Json", e);
                    throw new InternalBackEndException(e.getMessage());
                }
				
				break;

			default:
				throw new InternalBackEndException("PublishRequestHandler("
						+ code + ") not exist!");
			}
			
            
        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }
}