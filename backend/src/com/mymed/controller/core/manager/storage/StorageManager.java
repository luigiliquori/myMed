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
package com.mymed.controller.core.manager.storage;

import static com.mymed.utils.MiscUtils.decode;
import static com.mymed.utils.MiscUtils.encode;

import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
import org.apache.cassandra.thrift.SuperColumn;
import org.apache.commons.lang.NotImplementedException;

import ch.qos.logback.classic.Logger;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.properties.IProperties;
import com.mymed.properties.PropType;
import com.mymed.properties.PropertiesManager;
import com.mymed.utils.MConverter;
import com.mymed.utils.MLogger;

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
public class StorageManager implements IStorageManager {

	protected static final Logger LOGGER = MLogger.getLogger();

	private static final PropertiesManager PROPERTIES = PropertiesManager.getInstance();
	protected static final IProperties GENERAL = PROPERTIES.getManager(PropType.GENERAL);

	public static final String ENCODING = GENERAL.get("general.string.encoding");
	protected static final String CONFIG_FILE = GENERAL.get("general.config.file");

	protected CassandraWrapper wrapper;

	/**
	 * Default Constructor: will create a ServiceManger on top of a Cassandra
	 * Wrapper
	 */
	public StorageManager() {
		this(new WrapperConfiguration(CONFIG_FILE));
	}

	/**
	 * will create a ServiceManger on top of the WrapperType And use the specific
	 * configuration file for the transport layer
	 * 
	 * @param conf
	 *          The configuration of the transport layer
	 * @throws InternalBackEndException
	 */
	public StorageManager(final WrapperConfiguration conf) {
		super();
		wrapper = new CassandraWrapper(conf.getCassandraListenAddress(), conf.getThriftPort());
	}

