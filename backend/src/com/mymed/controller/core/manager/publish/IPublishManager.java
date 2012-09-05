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
package com.mymed.controller.core.manager.publish;

import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;

public interface IPublishManager {

	/**
	 * create indexes
	 */

	void create(
			List<String> predicates,
			String id,
			Map<String, String> metadatas)
					throws InternalBackEndException, IOBackEndException;

	/**
	 * 
	 * create Data
	 */

	void create(String id, Map<String, String> datas)
			throws InternalBackEndException, IOBackEndException;

	

	/**
	 * reads details of a data (return val modified from v1)
	 */
	Map<String, String> read(String id)
			throws InternalBackEndException, IOBackEndException;
	
	/**
	 * reads @param key column in Data
	 */
	String read(String id, String key )
			throws InternalBackEndException, IOBackEndException;
	
	/**
	 * reads details of a list of id's, Beware don't confuse with read(
			List<String> predicate,
			String start,
			String finish) 
		just differs with arguments
	 */
	Map<String, Map<String, String>> read(List<String> ids)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * reads results for the given predicate rows
	 */
	Map<String, Map<String, String>> read(
			List<String> predicate,
			String start,
			String finish) 
					throws InternalBackEndException, IOBackEndException;
	
	
	
	
	/**
	 * deletes all Data @param id row
	 */
	void delete(String id)
			throws InternalBackEndException, IOBackEndException;
	
	/**
	 * deletes just the @param key column in Data @param id row
	 */
	void delete(String id, String key)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * Delete an existing predicate
	 * 
	 * @param application
	 *            the application responsible for this predicate
	 * @param predicate
	 *            The predicate to delete
	 */
	void delete(String application, String predicate, String id)
			throws InternalBackEndException, IOBackEndException;


}
