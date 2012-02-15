package com.mymed.model.data.myjam;

import java.io.Serializable;

import android.os.Bundle;
import android.os.ResultReceiver;

import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.exception.IMymedException;
import com.mymed.model.data.AbstractMBean;

/**
 * Bean that stores the status of a call.
 * 
 * @author iacopo
 *
 */
public class MCallBean extends AbstractMBean implements Serializable{
	/**
	 * 
	 */
	private static final long serialVersionUID = -1558994015662764525L;
	
	private Integer callId;
	private Integer callCode;
	private Bundle attributes;
	private Object object;
	private Integer priority;
	private Integer numAttempts;
	private Integer maxNumAttempts;
	private ResultReceiver receiver;
	private String activityId;
	private HttpCall call;
	private String result;
	private IMymedException exception; 
	
	public MCallBean(){
		super();
		this.numAttempts = 0;
		this.priority = HttpCall.LOW_PRIORITY;
	}

	public Integer getCallId() {
		return callId;
	}

	public void setCallId(Integer callId) {
		this.callId = callId;
	}

	public Integer getCallCode() {
		return callCode;
	}

	public void setCallCode(Integer callCode) {
		this.callCode = callCode;
	}
	
	public Bundle getAttributes() {
		return attributes;
	}
	
	public void setAttributes(Bundle attributes) {
		this.attributes = attributes;
	}
	
	public Object getObject() {
		return object;
	}
	
	public void setObject(Object object) {
		this.object = object;
	}

	public Integer getNumAttempts() {
		return numAttempts;
	}

	public void setNumAttempts(Integer numAttempts) {
		this.numAttempts = numAttempts;
	}
	
	public void increaseNumAttempts() {
		numAttempts++;
	}

	public Integer getMaxNumAttempts() {
		return maxNumAttempts;
	}

	public void setMaxNumAttempts(Integer maxNumAttempts) {
		this.maxNumAttempts = maxNumAttempts;
	}

	public ResultReceiver getReceiver() {
		return receiver;
	}

	public Integer getPriority() {
		return priority;
	}
	
	public void setPriority(Integer priority) {
		this.priority = priority;
	}
	
	public void setReceiver(ResultReceiver receiver) {
		this.receiver = receiver;
	}

	public String getActivityId() {
		return activityId;
	}

	public void setActivityId(String activityId) {
		this.activityId = activityId;
	}

	public HttpCall getCall() {
		return call;
	}

	public void setCall(HttpCall call) {
		this.call = call;
	}

	public String getResult() {
		return result;
	}

	public void setResult(String result) {
		this.result = result;
	}

	public IMymedException getException() {
		return exception;
	}

	public void setException(IMymedException exception) {
		this.exception = exception;
	}
	
	/**
	 * Execute the call (if available)
	 */
	public void execute(){
		if (this.call != null){
			this.call.execute(this.priority);
			this.increaseNumAttempts();
		}
	}

}
