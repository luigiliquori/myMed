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
package com.mymed.controller.core.manager.storage.v2;

import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.TreeMap;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
import org.apache.cassandra.thrift.SuperColumn;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.utils.MConverter;

/**
 * This class represent the DAO pattern: Access to data varies depending on the
 * source of the data. Access to persistent storage, such as to a database,
 * varies greatly depending on the type of storage
 * 
 * Use a Data Access Object (DAO) to abstract and encapsulate all access to the
 * data source. The DAO manages the connection with the data source to obtain
 * and store data.
 * 
 * @author lvanni
 * 
 */
public class StorageManager extends
		com.mymed.controller.core.manager.storage.StorageManager {

	/**
	 * Default Constructor: will create a ServiceManger on top of a Cassandra
	 * Wrapper
	 */
	public StorageManager() {
		this(new WrapperConfiguration(CONFIG_FILE));
	}

	/**
	 * will create a ServiceManger on top of the WrapperType And use the
	 * specific configuration file for the transport layer
	 * 
	 * @param conf
	 *            The configuration of the transport layer
	 * @throws InternalBackEndException
	 */
	public StorageManager(final WrapperConfiguration conf) {
		super();
		wrapper = new CassandraWrapper(conf.getCassandraListenAddress(),
				conf.getThriftPort());
	}

	/**
	 * Insert a new entry in the database
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param key
	 *            the ID of the entry
	 * @param args
	 *            All columnName and the their value
	 * @throws InternalBackEndException
	 * @throws UnsupportedEncodingException
	 */

	/*
	 *  should replace the old inherited one
	 */
	public void insertSlice2(final String tableName, final String primaryKey,
			final Map<String, byte[]> args) throws InternalBackEndException,
			UnsupportedEncodingException {
		try {
			final Map<ByteBuffer, Map<String, List<Mutation>>> mutationMap = new HashMap<ByteBuffer, Map<String, List<Mutation>>>();
			final long timestamp = System.currentTimeMillis();
			final Map<String, List<Mutation>> tableMap = new HashMap<String, List<Mutation>>();
			final List<Mutation> sliceMutationList = new ArrayList<Mutation>(5);

			tableMap.put(tableName, sliceMutationList);

			final Iterator<Entry<String, byte[]>> iterator = args.entrySet()
					.iterator();
			while (iterator.hasNext()) {
				final Entry<String, byte[]> entry = iterator.next();

				final Mutation mutation = new Mutation();
				mutation.setColumn_or_supercolumn(new ColumnOrSuperColumn()
						.setColumn(new Column(MConverter
								.stringToByteBuffer(entry.getKey()), ByteBuffer
								.wrap(entry.getValue()), timestamp)));

				sliceMutationList.add(mutation);
			}

			// Insertion in the map
			mutationMap.put(ByteBuffer.wrap(primaryKey.getBytes(_ENCODING)),
					tableMap);

			LOGGER.info(
					"Performing a batch_mutate on table '{}' with key '{}'",
					tableName, primaryKey);

			wrapper.batchMutate(mutationMap, consistencyOnWrite);
		} catch (final InternalBackEndException e) {
			LOGGER.debug("Insert slice in table '{}' failed", tableName, e);
			throw new InternalBackEndException("InsertSlice failed."); // NOPMD
		}

		LOGGER.info("batch_mutate performed correctly");
	}

	/**
	 * Insert a new entry in the database
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param key
	 *            the ID of the entry
	 * @param args
	 *            All columnName and the their value
	 * @throws InternalBackEndException
	 */
	@Override
	public void insertSuperSlice(final String superTableName, final String key,
			final String superKey, final Map<String, byte[]> args)
			throws IOBackEndException, InternalBackEndException {
		try {
			final Map<ByteBuffer, Map<String, List<Mutation>>> mutationMap = new HashMap<ByteBuffer, Map<String, List<Mutation>>>();
			final long timestamp = System.currentTimeMillis();
			final Map<String, List<Mutation>> tableMap = new HashMap<String, List<Mutation>>();
			final List<Mutation> sliceMutationList = new ArrayList<Mutation>();

			tableMap.put(superTableName, sliceMutationList);

			final List<Column> columns = new ArrayList<Column>();

			for (Entry<String, byte[]> entry : args.entrySet()) {
				columns.add(new Column(MConverter.stringToByteBuffer(entry
						.getKey()), ByteBuffer.wrap(entry.getValue()),
						timestamp));
			}

			final Mutation mutation = new Mutation();
			final SuperColumn superColumn = new SuperColumn(
					MConverter.stringToByteBuffer(superKey), columns);
			mutation.setColumn_or_supercolumn(new ColumnOrSuperColumn()
					.setSuper_column(superColumn));
			sliceMutationList.add(mutation);

			// Insertion in the map
			mutationMap.put(MConverter.stringToByteBuffer(key), tableMap);

			wrapper.batchMutate(mutationMap, consistencyOnWrite);
		} catch (final InternalBackEndException e) {
			throw new InternalBackEndException(e);
		}
	}

	public Map<String, Map<String, String>> multiSelectList(
			final String tableName, final List<String> keys,
			final String start, final String finish) throws IOBackEndException,
			InternalBackEndException, UnsupportedEncodingException {

		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(start.getBytes(_ENCODING));
		sliceRange.setFinish(finish.getBytes(_ENCODING));
		sliceRange.setCount(maxNumColumns); // TODO Maybe better split and
											// perform several queries.
		predicate.setSlice_range(sliceRange);

		final ColumnParent parent = new ColumnParent(tableName);

		final Map<ByteBuffer, List<ColumnOrSuperColumn>> resultMap = wrapper
				.multiget_slice(keys, parent, predicate, consistencyOnRead);

		final List<ColumnOrSuperColumn> results = new ArrayList<ColumnOrSuperColumn>();

		for (List<ColumnOrSuperColumn> l : resultMap.values()) {
			results.addAll(l);
		}

		final Map<String, Map<String, String>> sliceMap = new TreeMap<String, Map<String, String>>();

		for (final ColumnOrSuperColumn res : results) {
			if (res.isSetSuper_column()) {
				final Map<String, String> slice = new HashMap<String, String>();

				for (final Column column : res.getSuper_column().getColumns()) {
					slice.put(MConverter.byteArrayToString(column.getName()),
							MConverter.byteArrayToString(column.getValue()));
				}
				sliceMap.put(MConverter.byteArrayToString(res.getSuper_column()
						.getName()), slice);
			}
		}

		return sliceMap;
	}

}
