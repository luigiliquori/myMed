package com.mymed.controller.core.services.requesthandler;

/**
 * Describe the header of all the request coming from the frontend
 * @author lvanni
 */
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
	
}
