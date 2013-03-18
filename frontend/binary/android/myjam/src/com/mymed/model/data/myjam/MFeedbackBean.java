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
package com.mymed.model.data.myjam;

import java.io.Serializable;

import com.mymed.model.data.AbstractMBean;

/**
 * Contains the feedback for the reports.
 * @author iacopo
 *
 */
public class MFeedbackBean extends AbstractMBean implements Serializable{
	/**
	 * Identifier used for serialization purposes.
	 */
	private static final long serialVersionUID = 3889004040399952200L;

	private String userId = null;
	private Integer value = null;
	//TODO Not used at the moment.
	private Integer timestamp = null;

	public void setValue(Integer value) {
		this.value = value;
	}
	public Integer getValue() {
		return value;
	}
	public void setUserId(String userId) {
		this.userId = userId;
	}
	public String getUserId() {
		return userId;
	}
	public Integer getTimestamp() {
		return timestamp;
	}
	public void setTimestamp(Integer timestamp) {
		this.timestamp = timestamp;
	}
	@Override
	public int hashCode() {
		int result = 17;
		result = this.userId.hashCode() + (result* PRIME);
		result = this.value.hashCode() + (result* PRIME);
		result = this.timestamp.hashCode() + (result* PRIME);
		return 0;
	}
	@Override
	public boolean equals(Object obj) {
		if (obj instanceof MFeedbackBean) {
			MFeedbackBean comparable = (MFeedbackBean) obj;
			boolean equals = true;
			equals &= comparable.getUserId() == null?this.userId==null:comparable.getUserId().equals(this.userId);
			equals &= comparable.getValue() == null?this.value==null:comparable.getValue().equals(this.value);
			equals &= comparable.getTimestamp() == null?this.timestamp==null:comparable.getTimestamp().equals(this.timestamp);
			return equals;
		} else {
			return false;
		}
	}

}
