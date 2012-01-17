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
package com.mymed.controller.core.manager.position;

import java.io.UnsupportedEncodingException;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.user.MPositionBean;
import com.mymed.utils.MLogger;

/**
 * Manage the session of a user
 * 
 * @author lvanni
 * @author Milo Casagrande
 * 
 */
public class PositionManager extends AbstractManager implements IPositionManager {

	private static final String ENCODING = "UTF8";
	
	public PositionManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public PositionManager(final StorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}
	
	/**
	 * Setup a new user profile into the database
	 * 
	 * @param user
	 *            the user to insert into the database
	 * @throws IOBackEndException
	 */
	@Override
	public MPositionBean create(final MPositionBean position) throws InternalBackEndException, IOBackEndException {
		try {
			final Map<String, byte[]> args = position.getAttributeToMap();
			storageManager.insertSlice(CF_POSITION, new String(args.get("userID"), ENCODING), args);

			return position;
		} catch (final UnsupportedEncodingException e) {
			MLogger.getLog().info("Error in string conversion using {} encoding", ENCODING);
			MLogger.getDebugLog().debug("Error in string conversion using {} encoding", ENCODING, e.getCause());

			throw new InternalBackEndException(e.toString());
		}
	}

	/**
	 * @throws IOBackEndException
	 * @see IPositionManager#read(String)
	 */
	@Override
	public MPositionBean read(final String userID) throws InternalBackEndException, IOBackEndException {
		
		MPositionBean position = new MPositionBean();
		
		final Map<byte[], byte[]> args = storageManager.selectAll(CF_POSITION, userID);
		if (args.isEmpty()) {
			MLogger.getLog().info("User with ID '{}' does not exists", userID);
			throw new IOBackEndException("position does not exist!", 404);
		}

		return (MPositionBean) introspection(position, args);
	}

	/**
	 * @throws IOBackEndException
	 * @see IPositionManager#update(MPositionBean)
	 */
	@Override
	public void update(final MPositionBean position) throws InternalBackEndException, IOBackEndException {
		create(position);
	}

}
