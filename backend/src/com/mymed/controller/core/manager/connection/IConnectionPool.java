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

/**
 * Common interface for the implementation of a pool
 * 
 * @author Milo Casagrande
 * 
 */
public interface IConnectionPool {

	/**
	 * Get a connection from the pool
	 * 
	 * @return the first available connection
	 */
	IConnection checkOut();

	/**
	 * Give a connection back to the pool
	 * 
	 * @param connection
	 *            the connection to return to the pool
	 */
	void checkIn(IConnection connection);

	/**
	 * Get the size of the pool, or how many connections are in it
	 * 
	 * @return the actual size of the pool
	 */
	int getSize();

	/**
	 * Get the maximum capacity of the pool
	 * 
	 * @return the maximum capacity of the pool
	 */
	int getCapacity();
}
