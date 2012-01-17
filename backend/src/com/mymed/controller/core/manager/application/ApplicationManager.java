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
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage an user profile
 * 
 * @author lvanni
 * 
 */
public class ApplicationManager extends AbstractManager implements IApplicationManager {

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ApplicationManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public ApplicationManager(final IStorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	@Override
	public void create(final MUserBean user) throws InternalBackEndException, IOBackEndException {
		// TODO Auto-generated method stub
	}

	@Override
	public MUserBean read(final String id) throws InternalBackEndException, IOBackEndException {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public void update(final MUserBean user) throws InternalBackEndException, IOBackEndException {
		// TODO Auto-generated method stub
	}

	@Override
	public void delete(final String id) throws InternalBackEndException {
		// TODO Auto-generated method stub
	}
}
