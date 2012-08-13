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
package com.mymed.controller.core.manager.authentication;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;

public interface IAuthenticationManager {

	/**
	 * create myMed user profile
	 * 
	 * @param user
	 * @param authentication
	 * @return
	 * @throws InternalBackEndException
	 */
	MUserBean create(MUserBean user, MAuthenticationBean authentication) throws InternalBackEndException,
	        IOBackEndException;


	/**
	 * create simple authentication
	 * 
	 * @param key
	 * @param authentication
	 */
	void create(String key, MAuthenticationBean authentication)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * authentication
	 * 
	 * @param login
	 * @param password
	 * @return
	 * @throws InternalBackEndException
	 */
	MUserBean read(String login, String password) throws InternalBackEndException, IOBackEndException;
	
	
	/**
	 * authentication simple read
	 * 
	 * @param key
	 * @return MAuthenticationBean
	 */
	MAuthenticationBean read(String key) throws InternalBackEndException, IOBackEndException;

	/**
	 * update myMed user profile : Authentication Table (password)
	 * 
	 * @param authentication
	 * @throws InternalBackEndException
	 */
	void update(String id, MAuthenticationBean authentication) throws InternalBackEndException, IOBackEndException;

	/**
	 * simple delete
	 * @param key
	 */
	void delete(String key) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * Sends mail before validating the account
	 * @param application
	 * @param recipient
	 * @param accessToken
	 */
	void sendRegistrationEmail( String application, MUserBean recipient,  String accessToken );
	
}
