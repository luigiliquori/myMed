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
import com.mymed.model.data.myjam.MyJamTypes.ReportType;
import com.mymed.model.data.myjam.MyJamTypes.TrafficFlowType;

/**
 * Static methods to validate the myJam MBeans.
 * @author iacopo
 *
 */
public class MyJamTypeValidator {
	
	public static void validate(MFeedbackBean mFeedBackBean) throws WrongFormatException {
		if (mFeedBackBean.getValue() == null)
			throw new WrongFormatException(" Value not set. ");
		if (mFeedBackBean.getValue() != 0 && mFeedBackBean.getValue() != 1)
			throw new WrongFormatException(" Value can be only 0 or 1. ");
	}
	
	public void validate(MSearchBean mShorteportBean) throws WrongFormatException {
		if (ReportType.valueOf(mShorteportBean.getValue())==null)
			throw new WrongFormatException(" Wrong report type. ");
		if (MyJamId.parseString(mShorteportBean.getId()).getType()!=MyJamId.REPORT_ID)
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
