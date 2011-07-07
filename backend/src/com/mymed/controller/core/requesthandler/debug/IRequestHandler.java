package com.mymed.controller.core.requesthandler.debug;

/**
 * Describe the header of all the request coming from the frontend
 * @author lvanni
 */
@Deprecated
public interface IRequestHandler {
	
	/** Cassandra debug operations*/
	static int CONNECT = 0;
	static int SETKEYSPACE = 1;
	static int SETCOLUMNFAMILY = 2;
	static int SETKEYUSERID = 3;
	static int INSERTKEY = 4;
	static int GETKEY = 5;
	
	/** set user profile */
	static int SETPROFILE = 10;
	/** get user profile*/
	static int GETPROFILE = 11;
	
	// SDK APIs Tests
	static int REGISTER = 20;
	static int GETAPPLIST = 21;
	static int GETAPPLIACTION = 22;
	static int PUBLISH = 23;
}
