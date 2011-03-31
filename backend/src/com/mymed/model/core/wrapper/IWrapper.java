package com.mymed.model.core.wrapper;

import java.io.UnsupportedEncodingException;
import java.util.Map;

import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

import com.mymed.model.core.data.dht.IDHT.Type;

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
public interface IWrapper {
	
	/** Default ConsistencyLevel */
	public static ConsistencyLevel consistencyOnWrite = ConsistencyLevel.ANY;
	public static ConsistencyLevel consistencyOnRead = ConsistencyLevel.ONE;
	
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
			Map<String, byte[]> args);
	
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
			TimedOutException, TException;
	
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
	 */
	public Map<byte[], byte[]> selectAll(String tableName, String primaryKey) throws UnsupportedEncodingException,
			InvalidRequestException, NotFoundException, UnavailableException,
			TimedOutException, TException;
	
	/**
	 * Common put operation
	 * 
	 * @param key
	 * @param value
	 * @param DHTType
	 *            The type of DHT used for the operation
	 */
	public void put(String key, String value, Type DHTType);
	
	/**
	 * Common get operation
	 * 
	 * @param key
	 * @param DHTType
	 *            The type of DHT used for the operation
	 */
	public String get(String key, Type DHTType);

}
