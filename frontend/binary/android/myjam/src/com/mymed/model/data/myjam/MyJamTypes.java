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
/**
 * Contains the enum classes.
 * @author iacopo
 *
 */
public abstract class MyJamTypes{
	
	/** TrafficFlowType*/
	public enum TrafficFlowType { 
		NORMAL, 	
		SLOW, 
		VERY_SLOW,
		BLOCKED;

		TrafficFlowType(){
		}
		/**
		 * Returns a string representing the enum,
		 * the enum name words must be separated by
		 * the char "_".
		 */
		@Override
		public String toString(){
			String tmp = this.name();
			tmp=tmp.replace("_", " ");
			return tmp.toLowerCase();
		}
	}
	
	/** ReportType*/ 
	public enum ReportType { 
		JAM (1), 	// Those values corresponds to the duration of the reports in the system.
		CAR_CRASH (2), 
		WORK_IN_PROGRESS (12),
		FIXED_SPEED_CAM (0),//In Cassandra fix TTL to 0 is equivalent to don't fix it.
		MOBILE_SPEED_CAM (8);

		public final int permTime;
		private static final int secPerHour = 60 * 60;

		ReportType(int permTime){
			this.permTime = permTime*secPerHour;
		}
		/*
		 * Returns a string representing the enum,
		 * the enum name words must be separated by
		 * the char "_".
		 */
		@Override
		public String toString(){
			String tmp = this.name();
			tmp=tmp.replace("_", " ");
			return tmp.charAt(0)+tmp.substring(1).toLowerCase();
		}
	}
	
}