	/**
	 * Get the value of an entry column
	 * 
	 * @param tableName
	 *          the name of the Table/ColumnFamily
	 * @param key
	 *          the ID of the entry
	 * @param columnName
	 *          the name of the column
	 * @return the value of the column
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	@Override
	public byte[] selectColumn(
			final String tableName, 
			final String key, 
			final String columnName) throws InternalBackEndException, IOBackEndException 
			{

		byte[] resultValue;

		final ColumnPath colPathName = new ColumnPath(tableName);
		colPathName.setColumn(encode(columnName));

		LOGGER.info("Selecting column '{}' from table '{}' with key '{}'", new Object[] {columnName, tableName, key});

		resultValue = wrapper.get(key, colPathName, IStorageManager.consistencyOnRead).getColumn().getValue();


		LOGGER.info("Column selection performed");

		return resultValue;
			}

	public byte[] _selectSuperColumn(String tableName, String key,
			String columnName, String superColumn) throws InternalBackEndException,
			IOBackEndException {
		byte[] resultValue;

		final ColumnPath colPathName = new ColumnPath(tableName);
		colPathName.setColumn(encode(columnName));
		colPathName.setSuper_column(encode(superColumn));

		LOGGER.info("Selecting Super column '{}' from column '{}' from table '{}' with key '{}'", new Object[] {superColumn, columnName, tableName, key});

		resultValue = wrapper.get(key, colPathName, IStorageManager.consistencyOnRead).getColumn().getValue();

		LOGGER.info("SuperColumn selection performed");

		return resultValue;
	}



	@Override
	public Map<byte[], byte[]> selectAll(
			final String tableName, 
			final String key) 
					throws InternalBackEndException, IOBackEndException 
					{
		// read entire row
		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(new byte[0]);
		sliceRange.setFinish(new byte[0]);
		predicate.setSlice_range(sliceRange);

		return selectByPredicate(tableName, key, predicate);
					}

	@Override
	public Map<byte[], byte[]> selectAll(
			final String tableName, 
			final String key, 
			final String start, 
			final int count, 
			final Boolean reversed) 
					throws InternalBackEndException, IOBackEndException, UnsupportedEncodingException 
					{
		// Read entire row
		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(encode(start));
		sliceRange.setFinish(new byte[0]);
		sliceRange.setCount(count);
		sliceRange.setReversed(reversed);
		predicate.setSlice_range(sliceRange);

		return selectByPredicate(tableName, key, predicate);
					}

	/**
	 * Get the value of a column family
	 * 
	 * @param tableName
	 *          the name of the Table/ColumnFamily
	 * @param key
	 *          the ID of the entry
	 * @param columnName
	 *          the name of the column
	 * @return the value of the column
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	@Override
	public List<Map<byte[], byte[]>> selectList(
			final String tableName, 
			final String key)
					throws InternalBackEndException, IOBackEndException 
					{
		// read entire row
		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(new byte[0]);
		sliceRange.setFinish(new byte[0]);
		predicate.setSlice_range(sliceRange);

		final ColumnParent parent = new ColumnParent(tableName);
		final List<ColumnOrSuperColumn> results = getSlice(key, parent, predicate);

		final List<Map<byte[], byte[]>> sliceList = new ArrayList<Map<byte[], byte[]>>();

		for (final ColumnOrSuperColumn res : results) {
			if (res.isSetSuper_column()) {
				final Map<byte[], byte[]> slice = new HashMap<byte[], byte[]>();

				for (final Column column : res.getSuper_column().getColumns()) {
					slice.put(column.getName(), column.getValue());
				}

				sliceList.add(slice);
			}
		}

		return sliceList;
					}

	@Override
	public List<Map<byte[], byte[]>> selectList(
			final String tableName, 
			final String key,
			final String start, 
			final int count, 
			final Boolean reversed)
					throws InternalBackEndException, IOBackEndException, UnsupportedEncodingException 
					{

		// read entire row
		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(encode(start));
		sliceRange.setFinish(new byte[0]);
		sliceRange.setCount(count);
		sliceRange.setReversed(reversed);
		predicate.setSlice_range(sliceRange);

		final ColumnParent parent = new ColumnParent(tableName);
		final List<ColumnOrSuperColumn> results = getSlice(key, parent, predicate);

		final List<Map<byte[], byte[]>> sliceList = new ArrayList<Map<byte[], byte[]>>();

		for (final ColumnOrSuperColumn res : results) {
			if (res.isSetSuper_column()) {
				final Map<byte[], byte[]> slice = new HashMap<byte[], byte[]>();

				for (final Column column : res.getSuper_column().getColumns()) {
					slice.put(column.getName(), column.getValue());
				}

				sliceList.add(slice);
			}
		}

		return sliceList;
					}



	/**
	 * Get the values of a range of columns
	 * 
	 * @param tableName
	 *          the name of the Table/ColumnFamily
	 * @param key
	 *          the ID of the entry
	 * @param columnNames
	 *          the name of the columns to return the values
	 * @return the value of the columns
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	@Override
	public Map<byte[], byte[]> selectRange(
			final String tableName, 
			final String key, 
			final List<String> columnNames)
					throws InternalBackEndException, IOBackEndException 
					{

		final List<ByteBuffer> columnNamesToByte = new ArrayList<ByteBuffer>();
		for (final String columnName : columnNames) {
			columnNamesToByte.add(MConverter.stringToByteBuffer(columnName));
		}

		final SlicePredicate predicate = new SlicePredicate();
		predicate.setColumn_names(columnNamesToByte);

		return selectByPredicate(tableName, key, predicate);
					}

	/**
	 * Used by selectAll and selectRange
	 */
	private Map<byte[], byte[]> selectByPredicate(
			final String columnFamily, 
			final String key,
			final SlicePredicate predicate) 
					throws InternalBackEndException, IOBackEndException 
					{
		if(!key.equals("")) {
			final ColumnParent parent = new ColumnParent(columnFamily);

			final List<ColumnOrSuperColumn> results = getSlice(key, parent, predicate);

			final Map<byte[], byte[]> slice = new HashMap<byte[], byte[]>(results.size());

			for (final ColumnOrSuperColumn res : results) {
				final Column col = res.getColumn();
				if(col != null) {
					slice.put(col.getName(), col.getValue());
				}
			}

			return slice;
		} else {
			return new HashMap<byte[], byte[]>();
		}
					}

