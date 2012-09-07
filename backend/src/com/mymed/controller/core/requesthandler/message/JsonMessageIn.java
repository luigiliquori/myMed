/*
 * Copyright 2012 INRIA 
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
package com.mymed.controller.core.requesthandler.message;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.mymed.model.data.application.DataBean;

/**
 * 
 */
public class JsonMessageIn {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private String code;
	private String accessToken;
	private String application;
	private String id;
	private String field;
	private String user;
	private String mailTemplate;
	private String lengthMax;
	private Map<String, String> data;
	private Map<String, String> metadata;
	private List<DataBean> predicates;
	
	public void init(){
		if (data == null)
			data = new HashMap<String, String>();
		if (metadata == null)
			metadata = new HashMap<String, String>();
		if (predicates == null)
			predicates = new ArrayList<DataBean>();
	}

	public String getCode() {
		return code;
	}

	public String getAccessToken() {
		return accessToken;
	}

	public String getApplication() {
		return application;
	}

	public String getId() {
		return id;
	}

	public String getField() {
		return field;
	}

	public String getUser() {
		return user;
	}

	public String getMailTemplate() {
		return mailTemplate;
	}

	public String getLengthMax() {
		return lengthMax;
	}

	public Map<String, String> getData() {
		return data;
	}

	public Map<String, String> getMetadata() {
		return metadata;
	}

	public List<DataBean> getPredicates() {
		return predicates;
	}

}
