package com.mymed.android.myjam.type;

import com.mymed.android.myjam.type.MyJamTypes.ReportType;
import com.mymed.android.myjam.type.MyJamTypes.TrafficFlowType;
import com.mymed.android.myjam.type.MyJamTypes.TransitType;


/**
 * 
 * @author iacopo
 *
 */
public class MReportBean implements IMyJamType{
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
			else if(this.reportType!=null && (transit = TransitType.valueOf(this.transitType))==null)
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
	
	/**
	 * This method is useful for the insertion in Cassandra.
	 * @return A map with the values of this object.
	 */
//	//TODO Substitute with getAttributeToMap AbstractMBean method.
//	public Map<String,String> getMapAttributes(){
//		Map<String,String> attMap = new HashMap<String,String>();
//		if (this.userName!=null)
//			attMap.put("user", this.userName);
//		if (this.reportType!=null)
//			attMap.put("type", this.reportType.name());
//		if (this.transitType!=null)
//			attMap.put("transit", this.transitType.name());
//		if (this.trafficFlowType!=null)
//			attMap.put("traffic_flow", this.trafficFlowType.name());
//		if (this.comment!=null)
//			attMap.put("comment", this.comment);
//		return attMap;
//	}
	
//	@Override
//	public Map<String, byte[]> getAttributeToMap() throws InternalBackEndException {
//		final Map<String, byte[]> args = new HashMap<String, byte[]>();
//		byte[] returnValueByteArray = null;
//		
//		for (final Method method: this.getClass().getMethods()){
//			String methodName = method.getName();
//			ClassType returnType;
//			if (methodName.startsWith("get")){
//				try{
//					Object returnValue= method.invoke(this,(Object[]) null);
//					
//					if (returnValue!=null){
//						if (method.getReturnType().isEnum())
//							returnValueByteArray = MConverter.stringToByteBuffer(returnValue.toString()).array();
//						else if((returnType=ClassType.inferTpye(method.getReturnType())) != null)
//							returnValueByteArray = ClassType.classTypeToByteArray(returnType,returnValue);
//						else
//							break;
//					}
//						args.put(methodName.replaceFirst("get", ""), returnValueByteArray);
//				}catch(Exception e){
//					throw new InternalBackEndException("getAttribueToMap failed!: Introspection error");
//				}
//			}
//		}
//		return args;
//	}
}
