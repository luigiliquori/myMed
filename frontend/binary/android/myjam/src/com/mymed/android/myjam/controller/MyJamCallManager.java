package com.mymed.android.myjam.controller;

import java.lang.reflect.Type;
import java.util.List;

import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

import com.google.gson.Gson;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MSearchReportBean;

/**
 * Singleton class used to perform HTTP calls, according to MyJam API.
 * @author iacopo
 *
 */
public class MyJamCallManager extends HTTPCall implements ICallAttributes{
	private static MyJamCallManager instance;
	private static final String QUERY ="?code=";
//	private static final String MY_JAM_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/MyJamRequestHandler";
	private static final String MY_JAM_REPORT_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/MyJamReportRequestHandler";
	private static final String MY_JAM_UPDATE_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/MyJamUpdateRequestHandler";
	private static final String MY_JAM_FEEDBACK_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/MyJamFeedbackRequestHandler";
	//  For local testing with the emulator.
//	private static final String MY_JAM_REPORT_HANDLER_URL = "http://10.0.2.2:8080/LocalMyMed/MyJamReportRequestHandler";
//	private static final String MY_JAM_UPDATE_HANDLER_URL = "http://10.0.2.2:8080/LocalMyMed/MyJamUpdateRequestHandler";
//	private static final String MY_JAM_FEEDBACK_HANDLER_URL = "http://10.0.2.2:8080/LocalMyMed/MyJamFeedbackRequestHandler";
	
	private Gson gson;
	
	private MyJamCallManager(){
		gson = new Gson();
	};
	
