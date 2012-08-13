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
package com.mymed.controller.core.manager.pubsub.v2;

import java.io.UnsupportedEncodingException;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.user.MUserBean;

public interface IPubSubManager {

	/**
	 * create indexes
	 */

	void create(
			List<String> predicates,
			String id,
			Map<String, String> metadata)
					throws InternalBackEndException, IOBackEndException;

	/**
	 * 
	 * create Data
	 */

	void create(String application, String subPredicate, Map<String, String> dataList)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * subscribe v2
	 * 
	 */
	void create(String application, String predicate, String subscriber, String index, String mailTemplate)
			throws InternalBackEndException, IOBackEndException;

	

	/**
	 * reads all Data (return val modified from v1)
	 */
	Map<String, String> read(String application, String predicate)
			throws InternalBackEndException, IOBackEndException;
	
	/**
	 * reads @name field in Data
	 */
	String read( String application, String predicate, String name )
			throws InternalBackEndException, IOBackEndException;

	/**
	 * reads results
	 * the extended read used in v2, for range queries
	 * reads over rows in predicate, and in the column slice [start-finish]
	 */
	Map<String, Map<String, String>> read(
			List<String> predicate,
			String start,
			String finish) 
					throws InternalBackEndException, IOBackEndException, UnsupportedEncodingException;
	
	
	
	
	
	/**
	 * Get the Subscriptions Entry related to application + user
	 */

	Map<String, String> read(String user) throws InternalBackEndException,
			IOBackEndException;

	
	/**
	 * deletes all Data row
	 */
	void delete(String id)
			throws InternalBackEndException, IOBackEndException;
	
	/**
	 * deletes just a Data Field
	 */
	void delete(String id, String field)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * Delete an existing predicate
	 * 
	 * @param application
	 *            the application responsible for this predicate
	 * @param predicate
	 *            The predicate to delete
	 */
	void delete(String application, String predicate, String subPredicate, String publisherID)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * Delete an existing predicate in Subscribers CF, you seem to like multiple
	 * functions with same names and different arguments so here's a new one
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

	void sendEmailsToSubscribers(String application, String predicate,
			Map<String, String> details,
			MUserBean publisher);

}
