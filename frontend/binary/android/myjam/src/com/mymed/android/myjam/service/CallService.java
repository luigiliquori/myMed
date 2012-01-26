package com.mymed.android.myjam.service;

import java.util.LinkedHashMap;
import java.util.Map;

import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.controller.HttpCallHandler;
import com.mymed.android.myjam.controller.ICallAttributes;
import com.mymed.android.myjam.controller.MyJamCallFactory;

import android.app.Service;
import android.content.Intent;
import android.os.Bundle;
import android.os.HandlerThread;
import android.os.IBinder;
import android.os.Looper;
import android.os.Message;
import android.os.ResultReceiver;
import android.util.Log;

/**
 * Service that handles the requests to the front-end.
 * 
 * @author iacopo
 *
 */
public class CallService extends Service {
	private static final String TAG="CallService";
	
    public static final String EXTRA_STATUS_RECEIVER =
            "com.mymed.android.myjam.extra.STATUS_RECEIVER";
    
    public static final String EXTRA_REQUEST_CODE =
            "com.mymed.android.myjam.extra.REQUEST_CODE";
    
    public static final String EXTRA_UPDATE_ID =
            "com.mymed.android.myjam.extra.UPDATE_ID";
    
    public static final String EXTRA_ATTRIBUTES =
            "com.mymed.android.myjam.extra.ATTRIBUTES";
    
    public static final String EXTRA_OBJECT =
            "com.mymed.android.myjam.extra.OBJECT";   
    
    public static final int STATUS_RUNNING = 0x1;
    public static final int STATUS_ERROR = 0x2;
    public static final int STATUS_FINISHED = 0x3; 
	
    private volatile Looper mServiceLooper;
    private volatile ServiceHandler mServiceHandler;    
    private volatile Map<Integer, Object> callsMap;
    
    public interface RequestCode{
    	int LOG_IN = 0x0;
    	int PARTIAL_LOG_OUT = 0x1;
    	int COMPLETE_LOG_OUT = 0x2;

    	int SEARCH_REPORTS = 0x3;
    	int GET_REPORT = 0x4;
    	int GET_UPDATES = 0x5;
    	int GET_REPORT_FEEDBACKS = 0x6;
    	int GET_UPDATE_FEEDBACKS = 0x7;
    	
    	int INSERT_REPORT = 0x8;
    	int INSERT_UPDATE = 0x9;
    	int INSERT_REPORT_FEEDBACK = 0x10;
    	int INSERT_UPDATE_FEEDBACK = 0x11;
    }


    private final class ServiceHandler extends HttpCallHandler {
    	public ServiceHandler(Looper looper) {
    		super (looper);
    	}

    	@Override
    	public void handleMessage(Message msg) {
    		final ResultReceiver receiver;
    		final Bundle bundle = new Bundle();
    		switch(msg.what){
    		/** It is a new request arrived at the service. */
    		case NEW_REQUEST:
    			Intent intent = (Intent) msg.obj;
    			receiver = intent.getParcelableExtra(EXTRA_STATUS_RECEIVER);
    			callsMap.put(msg.arg1, receiver);
        		handleIntent((Intent) msg.obj, msg.arg1);
    			break;
    		/** The call started. */
    		case MSG_CALL_START:
    			Log.d(TAG,"Call "+msg.arg1+" started.");
    			bundle.putInt(EXTRA_REQUEST_CODE, msg.arg1);
    			receiver = (ResultReceiver) callsMap.get(msg.arg1);
    			if (receiver != null) receiver.send(STATUS_RUNNING, bundle);
    			break;
    		/** The call ended successfully. */
    		case MSG_CALL_SUCCESS:
    			Log.d(TAG,"Call "+msg.arg1+" succesfull. Result: "+(String) msg.obj);
    			bundle.putInt(EXTRA_REQUEST_CODE, msg.arg1);
    			handleCallSuccess(msg);
    			receiver = (ResultReceiver) callsMap.get(msg.arg1);
    			if (receiver != null) receiver.send(STATUS_FINISHED, bundle);
    		/** The call has been interrupted. */
    		case MSG_CALL_INTERRUPTED:
    			callsMap.remove(msg.arg1);
    			if (callsMap.size() == 0)
    				stopSelf();
    			break;
    		/** The call ended due to an error. */
    		case MSG_CALL_ERROR:
    			Log.d(TAG,"Call "+msg.arg1+" error: "+((Exception) msg.obj).getMessage());
    			bundle.putInt(EXTRA_REQUEST_CODE, msg.arg1);
    			receiver = (ResultReceiver) callsMap.get(msg.arg1);
    			if (receiver != null) receiver.send(STATUS_ERROR, bundle);
    			callsMap.remove(msg.arg1);
    			break;
    		}
    		//stopSelf(msg.arg1); // Must be stopped when the work ends.
    	}
    }

    @Override
    public void onCreate() {

        super .onCreate();
        HandlerThread thread = new HandlerThread(TAG);
        thread.start();

        mServiceLooper = thread.getLooper();
        mServiceHandler = new ServiceHandler(mServiceLooper);
        callsMap = new LinkedHashMap<Integer,Object>();
    }

    @Override
    public void onStart(Intent intent, int startId) {
        Message msg = mServiceHandler.obtainMessage();
        msg.what = HttpCallHandler.NEW_REQUEST;
        msg.arg1 = startId;
        msg.obj = intent;
        mServiceHandler.sendMessage(msg);
    }

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {
        onStart(intent, startId);
        return START_NOT_STICKY;
    }

    @Override
    public void onDestroy() {
        mServiceLooper.quit();
    }

    /**
     * Started service, don't bind. 
     */
    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }
    
    private void handleIntent(Intent intent, int reqId){
		final Bundle contBundle = intent.getBundleExtra(EXTRA_ATTRIBUTES);
		final int reqCode = intent.getIntExtra(EXTRA_REQUEST_CODE, 0);
		final HttpCall call;
    	switch(reqCode){
    		//TODO
    		case RequestCode.SEARCH_REPORTS:
    			call = MyJamCallFactory.searchReports(reqId, mServiceHandler,
    			contBundle.getInt(ICallAttributes.LATITUDE),
    			contBundle.getInt(ICallAttributes.LONGITUDE),
    			contBundle.getInt(ICallAttributes.RADIUS));
    			call.execute();
    			break;
    	}
    }
    
    private void handleCallSuccess(Message msg){
    	
    }
}