	/**
	 * Retrieve the slice.
	 * 
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	private List<ColumnOrSuperColumn> getSlice(
			final String key, 
			final ColumnParent parent, 
			final SlicePredicate predicate)
					throws InternalBackEndException, IOBackEndException 
					{

		LOGGER.info("Selecting slice from column family '{}' with key '{}'", parent.getColumn_family(), key);

		final List<ColumnOrSuperColumn> slice = wrapper.get_slice(key, parent, predicate, consistencyOnRead);

		LOGGER.info("Slice selection completed");

		return slice;
					}

	/**
	 * Count columns in record
	 * 
	 * @param key
	 * @param parent
	 * @return
	 * @throws InternalBackEndException
	 */
	@Override
	public int countColumns(
			final String tableName, 
			final String key) 
					throws InternalBackEndException 
					{

		final ColumnParent parent = new ColumnParent(tableName);
		LOGGER.info("Selecting slice from column family '{}' with key '{}'", parent.getColumn_family(), key);

		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(new byte[0]);
		sliceRange.setFinish(new byte[0]);
		predicate.setSlice_range(sliceRange);
		final int count = wrapper.get_count(key, parent, predicate, consistencyOnRead);

		LOGGER.info("Slice selection completed");
		return count;
					}

	/**
	 * Update the value of a Simple Column
	 * 
	 * @param tableName
	 *          the name of the Table/ColumnFamily
	 * @param key
	 *          the ID of the entry
	 * @param columnName
	 *          the name of the column
	 * @param value
	 *          the value updated
	 * @return true is the value is updated, false otherwise
	 * @throws InternalBackEndException
	 */
	@Override
	public void insertColumn(
			final String tableName, 
			final String key, 
			final String columnName, 
			final byte[] value)
					throws InternalBackEndException 
					{

		final ColumnParent parent = new ColumnParent(tableName);

		insert(key, parent, columnName, value);
					}


	/**
	 * Update the value of a Super Column
	 * 
	 * @param tableName
	 *          the name of the Table/ColumnFamily
	 * @param key
	 *          the ID of the entry
	 * @param superColumn
	 *          the ID of the superColumn
	 * @param columnName
	 *          the name of the column
	 * @param value
	 *          the value updated
	 * @return true is the value is updated, false otherwise
	 * @throws InternalBackEndException
	 */
	@Override
	public void insertSuperColumn(
			final String tableName, 
			final String key, 
			final String superColumn,
			final String columnName, 
			final byte[] value) 
					throws InternalBackEndException 
					{
		final ColumnParent parent = new ColumnParent(tableName);
		parent.setSuper_column(MConverter.stringToByteBuffer(superColumn));
		insert(key, parent, columnName, value);
					}

	/**
	 * Perform the real insert operation
	 * 
	 * @param key
	 *          the ID of the entry
	 * @param parent
	 *          the ColumParent
	 * @param columnName
	 *          the name of the column
	 * @param value
	 *          the value updated
	 * @throws InternalBackEndException
	 */

	private void insert(
			final String key, 
			final ColumnParent parent, 
			final String columnName, final byte[] value)
					throws InternalBackEndException 
					{
		final long timestamp = System.currentTimeMillis();
		final ByteBuffer buffer = ByteBuffer.wrap(value);
		final Column column = new Column(MConverter.stringToByteBuffer(columnName), buffer, timestamp);
		LOGGER.info("Inserting column '{}' into '{}' with key '{}'", new Object[] {columnName, parent.getColumn_family(),
				key});
		wrapper.insert(key, parent, column, consistencyOnWrite);

		LOGGER.info("Column '{}' inserted", columnName);
					}



