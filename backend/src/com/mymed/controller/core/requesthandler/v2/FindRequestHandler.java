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
import static com.mymed.utils.MiscUtils.makePrefix;
import static com.mymed.utils.MatchMaking.parseInt;

import java.util.ArrayList;
import java.util.Collections;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.TreeMap;

import javax.servlet.ServletException;
import javax.servlet.annotation.MultipartConfig;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.IndexBean;
import com.mymed.utils.MatchMaking;
import com.mymed.utils.MatchMaking.Index;
import com.mymed.utils.MatchMaking.IndexRow;


@MultipartConfig
@WebServlet("/v2/FindRequestHandler")

/**
 * Servlet implementation class PubSubRequestHandler
 */
public class FindRequestHandler extends AbstractRequestHandler {

	/**
	 * Generated serial ID.
	 */
	protected static final long serialVersionUID = 4295832798531448329L;
    
	/**
	 * JSON 'results' attribute.
	 */
	protected static final String JSON_RESULTS = JSON.get("json.results");

	/**
	 * JSON 'details' attribute.
	 */
	
	protected static final int maxNumColumns = 10000; // arbitrary max number of cols, to overrides default's 100 

	protected final PubSubManager pubsubManager;

	public FindRequestHandler() throws InternalBackEndException {
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

			switch (code) {
				
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

	@Override
	protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
		final JsonMessage<Object> message = new JsonMessage<Object>(200, this.getClass().getName());

		try {

			final Map<String, String> parameters = getParameters(request);
			// Check the access token
			checkToken(parameters);

			final RequestCode code = REQUEST_CODE_MAP.get(parameters
					.get(JSON_CODE));

			final String 
				application,
				index = parameters.get("index"), 
				namespace = parameters.get(JSON_NAMESPACE),
				min = parameters.get("min"),
				max = parameters.get("max");
			
			List<IndexBean> query = new ArrayList<IndexBean>();

			if ((application = parameters.get(JSON_APPLICATION)) == null)
				throw new InternalBackEndException(
						"missing application argument!");

			// retrieve query params
			try {
				if (index != null)
					query = gson.fromJson(index, indexType);
			} catch (final JsonSyntaxException e) {
				LOGGER.debug("Error in Json format", e);
				throw new InternalBackEndException("jSon format is not valid");
			} catch (final JsonParseException e) {
				LOGGER.debug("Error in parsing Json", e);
				throw new InternalBackEndException(e.getMessage());
			}
			// sort indexes
			Collections.sort(query);

			/* generate the ROWS to search */

			LinkedHashMap<String, List<String>> indexes = MatchMaking
					.formatIndexes(query);
			
			LOGGER.info("indexes: {} ", indexes );

			List<IndexRow> combi = MatchMaking.getPredicate(
					indexes, 
					min!=null?parseInt(min):query.size(),
					max!=null?parseInt(max):query.size());
			
			switch (code) {
			
			case READ :
				message.setMethod(JSON_CODE_READ);

				/* final result list sent in response */
				List<Map<String, String>> resList = new ArrayList<Map<String, String>>();

				/* resultMap for range query */
				Map<String, Map<String, String>> resMap;

				/* temporary Map used for successive range queries */
				TreeMap<String, Map<String, String>> filterMap = new TreeMap<String, Map<String, String>>();

				String prefix = makePrefix(application, namespace);

				List<List<String>> ranges = MatchMaking.getRanges(query);

				for (IndexRow r : combi)
					r.add(0, new Index("", prefix));
				
				List<String> rows = IndexRow.getVals(combi);
				
				LOGGER.info("ext find rows {}", rows.size());

				LOGGER.info("ext find ranges: " + ranges.size());

				if (ranges.size() != 0) {
					LOGGER.info("ext find DB ranges: " + ranges.get(0).get(0)
							+ "->" + ranges.get(0).get(1));
					List<String> range = ranges.remove(0);
					resMap = pubsubManager.read(rows,
							range.get(0), range.get(1));
				} else {
					/*
					 * there is just one elt in rows, equivalent to v1
					 */
					resMap = pubsubManager.read(rows, "", "");
				}

				while (ranges.size() != 0) { // filter stuff
					LOGGER.info("ext find Filter ranges: "
							+ ranges.get(0).get(0) + "->"
							+ ranges.get(0).get(1));
					List<String> range = ranges.remove(0);
					// uncap one layer of comparators

					filterMap.clear();
					for (String key : resMap.keySet()) {
						Map<String, String> val = resMap.get(key);
						String[] parts = key.split("\\+", 2);
						filterMap.put(parts[parts.length - 1], val);
					}
					// now the Map is re-indexed on the new group values, do the
					// corresponding (Java Map) slice on it
					resMap = new TreeMap<String, Map<String, String>>(
							filterMap.subMap(range.get(0), true, range.get(1),
									true));

					LOGGER.info("ext uncap .. " + resMap.size());
				}

				/* at the end, get values */
				for (Map<String, String> m : resMap.values()) {
					resList.add(m);
				}

				if (resList.isEmpty()) {
					throw new IOBackEndException(
							"No reslult found for Application: " + application
									+ " Predicate: " + index, 404);
				}
				message.setDescription("Results found for Application: "
						+ application + " Predicate: " + index);
				LOGGER.info("Results found for Application: " + application
						+ " Predicate: " + index);

				message.addDataObject(JSON_RESULTS, resList);

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
