package com.mymed.model.data.myjam;
/**
 * Contains the enum classes.
 * @author iacopo
 *
 */
public abstract class MyJamTypes{
	
	/** TransitType*/ 
	public enum TransitType { 
		NORMAL, 	
		PARTIALLY_COMROMISED, 
		COMPROMISED;

		TransitType(){
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
			return tmp.toLowerCase();
		}
	}

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
		JAM (1), //1 	
		CAR_CRASH (2), //2 
		WORK_IN_PROGRESS (12), //12
		FIXED_SPEED_CAM (4380),//4380 //In Cassandra fix TTL to 0 is equivalent to don't fix it.
		MOBILE_SPEED_CAM (8);//8

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
			return tmp.toLowerCase();
		}
	}
	
}
