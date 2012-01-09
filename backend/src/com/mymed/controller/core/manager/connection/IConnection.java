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
package com.mymed.controller.core.manager.connection;

import com.mymed.controller.core.exception.InternalBackEndException;

/**
 * Interface to have a common set of methods in case we want to implement a
 * different class of connections for mymed.
 * 
 * @author Milo Casagrande
 * 
 */
public interface IConnection {

	/**
	 * Open the connection to the database
	 * 
	 * @throws InternalBackEndException
	 *             when there is a problem opening the connection
	 */
	void open() throws InternalBackEndException;

	/**
	 * Close the connection to the database
	 */
	void close();

	/**
	 * Check if the connection is open or not
	 * 
	 * @return true if the connection is open, false otherwise
	 */
	boolean isOpen();

	/**
	 * Get the address in use with the connection
	 * 
	 * @return the address associated with the connection
	 */
	String getAddress();

	/**
	 * Get the port in use with the connection
	 * 
	 * @return the port associated with the connection
	 */
	int getPort();
}
