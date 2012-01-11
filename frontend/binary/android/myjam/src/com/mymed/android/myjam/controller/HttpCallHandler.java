package com.mymed.android.myjam.controller;

import android.os.Handler;

/**
 * Handle the results of the Http calls.
 * @author iacopo
 *
 */
public class HttpCallHandler extends Handler{
	
	public static final int MSG_CALL_START = 0;
	public static final int MSG_CALL_SUCCESS = 1;
	public static final int MSG_CALL_ERROR = 2;
	public static final int MSG_CALL_INTERRUPTED = 3;
	
	/**
	 * The call started.
	 * 
	 * @param id Id of the call.
	 */
	public void callStart(int id){
		this.sendMessage(obtainMessage(MSG_CALL_START, id, 0 ,null));	
	}
	
	/**
	 * The call was successful.
	 * 
	 * @param id Id of the call.
	 */
	public void callSuccess(int id, String result){
		sendMessage(obtainMessage(MSG_CALL_SUCCESS, id, 0, result));
	}

	/**
	 * The call was interrupted by an exception. 
	 * 
	 * @param id Id of the call.
	 * @param e	Exception thrown.
	 */
	public void callError(int id, Exception e){
		sendMessage(obtainMessage(MSG_CALL_ERROR, id, 0, e));
	}
	
	/**
	 * 
	 * The was interrupted.
	 * 
	 * @param id
	 */
	public void callInterrupted(int id){
		this.sendMessage(obtainMessage(MSG_CALL_INTERRUPTED, id, 0 ,null));
	}
}
