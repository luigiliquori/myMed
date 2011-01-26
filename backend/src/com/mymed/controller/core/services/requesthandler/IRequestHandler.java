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

	/**
	 * http://mymed2.sophia.inria.fr:8080/mymed_backend/RequestHandler?act=10&user={"id":"facebook1421481661","name":"Laurent Vanni","gender":"male","locale":"fr_FR","updated_time":"2010-08-13T10:36:53+0000","profile":"http:\/\/www.facebook.com\/profile.php?id=1421481661","profile_picture":"http:\/\/graph.facebook.com\/1421481661\/picture?type=large","social_network":"facebook"} 
	 */
	
}
