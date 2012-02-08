package com.mymed.utils;

import java.lang.ref.WeakReference;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.controller.HttpCallHandler;
import com.mymed.android.myjam.service.CallService;
import com.mymed.model.data.myjam.MCallBean;

import android.os.Bundle;
import android.os.Handler;
import android.os.ResultReceiver;
import android.util.Log;

/**
 * Singleton class that retains the status of the calls.
 * @author iacopo
 *
 */
public class MyResultReceiver extends ResultReceiver {
	private static MyResultReceiver instance;
    private static final String TAG = "MyResultReceiver";
    private WeakReference<Receiver> mReceiver;
    private String currActivity;
    /** Keep track of the ongoing calls. */
    private Map<String,List<MCallBean>> highPriorityCalls;
    private Map<String,List<MCallBean>> lowPriorityCalls;
    

    public static MyResultReceiver getInstance(){
    	if (instance == null)
    		instance = new MyResultReceiver();
    	return instance;
    }
    
    /** The result receiver is associated to the main thread (UI thread). */
    private MyResultReceiver() {
        super(new Handler());
        
        lowPriorityCalls = new HashMap<String,List<MCallBean>>();
        highPriorityCalls = new HashMap<String,List<MCallBean>>();
    }
    
    public void clearReceiver() {
        mReceiver = null;
    }

    /**
     * Sets the {@link Receiver} and the 
     * @param activityId
     * @param receiver
     */
    public void setReceiver(String activityId, Receiver receiver) {
    	this.currActivity = activityId;
        this.mReceiver = new WeakReference<Receiver>(receiver);
        
        /** Initializes the list of calls associated to the current activity. */
        if (lowPriorityCalls.get(currActivity)==null)
        	lowPriorityCalls.put(currActivity, new ArrayList<MCallBean>());
        if (highPriorityCalls.get(currActivity)==null)
        	highPriorityCalls.put(currActivity, new ArrayList<MCallBean>());
    }

    /**
     * The activities that want to receive results from {@link MyResultReceiver} must implement
     * the receiver interface.
     * 
     * @author iacopo
     *
     */
    public interface Receiver {
        public void onReceiveResult(int resultCode, Bundle resultData);
    }
    
	public List<MCallBean> getOngoingCalls(int priority) {
		return priority==HttpCall.LOW_PRIORITY?lowPriorityCalls.get(this.currActivity):highPriorityCalls.get(this.currActivity);
	}

    @Override
    protected void onReceiveResult(int resultCode, Bundle resultData) {
    	final MCallBean call = (MCallBean) resultData.getSerializable(CallService.CALL_BEAN);
    	
    	switch (resultCode) {
    	case HttpCallHandler.MSG_CALL_START:
    		//setmSyncing(true);
    		switch(call.getPriority()){
    		case HttpCall.LOW_PRIORITY:
    			lowPriorityCalls.get(call.getActivityId()).add(call);
    			break;
    		case HttpCall.HIGH_PRIORITY:
    			highPriorityCalls.get(call.getActivityId()).add(call);
    			break;
    		}
    		break;
    	case HttpCallHandler.MSG_CALL_SUCCESS: 
    	case HttpCallHandler.MSG_CALL_ERROR:
    	case HttpCallHandler.MSG_CALL_INTERRUPTED:
    		switch(call.getPriority()){
    		case HttpCall.LOW_PRIORITY:
    			lowPriorityCalls.get(call.getActivityId()).remove(call);
    			break;
    		case HttpCall.HIGH_PRIORITY:
    			highPriorityCalls.get(call.getActivityId()).remove(call);
    			break;
    		}
    		break;
    	}
        if (currActivity.equals(call.getActivityId()) && mReceiver != null) {
            mReceiver.get().onReceiveResult(resultCode, resultData);
        } else {
            Log.w(TAG, "Dropping result on floor for code " + resultCode + ": "
                    + resultData.toString());
        }
    }
}
