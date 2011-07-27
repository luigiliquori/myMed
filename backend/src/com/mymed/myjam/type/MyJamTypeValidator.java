package com.mymed.myjam.type;

import com.mymed.myjam.type.MyJamTypes.ReportType;
import com.mymed.myjam.type.MyJamTypes.TrafficFlowType;
import com.mymed.myjam.type.MyJamTypes.TransitType;

/**
 * Static methods to validate the myJam MBeans.
 * @author iacopo
 *
 */
public class MyJamTypeValidator {
	private final static  int maxGrade = 10;
	
	public static void validate(MFeedBackBean mFeedBackBean) throws WrongFormatException {
		int grade = mFeedBackBean.getGrade();
		if (mFeedBackBean.getGrade()==null)
			throw new WrongFormatException(" Grade not set. ");
		if (grade<0 || grade>maxGrade)
			throw new WrongFormatException(" Grade out of bound. ");
	}
	
	public void validate(MShortReportBean mShorteportBean) throws WrongFormatException {
		if (ReportType.valueOf(mShorteportBean.getReportType())==null)
			throw new WrongFormatException(" Wrong report type. ");
		if (MyJamId.parseString(mShorteportBean.getReportId()).getType()!=MyJamId.REPORT_ID)
			throw new WrongFormatException(" Wrong report id. ");
	}
	
	public static void validate(MReportBean mReportBean) throws WrongFormatException {
		ReportType type = null;
		TrafficFlowType trafficFlow = null;
		TransitType transit = null;
		try{
			
			if ((type = ReportType.valueOf(mReportBean.getReportType()))==null)
				throw new WrongFormatException(" Wrong report type.");
			else if(mReportBean.getTrafficFlowType()!=null && (trafficFlow = TrafficFlowType.valueOf(mReportBean.getTrafficFlowType()))==null)
				throw new WrongFormatException(" Wrong traffic flow type.");
			else if(mReportBean.getTransitType()!=null && (transit = TransitType.valueOf(mReportBean.getTransitType()))==null)
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
		}catch(IllegalArgumentException e){
			throw new WrongFormatException("Wrong attribute");
		}
	}

}
