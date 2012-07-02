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
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
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
    
    private static final String JSON_DETAILS = JSON.get("json.details");
    
    private static final String JSON_PREDICATE_LIST = JSON.get("json.predicate.list");

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

        if ( !request.getContentType().matches("multipart/form-data.*")) {
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
            final String application, predicate;
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
						application, predicate, userId);
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
	                Collections.sort(predicateList);
	            } catch (final JsonSyntaxException e) {
	                throw new InternalBackEndException("jSon format is not valid");
	            } catch (final JsonParseException e) {
	                throw new InternalBackEndException(e.getMessage());
	            }
	            int level = predicateList.size();
	            if (parameters.get("level") != null){
	            	level = Integer.parseInt(parameters.get("level"));
	            }
	            dataId = parameters.get("id") != null ? parameters.get("id") : PubSub.toString(predicateList);

				LOGGER.info("deleting " + dataId + " with level: " + level);
				for (int i = 1; i <= level; i++) {
					List<List<PubSub.Index>> predicates = PubSub.getIndex(
							predicateList, i);
					/* store indexes for this data */
					// LOGGER.info("indexing "+data_id+" with level: "+i);
					for (List<PubSub.Index> p : predicates) {
						pubsubManager.deleteIndex(application,
								PubSub.Index.toRowString(p),
								PubSub.Index.toColString(p) + dataId,
								userId);
					}
				}
				
				/* deletes data */
				pubsubManager.deleteData(application, dataId, userId);

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
            final String application, userId, data, dataId;
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
            predicateList = PubSub.getPredicate(dataList);
            
            level = predicateList.size();
            if (parameters.get("level") != null){
            	level = Integer.parseInt(parameters.get("level"));
            }
            
            Collections.sort(predicateList); 
            dataId = parameters.get("id") != null ? parameters.get("id") : PubSub.toString(predicateList);
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
					List<List<PubSub.Index>> predicates = PubSub.getIndex(predicateList, i);
					/* store indexes for this data */
                    LOGGER.info("indexing "+dataId+" with level: "+i+"."+predicates.size());
                    for(List<PubSub.Index> p : predicates) {
                    	String s1 = PubSub.Index.toRowString(p);
                    	String s2 = PubSub.Index.toColString(p);
                		pubsubManager.createIndex(application, s1, s2, dataId, userBean, dataList);
                		pubsubManager.createMail(application, s1, userBean, dataList);
                    }
                }
				
				pubsubManager.createData(application, dataId, userBean, dataList);

				break;
				
			case UPDATE:
				message.setMethod(JSON_CODE_UPDATE);
				
				LOGGER.info("creating/updating "+dataId+" size "+dataList.size());
				pubsubManager.createData(application, dataId, userBean, dataList);
				
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