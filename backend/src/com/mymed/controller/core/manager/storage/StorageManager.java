package com.mymed.controller.core.manager.storage;

import java.io.File;
import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.utils.MConverter;

/**
 * @author lvanni
 * 
 *         This class represent the DAO pattern: Access to data varies depending
 *         on the source of the data. Access to persistent storage, such as to a
 *         database, varies greatly depending on the type of storage
 * 
 *         Use a Data Access Object (DAO) to abstract and encapsulate all access
 *         to the data source. The DAO manages the connection with the data
 *         source to obtain and store data.
 */
public class StorageManager implements IStorageManager {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/**
	 * The Default path of the wrapper config file
	 */
	public static String CONFIG_PATH = "/local/mymed/backend/conf/config.xml";

	/** wrapper */
	private CassandraWrapper wrapper;

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
	public StorageManager() throws InternalBackEndException {
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
	public StorageManager(WrapperConfiguration conf)
			throws InternalBackEndException {
		this.wrapper = new CassandraWrapper(conf.getCassandraListenAddress(),
				conf.getThriftPort());
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the value of an entry column
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param key
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @return the value of the column
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public byte[] selectColumn(String tableName, String key, String columnName)
			throws InternalBackEndException, IOBackEndException {
		try {
			wrapper.open();
			ColumnPath colPathName = new ColumnPath(tableName);
			colPathName.setColumn(columnName.getBytes("UTF8"));
			return wrapper
					.get(key, colPathName, StorageManager.consistencyOnRead)
					.getColumn().getValue();
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"UnsupportedEncodingException with\n"
							+ "\t- columnFamily = " + tableName + "\n"
							+ "\t- key = " + key + "\n" + "\t- columnName = "
							+ columnName + "\n");
		} finally {
			wrapper.close();
		}
	}

	/**
	 * Get the value of a column family
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param key
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @return the value of the column
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public Map<byte[], byte[]> selectAll(String tableName, String key)
			throws InternalBackEndException, IOBackEndException {

		wrapper.open();
		// read entire row
		SlicePredicate predicate = new SlicePredicate();
		SliceRange sliceRange = new SliceRange();
		sliceRange.setStart(new byte[0]);
		sliceRange.setFinish(new byte[0]);
		predicate.setSlice_range(sliceRange);
		wrapper.close();

		return selectByPredicate(tableName, key, predicate);
	}

	/**
	 * Get the values of a range of columns
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param key
	 *            the ID of the entry
	 * @param columnNames
	 *            the name of the columns to return the values
	 * @return the value of the columns
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public Map<byte[], byte[]> selectRange(String tableName, String key,
			List<String> columnNames) throws ServiceManagerException,
			InternalBackEndException, IOBackEndException {
		wrapper.open();
		List<ByteBuffer> columnNamesToByte = new ArrayList<ByteBuffer>();
		for (String columnName : columnNames) {
			columnNamesToByte.add(MConverter.stringToByteBuffer(columnName));
		}
		SlicePredicate predicate = new SlicePredicate();
		predicate.setColumn_names(columnNamesToByte);
		wrapper.close();
		return selectByPredicate(tableName, key, predicate);
	}

	/*
	 * Used by selectAll and selectRange
	 */
	private Map<byte[], byte[]> selectByPredicate(String columnFamily,
			String key, SlicePredicate predicate)
			throws InternalBackEndException, IOBackEndException {
		wrapper.open();
		ColumnParent parent = new ColumnParent(columnFamily);
		List<ColumnOrSuperColumn> results = wrapper.get_slice(key, parent,
				predicate, consistencyOnRead);

		Map<byte[], byte[]> slice = new HashMap<byte[], byte[]>();
		for (ColumnOrSuperColumn res : results) {
			Column column = res.column;
			slice.put(column.name.array(), column.value.array());
		}
		wrapper.close();
		return slice;
	}

	/**
	 * Update the value of a Simple Column
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param key
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @param value
	 *            the value updated
	 * @return true is the value is updated, false otherwise
	 * @throws ServiceManagerException
	 * @throws InternalBackEndException
	 */
	public void insertColumn(String tableName, String key, String columnName,
			byte[] value) throws InternalBackEndException {
		wrapper.open();
		long timestamp = System.currentTimeMillis();
		ColumnParent parent = new ColumnParent(tableName);
		ByteBuffer buffer = ByteBuffer.wrap(value);
		Column column = new Column(MConverter.stringToByteBuffer(columnName),
				buffer, timestamp);

		wrapper.insert(key, parent, column, consistencyOnWrite);
		wrapper.close();
	}

	/**
	 * Update the value of a Super Column
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param key
	 *            the ID of the entry
	 * @param superColumn
	 *            the ID of the superColumn
	 * @param columnName
	 *            the name of the column
	 * @param value
	 *            the value updated
	 * @return true is the value is updated, false otherwise
	 * @throws ServiceManagerException
	 * @throws InternalBackEndException
	 * 
	 * 
	 */
	public void insertSuperColumn(String tableName, String key,
			String superColumn, String columnName, byte[] value)
			throws ServiceManagerException, InternalBackEndException {
		wrapper.open();
		long timestamp = System.currentTimeMillis();
		ColumnParent parent = new ColumnParent(tableName);
		parent.setSuper_column(MConverter.stringToByteBuffer(superColumn));
		ByteBuffer buffer = ByteBuffer.wrap(value);
		Column column = new Column(MConverter.stringToByteBuffer(columnName),
				buffer, timestamp);

		wrapper.insert(key, parent, column, consistencyOnWrite);
		wrapper.close();
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
	 * @return true if the entry is correctly stored, false otherwise
	 * @throws ServiceManagerException
	 * @throws InternalBackEndException
	 */
	// TODO FIX this method!!!!
	public void insertSlice(String tableName, String key,
			Map<String, byte[]> args) throws ServiceManagerException,
			InternalBackEndException {
		for (String columnName : args.keySet()) {
			this.insertColumn(tableName, key, columnName, args.get(columnName));
		}
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
	public void removeColumn(String tableName, String key, String columnName)
			throws InternalBackEndException {
		wrapper.open();
		String columnFamily = tableName;
		try {
			long timestamp = System.currentTimeMillis();
			ColumnPath columnPath = new ColumnPath(columnFamily);
			columnPath.setColumn(columnName.getBytes("UTF8"));
			wrapper.remove(key, columnPath, timestamp, consistencyOnWrite);
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"removeColumn failed because of a UnsupportedEncodingException");
		} finally {
			wrapper.close();
		}
	}

	/**
	 * Remove an entry in the columnFamily
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @throws ServiceManagerException
	 * @throws InternalBackEndException
	 * @throws UnsupportedEncodingException
	 */
	public void removeAll(String tableName, String key)
			throws ServiceManagerException, InternalBackEndException {
		wrapper.open();
		String columnFamily = tableName;
		long timestamp = System.currentTimeMillis();
		ColumnPath columnPath = new ColumnPath(columnFamily);
		wrapper.remove(key, columnPath, timestamp, consistencyOnWrite);
		wrapper.close();
	}

	/* --------------------------------------------------------- */
	/* Common DHT operations */
	/* --------------------------------------------------------- */
	/**
	 * Common put operation The DHT type is by default Cassandra
	 * 
	 * @param key
	 * @param value
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public void put(String key, byte[] value) throws IOBackEndException,
			InternalBackEndException {
		this.insertColumn("Services", key, key, value);
	}

	/**
	 * Common get operation The DHT type is by default Cassandra
	 * 
	 * @param key
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public byte[] get(String key) throws IOBackEndException,
			InternalBackEndException {
		return this.selectColumn("Services", key, key);
	}

}
