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
import static com.mymed.utils.PubSub.subList;

import java.util.ArrayList;
import java.util.Collections;
import java.util.LinkedHashMap;
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
import com.mymed.model.data.application.DataBean;
import com.mymed.model.data.application.IndexBean;
import com.mymed.model.data.application.MOntologyID;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.PubSub;
import com.mymed.utils.PubSub.Index;


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

    protected PubSubManager pubsubManager;
    protected ProfileManager profileManager;

    /**
     * JSON 'predicate' attribute.
     */
    
    private static final String JSON_DETAILS = JSON.get("json.details");

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
            final String application, dataId, namespace = parameters.get(JSON_NAMESPACE);
            
            if ((application = parameters.get(JSON_APPLICATION)) == null) {
                throw new InternalBackEndException("missing application argument!");
            } else if ((dataId = parameters.get("id")) == null ) {
				throw new InternalBackEndException("missing id argument!");
			}
            
            String prefix = makePrefix(application, namespace);
            
			switch (code) {
			case READ:
				message.setMethod(JSON_CODE_READ);

				// Get DATA (details)
				final List<DataBean> details = pubsubManager.read(prefix, dataId);
				if (details.isEmpty()) {
					throw new IOBackEndException("no results found!", 404);
				}
				message.setDescription("Details found for Application: "
						+ application + " Predicate: " + dataId);
				LOGGER.info("Details found for Application: " + application
						+ " Predicate: " + dataId);

				message.addDataObject(JSON_DETAILS, details);
				break;
				
			case DELETE:

				message.setMethod(JSON_CODE_DELETE);
				LOGGER.info("deleting " + dataId);
				String index = pubsubManager.read(prefix, dataId, "_index");

				List<IndexBean> indexList = new ArrayList<IndexBean>();
				try {
					indexList = gson.fromJson(index,
							new TypeToken<List<IndexBean>>() {}.getType());
				} catch (final JsonSyntaxException e) {
					LOGGER.debug("Error in Json format", e);
					throw new InternalBackEndException(
							"jSon format is not valid");
				} catch (final JsonParseException e) {
					LOGGER.debug("Error in parsing Json", e);
					throw new InternalBackEndException(e.getMessage());
				}
				Collections.sort(indexList);

				LOGGER.info(" deleting  " + dataId + "." + indexList.size());

				LinkedHashMap<String, List<Index>> indexes = PubSub
						.formatIndexes(indexList);

				List<Index> combi = PubSub.getPredicate(indexes, 0,
						indexList.size());
				for (Index i : combi) {
					pubsubManager.delete(makePrefix(application, namespace),
							i.row, i.col + dataId, null);
				}

				/* deletes data */
				pubsubManager
						.delete(makePrefix(application, namespace), dataId);

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
            final String 
            	application = parameters.get(JSON_APPLICATION), 
            	data = parameters.get(JSON_DATA),
            	index = parameters.get("index"),
            	dataId = parameters.get("id"),
            	userId = parameters.get(JSON_USER), 
            	namespace = parameters.get(JSON_NAMESPACE);
			
			
            if (application == null) {
                throw new InternalBackEndException("missing application argument!");
            }
            List<IndexBean> indexList = new ArrayList<IndexBean>();
            List<DataBean> dataList = new ArrayList<DataBean>();

            try {
            	if (data != null){
	                dataList = gson.fromJson(data, new TypeToken<List<DataBean>>() {}.getType());
            	}
            	if (index != null){
            		indexList = gson.fromJson(index, new TypeToken<List<IndexBean>>() {}.getType());
            	}
            } catch (final JsonSyntaxException e) {
                LOGGER.debug("Error in Json format", e);
                throw new InternalBackEndException("jSon format is not valid");
            } catch (final JsonParseException e) {
                LOGGER.debug("Error in parsing Json", e);
                throw new InternalBackEndException(e.getMessage());
            }
            
            Collections.sort(dataList);
            Collections.sort(indexList);
           
            LOGGER.info("in "+dataId+"."+dataList.size());
            
            LinkedHashMap<String, List<Index>> indexes = PubSub.formatIndexes(indexList);
			
			List<Index> combi = PubSub.getPredicate(indexes, 0, dataList.size());
        	
        	LOGGER.info("ext find rows: "+combi.size()+" initial: "+combi.get(0));
        	
        	String prefix = makePrefix(application, namespace);

			switch (code) {

			case CREATE:
				message.setMethod(JSON_CODE_CREATE);

				/*
				 * construct all composite indexes* under level length: *->all subsets of predicateList
				 * 
				 * @see PubSub.getIndex
				 */

				if (userId == null) {
					throw new InternalBackEndException("missing user argument!");
				}
				final MUserBean userBean = profileManager.read(userId);

				for (Index i : combi) {
					pubsubManager.create(prefix, i.row, i.col, dataId,
							subList(dataList, MOntologyID.DATA));
					pubsubManager.sendEmailsToSubscribers(prefix, i.row,
							userBean, dataList);
				}

				/*
				 * add indexes as a data, the only way to remove all the indexes
				 * later if necessary
				 */
				dataList.add(new DataBean(MOntologyID.TEXT, "_index", gson
						.toJson(indexList)));
				/* creates data */
				pubsubManager.create(prefix, dataId, dataList);

				break;
				
			case UPDATE:
				message.setMethod(JSON_CODE_UPDATE);

				LOGGER.info("updating data " + dataId + " size " + dataList.size());
				for (Index i : combi) {
					pubsubManager.create(prefix, i.row, i.col, dataId,
							subList(dataList, MOntologyID.DATA));
				}

				/* creates data */
				pubsubManager.create(prefix, dataId, dataList);
				
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