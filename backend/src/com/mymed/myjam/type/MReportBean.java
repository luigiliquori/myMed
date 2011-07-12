package com.mymed.myjam.type;

import java.lang.reflect.Method;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.AbstractMBean;
import com.mymed.myjam.type.MyJamTypes.ReportType;
import com.mymed.myjam.type.MyJamTypes.TrafficFlowType;
import com.mymed.myjam.type.MyJamTypes.TransitType;
import com.mymed.utils.ClassType;


/**
 * 
 * @author iacopo
 *
 */
public class MReportBean extends AbstractMBean implements IMyJamType{
	private String userName;
	private String reportType;
	private String transitType;
	private String trafficFlowType;
	private String comment;
	
	public MReportBean(){}
	
	/**Getter and Setter*/
	public void setUserName(String userName) {
		this.userName = userName;
	}

	public String getUserName() {
		return userName;
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
	
	/** Validator */
	@Override
	public void validate() throws WrongFormatException {
		ReportType type = null;
		TrafficFlowType trafficFlow = null;
		TransitType transit = null;
		try{
			
			if ((type = ReportType.valueOf(this.reportType))==null)
				throw new WrongFormatException(" Wrong report type.");
			else if(this.trafficFlowType!=null && (trafficFlow = TrafficFlowType.valueOf(this.trafficFlowType))==null)
				throw new WrongFormatException(" Wrong traffic flow type.");
			else if(this.transitType!=null && (transit = TransitType.valueOf(this.transitType))==null)
				throw new WrongFormatException(" Wrong transit type.");
			
			
			
			switch (type){
				case CAR_CRASH:
				case WORK_IN_PROGRESS:
					if (trafficFlow == null || 
							transit == null)
						throw new WrongFormatException(" Wrong attributes. ");
					if (transit == TransitType.COMPROMIZED && trafficFlow != TrafficFlowType.BLOCKED)
						throw new WrongFormatException(" Wrong attributes. ");
					break;
				case JAM:
					if (trafficFlow == null ||
							transit != null)
						throw new WrongFormatException(" Wrong attributes. ");
					break;
				case MOBILE_SPEED_CAM:
				case FIX_SPEED_CAM:
					if (trafficFlow != null ||
							transit != null)
						throw new WrongFormatException(" Wrong attributes. ");
					break;
				default:
					throw new WrongFormatException("Wrong report type");
			}
			
		}catch(NullPointerException e){
			throw new WrongFormatException("Missing attribute");
		}		
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
						returnValueByteArray = ClassType.classTypeToByteArray(returnType,returnValue);
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