	// Singleton
	public static MyJamCallManager getInstance(){
		if (instance == null)
			instance = new MyJamCallManager();
		return instance;
	}
	
//	/** Request Code*/
//	public interface MyJamRequestCode { 
//		public static final int SEARCH_REPORTS = 0;
//		public static final int GET_REPORT = 1; 	
//		public static final int GET_NUMBER_UPDATES = 2;
//		public static final int GET_UPDATES = 3;
//		public static final int GET_FEEDBACKS = 4;
//		public static final int GET_ACTIVE_REPORTS = 5;	
//		public static final int GET_USER_REPORT_UPDATE = 6;	//TODO To implement
//		public static final int INSERT_REPORT = 7;
//		public static final int INSERT_UPDATE = 8;
//		public static final int INSERT_FEEDBACK = 9;
//		public static final int DELETE_REPORT = 10;
//		//TODO Eventually add DELETE_REPORT and DELETE_UPDATE
//	}
	
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
	public List<MSearchReportBean> searchReports(int latitude,int longitude, int radius) 
			throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.READ.code;
		q=appendAttribute(q,LATITUDE,String.valueOf(latitude));
		q=appendAttribute(q,LONGITUDE,String.valueOf(longitude));
		q=appendAttribute(q,RADIUS,String.valueOf(radius));
//		String response = httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.GET,null);
		JSONObject response;
		try{
			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_REPORT_HANDLER_URL+q,httpMethod.GET,null)).nextValue();
			JSONObject data = response.getJSONObject("data");
			String jsonData = data.getString("search_reports");
			Type shortReportListType = new TypeToken<List<MSearchReportBean>>(){}.getType();
			return this.gson.fromJson(jsonData, shortReportListType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	/**
	 * Gets the report corresponding to id.
	 * @param id Identifier of the Report.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public MReportBean getReport(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.READ.code;
		q=appendAttribute(q,REPORT_ID,id);
		
		JSONObject response;
		try{
			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_REPORT_HANDLER_URL+q,httpMethod.GET,null)).nextValue();
			JSONObject data = response.getJSONObject("data");
			String jsonData = data.getString("report");
			Type shortReportListType = new TypeToken<MReportBean>(){}.getType();
			return this.gson.fromJson(jsonData, shortReportListType);

		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	/**
	 * Returns the number of available updates.
	 * @param id Identifier of the Report.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public int getNumberUpdates(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.READ.code;
		q=appendAttribute(q,REPORT_ID,id);

		JSONObject response;
		try{
			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_UPDATE_HANDLER_URL+q,httpMethod.GET,null)).nextValue();
			JSONObject data = response.getJSONObject("data");
			String jsonData = data.getString("num_updates");
			return this.gson.fromJson(jsonData, int.class);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	
	/**
	 * Gets the last "numUpdates" updates. 
	 * @param ids List of updates id.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public List<MReportBean> getUpdates(String id,int numUpdates) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.READ.code;
		q=appendAttribute(q,REPORT_ID,id);
		q=appendAttribute(q,NUM,String.valueOf(numUpdates));
		
		JSONObject response;
		try{
			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_UPDATE_HANDLER_URL+q,httpMethod.GET,null)).nextValue();
			JSONObject data = response.getJSONObject("data");
			String jsonData = data.getString("updates");
			Type reportListType = new TypeToken<List<MReportBean>>(){}.getType();
			return this.gson.fromJson(jsonData, reportListType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}		
	}
	
	/**
	 * Gets the feedbacks corresponding to report id. 
	 * @param id Id of the report.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public List<MFeedBackBean> getFeedBacks(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.READ.code;
		q=appendAttribute(q,REPORT_ID,id);
 
		JSONObject response;
		try{
			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_FEEDBACK_HANDLER_URL+q,httpMethod.GET,null)).nextValue();
			JSONObject data = response.getJSONObject("data");
			String jsonData = data.getString("feedbacks");
			Type feedListType = new TypeToken<List<MFeedBackBean>>(){}.getType();
			return this.gson.fromJson(jsonData, feedListType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	
	/**
	 * Gets the ids of the active reports inserted by a user.
	 * @param userId
	 * @return A list of ids.
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public List<String> getActiveReports(String userId) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.READ.code;
		
		q=appendAttribute(q,USER_ID,userId);
		
		JSONObject response;
		try{
			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_REPORT_HANDLER_URL+q,httpMethod.GET,null)).nextValue();
			JSONObject data = response.getJSONObject("data");
			String jsonData = data.getString("active_reports");
			Type stringListType = new TypeToken<List<String>>(){}.getType();
			return this.gson.fromJson(jsonData, stringListType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	
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
	public MReportBean insertReport(int latitude,int longitude,MReportBean report) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.CREATE.code;
		q=appendAttribute(q,LATITUDE,String.valueOf(latitude));
		q=appendAttribute(q,LONGITUDE,String.valueOf(longitude));
		String jSonReport = gson.toJson(report);
		
		JSONObject response;
		try{
			String res = httpRequest(MY_JAM_REPORT_HANDLER_URL+q,httpMethod.POST,jSonReport);
			response = (JSONObject) new JSONTokener(res).nextValue();
			JSONObject data = response.getJSONObject("data");
			String jsonData = data.getString("report");
			System.out.println(jsonData);
			Type mReportBeanType = new TypeToken<MReportBean>(){}.getType();
			return this.gson.fromJson(jsonData, mReportBeanType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	
	/**
	 * Inserts a new update
	 * @param id		Id of the report which update refers to.
	 * @param update	Java bean containing the informations.
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 * @return The updateId
	 */
	public MReportBean insertUpdate(String id,MReportBean update) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.CREATE.code;
		q=appendAttribute(q,REPORT_ID,id);
		String jSonReport = gson.toJson(update);
		
		JSONObject response;
		try{
			response = (JSONObject) new JSONTokener(httpRequest(MY_JAM_UPDATE_HANDLER_URL+q,httpMethod.POST,jSonReport)).nextValue();
			JSONObject data = response.getJSONObject("data");
			String jsonData = data.getString("update");
			System.out.println(jsonData);
			System.out.println(jsonData);
			Type mReportBeanType = new TypeToken<MReportBean>(){}.getType();
			return this.gson.fromJson(jsonData, mReportBeanType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	/**
	 * Inserts a new feedBack
	 * @param reportId Must be included, so that the system can chek if its expired or not.
	 * @param updateId
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public void insertFeedBack(String reportId,String updateId,MFeedBackBean update) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.CREATE.code;
		q=appendAttribute(q,REPORT_ID,reportId);
		if (updateId!=null)
			q=appendAttribute(q,UPDATE_ID,updateId);
		String jSonFeedBack = gson.toJson(update);
		System.out.println(httpRequest(MY_JAM_FEEDBACK_HANDLER_URL+q,httpMethod.POST,jSonFeedBack));
	}
	
	public void deleteReport(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+RequestCode.DELETE.code;
		q=appendAttribute(q,REPORT_ID,id);
		System.out.println(httpRequest(MY_JAM_FEEDBACK_HANDLER_URL+q,httpMethod.DELETE,null));
	}
}
