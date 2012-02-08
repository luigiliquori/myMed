package com.mymed.android.myjam.controller;

import com.google.gson.Gson;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;


/**
 * Singleton class used to perform HTTP calls, according to MyJam API.
 * @author iacopo
 *
 */
public class MyJamCallFactory extends HttpCall implements ICallAttributes{

	private MyJamCallFactory(HttpCallHandler handler, HttpMethod method,
			String uriString, Integer id) {
		super(id, handler, method, uriString);
	}
	
	private MyJamCallFactory(HttpCallHandler handler, HttpMethod method,
			String uriString, Integer id, String JSonObj) {
		super(id, handler, method, uriString, JSonObj);
	}

	//  For local testing with the emulator.
	private static final String MY_JAM_REPORT_HANDLER_URL = BACKEND_URL+"MyJamReportRequestHandler";
	private static final String MY_JAM_UPDATE_HANDLER_URL = BACKEND_URL+"MyJamUpdateRequestHandler";
	private static final String MY_JAM_FEEDBACK_HANDLER_URL = BACKEND_URL+"MyJamFeedbackRequestHandler";
	
	private static Gson gson;
	
	/**
	 * Searches the reports in the specified area.
	 * @param latitude Longitude of the center of the area [micro-degree].
	 * @param longitude Latitude of the center of the area [micro-degree].
	 * @param radius Diameter of the area [meter].
	 * @return
	 * @throws InternalClientException 
	 * @throws IOBackEndException 
	 * @throws InternalBackEndException 
	 */
	public static HttpCall searchReports(int id, HttpCallHandler handler, 
			String accessToken, int latitude, int longitude, int radius){
		HttpCall call = new MyJamCallFactory(handler,HttpMethod.GET, MY_JAM_REPORT_HANDLER_URL,id);
		call.appendAttribute(CODE, RequestCode.READ.code);
		call.appendAttribute(ACCESS_TOKEN, accessToken);
		call.appendAttribute(LATITUDE, String.valueOf(latitude));
		call.appendAttribute(LONGITUDE, String.valueOf(longitude));
		call.appendAttribute(RADIUS, String.valueOf(radius));
		return call;
	}
//	public List<MSearchBean> searchReports(int latitude,int longitude, int radius) 
//			throws InternalBackEndException, IOBackEndException, InternalClientException{
//		HttpCall searchCall = new HttpCall(new HttpCallHandler(), HttpMethod.GET, MY_JAM_REPORT_HANDLER_URL);
//		String q=QUERY+RequestCode.READ.code;
//		searchCall.appendAttribute(LATITUDE,String.valueOf(latitude));
//		searchCall.appendAttribute(LONGITUDE,String.valueOf(longitude));
//		searchCall.appendAttribute(RADIUS,String.valueOf(radius));
////		String response = httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.GET,null);
//		JSONObject response;
//		try{
//			//response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_REPORT_HANDLER_URL+q,HttpMethod.GET,null)).nextValue();
//			//JSONObject data = response.getJSONObject("data");
//			//String jsonData = data.getString("search_reports");
//			Type shortReportListType = new TypeToken<List<MSearchBean>>(){}.getType();
//			//return this.gson.fromJson(jsonData, shortReportListType);
//		}catch(JsonSyntaxException e){
//			throw new InternalBackEndException("Wrong response format.");
//		} catch (JSONException e) {
//			throw new InternalBackEndException("Wrong response format.");
//		}
//	}

