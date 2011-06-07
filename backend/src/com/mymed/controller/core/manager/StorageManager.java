package com.mymed.controller.core.manager;

import java.io.File;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.factory.DHTWrapperFactory;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;
import com.mymed.model.core.wrappers.AbstractDHTWrapper;
import com.mymed.model.core.wrappers.cassandra.api06.CassandraWrapper;

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
	/** The type of wrapper used by the service manager */
	private WrapperType type;

	/** wrapper */
	private AbstractDHTWrapper wrapper;

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
		this(WrapperType.CASSANDRA, new WrapperConfiguration(new File(
				"./conf/config.xml")));
	}

	/**
	 * will create a ServiceManger on top of a Cassandra Wrapper And use the
	 * specific configuration file for the transport layer
	 * 
	 * @param conf
	 *            The configuration of the transport layer
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public StorageManager(WrapperConfiguration conf)
			throws InternalBackEndException {
		this(WrapperType.CASSANDRA, conf);
	}

	/**
	 * will create a ServiceManger on top of a WrapperType And use the default
	 * config.xml in rootpath/conf
	 * 
	 * @param type
	 *            Type of DHTClient used
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public StorageManager(WrapperType type) throws InternalBackEndException {
		this(type, new WrapperConfiguration(new File("./conf/config.xml")));
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
	public StorageManager(WrapperType type, WrapperConfiguration conf)
			throws InternalBackEndException {
		this.type = type;
		this.wrapper = DHTWrapperFactory.createDHTWrapper(type, conf);
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Get the value of an entry column
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @return the value of the column
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public byte[] selectColumn(String tableName, String primaryKey,
			String columnName) throws ServiceManagerException,
			InternalBackEndException, IOBackEndException {
		if (!type.equals(WrapperType.CASSANDRA)) {
			throw new ServiceManagerException(
					"selectColumn is not yet supported by the DHT type: "
							+ type);
		} else {
			try {
				CassandraWrapper cassandraWrapper = (CassandraWrapper) this.wrapper;
				ColumnPath colPathName = new ColumnPath(tableName);
				colPathName.setColumn(columnName.getBytes("UTF8"));
				return cassandraWrapper.get("Mymed", primaryKey, colPathName,
						StorageManager.consistencyOnRead).getColumn()
						.getValue();
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
				throw new ServiceManagerException(
						"UnsupportedEncodingException with\n"
								+ "\t- columnFamily = " + tableName + "\n"
								+ "\t- primaryKey = " + primaryKey + "\n"
								+ "\t- columnName = " + columnName + "\n");
			}
		}
	}

	/**
	 * Get the value of a column family
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @return the value of the column
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public Map<byte[], byte[]> selectAll(String tableName, String primaryKey)
			throws ServiceManagerException, InternalBackEndException,
			IOBackEndException {
		if (!type.equals(WrapperType.CASSANDRA)) {
			throw new ServiceManagerException(
					"selectAll is not yet supported by the DHT type: " + type);
		} else {
			// read entire row
			SlicePredicate predicate = new SlicePredicate();
			SliceRange sliceRange = new SliceRange();
			sliceRange.setStart(new byte[0]);
			sliceRange.setFinish(new byte[0]);
			predicate.setSlice_range(sliceRange);

			return selectByPredicate(tableName, primaryKey, predicate);
		}
	}

	/**
	 * Get the values of a range of columns
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param columnNames
	 *            the name of the columns to return the values
	 * @return the value of the columns
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public Map<byte[], byte[]> selectRange(String tableName, String primaryKey,
			List<String> columnNames) throws ServiceManagerException,
			InternalBackEndException, IOBackEndException {
		if (!type.equals(WrapperType.CASSANDRA)) {
			throw new ServiceManagerException(
					"selectAll is not yet supported by the DHT type: " + type);
		} else {
			List<byte[]> columnNamesToByte = new ArrayList<byte[]>();
			for (String columnName : columnNames) {
				try {
					columnNamesToByte.add(columnName.getBytes("UTF8"));
				} catch (UnsupportedEncodingException e) {
					e.printStackTrace();
					throw new ServiceManagerException(
							"UnsupportedEncodingException with"
									+ "\n\t- columnFamily = " + tableName
									+ "\n\t- primaryKey = " + primaryKey
									+ "\n\t- columnFamily = " + columnName
									+ "\n");
				}
			}
			SlicePredicate predicate = new SlicePredicate();
			predicate.setColumn_names(columnNamesToByte);
			return selectByPredicate(tableName, primaryKey, predicate);
		}
	}

	/*
	 * Used by selectAll and selectRange
	 */
	private Map<byte[], byte[]> selectByPredicate(String columnFamily,
			String primaryKey, SlicePredicate predicate)
			throws InternalBackEndException, IOBackEndException {
		CassandraWrapper cassandraWrapper = (CassandraWrapper) this.wrapper;
		String keyspace = "Mymed";

		ColumnParent parent = new ColumnParent(columnFamily);
		List<ColumnOrSuperColumn> results = cassandraWrapper.get_slice(
				keyspace, primaryKey, parent, predicate, consistencyOnRead);

		Map<byte[], byte[]> slice = new HashMap<byte[], byte[]>();
		for (ColumnOrSuperColumn res : results) {
			Column column = res.column;
			slice.put(column.name, column.value);
		}

		return slice;
	}

	/**
	 * Update the value of a Simple Column
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @param value
	 *            the value updated
	 * @return true is the value is updated, false otherwise
	 * @throws ServiceManagerException
	 * @throws InternalBackEndException
	 */
	public void insertColumn(String tableName, String primaryKey,
			String columnName, byte[] value) throws ServiceManagerException,
			InternalBackEndException {
		if (!type.equals(WrapperType.CASSANDRA)) {
			throw new ServiceManagerException(
					"selectAll is not yet supported by the DHT type: " + type);
		} else {
			try {
				CassandraWrapper cassandraWrapper = (CassandraWrapper) this.wrapper;
				ColumnPath colPathName = new ColumnPath(tableName);
				colPathName.setColumn(columnName.getBytes("UTF8"));
				long timestamp = System.currentTimeMillis();
				cassandraWrapper.insert("Mymed", primaryKey, colPathName,
						value, timestamp, consistencyOnWrite);
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
				throw new ServiceManagerException(
						"UnsupportedEncodingException with\n"
								+ "\t- columnFamily = " + tableName + "\n"
								+ "\t- primaryKey = " + primaryKey + "\n"
								+ "\t- columnName = " + columnName + "\n");
			}
		}
	}

	/**
	 * Insert a new entry in the database
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param args
	 *            All columnName and the their value
	 * @return true if the entry is correctly stored, false otherwise
	 * @throws ServiceManagerException
	 * @throws InternalBackEndException
	 */
	public boolean insertSlice(String tableName, String primaryKey,
			Map<String, byte[]> args) throws ServiceManagerException,
			InternalBackEndException {
		if (!type.equals(WrapperType.CASSANDRA)) {
			throw new ServiceManagerException(
					"insertInto is not yet supported by the DHT type: " + type);
		} else {
			for (String columnName : args.keySet()) {
				this.insertColumn(tableName, primaryKey, columnName, args
						.get(columnName));
			}
			return true;
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
			throws ServiceManagerException, InternalBackEndException {
		if (!type.equals(WrapperType.CASSANDRA)) {
			throw new ServiceManagerException(
					"selectColumn is not yet supported by the DHT type: "
							+ type);
		} else {
			CassandraWrapper cassandraWrapper = (CassandraWrapper) this.wrapper;
			String keyspace = "Mymed";
			String columnFamily = tableName;
			try {
				long timestamp = System.currentTimeMillis();
				ColumnPath columnPath = new ColumnPath(columnFamily);
				columnPath.setColumn(columnName.getBytes("UTF8"));
				cassandraWrapper.remove(keyspace, key, columnPath, timestamp,
						consistencyOnWrite);
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
				throw new ServiceManagerException(
						"removeColumn failed because of a UnsupportedEncodingException");
			}
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
		if (!type.equals(WrapperType.CASSANDRA)) {
			throw new ServiceManagerException(
					"selectColumn is not yet supported by the DHT type: "
							+ type);
		} else {
			CassandraWrapper cassandraWrapper = (CassandraWrapper) this.wrapper;
			String keyspace = "Mymed";
			String columnFamily = tableName;
			long timestamp = System.currentTimeMillis();
			ColumnPath columnPath = new ColumnPath(columnFamily);
			cassandraWrapper.remove(keyspace, key, columnPath, timestamp,
					consistencyOnWrite);
		}

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
		this.wrapper.put(key, value);
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
		return this.wrapper.getValue(key);
	}

}
