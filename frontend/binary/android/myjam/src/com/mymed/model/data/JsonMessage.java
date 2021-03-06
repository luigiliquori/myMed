/*
 * Copyright 2012 INRIA Licensed under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law
 * or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 */
package com.mymed.model.data;

import java.util.HashMap;
import java.util.Map;

import com.google.mygson.Gson;

/**
 * Represent the message/notification for the frontend
 * @author lvanni
 *
 */
public class JsonMessage {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private int status;
	private String handler;
	private String method;
	private String description;
	private Map<String, String> data;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default Constructor
	 * @param status 
	 * 		server status: 200|404|500|..
	 * @param handler
	 * 		The ClassName of the handler responsible for the message
	 */
	public JsonMessage(int status, String handler) {
		this(status, handler, "unknown", new HashMap<String, String>());
	}
	
	/**
	 * 
	 * @param status
	 * 		The server status: (see http server code 200|404|500|...)
	 * @param handler
	 * 		The ClassName of the handler responsible for the message
	 * @param method
	 * 		The method ID: CREATE|READ|UPDATE|DELETE
	 * @param data
	 * 		The content of the response
	 */
	public JsonMessage(int status, String handler, String method, Map<String, String> data) {
		this.status = status;
		this.handler = handler;
		this.method = method;
		this.data = data;
	}
	
	
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
	@Override
	public String toString() {
		// TODO Auto-generated method stub
		return new Gson().toJson(this);
	}

	/* --------------------------------------------------------- */
	/* GETTER&SETTER */
	/* --------------------------------------------------------- */
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
