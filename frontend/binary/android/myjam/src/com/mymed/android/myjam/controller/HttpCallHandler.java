package com.mymed.android.myjam.controller;

import android.os.Handler;

public class HttpCallHandler extends Handler{
	
	public static final int MSG_CALL_START = 0;
	public static final int MSG_CALL_SUCCESS = 1;
	public static final int MSG_CALL_ERROR = 3;
	
	public void callStart(){
		this.sendMessage(obtainMessage(MSG_CALL_START));	
	}

	public void callSuccess(String result){
		sendMessage(obtainMessage(MSG_CALL_SUCCESS, result));
	}

	public void callError(Exception e){
		sendMessage(obtainMessage(MSG_CALL_ERROR, e));
	}
}
