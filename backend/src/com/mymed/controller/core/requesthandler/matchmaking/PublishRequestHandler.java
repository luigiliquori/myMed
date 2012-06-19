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

import java.lang.reflect.Type;
import java.net.URLDecoder;
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
import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.pubsub.PubSubManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.locator.Locator;

/**
 * Servlet implementation class PubSubRequestHandler
 */
@MultipartConfig
@WebServlet("/PublishRequestHandler")
public class PublishRequestHandler extends AbstractMatchMaking {

    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = 7612306539244045439L;

    /**
     * JSON 'predicate' attribute.
     */
    private static final String JSON_PREDICATE = JSON.get("json.predicate");

    private final PubSubManager pubsubManager;
    private ProfileManager profileManager;

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
            final String application, predicateListJson, user;
            user = parameters.get(JSON_USERID) != null ? parameters.get(JSON_USERID) : parameters.get(JSON_USER);
            String userID;
            
            switch (code) {
                case READ :
                    break;
                case DELETE :
                    message.setMethod(JSON_CODE_DELETE);
                    if ((application = parameters.get(JSON_APPLICATION)) == null) {
                        throw new InternalBackEndException("missing application argument!");
                    } else if ((predicateListJson = parameters.get(JSON_PREDICATE)) == null) {
                        throw new InternalBackEndException("missing predicate argument!");
                    } else if (user == null) {
                        throw new InternalBackEndException("missing user argument!");
                    }
                   
                    try {
                    	final MUserBean userBean = getGson().fromJson(user, MUserBean.class);
                    	userID = userBean.getId();
                    } catch (final JsonSyntaxException e) {
                    	userID = user;
                    }
                    
                    try {
                        final Type dataType = new TypeToken<List<MDataBean>>() {
                        }.getType();
                        final List<MDataBean> predicateListObject = getGson().fromJson(predicateListJson, dataType);

                        // construct the subPredicate
                        final StringBuffer bufferSubPredicate = new StringBuffer(150);
                        for (final MDataBean element : predicateListObject) {
                            bufferSubPredicate.append(element.getKey());
                            bufferSubPredicate.append(element.getValue());
                        }
                        bufferSubPredicate.trimToSize();

                        String data_id = parameters.get("id") != null ? parameters.get("id") : bufferSubPredicate.toString();

                        // construct indexes 
                        int level = predicateListObject.size();
                        if (parameters.get("level") != null){
                        	level = Integer.parseInt(parameters.get("level"));
                        }
                        List<StringBuffer> predicates = getPredicate(predicateListObject, level);
                        
                        LOGGER.info("deleting "+data_id+" with level: "+level);
                        for(StringBuffer predicate : predicates) {
                        	pubsubManager.delete(application, predicate.toString(), data_id, userID);
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
            final String application, predicateListJson, user, data;
            user = parameters.get(JSON_USERID) != null ? parameters.get(JSON_USERID) : parameters.get(JSON_USER);
            
            
            if (code.equals(RequestCode.CREATE)) {
                message.setMethod(JSON_CODE_CREATE);
                if ((application = parameters.get(JSON_APPLICATION)) == null) {
                    throw new InternalBackEndException("missing application argument!");
                } else if ((predicateListJson = parameters.get(JSON_PREDICATE)) == null) {
                    throw new InternalBackEndException("missing predicate argument!");
                } else if (user == null) {
                    throw new InternalBackEndException("missing userID argument!");
                } else if ((data = parameters.get(JSON_DATA)) == null) {
                    throw new InternalBackEndException("missing data argument!");
                }

                MUserBean userBean;
                try {
                	userBean = getGson().fromJson(user, MUserBean.class);
                } catch (final JsonSyntaxException e) {
                	userBean = profileManager.read(user);
                }
                try {
                    final Type dataType = new TypeToken<List<MDataBean>>() {
                    }.getType();
                    final List<MDataBean> dataList = getGson().fromJson(data, dataType);
                    final List<MDataBean> predicateListObject = getGson().fromJson(predicateListJson, dataType);

                    // construct the subPredicate
                    final StringBuffer bufferSubPredicate = new StringBuffer(150);
                    for (final MDataBean element : predicateListObject) {
                    	bufferSubPredicate.append(element.getKey());
                    	bufferSubPredicate.append(element.getValue());
                    }

                    bufferSubPredicate.trimToSize();
                    
                    String data_id = parameters.get("id") != null?parameters.get("id"):bufferSubPredicate.toString();

                    // construct indexes 
                    int level = predicateListObject.size();
                    if (parameters.get("level") != null){
                    	level = Integer.parseInt(parameters.get("level"));
                    }
                    
                    /* store as indexes only keywords.
                     * date, gps are managed another way
                     * you don't need to store 43.121524, 7.12848421 as a row in your table, or 2012-08-07T14:36:47
                     * because rows are not ordered, you will never find back those fine-grained values
                     */
                    
                    List<MDataBean> keywords = getOntologyList( predicateListObject, MDataBean.KEYWORD);
                    
                    List<StringBuffer> predicates = getPredicate(keywords, level);
                    
                    LOGGER.info("indexing "+data_id+" with level: "+level);
                    for(StringBuffer predicate : predicates) {
                		pubsubManager.create(application, predicate.toString(),data_id, userBean, dataList);
                    }
                    
                    /* store positions  */
                    List<MDataBean> positions = getOntologyList( predicateListObject, MDataBean.GPS);
                    
                    LOGGER.info("storing position index for "+data_id);
                    for(MDataBean predicate : positions) {
						int latitude = Integer.parseInt(predicate.getValue().split(",")[0]);
						int longitude = Integer.parseInt(predicate.getValue().split(",")[1]);
						final long locId = Locator.getLocationId(latitude, longitude);
						final String areaId = String.valueOf(Locator.getAreaId(locId));
                		pubsubManager.create(application, predicate.getKey() + areaId, 
                				MDataBean.GPS+locId+"+"+data_id, userBean, dataList);
                    }
                    
                    /* store dates  */
                    List<MDataBean> dates = getOntologyList( predicateListObject, MDataBean.DATE);
                    
                    LOGGER.info("storing date index for "+data_id);
                    for(MDataBean predicate : dates) {
                		pubsubManager.create(application, predicate.getKey() + predicate.getValue().substring(0,10), //keeps day only
                				MDataBean.DATE+predicate.getValue().substring(11)+"+"+data_id, userBean, dataList);
                    }
                    
                } catch (final JsonSyntaxException e) {
                    LOGGER.debug("Error in Json format", e);
                    throw new InternalBackEndException("jSon format is not valid");
                } catch (final JsonParseException e) {
                    LOGGER.debug("Error in parsing Json", e);
                    throw new InternalBackEndException(e.getMessage());
                } catch (GeoLocationOutOfBoundException e) {
					// TODO Auto-generated catch block
                	LOGGER.debug("Error getting Positions", e);
					e.printStackTrace();
				}
            } else {
                throw new InternalBackEndException("PublishRequestHandler(" + code + ") not exist!");
            }
        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }
}