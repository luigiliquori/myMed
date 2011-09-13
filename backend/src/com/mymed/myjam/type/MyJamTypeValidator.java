package com.mymed.myjam.type;

import com.mymed.myjam.type.MyJamTypes.ReportType;
import com.mymed.myjam.type.MyJamTypes.TrafficFlowType;

/**
 * Static methods to validate the myJam MBeans.
 * @author iacopo
 *
 */
public class MyJamTypeValidator {
	
	public static void validate(MFeedBackBean mFeedBackBean) throws WrongFormatException {
		if (mFeedBackBean.getValue()==null)
			throw new WrongFormatException(" Value not set. ");
		if (mFeedBackBean.getValue()!=0 && mFeedBackBean.getValue()!=1)
			throw new WrongFormatException(" Value can be only 0 or 1. ");
	}
	
	public void validate(MSearchReportBean mShorteportBean) throws WrongFormatException {
		if (ReportType.valueOf(mShorteportBean.getReportType())==null)
			throw new WrongFormatException(" Wrong report type. ");
		if (MyJamId.parseString(mShorteportBean.getReportId()).getType()!=MyJamId.REPORT_ID)
			throw new WrongFormatException(" Wrong report id. ");
	}
	
	public static void validate(MReportBean mReportBean) throws WrongFormatException {
		ReportType type = null;
		TrafficFlowType trafficFlow = null;
		try{
			
			if ((type = ReportType.valueOf(mReportBean.getReportType()))==null)
				throw new WrongFormatException(" Wrong report type.");
			else if(mReportBean.getTrafficFlowType()!=null && (trafficFlow = TrafficFlowType.valueOf(mReportBean.getTrafficFlowType()))==null)
				throw new WrongFormatException(" Wrong traffic flow type.");
			
			
			
			switch (type){
				case CAR_CRASH:
				case WORK_IN_PROGRESS:
					if (trafficFlow == null)
						throw new WrongFormatException(" Wrong attributes. ");
					break;
				case JAM:
					if (trafficFlow == null)
						throw new WrongFormatException(" Wrong attributes. ");
					break;
				case MOBILE_SPEED_CAM:
				case FIXED_SPEED_CAM:
					if (trafficFlow != null)
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
