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
package com.mymed.controller.core.manager.application;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage the profile of an user
 * 
 * @author lvanni
 * 
 */
public interface IApplicationManager {

	void create(MUserBean user) throws InternalBackEndException, IOBackEndException;

	MUserBean read(String id) throws InternalBackEndException, IOBackEndException;

	void update(MUserBean user) throws InternalBackEndException, IOBackEndException;

	void delete(String id) throws InternalBackEndException;
}
