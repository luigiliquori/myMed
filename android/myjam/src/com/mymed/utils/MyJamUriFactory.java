package com.mymed.utils;

import java.util.HashMap;

import android.os.Bundle;

/**
 * Class that generates uri's for REST call.
 * @author iacopo
 *
 */
public class MyJamUriFactory {
	private static final String QUERY ="?code=";
	private static final String MY_JAM_HANDLER_URL = "http://iacoporozzo.dyndns-server.com/backend/MyJamRequestHandler";
	
    private static HashMap<Integer,String[]> attributesMap;	
	
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
	
	public interface MyJamAttributes {
		public static final String ID = "id";
		public static final String NUM = "num";
		public static final String USER_ID  = "userId";
		public static final String LATITUDE = "latitude";
		public static final String LONGITUDE = "longitude";
		public static final String RADIUS = "radius";
	}
	
	public static  String getUri(int requestCode,Bundle bundle){
		String uri = MY_JAM_HANDLER_URL + QUERY;
		String attVal;
		
		for (String currAtt : attributesMap.get(requestCode)){
			if ((attVal = bundle.getString(currAtt)) == null)
				throw new IllegalArgumentException();
			uri+="&"+currAtt+"="+attVal;
		}
		return uri;		
	}
	
    static {
    	attributesMap = new HashMap<Integer,String[]>();
        attributesMap.put(MyJamRequestCode.SEARCH_REPORTS, new String[]{MyJamAttributes.LATITUDE,MyJamAttributes.LONGITUDE,MyJamAttributes.RADIUS});
        attributesMap.put(MyJamRequestCode.GET_REPORT, new String[]{MyJamAttributes.ID});
    }

}
