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
package com.mymed.controller.core.manager.subscription;

import static com.mymed.utils.MiscUtils.encode;

import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;

/**
 * The pub/sub mechanism manager.
 * 
 */
public class SubscriptionManager extends AbstractManager implements
		ISubscriptionManager {

	/**
	 * The subscribees (users subscribed to a predicate) column family.
	 */
	protected static final String CF_SUBSCRIBEES = COLUMNS
			.get("column.cf.subscribees");

	/**
	 * The subscribers (predicates subscribed by a user) column family.
	 */
	protected static final String CF_SUBSCRIBERS = COLUMNS
			.get("column.cf.subscribers");

	/**
	 * Default constructor.
	 * 
	 * @throws InternalBackEndException
	 */
	public SubscriptionManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public SubscriptionManager(final IStorageManager storageManager)
			throws InternalBackEndException {
		super(storageManager);
	}

	@Override
	public void create(
			final String application,
			final String predicate,
			final String subscriber,
			final String desc,
			final String mailTemplate)
					throws InternalBackEndException, IOBackEndException {
		// STORE A NEW ENTRY IN THE UserList (SubscriberList)
		storageManager.insertColumn(CF_SUBSCRIBEES, application + predicate,
				subscriber, encode(mailTemplate));

		storageManager.insertColumn(CF_SUBSCRIBERS, application + subscriber,
				(predicate.length() == 0) ? "ALL" : predicate, encode(desc));

	}

	/** reads Subscriptions for a user */
	@Override
	public Map<String, String> read(String appUser)
			throws InternalBackEndException, IOBackEndException {

		final Map<String, String> map = storageManager.selectAllStr(CF_SUBSCRIBERS, appUser);
		LOGGER.info("^^^" + appUser + " is subscribed to {}", map);
		return map;
	}
	
	@Override
	public String read(String appUser, String key)
			throws InternalBackEndException, IOBackEndException {

		return storageManager.selectColumnStr(CF_SUBSCRIBERS, appUser, key);
	}

	@Override
	public final void delete(
			final String application,
			final String predicate,
			final String user) 
					throws InternalBackEndException,
			IOBackEndException {
		// Remove subscriber member from subsribers list
		storageManager.removeColumn(CF_SUBSCRIBERS, application + user,
				(predicate.length() == 0) ? "ALL" : predicate);
		// Remove subscriber member from predicates subscribed list
		storageManager.removeColumn(CF_SUBSCRIBEES, application + predicate,
				user);
	}

}