	/**
	 * Gets the report corresponding to id.
	 * @param id Identifier of the Report.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public static HttpCall getReport(int id, HttpCallHandler handler, 
			String accessToken, String reportId){
		HttpCall call = new MyJamCallFactory(handler,HttpMethod.GET, MY_JAM_REPORT_HANDLER_URL,id);
		call.appendAttribute(CODE, RequestCode.READ.code);
		call.appendAttribute(ACCESS_TOKEN, accessToken);
		call.appendAttribute(REPORT_ID, reportId);
		return call;
	}
//	public MReportBean getReport(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
//		String q=QUERY+RequestCode.READ.code;
//		q=appendAttribute(q,REPORT_ID,id);
//		
//		JSONObject response;
//		try{
//			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_REPORT_HANDLER_URL+q,HttpMethod.GET,null)).nextValue();
//			JSONObject data = response.getJSONObject("data");
//			String jsonData = data.getString("report");
//			Type shortReportListType = new TypeToken<MReportBean>(){}.getType();
//			return this.gson.fromJson(jsonData, shortReportListType);
//
//		}catch(JsonSyntaxException e){
//			throw new InternalBackEndException("Wrong response format.");
//		} catch (JSONException e) {
//			throw new InternalBackEndException("Wrong response format.");
//		}
//	}
	
	/**
	 * Gets the updates with timestamp equal or greater then {@value startTime}.
	 * @param id
	 * @param handler
	 * @param reportId
	 * @param startTime
	 * @return
	 */
	public static HttpCall getUpdates(int id, HttpCallHandler handler,
			String accessToken, String reportId, long startTime){
		HttpCall call = new MyJamCallFactory(handler,HttpMethod.GET, MY_JAM_UPDATE_HANDLER_URL,id);
		call.appendAttribute(CODE, RequestCode.READ.code);
		call.appendAttribute(ACCESS_TOKEN, accessToken);
		call.appendAttribute(REPORT_ID, reportId);
		call.appendAttribute(START_TIME, String.valueOf(startTime));
		return call;
	}
	
//	public List<MReportBean> getUpdates(String id,int numUpdates) throws InternalBackEndException, IOBackEndException, InternalClientException{
//		String q=QUERY+RequestCode.READ.code;
//		q=appendAttribute(q,REPORT_ID,id);
//		q=appendAttribute(q,NUM,String.valueOf(numUpdates));
//		
//		JSONObject response;
//		try{
//			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_UPDATE_HANDLER_URL+q,HttpMethod.GET,null)).nextValue();
//			JSONObject data = response.getJSONObject("data");
//			String jsonData = data.getString("updates");
//			Type reportListType = new TypeToken<List<MReportBean>>(){}.getType();
//			return this.gson.fromJson(jsonData, reportListType);
//		}catch(JsonSyntaxException e){
//			throw new InternalBackEndException("Wrong response format.");
//		} catch (JSONException e) {
//			throw new InternalBackEndException("Wrong response format.");
//		}		
//	}
//	
	/**
	 * Gets the feedbacks corresponding to report id.
	 * @param id	Call identifier.
	 * @param handler	Handler for the callback.
	 * @param reportId	Identifier of the report.
	 * @return	The {@link HttpCall}.
	 */
	public static HttpCall getFeedbacks(int id, HttpCallHandler handler,
			String accessToken, String reportId){
		HttpCall call = new MyJamCallFactory(handler,HttpMethod.GET, MY_JAM_FEEDBACK_HANDLER_URL,id);
		call.appendAttribute(CODE, RequestCode.READ.code);
		call.appendAttribute(ACCESS_TOKEN, accessToken);
		call.appendAttribute(REPORT_ID, reportId);
		return call;
	}
//	public List<MFeedBackBean> getFeedBacks(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
//		String q=QUERY+RequestCode.READ.code;
//		q=appendAttribute(q,REPORT_ID,id);
// 
//		JSONObject response;
//		try{
//			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_FEEDBACK_HANDLER_URL+q,HttpMethod.GET,null)).nextValue();
//			JSONObject data = response.getJSONObject("data");
//			String jsonData = data.getString("feedbacks");
//			Type feedListType = new TypeToken<List<MFeedBackBean>>(){}.getType();
//			return this.gson.fromJson(jsonData, feedListType);
//		}catch(JsonSyntaxException e){
//			throw new InternalBackEndException("Wrong response format.");
//		} catch (JSONException e) {
//			throw new InternalBackEndException("Wrong response format.");
//		}
//	}
//	
//	/**
//	 * Gets the ids of the active reports inserted by a user.
//	 * @param userId
//	 * @return A list of ids.
//	 * @throws InternalBackEndException
//	 * @throws IOBackEndException
//	 * @throws InternalClientException
//	 */
//	public List<String> getActiveReports(String userId) throws InternalBackEndException, IOBackEndException, InternalClientException{
//		String q=QUERY+RequestCode.READ.code;
//		
//		q=appendAttribute(q,USER_ID,userId);
//		
//		JSONObject response;
//		try{
//			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_REPORT_HANDLER_URL+q,HttpMethod.GET,null)).nextValue();
//			JSONObject data = response.getJSONObject("data");
//			String jsonData = data.getString("active_reports");
//			Type stringListType = new TypeToken<List<String>>(){}.getType();
//			return this.gson.fromJson(jsonData, stringListType);
//		}catch(JsonSyntaxException e){
//			throw new InternalBackEndException("Wrong response format.");
//		} catch (JSONException e) {
//			throw new InternalBackEndException("Wrong response format.");
//		}
//	}
//	
	/**
	 * Inserts a new Report
	 * @param cf	The name of the keyspace.
	 * @param key	The row key.
	 * @param sCol	The SuperColumn name.
	 * @param name	The Column name.
	 * @param value	The Column value.
	 * @param ttl	The Column time to live before expiration (seconds).
	 * @return		
	 * @throws InternalClientException 
	 * @throws IOBackEndException 
	 * @throws InternalBackEndException 
	 */
	public static HttpCall insertReport(int id, HttpCallHandler handler,
			String accessToken, int latitude,int longitude, MReportBean report){
		String jsonReport = gson.toJson(report);
		HttpCall call = new MyJamCallFactory(handler,HttpMethod.POST, 
				MY_JAM_REPORT_HANDLER_URL,id,jsonReport);
		call.appendAttribute(CODE, RequestCode.CREATE.code);
		call.appendAttribute(ACCESS_TOKEN, accessToken);
		call.appendAttribute(LATITUDE, String.valueOf(latitude));
		call.appendAttribute(LONGITUDE, String.valueOf(longitude));
		return call;
	}
	
//	public MReportBean insertReport(int latitude,int longitude,MReportBean report) throws InternalBackEndException, IOBackEndException, InternalClientException{
//		String q=QUERY+RequestCode.CREATE.code;
//		q=appendAttribute(q,LATITUDE,String.valueOf(latitude));
//		q=appendAttribute(q,LONGITUDE,String.valueOf(longitude));
//		String jSonReport = gson.toJson(report);
//		
//		JSONObject response;
//		try{
//			String res = httpRequest(MY_JAM_REPORT_HANDLER_URL+q,HttpMethod.POST,jSonReport);
//			response = (JSONObject) new JSONTokener(res).nextValue();
//			JSONObject data = response.getJSONObject("data");
//			String jsonData = data.getString("report");
//			System.out.println(jsonData);
//			Type mReportBeanType = new TypeToken<MReportBean>(){}.getType();
//			return this.gson.fromJson(jsonData, mReportBeanType);
//		}catch(JsonSyntaxException e){
//			throw new InternalBackEndException("Wrong response format.");
//		} catch (JSONException e) {
//			throw new InternalBackEndException("Wrong response format.");
//		}
//	}
//	
	/**
	 * Inserts a new update
	 * @param id		Id of the report which update refers to.
	 * @param update	Java bean containing the informations.
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 * @return The updateId
	 */
	public static HttpCall insertUpdate(int id, HttpCallHandler handler, 
			String accessToken, String reportId, MReportBean update){
		String jsonUpdate = gson.toJson(update);
		HttpCall call = new MyJamCallFactory(handler,HttpMethod.POST, 
				MY_JAM_UPDATE_HANDLER_URL,id,jsonUpdate);
		call.appendAttribute(CODE, RequestCode.CREATE.code);
		call.appendAttribute(ACCESS_TOKEN, accessToken);
		call.appendAttribute(REPORT_ID, reportId);
		return call;
	}
	
//	public MReportBean insertUpdate(String id,MReportBean update) throws InternalBackEndException, IOBackEndException, InternalClientException{
//		String q=QUERY+RequestCode.CREATE.code;
//		q=appendAttribute(q,REPORT_ID,id);
//		String jSonReport = gson.toJson(update);
//		
//		JSONObject response;
//		try{
//			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_UPDATE_HANDLER_URL+q,HttpMethod.POST,jSonReport)).nextValue();
//			JSONObject data = response.getJSONObject("data");
//			String jsonData = data.getString("update");
//			System.out.println(jsonData);
//			System.out.println(jsonData);
//			Type mReportBeanType = new TypeToken<MReportBean>(){}.getType();
//			return this.gson.fromJson(jsonData, mReportBeanType);
//		}catch(JsonSyntaxException e){
//			throw new InternalBackEndException("Wrong response format.");
//		} catch (JSONException e) {
//			throw new InternalBackEndException("Wrong response format.");
//		}
//	}
	/**
	 * Inserts a new feedBack
	 * @param reportId Must be included, so that the system can check if its expired or not.
	 * @param updateId
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public static HttpCall insertFeedBack(int id, HttpCallHandler handler, 
			String accessToken, String reportId, String updateId, MFeedBackBean feedback){
		String jsonUpdate = gson.toJson(feedback);
		HttpCall call = new MyJamCallFactory(handler,HttpMethod.POST, 
				MY_JAM_UPDATE_HANDLER_URL,id,jsonUpdate);
		call.appendAttribute(CODE, RequestCode.CREATE.code);
		call.appendAttribute(ACCESS_TOKEN, accessToken);
		call.appendAttribute(REPORT_ID, reportId);
		if (updateId!=null)
			call.appendAttribute(UPDATE_ID,updateId);
		return call;
	}
//	public void insertFeedBack(String reportId,String updateId,MFeedBackBean update) throws InternalBackEndException, IOBackEndException, InternalClientException{
//		String q=QUERY+RequestCode.CREATE.code;
//		q=appendAttribute(q,REPORT_ID,reportId);
//		if (updateId!=null)
//			q=appendAttribute(q,UPDATE_ID,updateId);
//		String jSonFeedBack = gson.toJson(update);
//		System.out.println(httpRequest(MY_JAM_FEEDBACK_HANDLER_URL+q,HttpMethod.POST,jSonFeedBack));
//	}

	public static HttpCall deleteReport(int id, HttpCallHandler handler, 
			String accessToken, String reportId){
		HttpCall call = new MyJamCallFactory(handler,HttpMethod.DELETE, 
				MY_JAM_REPORT_HANDLER_URL,id);
		call.appendAttribute(CODE, RequestCode.DELETE.code);
		call.appendAttribute(ACCESS_TOKEN, accessToken);
		call.appendAttribute(REPORT_ID, reportId);
		return call;
	}
//	public void deleteReport(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
//		String q=QUERY+RequestCode.DELETE.code;
//		q=appendAttribute(q,REPORT_ID,id);
//		System.out.println(httpRequest(MY_JAM_FEEDBACK_HANDLER_URL+q,HttpMethod.DELETE,null));
//	}
	
	static {
		gson = new Gson();
	}
}
