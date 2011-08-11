package com.mymed.myjam.type;

import java.lang.reflect.Method;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.AbstractMBean;
import com.mymed.utils.ClassType;


/**
 * Contains the details of the report
 * @author iacopo
 *
 */
public class MReportBean extends AbstractMBean{
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
	public Map<String, byte[]> getAttributeToMap() throws InternalBackEndException {
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
					throw new InternalBackEndException("getAttribueToMap failed!: Introspection error");
				}
			}
		}
		return args;
	}
}
