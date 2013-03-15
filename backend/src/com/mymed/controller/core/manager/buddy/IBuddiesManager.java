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
package com.mymed.controller.core.manager.buddy;

import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.InternalBackEndException;

public interface IBuddiesManager {

	/**
	 * read the list
	 * 
	 * @param mymedID
	 * @return
	 * @throws InternalBackEndException
	 */
	List<Map<String, String>> read(String mymedID) throws InternalBackEndException;

	/**
	 * add new buddy
	 * 
	 * @param mymedID
	 * @param buddyID
	 * @throws InternalBackEndException
	 */
	void update(String mymedID, String buddyID) throws InternalBackEndException;

	/**
	 * remove a buddy from the buddyList
	 * 
	 * @param mymedID
	 * @param buddyID
	 * @throws InternalBackEndException
	 */
	void delete(String mymedID, String buddyID) throws InternalBackEndException;
}
