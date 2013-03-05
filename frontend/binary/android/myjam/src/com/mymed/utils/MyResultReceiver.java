package com.mymed.utils;

import java.lang.ref.WeakReference;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.controller.HttpCallHandler;

import android.os.Bundle;
import android.os.Handler;
import android.os.ResultReceiver;
import android.util.Log;

/**
 * Singleton class that retains the status of the calls and notifies the registered activity.
 * 
 * @author iacopo
 *
 */
public class MyResultReceiver extends ResultReceiver {
	public static final int MSG_SERVICE_DESTROYED = -1;
	
	private static final String TAG = "MyResultReceiver";
	public static final String CALL_ID = "com.mymed.android.myjam.bundle.CALL_ID";
	public static final String CALL_CODE = "com.mymed.android.myjam.bundle.CALL_CODE";
	public static final String STATUS_CODE = "com.mymed.android.myjam.bundle.STATUS_CODE";
	public static final String ACTIVITY_ID = "com.mymed.android.myjam.bundle.ACTIVITY_ID";
	public static final String PRIORITY = "com.mymed.android.myjam.bundle.PRIORITY";
	public static final String NUM_ATTEMPTS = "com.mymed.android.myjam.bundle.NUM_ATTEMPTS";
	public static final String MAX_NUM_ATTEMPTS = "com.mymed.android.myjam.bundle.MAX_NUM_ATTEMPTS";
	public static final String MESSAGE = "com.mymed.android.myjam.bundle.MESSAGE";
	
	
	private static MyResultReceiver instance;
	private WeakReference<IReceiver> mReceiver;
	/** Keep track of the ongoing calls. */
	private String currActivity;
	// The array of int contains the call id at the position 0 and the call code at the position 1.
	private int[] highPriorityCall;
	private Map<String,Map<Integer,int[]>> lowPriorityCalls;
//	private int numLowPriorityCalls;

	/**
	 * Instantiate {@link MyResultReceiver} if the local instance is not {@value null}.
	 * 
	 * @param activityId Id of the calling activity.
	 * @param receiver	{@link IReceiver} implementation of the calling activity. 
	 * @return An instance of {@link MyResultReceiver}.
	 */
	public static MyResultReceiver getInstance(String activityId, IReceiver receiver){
		if (instance == null)
			instance = new MyResultReceiver();
		instance.setReceiver(activityId, receiver);
		return instance;
	}
	
	/**
	 * Release the resources.
	 */
	public static void shutdown(){
		instance = null;
	}
	
	/**
	 * Return current {@link MyResultReceiver} instance.
	 * 
	 * @return An instance of {@link MyResultReceiver} or null.
	 */
	public static MyResultReceiver get(){
		return instance;
	}

	/** The result receiver is associated to the main thread (UI thread). */
	private MyResultReceiver() {
		super(new Handler());
		/**
		 * Contains the attributes of the low priority calls associated the activities.
		 */
		lowPriorityCalls = new HashMap<String,Map<Integer,int[]>>();
		// When highPriorityCall is set to Integer.MIN_VALUE, means that no high priority calls are ongoing.
		highPriorityCall = null;
	}

	public void clearReceiver() {
		this.mReceiver = null;
	}

	/**
	 * Sets the {@link Receiver} and the 
	 * @param activityId
	 * @param receiver
	 */
	private void setReceiver(String activityId, IReceiver receiver) {
		this.currActivity = activityId;
		this.mReceiver = new WeakReference<IReceiver>(receiver);

		/** Initializes the list of calls associated to the current activity. */
		if (lowPriorityCalls.get(currActivity)==null)
			lowPriorityCalls.put(currActivity, new HashMap<Integer,int[]>());
	}
	
	/**
	 * Check if there is an ongoing hp call.
	 */
	public void checkOngoingCalls(){
		if (highPriorityCall!=null)
			mReceiver.get().onUpdateProgressStatus(true, highPriorityCall[1], highPriorityCall[0]);
		else
			mReceiver.get().onUpdateProgressStatus(false,-1,-1);
	}

	/**
	 * The activities that want to receive results from {@link MyResultReceiver} must implement
	 * the receiver interface.
	 * 
	 * @author iacopo
	 *
	 */
	public interface IReceiver{

		/**
		 * Provide an update of the progress status on the current activity.
		 * 
		 * @param state 
		 * 		If {@value true} The progress dialog must be shown.
		 * 		If {@value true} The progress dialog must be cleaned.
		 * @param callCode 
		 * 		Code of the call involved.
		 * @param callId
		 * 		Id of the call involved.
		 */
		public void onUpdateProgressStatus(boolean state, int callCode, int callId);