	/**
	 * Insert a new entry in the database
	 * 
	 * @param tableName
	 *          the name of the Table/ColumnFamily
	 * @param key
	 *          the ID of the entry
	 * @param args
	 *          All columnName and the their value
	 * @throws InternalBackEndException
	 */
	@Override
	public void insertSlice(
			final String tableName, 
			final String primaryKey, 
			final Map<String, byte[]> args)
					throws InternalBackEndException
					{
		try {
			final Map<String, Map<String, List<Mutation>>> mutationMap = new HashMap<String, Map<String, List<Mutation>>>();
			final long timestamp = System.currentTimeMillis();
			final Map<String, List<Mutation>> tableMap = new HashMap<String, List<Mutation>>();
			final List<Mutation> sliceMutationList = new ArrayList<Mutation>(5);

			tableMap.put(tableName, sliceMutationList);

			final Iterator<Entry<String, byte[]>> iterator = args.entrySet().iterator();
			while (iterator.hasNext()) {
				final Entry<String, byte[]> entry = iterator.next();

				final Mutation mutation = new Mutation();
				mutation.setColumn_or_supercolumn(new ColumnOrSuperColumn().setColumn(new Column(MConverter
						.stringToByteBuffer(entry.getKey()), ByteBuffer.wrap(entry.getValue()), timestamp)));

				sliceMutationList.add(mutation);
			}

			// Insertion in the map
			mutationMap.put(primaryKey, tableMap);

			LOGGER.info("Performing a batch_mutate on table '{}' with key '{}'", tableName, primaryKey);

			wrapper.batch_mutate(mutationMap, consistencyOnWrite);
		} catch (final InternalBackEndException e) {
			LOGGER.debug("Insert slice in table '{}' failed", tableName, e);
			throw new InternalBackEndException("InsertSlice failed."); // NOPMD
		}

		LOGGER.info("batch_mutate performed correctly");
					}



	/**
	 * Insert a new entry in the database
	 * 
	 * @param superTableName
	 *          the name of the Table/SuperColumnFamily
	 * @param key
	 *          the ID of the entry
	 * @param superKey
	 *          the ID of the entry in the SuperColumnFamily
	 * @param args
	 *          All columnName and the their value
	 * @throws ServiceManagerException
	 * @throws InternalBackEndException
	 * 
	 */
	@Override
	public void insertSuperSlice(
			final String superTableName, 
			final String key, 
			final String superKey,
			final Map<String, byte[]> args) 
					throws IOBackEndException, InternalBackEndException 
					{
		try {

//			System.out.println("\nWRITE: " + superTableName + ", " + key + "," + superKey);
			
			LOGGER.info(String.format("Inserting into SCF %s key:%s superKey:%s nbArgs:%d",
					superTableName,
					key,
					superKey,
					args.size()));

			final Map<String, Map<String, List<Mutation>>> mutationMap = new HashMap<String, Map<String, List<Mutation>>>();
			final long timestamp = System.currentTimeMillis();
			final Map<String, List<Mutation>> tableMap = new HashMap<String, List<Mutation>>();
			final List<Mutation> sliceMutationList = new ArrayList<Mutation>(5);

			tableMap.put(superTableName, sliceMutationList);


			final List<Column> columns = new ArrayList<Column>();

			for (Entry<String, byte[]> entry : args.entrySet()){
				columns.add(new Column(MConverter.stringToByteBuffer(entry.getKey()), ByteBuffer.wrap(entry.getValue()),
						timestamp));
			}

			final Mutation mutation = new Mutation();
			final SuperColumn superColumn = new SuperColumn(MConverter.stringToByteBuffer(superKey), columns);
			mutation.setColumn_or_supercolumn(new ColumnOrSuperColumn().setSuper_column(superColumn));
			sliceMutationList.add(mutation);

			// Insertion in the map
			mutationMap.put(key, tableMap);

			wrapper.batch_mutate(mutationMap, consistencyOnWrite);

		} catch (final InternalBackEndException e) {
			throw new InternalBackEndException(e);
		}
					}

