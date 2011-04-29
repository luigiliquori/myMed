package com.mymed.model.core.wrapper;

import java.io.UnsupportedEncodingException;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

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
public interface IWrapper {

	/** Default ConsistencyLevel */
	public static ConsistencyLevel consistencyOnWrite = ConsistencyLevel.ONE;
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
			Map<String, byte[]> args) throws WrapperException;

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
			TimedOutException, TException, WrapperException;

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
	 * @throws WrapperException
	 * @throws UnsupportedEncodingException
	 */
	public boolean updateColumn(String tableName, String primaryKey,
			String columnName, byte[] value) throws WrapperException,
			UnsupportedEncodingException;

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
	 * @throws UnsupportedEncodingException
	 * @throws InvalidRequestException
	 * @throws NotFoundException
	 * @throws UnavailableException
	 * @throws TimedOutException
	 * @throws TException
	 * @throws WrapperException
	 */
	public Map<byte[], byte[]> selectAll(String tableName, String primaryKey)
			throws UnsupportedEncodingException, InvalidRequestException,
			NotFoundException, UnavailableException, TimedOutException,
			TException, WrapperException;

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
	 * @throws UnsupportedEncodingException
	 * @throws InvalidRequestException
	 * @throws NotFoundException
	 * @throws UnavailableException
	 * @throws TimedOutException
	 * @throws TException
	 * @throws WrapperException
	 */
	public Map<byte[], byte[]> selectRange(String tableName, String primaryKey,
			List<String> columnNames) throws UnsupportedEncodingException,
			InvalidRequestException, NotFoundException, UnavailableException,
			TimedOutException, TException, WrapperException;

	/**
	 * Remove a specific column defined by the columnName
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @throws WrapperException
	 */
	public void removeColumn(String tableName, String key, String columnName)
			throws WrapperException, UnsupportedEncodingException;

	/**
	 * Common put operation
	 * 
	 * @param key
	 * @param value
	 * @param DHTType
	 *            The type of DHT used for the operation
	 */
	public void put(String key, byte[] value);

	/**
	 * Common get operation
	 * 
	 * @param key
	 * @param DHTType
	 *            The type of DHT used for the operation
	 */
	public byte[] get(String key);

}
