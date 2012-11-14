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
package com.mymed.controller.core.manager.profile;

import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage the profile of an user
 * 
 * @author lvanni
 * 
 */
public interface IProfileManager {

	/**
	 * Setup a new user profile into the database
	 * 
	 * @param user
	 *            the user to insert into the database
	 * @return the profile of the user
	 */
	MUserBean create(MUserBean user) throws InternalBackEndException, IOBackEndException;

	/**
	 * @param id
	 *            the id of the user
	 * @return the User corresponding to the id
	 */
	MUserBean read(String id) throws InternalBackEndException, IOBackEndException;
	
	Map<String, String> readSimple(String id) throws InternalBackEndException, IOBackEndException;

	/**
	 * Update the profile of an user into the database
	 * 
	 * @param user
	 *            The profile updated to store
	 * @return
	 */
	MUserBean update(MUserBean user) throws InternalBackEndException, IOBackEndException;

	void update(String id,  Map<String, String> map) throws InternalBackEndException, IOBackEndException;
	void update(String id,  Map<String, String> map, boolean temp) throws InternalBackEndException, IOBackEndException;
	
	void update(String id, String key, String value) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * Delete an existing user
	 * 
	 * @param user
	 *            The user to delete
	 */
	void delete(String id) throws InternalBackEndException, IOBackEndException;

	void deleteSimple(String id) throws InternalBackEndException,
			IOBackEndException;

	
}
