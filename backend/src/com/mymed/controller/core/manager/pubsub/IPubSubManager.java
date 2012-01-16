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
package com.mymed.controller.core.manager.pubsub;

import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage the profile of an user
 * 
 * @author lvanni
 * 
 */
public interface IPubSubManager {

	/**
	 * Insert a new Predicate in the dataBase - PUBLISH
	 * 
	 * @param application
	 *            the application responsible for this predicate
	 * @param publisher
	 *            the user who insert the new predicate
	 */
	public void create(String application, String predicate, String subPredicate,
			MUserBean publisher, List<MDataBean> dataList)
			throws InternalBackEndException, IOBackEndException;
	
	/**
	 * Insert a new Predicate in the dataBase - SUBSCRIBE
	 * @param application
	 * @param predicate
	 * @param publisher
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public void create(String application, String predicate, MUserBean publisher)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * @param application
	 *            the application responsible for this predicate
	 * @param predicate
	 *            the predicateID to read
	 * @return the list of the publisher who provide this predicate
	 */
	public List<Map<String, String>> read(String application, String predicate)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * Get the DataList Entry related to aplication+predicate+userID
	 * @param application
	 * @param predicate
	 * @param userID
	 * @return
	 * 		A DataList Entry
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public List<Map<String, String>> read(String application, String predicate,
			String userID) throws InternalBackEndException, IOBackEndException;

	/**
	 * Delete an existing predicate
	 * 
	 * @param application
	 *            the application responsible for this predicate
	 * @param predicate
	 *            The predicate to delete
	 */
	public void delete(String application, String predicate, MUserBean user)
			throws InternalBackEndException, IOBackEndException;
}