		/**
		 * Provide an error message to be notified from the current activity.
		 * 
		 * @param callCode
		 * 		Code of the call involved.
		 * @param callId
		 * 		Id of the call involved.
		 * @param errorMessage
		 * 		Error message.
		 * @param numAttempt
		 * 		Attempt number of the current call.
		 * @param maxAttempts
		 * 		Maximum number of attempts for the current call.
		 */
		public void onCallError(int callCode, int callId, int errorCode, String errorMessage, int numAttempt, int maxAttempts);

		/**
		 * Communicate that the current call has been interrupted.
		 * 
		 * @param callCode	Code of the interrupted call.
		 * @param callId	Id of interrupted call.
		 */
		public void onCallInterrupted(int callCode, int callId);
		
		/**
		 * Communicate that the current call has been successful.
		 * 
		 * @param callCode	Code of the interrupted call.
		 * @param callId	Id of interrupted call.
		 */
		public void onCallSuccess(int callCode, int callId);
		
	}

	/**
	 * Returns a list containing an array of int[].
	 * 
	 * @return
	 */
	public List<int[]> getOngoingLPCalls() {
		Map<Integer,int[]> ongoingCalls = lowPriorityCalls.get(this.currActivity);
		List<int[]> tmpList = new ArrayList<int[]>(ongoingCalls.values());
		return tmpList;
	}
	
	public int[] getOngoingHPCall() {
		return highPriorityCall;
	}

	@Override
	protected void onReceiveResult(int resultCode, Bundle bundle) {
		final int callId = bundle.getInt(MyResultReceiver.CALL_ID);
		final int callCode = bundle.getInt(MyResultReceiver.CALL_CODE);
		final int statusCode = bundle.getInt(MyResultReceiver.STATUS_CODE);
		final int numAttempts = bundle.getInt(MyResultReceiver.NUM_ATTEMPTS);
		final int maxNumAttempts = bundle.getInt(MyResultReceiver.MAX_NUM_ATTEMPTS);
		final int priority = bundle.getInt(MyResultReceiver.PRIORITY, HttpCall.LOW_PRIORITY);
		final String activityId = bundle.getString(MyResultReceiver.ACTIVITY_ID);
		final String message = bundle.getString(MyResultReceiver.MESSAGE);
		final int[] callDetails = new int[]{callId,callCode};
		
		switch (resultCode) {
		case HttpCallHandler.MSG_CALL_START:
			currActivity = activityId;
			switch(priority){
			case HttpCall.LOW_PRIORITY:
				lowPriorityCalls.get(activityId).put(callId, callDetails);
//				numLowPriorityCalls++;
				break;
			case HttpCall.HIGH_PRIORITY:
				highPriorityCall=callDetails;
				if (activityId.equals(this.currActivity))
					// The progress dialog is shown only for high priority calls.
					if (mReceiver!=null) {
						mReceiver.get().onUpdateProgressStatus(true, callCode, callId);
						Log.d(TAG, "Show progress status " + callId);
					}
				break;
			}
			break;
		case HttpCallHandler.MSG_CALL_SUCCESS:
			if (mReceiver!=null) mReceiver.get().onCallSuccess(callCode, callId);
		case HttpCallHandler.MSG_CALL_NOT_STARTED: 
		case HttpCallHandler.MSG_CALL_ERROR:
			if (mReceiver!=null && resultCode == HttpCallHandler.MSG_CALL_ERROR)
				mReceiver.get().onCallError(callCode, callId, statusCode, message, 
						numAttempts, maxNumAttempts);
		case HttpCallHandler.MSG_CALL_INTERRUPTED:
			if (mReceiver!=null && resultCode == HttpCallHandler.MSG_CALL_INTERRUPTED)
				mReceiver.get().onCallInterrupted(callCode, callId);
			switch(priority){
			case HttpCall.LOW_PRIORITY:
//				numLowPriorityCalls++;
				lowPriorityCalls.get(activityId).remove(callId);
				break;
			case HttpCall.HIGH_PRIORITY:
				highPriorityCall=null;
				if (mReceiver!=null && activityId.equals(this.currActivity)) {
					mReceiver.get().onUpdateProgressStatus(false, callCode, callId);
					Log.d(TAG, "Dismiss progress status " + callId+" Result code: "+resultCode);
				}else{
					Log.d(TAG, "Missed dismiss: "+callId+" Result code: "+resultCode);
				}
				break;
			}
		}
	}
}
