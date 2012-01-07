package com.mymed.android.myjam.test;

import android.os.Handler;
import android.os.Message;
import android.util.Log;

import com.mymed.android.myjam.controller.CallManager;
import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.controller.HttpCall.HttpMethod;
import com.mymed.android.myjam.controller.ICallAttributes;
import com.mymed.android.myjam.controller.ICallAttributes.RequestCode;
import com.mymed.android.myjam.controller.HttpCallHandler;

import junit.framework.TestCase;


public class multithreadCallTest extends TestCase implements ICallAttributes{

	private final static String TAG = "MultithreadCallTest";
	private final static String QUERY ="?code=";
	
	private static final String MY_JAM_REPORT_HANDLER_URL = "http://10.0.2.2:8080/mymed_backend/MyJamReportRequestHandler";
	private static final String MY_JAM_UPDATE_HANDLER_URL = "http://10.0.2.2:8080/mymed_backend/MyJamUpdateRequestHandler";
	private static final String MY_JAM_FEEDBACK_HANDLER_URL = "http://10.0.2.2:8080/mymed_backend/MyJamFeedbackRequestHandler";
	
	private final Handler handler = new Handler(){
		public void handleMessage(Message message) {
			switch (message.what) {
			case HttpCallHandler.MSG_CALL_START: 
				Log.i(TAG, "Starting call...");
				break;
			case HttpCallHandler.MSG_CALL_SUCCESS: 
				String response = (String) message.obj;
				Log.i(TAG, response);
				break;
			case HttpCallHandler.MSG_CALL_ERROR: 
				Exception e = (Exception) message.obj;
				Log.i(TAG, "Error: "+e.getMessage());
				break;
			}
		}
	};

	public void testCall(){
		HttpCall testCall = new HttpCall(handler, HttpMethod.GET, MY_JAM_REPORT_HANDLER_URL+QUERY+RequestCode.READ.code);
		testCall.appendAttribute(LATITUDE, String.valueOf(7500000));
		testCall.appendAttribute(LONGITUDE, String.valueOf(44500000));
		testCall.appendAttribute(RADIUS, String.valueOf(10000));
		testCall.run();
		CallManager.shutDown();
	}
}
