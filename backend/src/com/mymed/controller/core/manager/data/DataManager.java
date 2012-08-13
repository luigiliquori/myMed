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
package com.mymed.controller.core.manager.data;

import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.user.MUserBean;

/**
 * The pub/sub mechanism manager.
 * 
 */
public class DataManager extends AbstractManager implements IDataManager {

	/**
	 * The application controller super column.
	 */
	protected static final String SC_APPLICATION_CONTROLLER = COLUMNS
			.get("column.sc.application.controller");

	/**
	 * The data table.
	 */
	protected static final String CF_DATA = COLUMNS.get("column.cf.data");

	/**
	 * Default constructor.
	 * 
	 * @throws InternalBackEndException
	 */
	public DataManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public DataManager(final IStorageManager storageManager)
			throws InternalBackEndException {
		super(storageManager);
	}

	/**
	 * Publish mechanism.
	 * 
	 * @see IDataManager#create(String, String, MUserBean)
	 */

	/* v2 create indexes */
	public final void create(
			final List<String> predicates,
			final String id,
			final Map<String, String> metadata)
					throws InternalBackEndException, IOBackEndException {

		storageManager.insertSuperSliceListStr(SC_APPLICATION_CONTROLLER,
				predicates, id, metadata);

	}

	/* v2 create data */
	public final void create(final String id, final Map<String, String> dataList)
			throws InternalBackEndException, IOBackEndException {

		/*
		 * stores data
		 * 
		 * in CF Data
		 */
		storageManager.insertSliceStr(CF_DATA, id, dataList);

	}

	/** read details */
	@Override
	public Map<String, String> read(final String id)
			throws InternalBackEndException, IOBackEndException {

		final Map<String, String> map = storageManager
				.selectAllStr(CF_DATA, id);

		return map;
	}

	/** read detail 1-item */
	@Override
	public String read(final String id, final String key)
			throws InternalBackEndException, IOBackEndException {

		return storageManager.selectColumnStr(CF_DATA, id, key);
	}

	/** read results */
	@Override
	public final Map<String, Map<String, String>> read(
			final List<String> predicate,
			final String start,
			final String finish) 
					throws InternalBackEndException,
			IOBackEndException {

		final Map<String, Map<String, String>> resMap = new LinkedHashMap<String, Map<String, String>>();
		resMap.putAll(storageManager.multiSelectList(SC_APPLICATION_CONTROLLER,
				predicate, start, finish));
		return resMap;
	}

	/* v2 deletes */
	public final void delete(final String id) throws InternalBackEndException,
			IOBackEndException {
		storageManager.removeAll(CF_DATA, id);
	}

	/* v2 deletes */
	public final void delete(
			final String id,
			final String key)
					throws InternalBackEndException, IOBackEndException {
		storageManager.removeColumn(CF_DATA, id, key);
	}

	@Override
	public final void delete(
			final String application,
			final String predicate,
			final String id) 
					throws InternalBackEndException,
			IOBackEndException {
		storageManager.removeSuperColumn(SC_APPLICATION_CONTROLLER, application
				+ predicate, id);
	}

}
