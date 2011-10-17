package com.mymed.controller.core.requesthandler.message;

import java.util.HashMap;
import java.util.Map;

import com.google.gson.Gson;
import com.mymed.model.data.AbstractMBean;

/**
 * Represent the message/notification for the frontend
 * @author lvanni
 *
 */
public class JsonMessage {

	/*
	 * message {
	 * 		"status"		: 200|404|500|... ,
	 * 		"handler"	: RequestHandlerID ,
	 * 		"method"	: CREATE|READ|UPDATE|DELETE ,
	 * 		"data"		: [
	 * 				"message"	: "String" ,
	 * 				"data_id_1" : "String" ,
	 * 				"data_id_2" : "String"
	 * 		]
	 * }
	 */
	
	private int status;
	private String handler;
	private String method;
	private String description;
	private Map<String, String> data;

	public JsonMessage(int status, String handler) {
		this(status, handler, "unknown", new HashMap<String, String>());
	}
	
	public JsonMessage(int status, String handler, String method,Map<String, String> data) {
		this.status = status;
		this.handler = handler;
		this.method = method;
		this.data = data;
	}
	
	@Override
	public String toString() {
		// TODO Auto-generated method stub
		return new Gson().toJson(this);
	}

	public int getStatus() {
		return status;
	}

	public void setStatus(int status) {
		this.status = status;
	}

	public String getHandler() {
		return handler;
	}

	public void setHandler(String handler) {
		this.handler = handler;
	}

	public String getMethod() {
		return method;
	}

	public void setMethod(String method) {
		this.method = method;
	}

	public Map<String, String> getData() {
		return data;
	}

	public void addData(String key, String value) {
		this.data.put(key, value);
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}
}
