/*
 * Copyright 2012 POLITO 
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
package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.myjam.MyJamManager;
import com.mymed.controller.core.manager.storage.GeoLocStorageManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.id.MyMedId;
import com.mymed.model.data.myjam.MReportBean;

import com.mymed.model.data.myjam.MyJamTypeValidator;
import com.mymed.utils.IMyJamCallAttributes;
import com.mymed.utils.MConverter;

/**
 * Manages the requests related to reports.
 * @author iacopo
 *
 */
@WebServlet("/MyJamReportRequestHandler")
public class MyJamReportRequestHandler  extends AbstractRequestHandler implements IMyJamCallAttributes {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;
	
	private MyJamManager myJamManager;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * @throws ServletException
	 * @see HttpServlet#HttpServlet()
	 */
	public MyJamReportRequestHandler() throws ServletException {
		super();
		myJamManager = new MyJamManager(new GeoLocStorageManager());
	}

	/* --------------------------------------------------------- */
	/* extends HttpServlet */
	/* --------------------------------------------------------- */
	@Override
	protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException{

		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get("code"));
			MyMedId id;
			String reportId, userId, latitude, longitude, radius;
			
			// accessToken
			if (parameters.get("accessToken") == null) {
				throw new InternalBackEndException("accessToken argument is missing!");
			} else {
				checkToken(parameters); // Security Validation
			}

			switch (code) {
			case READ : // GET
				message.setMethod("READ");
				if ((latitude = parameters.get(LATITUDE)) != null && (longitude = parameters.get(LONGITUDE)) != null 
						&& (radius = parameters.get(RADIUS)) != null ){
					// Latitude and longitude in micro-degrees
					int lat = Integer.parseInt(latitude);
					int lon = Integer.parseInt(longitude);
					// Diameter in m.
					int rad = Integer.parseInt(radius);
					List<MSearchBean> resultList = myJamManager.searchReports(lat, lon, rad);
					message.addData("search_reports", this.getGson().toJson(resultList));
				}else if ((reportId = parameters.get(ID)) != null){
					id = MyMedId.parseString(reportId);	//This conversion is done only to check the syntax of the id.
					MReportBean reportInfo = myJamManager.getReport(id.toString());
					message.addData("report", this.getGson().toJson(reportInfo));
				}else if ((userId = parameters.get(USER_ID)) != null){
					List<String> activeRepId = myJamManager.getActiveReport(userId);
					message.addData("active_reports", this.getGson().toJson(activeRepId));
				}else
					throw new InternalBackEndException("missing parameter, bad request!");
				break;
			default :
				throw new InternalBackEndException(this.getClass().getName()+"(" + code + ") not exist!");
			}
		} catch (final AbstractMymedException e) {
			e.printStackTrace();
			LOGGER.info("Error in doGet");
			LOGGER.debug("Error in doGet", e);
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} catch (final NumberFormatException e) {
			e.printStackTrace();
			LOGGER.info("Error in doGet");
			LOGGER.debug("Error in doGet", e);
			message.setStatus(500);
			message.setDescription(e.getMessage());
		} 

		printJSonResponse(message, response);
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doPost(final HttpServletRequest request, final HttpServletResponse response)
			throws ServletException{
		
		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get("code"));
			String latitude, longitude, content;

			// accessToken
			if (parameters.get("accessToken") == null) {
				throw new InternalBackEndException("accessToken argument is missing!");
			} else {
				checkToken(parameters); // Security Validation
			}
			
			switch (code) {
			case CREATE :
				message.setMethod("CREATE");
				if ((latitude = parameters.get(LATITUDE)) != null && (longitude = parameters.get(LONGITUDE)) != null){
					int lat = Integer.parseInt(latitude);
					int lon = Integer.parseInt(longitude);
					content = MConverter.convertStreamToString(request.getInputStream(),request.getContentLength());
					MReportBean report = this.getGson().fromJson(content, MReportBean.class);
					MyJamTypeValidator.validate(report);
					MReportBean res = myJamManager.insertReport(report,lat,lon);
					message.addData("report", this.getGson().toJson(res));
				}else
					throw new InternalBackEndException("missing parameter, bad request!");
				break;
			default :
				throw new InternalBackEndException(this.getClass().getName()+"(" + code + ") not exist!");
			}

		} catch (final AbstractMymedException e) {
			e.printStackTrace();
			LOGGER.info("Error in doPost");
			LOGGER.debug("Error in doPost", e);
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} catch (final NumberFormatException e) {
			e.printStackTrace();
			LOGGER.info("Error in doPost");
			LOGGER.debug("Error in doPost", e);
			message.setStatus(500);
			message.setDescription(e.getMessage());
		}
		catch (IOException e) {
			//do nothing
		} 

		printJSonResponse(message, response);
	}
	
	/**
	 * @see HttpServlet#doDelete(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doDelete(HttpServletRequest request, HttpServletResponse response) throws ServletException{
		
		JsonMessage message = new JsonMessage(200, this.getClass().getName());
		
		try{
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get("code"));
			
			// accessToken
			if (parameters.get("accessToken") == null) {
				throw new InternalBackEndException("accessToken argument is missing!");
			} else {
				checkToken(parameters); // Security Validation
			}
			
			switch (code){
			case DELETE:
				message.setMethod("DELETE");
				myJamManager.deleteReport(parameters.get("id"));
				break;
			}
			//super.doDelete(request, response);
			//removed CR as super class does not have method. this entire method could be cancelled imho
		} catch (final AbstractMymedException e) { 
			e.printStackTrace();
			LOGGER.info("Error in doDelete");
			LOGGER.debug("Error in doDelete", e);
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		}
	}
	
}
