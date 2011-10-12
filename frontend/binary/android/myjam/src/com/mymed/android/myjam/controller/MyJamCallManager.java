package com.mymed.android.myjam.controller;

import java.lang.reflect.Type;
import java.util.List;

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
 * Singleton class used to perform REST calls, according to MyJam API.
 * @author iacopo
 *
 */
public class MyJamCallManager extends HTTPCall implements ICallAttributes{
	private static MyJamCallManager instance;
	private static final String QUERY ="?code=";
	private static final String MY_JAM_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/MyJamRequestHandler";
/** For local testing with the emulator.
	private static final String MY_JAM_HANDLER_URL = "http://10.0.2.2:8080/LocalMyMed/MyJamRequestHandler";
*/	
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
	
	/** Request Code*/
	public interface MyJamRequestCode { 
		public static final int SEARCH_REPORTS = 0;
		public static final int GET_REPORT = 1; 	
		public static final int GET_NUMBER_UPDATES = 2;
		public static final int GET_UPDATES = 3;
		public static final int GET_FEEDBACKS = 4;
		public static final int GET_ACTIVE_REPORTS = 5;	
		public static final int GET_USER_REPORT_UPDATE = 6;	//TODO To implement
		public static final int INSERT_REPORT = 7;
		public static final int INSERT_UPDATE = 8;
		public static final int INSERT_FEEDBACK = 9;
		public static final int DELETE_REPORT = 10;
		//TODO Eventually add DELETE_REPORT and DELETE_UPDATE
	}
	
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
		String q=QUERY+MyJamRequestCode.SEARCH_REPORTS;
		q=appendAttribute(q,LATITUDE,String.valueOf(latitude));
		q=appendAttribute(q,LONGITUDE,String.valueOf(longitude));
		q=appendAttribute(q,RADIUS,String.valueOf(radius));
		String response = httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.GET,null);
		Type shortReportListType = new TypeToken<List<MSearchReportBean>>(){}.getType();
		try{
			return this.gson.fromJson(response, shortReportListType);
		}catch(JsonSyntaxException e){
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
		String q=QUERY+MyJamRequestCode.GET_REPORT;
		q=appendAttribute(q,REPORT_ID,id);
		String response = httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.GET,null);
		try{
			return this.gson.fromJson(response, MReportBean.class);
		}catch(JsonSyntaxException e){
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
		String q=QUERY+MyJamRequestCode.GET_NUMBER_UPDATES;
		q=appendAttribute(q,REPORT_ID,id);
		String response = httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.GET,null);
		try{
			return this.gson.fromJson(response, int.class);
		}catch(JsonSyntaxException e){
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
		String q=QUERY+MyJamRequestCode.GET_UPDATES;
		q=appendAttribute(q,REPORT_ID,id);
		q=appendAttribute(q,NUM,String.valueOf(numUpdates));
		String response = httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.GET,null);
		Type reportListType = new TypeToken<List<MReportBean>>(){}.getType();
		try{
			return this.gson.fromJson(response, reportListType);
		}catch(JsonSyntaxException e){
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
		String q=QUERY+MyJamRequestCode.GET_FEEDBACKS;
		q=appendAttribute(q,REPORT_ID,id);
		String response = httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.GET,null);
		Type feedListType = new TypeToken<List<MFeedBackBean>>(){}.getType();
		try{
			return this.gson.fromJson(response, feedListType);
		}catch(JsonSyntaxException e){
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
		String q=QUERY+MyJamRequestCode.GET_ACTIVE_REPORTS;
		q=appendAttribute(q,USER_ID,userId);
		String response = httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.GET,null);
		Type stringListType = new TypeToken<List<String>>(){}.getType();
		try{
			return this.gson.fromJson(response, stringListType);
		}catch(JsonSyntaxException e){
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
		String q=QUERY+MyJamRequestCode.INSERT_REPORT;
		q=appendAttribute(q,LATITUDE,String.valueOf(latitude));
		q=appendAttribute(q,LONGITUDE,String.valueOf(longitude));
		String jSonReport = gson.toJson(report);
		String response =httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.POST,jSonReport);
		System.out.println(response);
		Type mReportBeanType = new TypeToken<MReportBean>(){}.getType();
		try{
			return this.gson.fromJson(response, mReportBeanType);
		}catch(JsonSyntaxException e){
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
		String q=QUERY+MyJamRequestCode.INSERT_UPDATE;
		q=appendAttribute(q,REPORT_ID,id);
		String jSonReport = gson.toJson(update);
		String response = httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.POST,jSonReport);
		System.out.println(response);
		Type mReportBeanType = new TypeToken<MReportBean>(){}.getType();
		try{
			return this.gson.fromJson(response, mReportBeanType);
		}catch(JsonSyntaxException e){
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
		String q=QUERY+MyJamRequestCode.INSERT_FEEDBACK;
		q=appendAttribute(q,REPORT_ID,reportId);
		if (updateId!=null)
			q=appendAttribute(q,UPDATE_ID,updateId);
		String jSonFeedBack = gson.toJson(update);
		System.out.println(httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.POST,jSonFeedBack));
	}
	
	public void deleteReport(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q=QUERY+MyJamRequestCode.DELETE_REPORT;
		q=appendAttribute(q,REPORT_ID,id);
		System.out.println(httpRequest(MY_JAM_HANDLER_URL+q,httpMethod.DELETE,null));
	}
}
