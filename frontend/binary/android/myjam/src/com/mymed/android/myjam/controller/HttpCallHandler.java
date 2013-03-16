/*
 * Copyright 2012 POLITO 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
package com.mymed.android.myjam.controller;

import android.os.Handler;
import android.os.Looper;

/**
 * Handle the results of the Http calls.
 * @author iacopo
 *
 */
public class HttpCallHandler extends Handler{
	
	public static final int NEW_CALL = 0x0;
	public static final int INTERRUPT_CALL = 0x1;
	public static final int MSG_CALL_START = 0x2;
	public static final int MSG_CALL_NOT_STARTED = 0x3;
	public static final int MSG_CALL_SUCCESS = 0x4;
	public static final int MSG_CALL_ERROR = 0x5;
	public static final int MSG_CALL_INTERRUPTED = 0x6;
	public static final int MSG_CALL_WAITING = 0x7;
	public static final int MSG_START_WAITING_CALLS = 0x8;
	
	/**
	 * Basic constructor.
	 * @param looper
	 */
	public HttpCallHandler() {
		super();
	}
	
	/**
	 * Constructor with looper.
	 * @param looper
	 */
	public HttpCallHandler(Looper looper) {
		super(looper);
	}
	
	/**
	 * The call started.
	 * 
	 * @param id Id of the call.
	 */
	public void callStart(int id){
		this.sendMessage(obtainMessage(MSG_CALL_START, id, Integer.MIN_VALUE ,null));	
	}
	
	/**
	 * The call doesn't start.
	 * 
	 * @param id Id of the call.
	 */
	public void callNotStart(int id){
		this.sendMessage(obtainMessage(MSG_CALL_NOT_STARTED, id, Integer.MIN_VALUE ,null));	
	}
	
	/**
	 * The call was successful.
	 * 
	 * @param id Id of the call.
	 */
	public void callSuccess(int id, String result){
		sendMessage(obtainMessage(MSG_CALL_SUCCESS, id, 200, result));
	}
	
	/**
	 * The call was interrupted by an exception. 
	 * 
	 * @param id Id of the call.
	 * @param errorMsg	Exception thrown.
	 */
	public void callError(int id, int errorCode, String errorMsg){
		sendMessage(obtainMessage(MSG_CALL_ERROR, id, errorCode, errorMsg));
	}
	
	/**
	 * 
	 * The was interrupted.
	 * 
	 * @param id
	 */
	public void callInterrupted(int id){
		this.sendMessage(obtainMessage(MSG_CALL_INTERRUPTED, id, Integer.MIN_VALUE ,null));
	}
	
	/**
	 * 
	 * The call is waiting for the network connection.
	 * 
	 * @param id
	 */
	public void callWaiting(int id){
		this.sendMessage(obtainMessage(MSG_CALL_WAITING, id, Integer.MIN_VALUE ,null));
	}
	
	/**
	 * 
	 * Trigger the start of the waiting calls.
	 * 
	 * @param id
	 */
	public void startWaitingCalls(){
		this.sendMessage(obtainMessage(MSG_START_WAITING_CALLS));
	}
	
	
}
	
