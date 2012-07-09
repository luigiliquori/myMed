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

import static com.mymed.utils.PubSub.constructRows;
import static com.mymed.utils.PubSub.getIndex;
import static com.mymed.utils.PubSub.isPredicate;
import static com.mymed.utils.PubSub.join;
import static com.mymed.utils.PubSub.maxNumColumns;
import static com.mymed.utils.PubSub.Index.joinCols;
import static com.mymed.utils.PubSub.Index.joinRows;

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
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.application.QueryBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.PubSub.Index;

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
	private static final String JSON_PREDICATE_LIST = JSON.get("json.predicate.list");

	/**
	 * JSON 'details' attribute.
	 */
	

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

			switch (code) {
			
//			case DELETE:
//				//could put here index removal, but total removal is done in pub delete, or index change is done below in update
//				break;
				
			default :
				throw new InternalBackEndException("FindRequestHandler(" + code + ") not exist!");
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
	 */
	@SuppressWarnings("serial")
	@Override
	protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
		final JsonMessage<Object> message = new JsonMessage<Object>(200, this.getClass().getName());

		try {
			
			final Map<String, String> parameters = getParameters(request);
			// Check the access token
			checkToken(parameters);

			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
			
			final String application, user, dataId;
			int level;
			String predicateList = null;
			
            if ((application = parameters.get(JSON_APPLICATION)) == null) {
                throw new InternalBackEndException("missing application argument!");
            } else if ((predicateList = parameters.get(JSON_PREDICATE_LIST)) == null) {
				throw new InternalBackEndException("missing predicate or predicateList argument!");
			}
			

			switch (code) {
			
			case READ :
				message.setMethod(JSON_CODE_READ);
				
				// GET RESULTS from ApplicationController indexes db
				
				Map<String, QueryBean> queryTmp = null;
				final Map<String, QueryBean> query;

				String start = parameters.get("start");
				int count = parameters.get("count") != null ? Integer.parseInt(parameters.get("count")) : 100;
				
				//retrieve query params
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
				
				level = 3;//query.size(); //by default, this should correspond or be less than the data publish level
                if (parameters.get("level") != null){
                	level = Integer.parseInt(parameters.get("level"));
                }
                
                LOGGER.info("ext find_ level ; "+level);
                
                /* final result list sent in response */
				List<Map<String, String>> resList = new ArrayList<Map<String, String>>();
				
				/* resultMap for range query */
				Map<String, Map<String, String>> resMap;
				
				/* temporary Map used for successive range queries */
				TreeMap<String, Map<String, String>> filterMap = new TreeMap<String, Map<String, String>>();
				
                if (query !=null){
                	/**
					 * @todo what follows is dirty
					 *   - sorry, working to make it clearer
					 *   - put more things in the manager less in handler
					 */
                	
                	/*
                	 * for a query like this
                	 * query={"name" : {"valueStart": "john", "valueEnd": "", "ontologyID": 0}}
                	 * it uses simple get on the row namejohn
                	 * 
                	 * When valueEnd is not empty: it performs a "range query" on ApplicationController
                	 * 
                	 * query={"rate" : {"valueStart": "2.25", "valueEnd": "5", "ontologyID": -1}}
                	 * 
                	 * this ontologyID (FLOAT) implies a row indexation based on value's integer part
                	 * so a multiget is performed over rows :{rate2, rate3, rate4}
                	 * the column names for this ontologyID are prefixed by the value, to order them
                	 * so the multiget operation is done with a slicepredicate [start=2.25 finish=5]
                	 * 
                	 * when query has more than one item, only the first (range) search is done using database manager,
                	 * remaining filtering is done here.
                	 * 
                	 */

                	List<List<String>> queryRows = new ArrayList<List<String>>();
                	
                	List<String[]> ranges = new ArrayList<String[]>();
                	
                	/* construct the ROWS to search */
                	int i=0;

                	List<String> keys  = new ArrayList<String>(query.keySet());
                	
                	for (; i < keys.size() && i < level; i++){
                		QueryBean item = query.get(keys.get(i));
                		queryRows.add(item.getRowIndexes(keys.get(i)));
                		if (item.getValueEnd().length() != 0){
                			ranges.add(new String[]{item.getValueStart(), item.getValueEnd()+"+"});
                		}	
                	}
                	
                	/*also add application on top of rows  */
                	queryRows.add(0, new ArrayList<String>()  {{add(application);}});
                	
                	List<String> rows = new ArrayList<String>();
                	
                	constructRows(queryRows, new int[queryRows.size()], 0, rows);
                	 
                	LOGGER.info("ext find rows: "+rows.size()+" initial: "+rows.get(0));
                	LOGGER.info("ext find ranges: "+ranges.size());

                	/* 
                	 * cnt: If the query is "simple" (doesn't require post filtering) we can directly apply the count parameter
                	 * else we need to get enough results (all for rows)
                	 */
					int cnt = (ranges.size() > 1 || keys.size() > level) ? maxNumColumns : count;
					
                	if (ranges.size() != 0){
                		LOGGER.info("ext find DB ranges: "+ranges.get(0)[0]+"->"+ranges.get(0)[1]);
                		String[] range = ranges.remove(0);
						resMap = pubsubManager.read(application, rows, start != null ? start : range[0], range[1], cnt);
            		} else {
            			resMap = pubsubManager.read(application, rows, start != null ? start : "", "", cnt); //there is just one elt in rows, equivalent to v1
            		}
                	/* now loop for remaining range queries */
                	
                	/* first finish filtering for i <= level */
                	while (ranges.size() != 0){ //filter
                		LOGGER.info("ext find Filter ranges: "+ranges.get(0)[0]+"->"+ranges.get(0)[1]);
                		String[] range = ranges.remove(0);
            			// uncap one layer of comparators
                		
                		filterMap.clear();
            			for (String key : resMap.keySet()){
            				Map<String, String> val = resMap.get(key);
            				String[] parts = key.split("\\+", 2);
            				filterMap.put(parts[parts.length-1], val);
            			}
            			//now the Map is re-indexed on the new group values, do the corresponding (Java Map) slice on it
						resMap = new TreeMap<String, Map<String, String>>(
								filterMap.subMap(range[0], true, range[1], true));
						
						LOGGER.info("ext uncap under level "+i+" "+resMap.size());
            		}
                	
                	/* filter resMap for remaining query items above level */
                	
                	for (; i<keys.size(); i++){
                		LOGGER.info("ext find__ "+i);
                		QueryBean item = query.get(keys.get(i));
                		queryRows.add(item.getRowIndexes(keys.get(i)));
                		
                		if (item.getValueEnd().length() > 0){ //this tells if it's a range query, valueEnd is empty for match query
                			/* 
                			 *  uncap one layer of col prefixes
                			 *  
                			 *  either we do that or we could just loop through data and check 
                			 *  Predicate item.getValueStart() < keys.get(i) < item.getValueEnd()
                			 *   like in the else 
                			 *   
                			 *   but this method is more fun
                			 */
                			
                			filterMap.clear();
                			for (String key : resMap.keySet()){
                				Map<String, String> val = resMap.get(key);
                				String[] parts = key.split("\\+", 2);
                				filterMap.put(parts[parts.length-1], val);
                			}
                			
                			resMap = new TreeMap<String, Map<String, String>>(
                					filterMap.subMap(item.getValueStart(), true, item.getValueEnd(), true));
                			LOGGER.info("ext uncap above level "+resMap.size());
                			
                		} else {
                			/*
                			 * we remove items that doesn't match the query predicate
                			 */
                			if (!isPredicate(resMap, keys.get(i), item.getValueStart())){
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
				
				/* returns resList with count parameter applied */
				message.addDataObject(JSON_RESULTS, resList.subList(0, Math.min(resList.size(), count)));


				break;
			
			case UPDATE:
				message.setMethod(JSON_CODE_UPDATE);
				// update indexes
				
				/**
				 * @todo put more things in the manager less in handler
				 */
				
				
				if ( (user = parameters.get(JSON_USERID)) == null) {
	                throw new InternalBackEndException("missing userID argument!");
	            }
				
				MUserBean userBean = profileManager.read(user);

				try {
					final Type dataType = new TypeToken<Map<String, QueryBean>>() {
					}.getType();
					final Map<String, QueryBean> predicateMap = getGson()
							.fromJson(predicateList, dataType);

					/* construct indexes */
					level = predicateMap.size();
					if (parameters.get("level") != null) {
						level = Integer.parseInt(parameters.get("level"));
					}

					/*previous indexes*/
					List<MDataBean> predicateListOld = new ArrayList<MDataBean>();
						
					/*new indexes*/
					List<MDataBean> predicateListNew = new ArrayList<MDataBean>();

					for (Entry<String, QueryBean> d : predicateMap.entrySet()) {
						predicateListOld.add(d.getValue().getStart(
								d.getKey()));
						predicateListNew.add(d.getValue().getEnd(
								d.getKey()));
					}

					Collections.sort(predicateListNew);
					Collections.sort(predicateListOld);
					
					dataId = parameters.get("id") != null ? parameters.get("id") : join(predicateListOld);
					LOGGER.info("update" + level);
					
					for (int i = 1; i <= level; i++) {
						List<List<Index>> predicatesOld = getIndex(predicateListOld, i);
						List<List<Index>> predicatesNew = getIndex(predicateListNew, i);
						/* store indexes for this data */
						LOGGER.info("updating " + dataId + " with level: " + i
								+ "." + predicatesNew.size() + "." + predicatesOld.size());
						for (List<Index> p : predicatesOld) {
							String s1 = joinRows(p);
							String s2 = joinCols(p);
							LOGGER.info("__" + s1 + " " + s2);
							pubsubManager.delete(application, s1, s2 + dataId, user);
						}
						for (List<Index> p : predicatesNew) {
							String s1 = joinRows(p);
							String s2 = joinCols(p);
							LOGGER.info("____" + s1 + " " + s2);
							pubsubManager.create(application, s1, s2, dataId, userBean, predicateListNew, null);
						}
					}

				} catch (final JsonSyntaxException e) {
					LOGGER.debug("Error in Json format", e);
					throw new InternalBackEndException(
							"jSon format is not valid");
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
		} catch (UnsupportedEncodingException e) {
			LOGGER.debug("Error in doGet operation", e);
			message.setStatus(404);
			message.setDescription(e.getMessage());
		}

		printJSonResponse(message, response);
	}
}