	/**
	 * Remove a specific column defined by the columnName
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @throws InternalBackEndException
	 */
	@Override
	public void removeColumn(
			final String tableName, 
			final String key, final String columnName) 
					throws InternalBackEndException 
					{
		final long timestamp = System.currentTimeMillis();
		final ColumnPath columnPath = new ColumnPath(tableName);
		columnPath.setColumn(encode(columnName));

		wrapper.remove(key, columnPath, timestamp, consistencyOnWrite);
					}

	/**
	 * 
	 * @param tableName
	 * @param key
	 * @param superColumnName
	 * @throws InternalBackEndException
	 */
	@Override
	public void removeSuperColumn(
			final String tableName, 
			final String key, 
			final String superColumnName)
					throws InternalBackEndException 
					{
		final String columnFamily = tableName;
		final long timestamp = System.currentTimeMillis();
		final ColumnPath columnPath = new ColumnPath(columnFamily);
		columnPath.setSuper_column(encode(superColumnName));

		wrapper.remove(key, columnPath, timestamp, consistencyOnWrite);
					}

	/**
	 * @return
	 */
	public CassandraWrapper getWrapper() {
		return wrapper;
	}

	/**
	 * Remove an entry in the columnFamily
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @throws InternalBackEndException
	 */
	@Override
	public void removeAll(
			final String tableName, 
			final String key) 
					throws InternalBackEndException 
					{

		final String columnFamily = tableName;
		final long timestamp = System.currentTimeMillis();
		final ColumnPath columnPath = new ColumnPath(columnFamily);

		LOGGER.info("Remove all columns in table '{}' with key '{}'", tableName, key);

		wrapper.remove(key, columnPath, timestamp, consistencyOnWrite);

		LOGGER.info("Removed all columns in table '{}'", tableName); 
					}

	@Override public Map<String, String> selectAllStr(
			String tableName, 
			String primaryKey)
					throws IOBackEndException, InternalBackEndException 
					{
		// Prepare output
		HashMap<String, String> result = new HashMap<String, String>();

		// Call the Map<byte[], byte[]> version
		Map<byte[], byte[]> map = this.selectAll(tableName, primaryKey);

		// Transform into map of strings
		for (Entry<byte[], byte[]> entry : map.entrySet()) {
			result.put(decode(entry.getKey()), decode(entry.getValue()));
		} 
		return result;
					}


	@Override
	public Map<String, Map<String, String>> multiSelectList(
			String tableName,
			List<String> keys, 
			String start, 
			String finish) 
			{
		throw new NotImplementedException();	
			}  

	@Override
	public Map<String, String> selectSuperColumn(String tableName, String key,
			String columnName) throws InternalBackEndException,
			IOBackEndException {
		// TODO Auto-generated method stub
		throw new NotImplementedException();
	}

	@Override
	public void insertStr(String key, ColumnParent parent, String columnName,
			String value) throws InternalBackEndException {
		throw new NotImplementedException();
	}

	@Override
	public void insertSliceStr(String tableName, String primaryKey,
			Map<String, String> args) throws InternalBackEndException {
		throw new NotImplementedException();	
	}
	
	@Override
	public void insertSliceStr(String tableName, String primaryKey, int ttl,
			Map<String, String> args) throws InternalBackEndException {
		throw new NotImplementedException();	
	}

	@Override
	public void insertSuperSliceStr(String superTableName, String key,
			String superKey, Map<String, String> args)
					throws InternalBackEndException {
		throw new NotImplementedException();
	}

	@Override
	public void insertSuperSliceListStr(String superTableName,
			List<String> keys, String superKey, Map<String, String> args)
			throws InternalBackEndException {
		throw new NotImplementedException();

	}

	@Override
	public String selectColumnStr(String tableName, String key,
			String columnName) throws IOBackEndException,
			InternalBackEndException {
		throw new NotImplementedException();
	}

	@Override
	public Map<ByteBuffer, List<ColumnOrSuperColumn>> batch_read(
			String tableName, List<String> keys)
			throws InternalBackEndException {
		throw new NotImplementedException();
	}
    
}
