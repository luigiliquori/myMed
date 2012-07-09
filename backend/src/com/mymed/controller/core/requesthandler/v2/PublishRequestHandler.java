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

import static com.mymed.utils.PubSub.getIndex;
import static com.mymed.utils.PubSub.getIndexData;
import static com.mymed.utils.PubSub.join;
import static com.mymed.utils.PubSub.makePrefix;
import static com.mymed.utils.PubSub.Index.joinCols;
import static com.mymed.utils.PubSub.Index.joinRows;

import java.lang.reflect.Type;
import java.util.Collections;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.MultipartConfig;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.PubSub.Index;

/**
 * Servlet implementation class PubSubRequestHandler
 */
@MultipartConfig
@WebServlet("/v2/PublishRequestHandler")
public class PublishRequestHandler extends com.mymed.controller.core.requesthandler.matchmaking.PublishRequestHandler {

    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = 7612306539244045439L;

    /**
     * JSON 'predicate' attribute.
     */
    
    private static final String JSON_PREDICATE = JSON.get("json.predicate");
    
    private static final String JSON_DETAILS = JSON.get("json.details");
    
    private static final String JSON_PREDICATE_LIST = JSON.get("json.predicate.list");

    public PublishRequestHandler() throws InternalBackEndException {
        super();
        profileManager = new ProfileManager();
        pubsubManager = new PubSubManager();
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
            final String application, predicate, namespace = parameters.get(JSON_NAMESPACE);
			String predicateListJson = null;
			final String dataId, userId;
            final List<MDataBean> predicateList;
            
            if ((application = parameters.get(JSON_APPLICATION)) == null) {
                throw new InternalBackEndException("missing application argument!");
            } else if ((predicate = parameters.get(JSON_PREDICATE)) == null &&  (predicateListJson = parameters.get(JSON_PREDICATE_LIST)) == null) {
				throw new InternalBackEndException("missing predicate or predicateList argument!");
			} else if ((userId = parameters.get(JSON_USERID)) == null) {
                throw new InternalBackEndException("missing user argument!");
            }
            
            switch (code) {
			case READ:
				message.setMethod(JSON_CODE_READ);
				
				// Get DATA (details)
				final List<Map<String, String>> details = pubsubManager.read(
						makePrefix(application, namespace), predicate, userId);
				if (details.isEmpty()) {
					throw new IOBackEndException("no results found!", 404);
				}
				message.setDescription("Details found for Application: "
						+ application + " User: " + userId + " Predicate: "
						+ predicate);
				LOGGER.info("Details found for Application: " + application
						+ " User: " + userId + " Predicate: " + predicate);

				message.addDataObject(JSON_DETAILS, details);
				break;
				
			case DELETE:
				message.setMethod(JSON_CODE_DELETE);
				
				/* tries to delete the indexes to the data if any */
				try {
	                final Type dataType = new TypeToken<List<MDataBean>>() {}.getType();
	                predicateList = getGson().fromJson(predicateListJson, dataType);
	            } catch (final JsonSyntaxException e) {
	                throw new InternalBackEndException("jSon format is not valid");
	            } catch (final JsonParseException e) {
	                throw new InternalBackEndException(e.getMessage());
	            }
				Collections.sort(predicateList);
	            int level = predicateList.size();
	            if (parameters.get("level") != null){
	            	level = Integer.parseInt(parameters.get("level"));
	            }
	            dataId = parameters.get("id") != null ? parameters.get("id") : join(predicateList);

				LOGGER.info("deleting " + dataId + " with level: " + level);
				for (int i = 1; i <= level; i++) {
					List<List<Index>> predicates = getIndex(
							predicateList, i);
					/* store indexes for this data */
					// LOGGER.info("indexing "+data_id+" with level: "+i);
					for (List<Index> p : predicates) {
						pubsubManager.delete(makePrefix(application, namespace),
								joinRows(p),
								joinCols(p) + dataId,
								userId);
					}
				}
				
				/* deletes data */
				pubsubManager.delete(application, dataId + userId);

				break;
			default:
				throw new InternalBackEndException("PublishRequestHandler("
						+ code + ") not exist!");
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
            final String application, userId, data, dataId, namespace = parameters.get(JSON_NAMESPACE);
			int level;
			final List<MDataBean> predicateList, dataList;
			
            
            if ((application = parameters.get(JSON_APPLICATION)) == null) {
                throw new InternalBackEndException("missing application argument!");
            } else if ((userId = parameters.get(JSON_USERID)) == null) {
                throw new InternalBackEndException("missing userID argument!");
            } else if ((data = parameters.get(JSON_DATA)) == null) {
                throw new InternalBackEndException("missing data argument!");
            }

            try {
                final Type dataType = new TypeToken<List<MDataBean>>() {}.getType();
                dataList = getGson().fromJson(data, dataType);

            } catch (final JsonSyntaxException e) {
                LOGGER.debug("Error in Json format", e);
                throw new InternalBackEndException("jSon format is not valid");
            } catch (final JsonParseException e) {
                LOGGER.debug("Error in parsing Json", e);
                throw new InternalBackEndException(e.getMessage());
            }
            
            final MUserBean userBean = profileManager.read(userId);
            
            /* split dataList between indexable data ("predicates in v1") and other data*/
            predicateList = getIndexData(dataList);
            
            level = predicateList.size();
            if (parameters.get("level") != null){
            	level = Integer.parseInt(parameters.get("level"));
            }
            
            Collections.sort(predicateList); 
            dataId = parameters.get("id") != null ? parameters.get("id") : join(predicateList);
            LOGGER.info("in "+dataId+" with level: "+level+"."+predicateList.size());
			switch (code) {

			case CREATE:
				message.setMethod(JSON_CODE_CREATE);
				
				/*
				 * construct all composite indexes* under level length: 
				 *  *->all subsets of predicateList
				 *  @see PubSub.getIndex
				 */
 
				for (int i = 1; i <= level; i++) {
					List<List<Index>> predicates = getIndex(predicateList, i);
					/* store indexes for this data */
                    LOGGER.info("indexing "+dataId+" with level: "+i+"."+predicates.size());
                    for(List<Index> p : predicates) {
                    	String s1 = joinRows(p);
                    	String s2 = joinCols(p);
                    	
                    	// TODO Add the predicate list
                		pubsubManager.create(makePrefix(application, namespace), s1, s2, dataId, userBean, dataList, null);
                		pubsubManager.sendEmailsToSubscribers(makePrefix(application, namespace), s1, userBean, dataList);
                    }
                }
				
				pubsubManager.create(makePrefix(application, namespace), dataId, userId, dataList);

				break;
				
			case UPDATE:
				message.setMethod(JSON_CODE_UPDATE);
				
				LOGGER.info("creating/updating "+dataId+" size "+dataList.size());
				pubsubManager.create(makePrefix(application, namespace), dataId, userId, dataList);
				pubsubManager.sendEmailsToSubscribers(makePrefix(application, namespace), dataId, userBean, dataList);
				
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
        
        /*
         * @TODO should create mails there? (after response sent)
         */
        
    }
}