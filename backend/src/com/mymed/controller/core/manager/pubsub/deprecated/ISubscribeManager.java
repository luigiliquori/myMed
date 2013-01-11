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
package com.mymed.controller.core.manager.pubsub.deprecated;

import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;

public interface ISubscribeManager {

	
	/**
	 * subscribe v2
	 * 
	 */
	void create(String application, String predicate, String subscriber, String index, String mailTemplate)
			throws InternalBackEndException, IOBackEndException;

	
	
	/**
	 * Get the Subscriptions Entry related to application + user
	 */

	Map<String, String> read(String appUser) throws InternalBackEndException,
			IOBackEndException;

	String read(String appUser, String key)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * Delete an existing predicate in Subscribers 
	 * 
	 * @param application
	 *            the application responsible for this predicate
	 * @param user
	 *            the user that has an ongoing subscription
	 * @param predicate
	 *            The predicate subscription pattern to delete from the row
	 */
	void delete(String application, String user, String predicate)
			throws InternalBackEndException, IOBackEndException;

}
