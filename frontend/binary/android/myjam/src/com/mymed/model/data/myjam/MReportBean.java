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
import java.lang.reflect.Method;
import java.util.HashMap;
import java.util.Map;

import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.model.data.AbstractMBean;
import com.mymed.utils.ClassType;


/**
 * Contains the details of the report
 * @author iacopo
 *
 */
public class MReportBean extends AbstractMBean implements Serializable{
	/**
	 * Identifier used for serialization purposes.
	 */
	private static final long serialVersionUID = -7725479086699369838L;

	
	private String id;
	private String userName;
	private String userId;
	private String reportType;
	private String transitType;
	private String trafficFlowType;
	private String comment;
	private Long locationId;
	private Long timestamp;
	
	public MReportBean(){}
	
	/**Getter and Setter*/
	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}
	
	public void setUserName(String userName) {
		this.userName = userName;
	}

	public String getUserName() {
		return userName;
	}
	
	public void setUserId(String userId) {
		this.userId = userId;
	}

	public String getUserId() {
		return userId;
	}
	
	public void setReportType(String reportType) {
		this.reportType = reportType;
	}

	public String getReportType() {
		return reportType;
	}

	public void setTransitType(String transitType) {
		this.transitType = transitType;
	}

	public String getTransitType() {
		return transitType;
	}

	public void setTrafficFlowType(String trafficFlowType) {
		this.trafficFlowType = trafficFlowType;
	}

	public String getTrafficFlowType() {
		return trafficFlowType;
	}

	public void setComment(String comment) {
		this.comment = comment;
	}

	public String getComment() {
		return comment;
	}
	
	public void setLocationId(long locationId) {
		this.locationId = locationId;
	}

	public Long getLocationId() {
		return locationId;
	}
	
	public void setTimestamp(long timestamp) {
		this.timestamp = timestamp;
	}

	public Long getTimestamp() {
		return timestamp;
	}	
	
	@Override
	public Map<String, byte[]> getAttributeToMap() throws InternalClientException {
		final Map<String, byte[]> args = new HashMap<String, byte[]>();
		byte[] returnValueByteArray = null;
		
		for (final Method method: this.getClass().getDeclaredMethods()){
			String methodName = method.getName();
			ClassType returnType;
			if (methodName.startsWith("get") && methodName!=("getAttributeToMap")){
				try{
					Object returnValue= method.invoke(this,(Object[]) null);
					if (returnValue != null){
						returnType=ClassType.inferTpye(method.getReturnType());
						returnValueByteArray = ClassType.objectToByteArray(returnType,returnValue);
						String attName = methodName.replaceFirst("get", "");
						attName = attName.substring(0,1).toLowerCase()+attName.substring(1);
						args.put(attName, returnValueByteArray);
					}
				}catch(Exception e){
					throw new InternalClientException("getAttribueToMap failed!: Introspection error");
				}
			}
		}
		return args;
	}

	@Override
	public int hashCode() {
		int result = 17;
		result = this.id.hashCode() + (result* PRIME);
		result = this.userId.hashCode() + (result* PRIME);
		result = this.userName.hashCode() + (result* PRIME);
		result = this.transitType.hashCode() + (result* PRIME);
		result = this.trafficFlowType.hashCode() + (result* PRIME);
		result = this.comment.hashCode() + (result* PRIME);
		result = this.locationId.hashCode() + (result* PRIME);
		result = this.timestamp.hashCode() + (result* PRIME);
		return result;
	}

	@Override
	public boolean equals(Object obj) {
		if (obj instanceof MReportBean) {
			MReportBean comparable = (MReportBean) obj;
			boolean equals = true;
			equals &= comparable.getId() == null?this.id==null:comparable.getId().equals(this.id);
			equals &= comparable.getUserId() == null?this.userId==null:comparable.getId().equals(this.userId);
			equals &= comparable.getUserName() == null?this.userName==null:comparable.getUserName().equals(this.userName);
			equals &= comparable.getTransitType() == null?this.transitType==null:comparable.getTransitType().equals(this.transitType);
			equals &= comparable.getTrafficFlowType() == null?this.trafficFlowType==null:comparable.getTrafficFlowType().equals(this.trafficFlowType);
			equals &= comparable.getComment() == null?this.comment==null:comparable.getComment().equals(this.comment);
			equals &= comparable.getLocationId() == null?this.locationId==null:comparable.getLocationId().equals(this.locationId);
			equals &= comparable.getTimestamp() == null?this.timestamp==null:comparable.getTimestamp().equals(this.timestamp);
			return equals;
		} else {
			return false;
		}
	}
}
