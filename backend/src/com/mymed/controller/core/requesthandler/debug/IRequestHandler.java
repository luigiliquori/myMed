/*
 * Copyright 2012 INRIA 
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
