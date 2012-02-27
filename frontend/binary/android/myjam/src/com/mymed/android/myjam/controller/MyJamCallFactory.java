package com.mymed.android.myjam.controller;



/**
 * Utility class used to build {@link HttpCall}, according to MyJam API.
 * 
 * @author iacopo
 *
 */
public class MyJamCallFactory {
//
//	private MyJamCallFactory(int id, int callCode, HttpCallHandler handler, HttpMethod method,
//			String uriString) {
//		super(id, callCode, handler, method, uriString);
//	}
//	
//	private MyJamCallFactory(int id, int callCode, HttpCallHandler handler, HttpMethod method,
//			String uriString, String JSonObj) {
//		super(id, callCode, handler, method, uriString, JSonObj);
//	}
//
//
//	
//	/**
//	 * Searches the reports in the specified area.
//	 * @param latitude Longitude of the center of the area [micro-degree].
//	 * @param longitude Latitude of the center of the area [micro-degree].
//	 * @param radius Diameter of the area [meter].
//	 * @return
//	 * @throws InternalClientException 
//	 * @throws IOBackEndException 
//	 * @throws InternalBackEndException 
//	 */
//	public static HttpCall searchReports(int id, HttpCallHandler handler, 
//			String accessToken, int latitude, int longitude, int radius){
//		HttpCall call = new MyJamCallFactory(id, CallCode.SEARCH_REPORTS, handler,HttpMethod.GET, MY_JAM_REPORT_HANDLER_URL);
//		call.appendAttribute(CODE, RequestCode.READ.code);
//		call.appendAttribute(ACCESS_TOKEN, accessToken);
//		call.appendAttribute(LATITUDE, String.valueOf(latitude));
//		call.appendAttribute(LONGITUDE, String.valueOf(longitude));
//		call.appendAttribute(RADIUS, String.valueOf(radius));
//		return call;
//	}
//
//	/**
//	 * Gets the report corresponding to id.
//	 * @param id Identifier of the Report.
//	 * @return
//	 * @throws InternalBackEndException
//	 * @throws IOBackEndException
//	 * @throws InternalClientException
//	 */
//	public static HttpCall getReport(int id, HttpCallHandler handler, 
//			String accessToken, String reportId){
//		HttpCall call = new MyJamCallFactory(id, CallCode.GET_REPORT, handler, HttpMethod.GET, MY_JAM_REPORT_HANDLER_URL);
//		call.appendAttribute(CODE, RequestCode.READ.code);
//		call.appendAttribute(ACCESS_TOKEN, accessToken);
//		call.appendAttribute(REPORT_ID, reportId);
//		return call;
//	}
//	
//	/**
//	 * Gets the updates with timestamp equal or greater then {@value startTime}.
//	 * @param id
//	 * @param handler
//	 * @param reportId
//	 * @param startTime
//	 * @return
//	 */
//	public static HttpCall getUpdates(int id, HttpCallHandler handler,
//			String accessToken, String reportId, long startTime){
//		HttpCall call = new MyJamCallFactory(id, CallCode.GET_UPDATES, handler,HttpMethod.GET, MY_JAM_UPDATE_HANDLER_URL);
//		call.appendAttribute(CODE, RequestCode.READ.code);
//		call.appendAttribute(ACCESS_TOKEN, accessToken);
//		call.appendAttribute(REPORT_ID, reportId);
//		call.appendAttribute(START_TIME, String.valueOf(startTime));
//		return call;
//	}
//	
//	/**
//	 * Gets the feedbacks corresponding to report id.
//	 * @param id	Call identifier.
//	 * @param handler	Handler for the callback.
//	 * @param reportId	Identifier of the report.
//	 * @return	The {@link HttpCall}.
//	 */
//	public static HttpCall getFeedbacks(int id, boolean report, HttpCallHandler handler,
//			String accessToken, String reportId){
//		HttpCall call = new MyJamCallFactory(id, report?CallCode.GET_REPORT_FEEDBACKS:CallCode.GET_UPDATE_FEEDBACKS, 
//				handler,HttpMethod.GET, MY_JAM_FEEDBACK_HANDLER_URL);
//		call.appendAttribute(CODE, RequestCode.READ.code);
//		call.appendAttribute(ACCESS_TOKEN, accessToken);
//		call.appendAttribute(REPORT_ID, reportId);
//		return call;
//	}
//
//	/**
//	 * Inserts a new Report
//	 * @param cf	The name of the keyspace.
//	 * @param key	The row key.
//	 * @param sCol	The SuperColumn name.
//	 * @param name	The Column name.
//	 * @param value	The Column value.
//	 * @param ttl	The Column time to live before expiration (seconds).
//	 * @return		
//	 * @throws InternalClientException 
//	 * @throws IOBackEndException 
//	 * @throws InternalBackEndException 
//	 */
//	public static HttpCall insertReport(int id, HttpCallHandler handler,
//			String accessToken, int latitude,int longitude, String jsonReport){
//		HttpCall call = new MyJamCallFactory(id, CallCode.INSERT_REPORT, handler,HttpMethod.POST, 
//				MY_JAM_REPORT_HANDLER_URL,jsonReport);
//		call.appendAttribute(CODE, RequestCode.CREATE.code);
//		call.appendAttribute(ACCESS_TOKEN, accessToken);
//		call.appendAttribute(LATITUDE, String.valueOf(latitude));
//		call.appendAttribute(LONGITUDE, String.valueOf(longitude));
//		return call;
//	}
//	
//	/**
//	 * Inserts a new update
//	 * @param id		Id of the report which update refers to.
//	 * @param update	Java bean containing the informations.
//	 * @throws InternalBackEndException
//	 * @throws IOBackEndException
//	 * @throws InternalClientException
//	 * @return The updateId
//	 */
//	public static HttpCall insertUpdate(int id, HttpCallHandler handler, 
//			String accessToken, String reportId, String jsonUpdate){
//		HttpCall call = new MyJamCallFactory(id, CallCode.INSERT_UPDATE, handler,HttpMethod.POST, 
//				MY_JAM_UPDATE_HANDLER_URL, jsonUpdate);
//		call.appendAttribute(CODE, RequestCode.CREATE.code);
//		call.appendAttribute(ACCESS_TOKEN, accessToken);
//		call.appendAttribute(REPORT_ID, reportId);
//		return call;
//	}
//
//	/**
//	 * Inserts a new feedBack
//	 * @param reportId Must be included, so that the system can check if its expired or not.
//	 * @param updateId
//	 * @throws InternalBackEndException
//	 * @throws IOBackEndException
//	 * @throws InternalClientException
//	 */
//	public static HttpCall insertFeedBack(int id, HttpCallHandler handler, 
//			String accessToken, String reportId, String updateId, String jsonFeedback){
//		HttpCall call = new MyJamCallFactory(id, updateId==null?CallCode.INSERT_REPORT_FEEDBACK:CallCode.INSERT_UPDATE_FEEDBACK,handler,HttpMethod.POST, 
//				MY_JAM_UPDATE_HANDLER_URL, jsonFeedback);
//		call.appendAttribute(CODE, RequestCode.CREATE.code);
//		call.appendAttribute(ACCESS_TOKEN, accessToken);
//		call.appendAttribute(REPORT_ID, reportId);
//		if (updateId!=null)
//			call.appendAttribute(UPDATE_ID,updateId);
//		return call;
//	}
//
//	public static HttpCall deleteReport(int id, HttpCallHandler handler, 
//			String accessToken, String reportId){
//		HttpCall call = new MyJamCallFactory(id, CallCode.DELETE_REPORT, handler,HttpMethod.DELETE, 
//				MY_JAM_REPORT_HANDLER_URL);
//		call.appendAttribute(CODE, RequestCode.DELETE.code);
//		call.appendAttribute(ACCESS_TOKEN, accessToken);
//		call.appendAttribute(REPORT_ID, reportId);
//		return call;
//	}
}
