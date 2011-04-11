package com.mymed.model.core.wrapper;

import java.io.UnsupportedEncodingException;
import java.util.Map;

import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

import com.mymed.model.core.data.dht.DHTClientFactory;
import com.mymed.model.core.data.dht.IDHTClient;
import com.mymed.model.core.data.dht.IDHTClient.ClientType;
import com.mymed.model.core.data.dht.protocol.cassandra.Cassandra;
import com.mymed.model.core.wrapper.exception.WrapperException;

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
public class Wrapper implements IWrapper {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** The type of client used by the wrapper */
	private ClientType type;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public Wrapper() {
		this(ClientType.CASSANDRA);
	}
	
	public Wrapper(ClientType type) {
		this.type = type;
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
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
	 * @throws WrapperException
	 */
	public boolean insertInto(String tableName, String primaryKey,
			Map<String, byte[]> args) throws WrapperException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"insertInto is not yet supported by the DHT type: " + type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) DHTClientFactory.createDHTClient(type);
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;
			// Store into Cassandra the new entry
			try {
				for (String columnName : args.keySet()) {
					node.setSimpleColumn(keyspace, columnFamily, new String(args.get(primaryKey), "UTF8"),
							columnName.getBytes("UTF8"), args.get(columnName),
							consistencyOnWrite);
				}
				return true;
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
			}
			return false;
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
	 * @throws WrapperException
	 */
	public Map<byte[], byte[]> selectAll(String tableName, String primaryKey)
			throws UnsupportedEncodingException, InvalidRequestException,
			NotFoundException, UnavailableException, TimedOutException,
			TException, WrapperException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectAll is not yet supported by the DHT type: " + type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) DHTClientFactory.createDHTClient(type);
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;

			return node.getSlice(keyspace, columnFamily, primaryKey,
					consistencyOnRead);
		}
	}
	
	/**
	 * Update the value of a Simple Column
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @param value
	 * 			  the value updated
	 * @return
	 * 				true is the value is updated, false otherwise
	 * @throws WrapperException
	 * @throws UnsupportedEncodingException
	 */
	public boolean updateColumn(String tableName, String primaryKey,
			String columnName, byte[] value) throws WrapperException, UnsupportedEncodingException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectAll is not yet supported by the DHT type: " + type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) DHTClientFactory.createDHTClient(type);
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;
			
			node.setSimpleColumn(keyspace, columnFamily, primaryKey, columnName.getBytes("UTF8"), value, consistencyOnWrite);
			return true; // TO FIX: return false if something wrong happen
		}
	}
	
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
	 * @throws WrapperException
	 */
	public byte[] selectColumn(String tableName, String primaryKey,
			String columnName) throws UnsupportedEncodingException,
			InvalidRequestException, NotFoundException, UnavailableException,
			TimedOutException, TException, WrapperException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectColumn is not yet supported by the DHT type: " + type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) DHTClientFactory.createDHTClient(type);
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;

			return node.getSimpleColumn(keyspace, columnFamily, primaryKey,
					columnName.getBytes("UTF8"), consistencyOnRead);
		}
	}

	/**
	 * Remove a specific column defined by the columnName
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @throws WrapperException 
	 * @throws UnsupportedEncodingException 
	 */
	public void removeColumn(String tableName, String key, String columnName) throws WrapperException, UnsupportedEncodingException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectColumn is not yet supported by the DHT type: " + type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) DHTClientFactory.createDHTClient(type);
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;
			node.removeColumn(keyspace, columnFamily, keyspace, columnName.getBytes("UTF8"), consistencyOnWrite);
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
	 */
	public void put(String key, String value) {
		IDHTClient node = DHTClientFactory.createDHTClient(type);
		node.put(key, value);
	}

	/**
	 * Common get operation The DHT type is by default Cassandra
	 * 
	 * @param key
	 */
	public String get(String key) {
		IDHTClient node = DHTClientFactory.createDHTClient(type);
		return node.get(key);
	}

}
