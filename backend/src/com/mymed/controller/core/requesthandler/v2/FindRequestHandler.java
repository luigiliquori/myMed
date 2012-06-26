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

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.TreeMap;

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
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
import com.mymed.controller.core.requesthandler.AbstractRequestHandler;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.application.QueryBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.PubSub;

@MultipartConfig
@WebServlet("/v2/FindRequestHandler")

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
	 * JSON 'predicate' attribute.
	 */
	private static final String JSON_PREDICATE_LIST = JSON.get("json.predicate.list");

	/**
	 * JSON 'details' attribute.
	 */
	private static final String JSON_DETAILS = JSON.get("json.details");

	private final PubSubManager pubsubManager;
	private final ProfileManager profileManager;

	public FindRequestHandler() throws InternalBackEndException {
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
			final String application, user, predicate;
			String predicateList = null;
			
			final Map<String, QueryBean> queryTmp, query;
			
			if ((application = parameters.get(JSON_APPLICATION)) == null) {
				throw new InternalBackEndException("missing application argument!");
			} else if ((predicate = parameters.get(JSON_PREDICATE)) == null &&  (predicateList = parameters.get(JSON_PREDICATE_LIST)) == null) {
				throw new InternalBackEndException("missing predicate or predicateList argument!");
			}

			// argument is user(Deprecated) or userID
			user = parameters.get(JSON_USERID) != null ? parameters.get(JSON_USERID) : parameters.get(JSON_USER);

			switch (code) {
			case READ :
				message.setMethod(JSON_CODE_READ);
				
				if (user != null) { // GET DETAILS
					final List<Map<String, String>> details = pubsubManager.read(application, predicate, user);
					if (details.isEmpty()) {
						throw new IOBackEndException("no results found!", 404);
					}
					message.setDescription("Details found for Application: " + application + " User: " + user
							+ " Predicate: " + predicate);
					LOGGER.info("Details found for Application: " + application + " User: " + user + " Predicate: "
							+ predicate);
					
					message.addDataObject(JSON_DETAILS, details);

				} else { // GET RESULTS

					String start = parameters.get("start");
					int count = parameters.get("count") != null ? Integer.parseInt(parameters.get("count")) : 100;
					
					try{
	                	final Type dataType = new TypeToken<Map<String, QueryBean>>() {}.getType();
	                	queryTmp = getGson().fromJson(predicateList, dataType);
		            } catch (final JsonSyntaxException e) {
		                LOGGER.debug("Error in Json format", e);
		                throw new InternalBackEndException("jSon format is not valid");
		            } catch (final JsonParseException e) {
		                LOGGER.debug("Error in parsing Json", e);
		                throw new InternalBackEndException(e.getMessage());
		            }
					// sort indexes
					query = new TreeMap<String, QueryBean>(queryTmp);
					
					int level = 3;//query.size(); //by default, this should correspond or be less than the way the data is published
                    if (parameters.get("level") != null){
                    	level = Integer.parseInt(parameters.get("level"));
                    }
                    
					List<Map<String, String>> resList = new ArrayList<Map<String, String>>();
					TreeMap<String, Map<String, String>> resMap;
					
                    if (query !=null){
                    	/**
    					 * @todo what follows is dirty
    					 *   - sorry, working to make it clearer
    					 *   - put more things in the manager less in handler
    					 */
                    	
                    	//perform a "range query" on AppController
                    	// ex: get all data with a price between 18.25 and 19.99
                    	
                    	// integer part of prices are indexed
                    	// ex: price18, price19
                    	// stored in keys
                    	List<List<String>> queryRows = new ArrayList<List<String>>();
                    	
                    	// not indexable parts (cents) are used as bounds for the search
                    	// ex: 25 (cents) to 99 (cents)
                    	List<String[]> ranges = new ArrayList<String[]>();
                    	
                    	/* construct the ROWS to search */
                    	
                    	/* index in query list arguments */
                    	int i=0;

                    	List<String> keys  = new ArrayList<String>(query.keySet());
                    	
                    	for (; i<keys.size() && i<=level; i++){
                    		LOGGER.info("ext find_ "+i);
                    		QueryBean item = query.get(keys.get(i));
                    		queryRows.add(item.getRowIndexes(keys.get(i)));
                    		if (item.getValueEnd().length() != 0){
                    			ranges.add(new String[]{item.getValueStart(), item.getValueEnd()});
                    		}	
                    	}
                    	
                    	/*also add application on top of rows  */
                    	queryRows.add(0, new ArrayList<String>()  {{add(application);}});
                    	
                    	List<String> rows = new ArrayList<String>();
                    	
                    	PubSub.constructRows(queryRows, new int[queryRows.size()], 0, rows);
                    	 
                    	LOGGER.info("ext find rows: "+rows.size()+rows.get(0));
                    	LOGGER.info("ext find ranges: "+ranges.toString());
                    	/* now loop for remaining range queries */

                    	LOGGER.info("ext find "+i);
                    	if (ranges.size() != 0){
                    		String[] range = ranges.remove(0);
							resMap = pubsubManager.read(application, rows, start != null ? start : range[0], range[1]);
                		} else {
                			LOGGER.info("ok");
                			resMap = pubsubManager.read(application, rows, start != null ? start : "", ""); //there is just one elt in rows, should be equivalent to v1
                		}
                    	
                    	/* finished filtering  for i <= level */
                    	while (ranges.size() != 0){ //filter
                    		
                    		String[] range = ranges.remove(0);
                			// uncap one layer of comparators
                			for (String key : resMap.keySet()){
                				Map<String, String> val = resMap.remove(key);
                				String[] parts = key.split("\\+", 2);
                				resMap.put(parts[parts.length-1], val);
                			}
                			//now the Map is re-indexed on the new group values, do the corresponding (Java Map) slice on it
							resMap = new TreeMap<String, Map<String, String>>(
									resMap.subMap(range[0], true, range[1], true));
							
							LOGGER.info("ext uncap under level "+resMap.size());
                			
                		}
                    	
                    	/* filter resMap for remaining query items above level */
                    	
                    	for (; i<keys.size() && i<=level; i++){
                    		LOGGER.info("ext find__ "+i);
                    		QueryBean item = query.get(keys.get(i));
                    		queryRows.add(item.getRowIndexes(keys.get(i)));
                    		
                    		if (item.getValueEnd().length() > 0){ //this tells if it's a range query, valueEnd is empty for match query
                    			/* 
                    			 *  uncap one layer of col prefixes
                    			 *  
                    			 *  either we do that or we could just loop through data and check
                    			 *  if item.getValueStart() < keys.get(i) < item.getValueEnd()
                    			 *   like in the else 
                    			 *   
                    			 *   but this method is more fun
                    			 */
       
                    			for (String key : resMap.keySet()){
                    				Map<String, String> val = resMap.remove(key);
                    				String[] parts = key.split("\\+", 2);
                    				resMap.put(parts[parts.length-1], val);
                    			}
                    			
                    			resMap = new TreeMap<String, Map<String, String>>(
										resMap.subMap(item.getValueStart(), true, item.getValueEnd(), true));
                    			LOGGER.info("ext uncap above level "+resMap.size());
                    			
                    		} else {
                    			/*
                    			 * we remove items that doesn't match the query predicate
                    			 */
                    			if (!PubSub.isPredicate(resMap, keys.get(i), item.getValueStart())){
                    				resMap.remove(keys.get(i));
                    			}
                    			LOGGER.info("ext find  filter by predicate "+resMap.size());
                    		}
                    	}
                    	
                    	/* at the end, get values */
                    	for ( Map<String, String> m : resMap.values()){
                    		resList.add(m);
                        }
                    }
					
					if (resList.isEmpty()) {
						throw new IOBackEndException("No reslult found for Application: " + application
								+ " Predicate: " + predicateList.toString(), 404);
					}
					message.setDescription("Results found for Application: " + application + " Predicate: " + predicateList.toString());
					LOGGER.info("Results found for Application: " + application + " Predicate: " + predicateList.toString()
							+ " start: " + start + " count: " + count );
					
					message.addDataObject(JSON_RESULTS, resList);

				}

				break;

			default :
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
			
			final String application, userId, predicateList, dataId;
            
            if ((application = parameters.get(JSON_APPLICATION)) == null) {
                throw new InternalBackEndException("missing application argument!");
            } else if ((userId = parameters.get(JSON_USERID)) == null) {
                throw new InternalBackEndException("missing userID argument!");
            } else if ((predicateList = parameters.get(JSON_PREDICATE_LIST)) == null) {
                throw new InternalBackEndException("missing predicateList argument!");
            }
            
            MUserBean userBean = profileManager.read(userId);
            
			switch (code) {
				case UPDATE :
					//update indexes
					/**
					 * @todo put more things in the manager less in handler
					 */
					
					message.setMethod(JSON_CODE_UPDATE);

	                try {
	                    final Type dataType = new TypeToken<Map<String, QueryBean>>() {}.getType();
	                    final Map<String, QueryBean> predicateMap = getGson().fromJson(predicateList, dataType);
	  
						dataId = parameters.get("id");
					
	                    /* construct indexes */
	                    int level = predicateMap.size();
	                    if (parameters.get("level") != null){
	                    	level = Integer.parseInt(parameters.get("level"));
	                    }
	                    
	                    List<MDataBean> predicateListOld = new ArrayList<MDataBean>(); //previous indexes
	                    List<MDataBean> predicateListNew = new ArrayList<MDataBean>(); //new indexes
	                    List<MDataBean> metadataList = new ArrayList<MDataBean>(); //other data that we want to also update in DataList
	                    
	                    for (Entry<String, QueryBean> d : predicateMap.entrySet()){
	                    	
	                    	if (d.getValue().getOntologyID() < PubSub.TEXT ) {
	                    		predicateListOld.add( d.getValue().toDataBeanStart(d.getKey()) );
	                    		predicateListNew.add( d.getValue().toDataBeanEnd(d.getKey()) );
	  
	                    	} else { /* save other data that appear in appcontroller */
	                    		metadataList.add( d.getValue().toDataBeanEnd(d.getKey()) );
	                    	}
	                    }
	                    
	                    metadataList.addAll(predicateListNew);
	                    Collections.sort(predicateListNew);
	                    Collections.sort(predicateListOld);
	                    LOGGER.info("update"+level);
	                    
						for (int i = 1; i <= level; i++) {
							List<List<PubSub.Index>> predicatesOld = PubSub.getComposites(predicateListOld, i);
							List<List<PubSub.Index>> predicatesNew = PubSub.getComposites(predicateListNew, i);
							/* store indexes for this data */
		                    LOGGER.info("updating "+dataId+" with level: "+i+"."+predicatesNew.size()+"."+predicatesOld.size());
		                    for(List<PubSub.Index> predicate : predicatesOld) {
		                    	String s1 = PubSub.Index.toRowString(predicate);
		                    	String s2 = PubSub.Index.toColString(predicate);
		                    	LOGGER.info("__"+s1+" "+s2);
		                		pubsubManager.deleteIndex(application, s1, s2 + dataId, userId);
		                    }
		                    for(List<PubSub.Index> predicate : predicatesNew) {
		                    	String s1 = PubSub.Index.toRowString(predicate);
		                    	String s2 = PubSub.Index.toColString(predicate);
		                    	LOGGER.info("____"+s1+" "+s2);
		                		pubsubManager.create(application, s1, s2, dataId, userBean, metadataList);
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
					
				default :
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
