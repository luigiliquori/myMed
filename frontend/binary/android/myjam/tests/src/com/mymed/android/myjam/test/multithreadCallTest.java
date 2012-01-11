package com.mymed.android.myjam.test;

import android.os.Handler;
import android.os.Message;
import android.util.Log;

import com.mymed.android.myjam.controller.CallManager;
import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.controller.HttpCall.HttpMethod;
import com.mymed.android.myjam.controller.ICallAttributes;
import com.mymed.android.myjam.controller.HttpCallHandler;

import junit.framework.TestCase;


public class multithreadCallTest extends TestCase implements ICallAttributes{

	private final static String TAG = "MultithreadCallTest";
	private final static String QUERY ="code";
	
	private Handler mMessageQueueHandler = new Handler();
	private HttpCall stopCall = null;
	
	private static final String MY_JAM_REPORT_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/MyJamReportRequestHandler";
	private static final String MY_JAM_UPDATE_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/MyJamUpdateRequestHandler";
	private static final String MY_JAM_FEEDBACK_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/MyJamFeedbackRequestHandler";
	
	private final HttpCallHandler handler = new HttpCallHandler(){
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
	
	private Runnable mStopCall = new Runnable() {
		@Override
		public void run() {
			if (stopCall!=null)
				stopCall.abort();
		}
	 };

	public void testCall(){
//        String[] urisToGet = {
//                "http://hc.apache.org/",
//                "http://hc.apache.org/httpcomponents-core/",
//                "http://hc.apache.org/httpcomponents-client/",
//                "http://svn.apache.org/viewvc/httpcomponents/"
//            };
        HttpCall[] testCall = new HttpCall[4];
		for (int i=0; i<1;i++){
//			testCall[i] = new HttpCall(handler, HttpMethod.GET, urisToGet[i],(long) i);
			testCall[i] = new HttpCall(handler, HttpMethod.GET, MY_JAM_REPORT_HANDLER_URL, i);
			testCall[i].appendAttribute(QUERY,RequestCode.READ.code);
			testCall[i].appendAttribute(LATITUDE, String.valueOf(7500000));
			testCall[i].appendAttribute(LONGITUDE, String.valueOf(44500000));
			testCall[i].appendAttribute(RADIUS, String.valueOf(10000));
		}

		for (int i=0; i<1;i++){
			testCall[i].execute();
		}
		stopCall = testCall[0];
		mMessageQueueHandler.postDelayed(mStopCall, 100);
		for (int i=0; i<4;i++){
//			testCall[i] = new HttpCall(handler, HttpMethod.GET, urisToGet[i],(long) i);
			testCall[i] = new HttpCall(handler, HttpMethod.GET, MY_JAM_REPORT_HANDLER_URL, i);
			testCall[i].appendAttribute(QUERY,RequestCode.READ.code);
			testCall[i].appendAttribute(LATITUDE, String.valueOf(7500000));
			testCall[i].appendAttribute(LONGITUDE, String.valueOf(44500000));
			testCall[i].appendAttribute(RADIUS, String.valueOf(10000));
		}
		for (int i=0; i<4;i++){
			testCall[i].execute();
		}
		
		
		CallManager.shutDown();
	}
}
