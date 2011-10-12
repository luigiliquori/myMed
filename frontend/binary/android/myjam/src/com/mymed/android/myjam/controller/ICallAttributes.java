package com.mymed.android.myjam.controller;
/**
 * Attributes of {@link MyJamCallService}
 * @author iacopo
 *
 */
public interface ICallAttributes {
	/*
	 *  Authentication attributes
	 */
	public static final String ID = "id";
	public static final String AUTHENTICATION = "authentication";
	public static final String PROFILE = "profile";
	public static final String USER_ID = "userID";
	public static final String IP = "ip";
	public static final String LOGIN = "login";
	public static final String PASSWORD = "password";
	
	/*
	 *  myJam Attributes
	 */
	//General.
	public static final String REPORT_ID = "id";
	public static final String UPDATE_ID = "updateID";	
	public static final String NUM = "num";
	//Search report.
	public static final String LATITUDE = "latitude";
	public static final String LONGITUDE = "longitude";
	public static final String RADIUS = "radius";	
	public static final String SEARCH_ID = "searchID";
}
