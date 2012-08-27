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
import static com.mymed.utils.MatchMakingv2.prefix;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.mailtemplates.MailDispatcher;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.publish.PublishManager;
import com.mymed.controller.core.requesthandler.message.JsonMessageOut;
import com.mymed.model.data.application.DataBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.CombiLine;
import com.mymed.utils.HashFunction;
import com.mymed.utils.MatchMakingv2;
import com.mymed.utils.MiscUtils;

/**
 * Servlet implementation class PubSubRequestHandler
 */

@WebServlet("/v2/DataRequestHandler")
public class DataRequestHandler extends AbstractRequestHandler {

	/**
	 * 
	 */
	private static final long serialVersionUID = -2988915549079597L;

	/**
	 * JSON 'results' attribute.
	 */
	protected static final String JSON_RESULTS = JSON.get("json.results");

	// private int INDEX_ONLY = 1; // to delete only indexes, not data

	protected PublishManager dataManager;
	protected ProfileManager profileManager;

	private final int MAIL_EXECUTOR_SIZE = 10;
	private ExecutorService mail_executor;

	/**
	 * JSON 'predicate' attribute.
	 */

	protected static final String JSON_DETAILS = JSON.get("json.details");

	public DataRequestHandler() throws InternalBackEndException {
		super();
		profileManager = new ProfileManager();
		dataManager = new PublishManager();
		mail_executor = Executors.newFixedThreadPool(MAIL_EXECUTOR_SIZE);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * com.mymed.controller.core.requesthandler.AbstractRequestHandler#doGet
	 * (javax.servlet.http.HttpServletRequest,
	 * javax.servlet.http.HttpServletResponse)
	 */
	@Override
	protected void doGet(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException {
		final JsonMessageOut<Object> message = new JsonMessageOut<Object>(200, this
				.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			// Check the access token
			checkToken(parameters);

			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
			final String 
				predicates = parameters.get("predicates"),
				application = parameters.containsKey(JSON_APPLICATION)?
					parameters.get(JSON_APPLICATION)
					:"",
				id = parameters.get("id"),
				field = parameters.get("field");

			switch (code) {
			case READ:
				message.setMethod(JSON_CODE_READ);

				if (id != null) {
					// Get DATA (details)
					final Map<String, String> details = dataManager.read(application + id);
					if (details.isEmpty())
						throw new IOBackEndException("no results found!", 404);
					
					message.setDescription("Details found for predicate: " + id);
					LOGGER.info("Details found for predicate: " + id);

					message.addDataObject(JSON_DETAILS, details);
				} else if (predicates != null) {
					// String level = parameters.get("level");
					List<DataBean> preds = new ArrayList<DataBean>();
					// retrieve query params
					try {
						preds = gson.fromJson(predicates, predicateType);
					} catch (final JsonSyntaxException e) {
						LOGGER.debug("Error in Json format", e);
						throw new InternalBackEndException("jSon format is not valid");
					} catch (final JsonParseException e) {
						LOGGER.debug("Error in parsing Json", e);
						throw new InternalBackEndException(e.getMessage());
					}

					/* generate the ROWS to search */
					LinkedHashMap<String, List<String>> xpreds = MatchMakingv2.format(preds);

					LOGGER.info("keywords: {} ", xpreds);
					
					List<String> rows = new CombiLine(xpreds).expand();
					prefix(application, rows);
					
					Map<String, Map<String, String>> resMap;

					resMap = dataManager.read(rows, "", "");

					if (resMap.isEmpty()) {
						throw new IOBackEndException(
								"No reslult found for predicate: " + predicates, 404);
					}
					message.setDescription("Results found for predicate: " + predicates);
					LOGGER.info("Results found predicate: " + predicates);

					message.addDataObject(JSON_RESULTS, resMap.values());

				} else {
					throw new InternalBackEndException(
							"missing id or index argument!");
				}

				break;

			case DELETE:

				message.setMethod(JSON_CODE_DELETE);
				// final String mode = parameters.get("mode");

				if (id == null)
					throw new InternalBackEndException("missing id argument!");

				
				LOGGER.info("deleting " + application + id);
				
				if (field != null){
					dataManager.delete(application + id, field);
					break;
				}
				
				String xpredicates = dataManager.read(application + id, "xpredicates");

				LOGGER.info(" trying to delete  " + id + "." + xpredicates);

				LinkedHashMap<String, List<String>> xpreds = new LinkedHashMap<String, List<String>>();

				if (xpredicates != null) {
					try {
						xpreds = gson.fromJson(xpredicates, xType);
						LOGGER.info(" get index : {} ", xpreds);
					} catch (final JsonSyntaxException e) {
						LOGGER.debug("Error in Json format", e);
						throw new InternalBackEndException(
								"jSon format is not valid");
					} catch (final JsonParseException e) {
						LOGGER.debug("Error in parsing Json", e);
						throw new InternalBackEndException(e.getMessage());
					}
				}
				
				LOGGER.info(" deleting  " + id + "." + xpreds.size());

				List<String> rows = new CombiLine(xpreds).expand();
				prefix(application, rows);

				for (String i : rows) {
					dataManager.delete("", i, id);
				}

				/* deletes data */
				dataManager.delete(application + id);

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
	 * 
	 * @see
	 * com.mymed.controller.core.requesthandler.AbstractRequestHandler#doPost
	 * (javax.servlet.http.HttpServletRequest,
	 * javax.servlet.http.HttpServletResponse)
	 */
	@Override
	protected void doPost(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException {
		final JsonMessageOut<Object> message = new JsonMessageOut<Object>(200, this
				.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			// Check the access token
			checkToken(parameters);

			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
			
			String id = parameters.get("id");
			final String
				application = parameters.containsKey(JSON_APPLICATION)?
					parameters.get(JSON_APPLICATION)
					:"",
				user = parameters.get(JSON_USER),
				data = parameters.get(JSON_DATA),
				metadata = parameters.get("metadata"),
				predicates = parameters.get("predicates"),
				lengthMax = parameters.get("lengthMax");
				
			Map<String, String> metas = new HashMap<String, String>();
			Map<String, String> datas = new HashMap<String, String>();

			List<DataBean> preds = new ArrayList<DataBean>();

			try {
				if (data != null)
					datas = gson.fromJson(data, dataType);
				if (metadata != null)
					metas = gson.fromJson(metadata, dataType);
				if (predicates != null)
					preds = gson.fromJson(predicates, predicateType);
			} catch (final JsonSyntaxException e) {
				LOGGER.debug("Error in Json format", e);
				throw new InternalBackEndException("jSon format is not valid");
			} catch (final JsonParseException e) {
				LOGGER.debug("Error in parsing Json", e);
				throw new InternalBackEndException(e.getMessage());
			}

			LOGGER.info("predicates: {} ", preds);
			/* generate the ROWS to search */
			LinkedHashMap<String, List<String>> xpreds = MatchMakingv2.format(preds);

			LOGGER.info("keywords: {} ", xpreds);

			List<String> rows = new CombiLine(xpreds, lengthMax).expand();
			MatchMakingv2.prefix(application, rows);

			/* make sure to put an id pointer */
			if (id == null) {
				final HashFunction h = new HashFunction(SOCIAL_NET_NAME);
				id = h.SHA1ToString(System.currentTimeMillis() + metas.toString());
			}
			metas.put("id", id);

			LOGGER.info("in " + id + "." + datas.size() + "."
					+ xpreds.size());

			// look if the content is signed
			MUserBean publisher = null;
			if (user != null)
				publisher = profileManager.read(user);

			switch (code) {

			case CREATE:
				message.setMethod(JSON_CODE_CREATE);

				dataManager.create(rows, id, metas);

				mail_executor.execute(new MailDispatcher(
						application,
						rows,
						datas,
						publisher));

				datas.put("xpredicates", gson.toJson(xpreds));

				/* creates data */
				dataManager.create(application + id, datas);

				break;

			case UPDATE:
				message.setMethod(JSON_CODE_UPDATE);

				if (id == null)
					throw new InternalBackEndException("missing id argument!");
				
				// @TODO put id out of data

				LOGGER.info("updating data " + id + " size "
						+ datas.size());

				/* creates data */
				dataManager.create(application + id, datas);

				if (datas.containsKey("_noNotification"))
					break;
				
				mail_executor.execute(new MailDispatcher(
						application,
						MiscUtils.singleton(id),
						datas,
						publisher));

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