package com.mymed.controller.core.manager.storage;

import java.io.File;
import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
import org.apache.cassandra.thrift.SuperColumn;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.utils.MConverter;
/**
 * Storage manager created ad hoc for myJam application.
 * @author iacopo
 *
 */
public class MyJamStorageManager implements IMyJamStorageManager{
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/**
	 * The Default path of the wrapper config file
	 */
	public static String CONFIG_PATH = "/home/iacopo/workspace/mymed/backend/conf/myJamConfig.xml";
	
	public static String KEYSPACE ="myJamKeyspace";

	private final CassandraWrapper wrapper;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default Constructor: will create a ServiceManger on top of a Cassandra
	 * Wrapper
	 * 
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public MyJamStorageManager() throws InternalBackEndException {
		this(new WrapperConfiguration(new File(CONFIG_PATH)));
	}

	/**
	 * /** will create a ServiceManger on top of the WrapperType And use the
	 * specific configuration file for the transport layer
	 * 
	 * @param type
	 *            Type of DHTClient used
	 * @param conf
	 *            The configuration of the transport layer
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public MyJamStorageManager(final WrapperConfiguration conf) throws InternalBackEndException {
		wrapper = new CassandraWrapper(conf.getCassandraListenAddress(), conf.getThriftPort());
	}

	@Override
	public void insertSlice(String tableName, String primaryKey,
			Map<String, byte[]> args) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException {
		Map<String,Map<String,List<Mutation>>> mutationMap = new HashMap<String,Map<String,List<Mutation>>>();
		/** The convention is to use microseconds since 1 Jenuary 1970*/
		long timestamp = (long) (System.currentTimeMillis()*1E3);	
		try{
			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);
			Map<String,List<Mutation>> tableMap = new HashMap<String,List<Mutation>>();
			List<Mutation> sliceMutationList = new ArrayList<Mutation>(5);
			tableMap.put(tableName,sliceMutationList);
			Iterator<String> iterator = args.keySet().iterator();
			while (iterator.hasNext()){
				String key = iterator.next();
				Mutation mutation = new Mutation();
				mutation.setColumn_or_supercolumn(new ColumnOrSuperColumn().setColumn(
						new Column(MConverter.stringToByteBuffer(key),
								ByteBuffer.wrap(args.get(key)),
								timestamp)));
				sliceMutationList.add(mutation);
			}
			//Insertion in the map
			mutationMap.put(primaryKey, tableMap); 
			wrapper.batch_mutate(mutationMap, consistencyOnWrite);
		}catch(InternalBackEndException e){
			throw new InternalBackEndException(" InsertSlice failed. ");
		}finally{
			wrapper.close();
		}
	}

	@Override
	public byte[] selectColumn(String tableName, String primaryKey,
			String columnName) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException {
		try {
			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);
			final ColumnPath colPathName = new ColumnPath(tableName);
			colPathName.setColumn(columnName.getBytes("UTF8"));
			return wrapper
					.get(primaryKey, colPathName, IStorageManager.consistencyOnRead)
					.getColumn().getValue();
		} catch (final UnsupportedEncodingException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"UnsupportedEncodingException with\n"
							+ "\t- columnFamily = " + tableName + "\n"
							+ "\t- key = " + primaryKey + "\n" + "\t- columnName = "
							+ columnName + "\n");
		} finally {
			wrapper.close();
		}
	}
	
	/**
	 * Return null if the column is not present.
	 * @param tableName
	 * @param primaryKey
	 * @param superColumn
	 * @param column
	 * @return
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public byte[] selectColumnInSuperColumn(String tableName, String primaryKey,
			byte[] superColumn, byte[] column) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException {
		byte[] columnValue;
		try {
										
		/** Transport Opening*/
		wrapper.open();
		wrapper.set_keyspace(KEYSPACE);

		/** Check if the position is already occupied.*/
		ColumnPath columnPath = new ColumnPath(tableName);
		columnPath.setSuper_column(superColumn);
		columnPath.setColumn(column);
		
			//TODO Define the consistency level to use on this operation.
		columnValue = wrapper.get(primaryKey, columnPath, consistencyOnRead).getColumn().getValue();
		} catch (IOBackEndException e) {
			return null;
		}catch (InternalBackEndException e){
			throw new InternalBackEndException("selectColumnInSuperColumn failed.");
		}
		return columnValue;
	}

	@Override
	public void insertColumn(String tableName, String primaryKey,
			String columnName, byte[] value) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException {
		
		insertColumn(tableName, primaryKey, MConverter.stringToByteBuffer(columnName).array(),value);
	}
	
	public void insertColumn(String tableName, String primaryKey,
			byte[] columnName, byte[] value) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException {
		
		try {
			final long timestamp = System.currentTimeMillis();
			final ColumnParent parent = new ColumnParent(tableName);
			final ByteBuffer bufferValue = ByteBuffer.wrap(value);
			final ByteBuffer bufferName = ByteBuffer.wrap(columnName);
			final Column column = new Column(
					bufferName, bufferValue,
					timestamp);

			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);//TODO
			wrapper.insert(primaryKey, parent, column, consistencyOnWrite);
		} finally {
			wrapper.close();
		}
		
	}
	
	@Override
	public void insertSuperColumn(String tableName, String key,
			String superColumn, String columnName, byte[] value)
			throws ServiceManagerException, InternalBackEndException {
		
		try {
			final long timestamp = (long) (System.currentTimeMillis()*1E3);
			final ColumnParent parent = new ColumnParent(tableName);
			parent.setSuper_column(MConverter.stringToByteBuffer(superColumn));
			final ByteBuffer buffer = ByteBuffer.wrap(value);
			final Column column = new Column(
					MConverter.stringToByteBuffer(columnName), buffer,
					timestamp);

			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);//TODO
			wrapper.insert(key, parent, column, consistencyOnWrite);
		} finally {
			wrapper.close();
		}		
	}
	
	public void insertExpiringSuperColumn(String tableName, String key,
			byte[] superColumn, byte[] columnName, byte[] value,int expiringTime)
			throws ServiceManagerException, InternalBackEndException {
		
		try {
			final long timestamp = (long) (System.currentTimeMillis()*1E3);
			final ColumnParent parent = new ColumnParent(tableName);
			parent.setSuper_column(superColumn);
			final ByteBuffer bufferValue = ByteBuffer.wrap(value);
			final ByteBuffer bufferName = ByteBuffer.wrap(columnName);
			Column column = new Column(
				bufferName, bufferValue,
				timestamp);
			column.setTtl(expiringTime);

			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);//TODO 
			wrapper.insert(key, parent, column, consistencyOnWrite);
		} finally {
			wrapper.close();
		}		
	}


	@Override
	public Map<byte[], byte[]> selectAll(String tableName, String primaryKey)
			throws ServiceManagerException, IOBackEndException,
			InternalBackEndException {
		// read entire row
		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(new byte[0]);
		sliceRange.setFinish(new byte[0]);
		predicate.setSlice_range(sliceRange);

		return selectByPredicate(tableName, primaryKey, predicate);
	}

	@Override
	public Map<byte[], byte[]> selectRange(String tableName, String primaryKey,
			List<String> columnNames) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException {
		
		final List<ByteBuffer> columnNamesToByte = new ArrayList<ByteBuffer>();
		for (final String columnName : columnNames) {
			columnNamesToByte.add(MConverter.stringToByteBuffer(columnName));
		}

		final SlicePredicate predicate = new SlicePredicate();
		predicate.setColumn_names(columnNamesToByte);

		return selectByPredicate(tableName, primaryKey, predicate);		
	}
	
	/**
	 * Selects a range of columns, specifying the extremes.
	 */
	public Map<byte[], byte[]> selectRange(String tableName, String primaryKey,
			byte[] startColumn, byte[] stopColumn) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException {

		final SliceRange range = new SliceRange();
		range.setStart(startColumn);
		range.setFinish(stopColumn);

		return selectByPredicate(tableName, primaryKey, new SlicePredicate().setSlice_range(range));		
	}
	/**
	 * Gets the first n columns of the given CF row.
	 * @param tableName		ColumnFamily.
	 * @param primaryKey	Key of the row.
	 * @param n				Number of returned columns (at most.)
	 * @return
	 */
	public Map<byte[], byte[]> getFirstN(String tableName, String primaryKey,
			Integer n) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException {

				final SliceRange range = new SliceRange();
				range.setStart(new byte[0]);
				range.setFinish(new byte[0]);
				range.setCount(n);

				return selectByPredicate(tableName, primaryKey, new SlicePredicate().setSlice_range(range));	
		
	}
	
	/**
	 * Used in myJam to perform geographical range queries.
	 * @param tableName
	 * @param primaryKey
	 * @param startColumn
	 * @param stopColumn
	 * @return
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public Map<byte[],Map<byte[], byte[]>> selectSCRange(String tableName, List<String> primaryKeys,
			byte[] startColumn, byte[] stopColumn) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException {

		final SliceRange range = new SliceRange();
		range.setStart(startColumn);
		range.setFinish(stopColumn);

		return selectSCByPredicate(tableName, primaryKeys, new SlicePredicate().setSlice_range(range));		
	}

	@Override
	public void removeColumn(String tableName, String key, String columnName)
			throws ServiceManagerException, IOBackEndException,
			InternalBackEndException {
		// TODO Auto-generated method stub
		
	}

	@Override
	public void removeAll(String tableName, String key)
			throws InternalBackEndException {
		// TODO Auto-generated method stub
		
	}

	@Override
	public void put(String key, byte[] value) throws IOBackEndException,
			InternalBackEndException {
		// TODO Auto-generated method stub
		
	}

	@Override
	public byte[] get(String key) throws IOBackEndException,
			InternalBackEndException {
		// TODO Auto-generated method stub
		return null;
	}
	
	/*
	 * Used by selectAll and selectRange
	 */
	private Map<byte[], byte[]> selectByPredicate(final String columnFamily,
			final String key, final SlicePredicate predicate)
			throws InternalBackEndException, IOBackEndException {

		try {
			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);

			final ColumnParent parent = new ColumnParent(columnFamily);
			final List<ColumnOrSuperColumn> results = wrapper.get_slice(key,
					parent, predicate, consistencyOnRead);

			final Map<byte[], byte[]> slice = new HashMap<byte[], byte[]>(
					results.size());

			for (final ColumnOrSuperColumn res : results) {
				final Column col = res.getColumn();

				slice.put(col.getName(), col.getValue());
			}

			return slice;
		} finally {
			wrapper.close();
		}
	}
	
	/*
	 * Used by selectAll and selectRange
	 */
	private Map<byte[],Map<byte[], byte[]>> selectSCByPredicate(final String columnFamily,
			final List<String> keys, final SlicePredicate predicate)
			throws InternalBackEndException, IOBackEndException {

		try {
			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);

			final ColumnParent parent = new ColumnParent(columnFamily);
			final Map<ByteBuffer,List<ColumnOrSuperColumn>> results = wrapper.multiget_slice(keys,
					parent, predicate, consistencyOnRead);
			List<ColumnOrSuperColumn> listResults = new LinkedList<ColumnOrSuperColumn>();
			for (final ByteBuffer key : results.keySet()){
				listResults.addAll(results.get(key));
			}
			final Map<byte[],Map<byte[], byte[]>> slice = new HashMap<byte[], Map<byte[],byte[]>>(
					listResults.size());

			for (final ColumnOrSuperColumn res : listResults) {
				final SuperColumn col = res.getSuper_column();
				Map<byte[],byte[]> colMap = new HashMap<byte[],byte[]>();
				for (Column resCol :col.columns){
					colMap.put(resCol.getName(), resCol.getValue());
				}
				slice.put(col.getName(), colMap);
			}

			return slice;
		} finally {
			wrapper.close();
		}
	}
}
