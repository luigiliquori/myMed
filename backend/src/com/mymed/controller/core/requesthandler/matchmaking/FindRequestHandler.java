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
import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.TreeMap;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.pubsub.PubSubManager;
import com.mymed.controller.core.requesthandler.AbstractRequestHandler;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.QueryBean;

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
            final String application, predicate, queryJson, user;
            List<QueryBean> query = null;
            
            if (code == RequestCode.READ) {
              
            	message.setMethod(JSON_CODE_READ);
                if ((application = parameters.get(JSON_APPLICATION)) == null) {
                    throw new InternalBackEndException("missing application argument!");
                } else if ((predicate = parameters.get(JSON_PREDICATE)) == null) {
                	throw new InternalBackEndException("missing predicate argument!");
                } else if ((queryJson = parameters.get("predicateList")) != null) { //extended find (differents ontologyID)
                	try{
                    	final Type dataType = new TypeToken<List<QueryBean>>() {}.getType();
                        query = getGson().fromJson(queryJson, dataType);
    	            } catch (final JsonSyntaxException e) {
    	                LOGGER.debug("Error in Json format", e);
    	                throw new InternalBackEndException("jSon format is not valid");
    	            } catch (final JsonParseException e) {
    	                LOGGER.debug("Error in parsing Json", e);
    	                throw new InternalBackEndException(e.getMessage());
    	            }
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
					
					
					List<Map<String, String>> resList = new ArrayList<Map<String, String>>();
					TreeMap<String, Map<String, String>> resMap;
					
                    if (query !=null){
                    	List<List<String>> keys = new ArrayList<List<String>>();
                    	List<String[]> bounds = new ArrayList<String[]>();
                    	
                    	List<String> l;
                    	for (QueryBean item : query){
							if ( (l=item.getGroups()).size() > 0){
								keys.add(l);
								bounds.add(new String[] {item.getValueStart(), item.getValueFinish()});
                    		}else{
                    			l = new ArrayList<String>();
                    			l.add(item.toString());
                    			keys.add(l);
                    		}
                    	}
                    	
                    	List<String> rows = new ArrayList<String>();
                    	
                    	l = new ArrayList<String>();
            			l.add(application);
                    	keys.add(0, l);
                    	constructRows(keys, new int[keys.size()], 0, rows);
                    	
                    	if (bounds.size() == 0){ // no range queries
                    		resMap = pubsubManager.read(application, rows, "", ""); //there is just one elt in rows
                    	} else { //perform the first range query, then other one will filter
                    		String[] bound = bounds.remove(0);
                    		resMap = pubsubManager.read(application, rows, bound[0], bound[1]);
                    		while (bounds.size() > 0){ //filter
                    			bound = bounds.remove(0);
                    			// remove one layer of comparators
                    			for (String key : resMap.keySet()){
                    				Map<String, String> val = resMap.remove(key);
                    				String[] parts = key.split("\\+", 2);
                    				resMap.put(parts[parts.length-1], val);
                    			}
                    			//now the Map is sorted on bounds ontology values, do the slice on it
								resMap = new TreeMap<String, Map<String, String>>(
										resMap.subMap(bound[0], true, bound[1], true));
                    			
                    		}
                    	}
                    	
                    	for ( Map<String, String> m : resMap.values()){
                    		resList.add(m);
                        }
                    	
                    } else {
                    	resList = pubsubManager.read(application, predicate, start, count, false);
                    }
                    
                    
					
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
    
    
    public static void constructRows(List<List<String>> data, int[] pos, int n, List<String> res) {
	    if (n == pos.length) {
	        StringBuilder b = new StringBuilder();
	        for (int i = 0 ; i != pos.length ; i++) {
	            b.append(data.get(i).get(pos[i]));
	        }
	        res.add(b.toString());
	    } else {
	        for (pos[n] = 0 ; pos[n] != data.get(n).size() ; pos[n]++) {
	        	constructRows(data, pos, n+1, res);
	        }
	    }
	}

    
}
