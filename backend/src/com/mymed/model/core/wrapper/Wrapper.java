package com.mymed.model.core.wrapper;

import java.io.UnsupportedEncodingException;
import java.util.Map;

import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

import com.mymed.model.core.data.dht.DHTFactory;
import com.mymed.model.core.data.dht.IDHT;
import com.mymed.model.core.data.dht.IDHT.Type;
import com.mymed.model.core.data.dht.protocol.Cassandra;

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
public class Wrapper {

	/** Default ConsistencyLevel */
	public static ConsistencyLevel consistencyOnWrite = ConsistencyLevel.ANY;
	public static ConsistencyLevel consistencyOnRead = ConsistencyLevel.ONE;

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
	 */
	public boolean insertInto(String tableName, String primaryKey,
			Map<String, byte[]> args) {
		// The main database is managed by Cassandra
		Cassandra node = (Cassandra) DHTFactory.createDHT(Type.CASSANDRA);

		// keyspace is similare to the database name
		String keyspace = "Mymed";
		// columnFamily is similare to the table name
		String columnFamily = tableName;

		// Store into Cassandra the new entry
		try {
			for (String columnName : args.keySet()) {
				node.setSimpleColumn(keyspace, columnFamily, primaryKey,
						columnName.getBytes("UTF8"), args.get(columnName),
						consistencyOnWrite);
			}
			return true;
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		return false;
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
	 */
	public byte[] selectColumn(String tableName, String primaryKey,
			String columnName) throws UnsupportedEncodingException,
			InvalidRequestException, NotFoundException, UnavailableException,
			TimedOutException, TException {
		// The main database is managed by Cassandra
		Cassandra node = (Cassandra) DHTFactory.createDHT(Type.CASSANDRA);

		// keyspace is similare to the database name
		String keyspace = "Mymed";
		// columnFamily is similare to the table name
		String columnFamily = tableName;
		
		return node.getSimpleColumn(keyspace, columnFamily, primaryKey,
				columnName.getBytes("UTF8"), consistencyOnRead);
	}

	/* --------------------------------------------------------- */
	/* Common DHT operations */
	/* --------------------------------------------------------- */
	/**
	 * Common put operation
	 * The DHT type is by default Cassandra 
	 * @param key
	 * @param value
	 */
	public void put(String key, String value) {
		put(key, value, Type.CASSANDRA);
	}
	
	/**
	 * Common get operation
	 * The DHT type is by default Cassandra 
	 * @param key
	 */
	public String get(String key) {
		return get(key, Type.CASSANDRA);
	}
	/**
	 * Common put operation
	 * 
	 * @param key
	 * @param value
	 * @param DHTType
	 *            The type of DHT used for the operation
	 */
	public void put(String key, String value, Type DHTType) {
		IDHT node = DHTFactory.createDHT(DHTType);
		node.put(key, value);
	}

	/**
	 * Common get operation
	 * 
	 * @param key
	 * @param DHTType
	 *            The type of DHT used for the operation
	 */
	public String get(String key, Type DHTType) {
		IDHT node = DHTFactory.createDHT(DHTType);
		return node.get(key);
	}

}
