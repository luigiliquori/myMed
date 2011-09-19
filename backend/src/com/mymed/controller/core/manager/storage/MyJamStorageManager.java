package com.mymed.controller.core.manager.storage;

import java.io.File;
import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedHashMap;
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
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.utils.MConverter;
/**
 * Storage manager created ad-hoc for myJam application.
 * @author iacopo
 *
 */
public class MyJamStorageManager extends StorageManager implements IMyJamStorageManager{
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/**
	 * The Default path of the wrapper config file
	 */
	public final static String CONFIG_PATH = "/home/iacopo/workspace/mymed/backend/conf/config.xml";
	//TODO Maximum number of received columns, maybe it would be better to insert in the configuration file as a parameter. 
	public final static int maxNumColumns=10000; 

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
			Map<String, byte[]> args) throws InternalBackEndException {
		Map<String,Map<String,List<Mutation>>> mutationMap = new HashMap<String,Map<String,List<Mutation>>>();
		/** The convention is to use microseconds since 1 Jenuary 1970*/
		long timestamp = (long) (System.currentTimeMillis()*1E3);	
		try{
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
		}
	}
	
	/**
	 * Selects a column in a CF.
	 * @param tableName
	 * @param primaryKey
	 * @param superColumn
	 * @param column
	 * @return
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public byte[] selectColumn(String tableName, String primaryKey,
			byte[] superColumn, byte[] column) throws IOBackEndException, InternalBackEndException {
		try {
		/** Check if the position is already occupied.*/
		final ColumnPath columnPath = new ColumnPath(tableName);
		if (superColumn != null)
			columnPath.setSuper_column(superColumn);
		columnPath.setColumn(column);
		
			//TODO Define the consistency level to use on this operation.
		return wrapper.get(primaryKey, columnPath, consistencyOnRead)
							.getColumn()
							.getValue();
		}catch (InternalBackEndException e){
			throw new InternalBackEndException("selectColumn failed.");
		}
	}
	
	/**
	 * Returns the expiring column, with the timestamp and the TTL.
	 * @param tableName	Name of the CF.
	 * @param primaryKey The row key.
	 * @param superColumn The SuperColumn name, can be put safely to null if is a CF, but is compulsory if is a SuperCF.
	 * @param column The Column name.
	 * @return
	 * @throws ServiceManagerException
	 * @throws IOBackEndException The column is not present.
	 * @throws InternalBackEndException
	 */
	public ExpColumnBean selectExpiringColumn(String tableName, String primaryKey,
			byte[] superColumn, byte[] column) throws IOBackEndException, InternalBackEndException {
		ExpColumnBean expCol = new ExpColumnBean();
		try {
		/** Set up parameters.*/
		ColumnPath columnPath = new ColumnPath(tableName);
		if (superColumn!=null)
			columnPath.setSuper_column(superColumn);
		columnPath.setColumn(column);
		
			//TODO Define the consistency level to use on this operation.
		Column res = wrapper.get(primaryKey, columnPath, consistencyOnRead).getColumn();
		//TODO Maybe there's a better way to pass all the arguments.
		expCol.setValue(res.getValue());
		expCol.setTimestamp(res.getTimestamp());
		expCol.setTimeToLive(res.getTtl());
				
		}catch (InternalBackEndException e){
			throw new InternalBackEndException("selectExpiringColumn failed.");
		}
		return expCol;
	}

	@Override
	public void insertColumn(String tableName, String primaryKey,
			String columnName, byte[] value) throws InternalBackEndException {
		try{
			insertColumn(tableName, primaryKey, columnName.getBytes("UTF8"),value);
		} catch (final UnsupportedEncodingException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"UnsupportedEncodingException with\n"
							+ "\t- columnFamily = " + tableName + "\n"
							+ "\t- key = " + primaryKey + "\n" + "\t- columnName = "
							+ columnName + "\n");
		}		
	}
	
	public void insertColumn(String tableName, String primaryKey,
			byte[] columnName, byte[] value) throws InternalBackEndException {
		
		final long timestamp = (long) (System.currentTimeMillis()*1E3);
		insertExpiringColumn(tableName,primaryKey,columnName,value,timestamp,0);
	}
	
	public void insertExpiringColumn(String tableName, String primaryKey,
			byte[] columnName, byte[] value, long timestamp, int expiringTime) throws InternalBackEndException {
			
		insertExpiringColumn(tableName,primaryKey,null,columnName,value,timestamp,expiringTime);
	}
	
	@Override
	public void insertSuperColumn(String tableName, String primaryKey,
			String superColumn, String columnName, byte[] value)
			throws InternalBackEndException {
		
		final long timestamp = (long) (System.currentTimeMillis()*1E3);
		try{
			insertExpiringColumn(tableName,primaryKey,superColumn.getBytes(),columnName.getBytes("UTF8"),value,timestamp,0);
		} catch (final UnsupportedEncodingException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"UnsupportedEncodingException with\n"
							+ "\t- columnFamily = " + tableName + "\n"
							+ "\t- key = " + primaryKey + "\n" + "\t- columnName = "
							+ columnName + "\n");	
		}
	}
	
	public void insertExpiringColumn(String tableName, String key,
			byte[] superColumn, byte[] columnName, byte[] value,long timestamp,int expiringTime)
			throws  InternalBackEndException {
		
			final ColumnParent parent = new ColumnParent(tableName);
			if (superColumn!=null)
				parent.setSuper_column(superColumn);
			final ByteBuffer bufferValue = ByteBuffer.wrap(value);
			final ByteBuffer bufferName = ByteBuffer.wrap(columnName);
			Column column = new Column(
				bufferName, bufferValue,
				timestamp);
			if (expiringTime>0)
				column.setTtl(expiringTime);
			wrapper.insert(key, parent, column, consistencyOnWrite);	
	}


	@Override
	public Map<byte[], byte[]> selectAll(String tableName, String primaryKey)
			throws IOBackEndException,
			InternalBackEndException {
		// read entire row
		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(new byte[0]);
		sliceRange.setFinish(new byte[0]);
		sliceRange.setReversed(true);
		sliceRange.setCount(maxNumColumns);
		predicate.setSlice_range(sliceRange);

		return selectByPredicate(tableName, primaryKey, predicate);
	}
	
	@Override
	public Map<String,Map<byte[], byte[]>> selectAll(String tableName, List<String> primaryKeys)
			throws IOBackEndException,InternalBackEndException {
		// read entire row
		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(new byte[0]);
		sliceRange.setFinish(new byte[0]);
		sliceRange.setReversed(true);
		sliceRange.setCount(maxNumColumns);
		predicate.setSlice_range(sliceRange);
		
		return selectByPredicate(tableName, primaryKeys, predicate);
	}

	@Override
	public Map<byte[], byte[]> selectRange(String tableName, String primaryKey,
			List<String> columnNames) throws IOBackEndException, InternalBackEndException {
		
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
			byte[] startColumn, byte[] stopColumn,int maxNum) throws IOBackEndException, InternalBackEndException {

		final SliceRange range = new SliceRange();
		range.setStart(startColumn);
		range.setFinish(stopColumn);
		range.setCount(maxNum);

		return selectByPredicate(tableName, primaryKey, new SlicePredicate().setSlice_range(range));		
	}
	/**
	 * Gets the first n columns of the given CF row.
	 * @param tableName		ColumnFamily.
	 * @param primaryKey	Key of the row.
	 * @param n				Number of returned columns (at most.)
	 * @return
	 */
	public Map<byte[], byte[]> getLastN(String tableName, String primaryKey,
			Integer n) throws IOBackEndException, InternalBackEndException {

				final SliceRange range = new SliceRange();
				range.setStart(new byte[0]);
				range.setFinish(new byte[0]);
				range.setReversed(true);
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
			byte[] startColumn, byte[] stopColumn) throws IOBackEndException, InternalBackEndException {

		final SliceRange range = new SliceRange();
		range.setStart(startColumn);
		range.setFinish(stopColumn);
		range.setCount(maxNumColumns);	//TODO Maybe better split and perform several queries.

		return selectSCByPredicate(tableName, primaryKeys, new SlicePredicate().setSlice_range(range));		
	}
	
	@Override
	public int countColumns(String tableName, String primaryKey,
			byte[] superColumn) throws IOBackEndException, InternalBackEndException {
		
		final ColumnParent parent = new ColumnParent(tableName);
		if (superColumn!=null)
			parent.setSuper_column(superColumn);
		final SlicePredicate predicate = new SlicePredicate();
		final SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(new byte[0]);
		sliceRange.setFinish(new byte[0]);
		sliceRange.setCount(maxNumColumns);
		predicate.setSlice_range(sliceRange);

		return wrapper.get_count(primaryKey, parent, predicate, consistencyOnRead);	
	}

	/**
	 * Remove a specific column defined by the columnName
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @throws ServiceManagerException
	 * @throws InternalBackEndException
	 * @throws UnsupportedEncodingException
	 */
	@Override
	public void removeColumn(final String tableName, final String key,
			final String columnName) throws InternalBackEndException {
		this.removeColumn(tableName, key, null,columnName.getBytes());
	}

	@Override
	public void removeAll(final String tableName, final String key)
			throws InternalBackEndException {
		final String columnFamily = tableName;
		final long timestamp = System.currentTimeMillis();
		final ColumnPath columnPath = new ColumnPath(columnFamily);

		wrapper.remove(key, columnPath, timestamp, consistencyOnWrite);
	}
	
	/**
	 * Remove a specific column defined by the columnName
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @throws ServiceManagerException
	 * @throws InternalBackEndException
	 * @throws UnsupportedEncodingException
	 */
	@Override
	public void removeColumn(String tableName, String primaryKey,
			byte[] superColumn, byte[] column) throws InternalBackEndException {
		
		final String columnFamily = tableName;
		final long timestamp = (long) (System.currentTimeMillis()*1E3);
		final ColumnPath columnPath = new ColumnPath(columnFamily);
		if (superColumn!=null)
			columnPath.setSuper_column(superColumn);
		if (column!=null)
			columnPath.setColumn(column);

		wrapper.remove(primaryKey, columnPath, timestamp, consistencyOnWrite);
	}
	
	/*
	 * Used by selectAll and selectRange
	 */
	private Map<byte[], byte[]> selectByPredicate(final String columnFamily,
			final String key, final SlicePredicate predicate)
			throws InternalBackEndException, IOBackEndException {

		final ColumnParent parent = new ColumnParent(columnFamily);
		final List<ColumnOrSuperColumn> results = wrapper.get_slice(key,
				parent, predicate, consistencyOnRead);

//			final Map<byte[], byte[]> slice = new HashMap<byte[], byte[]>(
//					results.size());
		/**
		 * I need to mantain the results ordered.
		*/
		final Map<byte[], byte[]> slice = new LinkedHashMap<byte[], byte[]>(
				results.size());
			
			
		for (final ColumnOrSuperColumn res : results) {
			final Column col = res.getColumn();
			slice.put(col.getName(), col.getValue());
		}

		return slice;
	}
	
	/*
	 * Used by selectAll and selectRange
	 */
	private Map<String,Map<byte[], byte[]>> selectByPredicate(final String columnFamily,
			final List<String> keys, final SlicePredicate predicate)
			throws InternalBackEndException, IOBackEndException {

		final ColumnParent parent = new ColumnParent(columnFamily);
		final Map<ByteBuffer,List<ColumnOrSuperColumn>> results = wrapper.multiget_slice(keys,
					parent, predicate, consistencyOnRead);

		final Map<String,Map<byte[], byte[]>> slice = new HashMap<String, Map<byte[],byte[]>>
					(results.size());


		for (final ByteBuffer key : results.keySet()) {
			final List<ColumnOrSuperColumn> columns = results.get(key);
			if (!columns.isEmpty()){
				Map<byte[],byte[]> colMap = new HashMap<byte[],byte[]>();
				for (ColumnOrSuperColumn resCol :columns){
					colMap.put(resCol.getColumn().getName(), resCol.getColumn().getValue());
				}
				slice.put(MConverter.byteBufferToString(key), colMap);
			}
		}

		return slice;
	}
	
	/*
	 * Used by selectAll and selectRange
	 */
	private Map<byte[],Map<byte[], byte[]>> selectSCByPredicate(final String columnFamily,
			final List<String> keys, final SlicePredicate predicate)
			throws InternalBackEndException, IOBackEndException {

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
	}
	
	public class ExpColumnBean{
		private byte[] value;
		private long timestamp;
		private int timeToLive;
		public void setValue(byte[] value) {
			this.value = value;
		}
		public byte[] getValue() {
			return value;
		}
		public void setTimestamp(long timestamp) {
			this.timestamp = timestamp;
		}
		public long getTimestamp() {
			return timestamp;
		}
		public void setTimeToLive(int timeToLive) {
			this.timeToLive = timeToLive;
		}
		public int getTimeToLive() {
			return timeToLive;
		}

	}
}
