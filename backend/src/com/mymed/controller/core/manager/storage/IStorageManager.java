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

import java.io.UnsupportedEncodingException;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.thrift.ConsistencyLevel;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;

/**
 * This class represent the DAO pattern: Access to data varies depending on the source of the data. Access to persistent
 * storage, such as to a database, varies greatly depending on the type of storage Use a Data Access Object (DAO) to
 * abstract and encapsulate all access to the data source. The DAO manages the connection with the data source to obtain
 * and store data.
 * 
 * @author lvanni
 */
public interface IStorageManager {

    /** Default ConsistencyLevel */
    ConsistencyLevel consistencyOnWrite = ConsistencyLevel.LOCAL_QUORUM;
    ConsistencyLevel consistencyOnRead = ConsistencyLevel.LOCAL_QUORUM;

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
    void insertSlice(String tableName, String primaryKey, Map<String, byte[]> args) throws IOBackEndException,
                    InternalBackEndException;

    /**
     * Insert a new entry in the database
     * 
     * @param superTableName
     *            the name of the Table/SuperColumnFamily
     * @param key
     *            the ID of the entry
     * @param superKey
     *            the ID of the entry in the SuperColumnFamily
     * @param args
     *            All columnName and the their value
     * @throws ServiceManagerException
     * @throws InternalBackEndException
     */
    void insertSuperSlice(final String superTableName, final String key, final String superKey,
                    final Map<String, byte[]> args) throws IOBackEndException, InternalBackEndException;

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
    byte[] selectColumn(String tableName, String primaryKey, String columnName) throws IOBackEndException,
                    InternalBackEndException;

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
     */
    void insertColumn(String tableName, String primaryKey, String columnName, byte[] value)
                    throws InternalBackEndException;

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
     * @throws InternalBackEndException
     */
    void insertSuperColumn(String tableName, String key, String superColumn, String columnName, byte[] value)
                    throws InternalBackEndException;

    /**
     * Get the value of a Column family
     * 
     * @param tableName:  the name of the Table/ColumnFamily
     * @param primaryKey: the ID of the entry
     * @return List of columns
     */
    Map<byte[], byte[]> selectAll(String tableName, String primaryKey) throws IOBackEndException,
                    InternalBackEndException;
    
    Map<String, String> selectAllStr(String tableName, String primaryKey) throws IOBackEndException,
    InternalBackEndException;
    
    /**
     * Get the value of a Column family
     * 
     * @param tableName: the name of the Table/ColumnFamily
     * @param primaryKey: the ID of the entry
     * @param start: column name
     * @param count: limit
     * @param reversed: order
     * @return List of columns
     * @see http://wiki.apache.org/cassandra/API#SliceRange
     * @return the value of the column
     */
    Map<byte[], byte[]> selectAll(String tableName, String primaryKey, String start, int count, Boolean reversed) throws IOBackEndException,
                    InternalBackEndException, UnsupportedEncodingException;

    /**
     * Get the list of values of a Super Column Family
     * 
     * @param tableName
     * @param key
     * @return
     * @throws InternalBackEndException
     * @throws IOBackEndException
     */
    List<Map<byte[], byte[]>> selectList(final String tableName, final String key) throws InternalBackEndException,
                    IOBackEndException;
    
    /**
     * Get the list of values of a Super Column Family
     * 
     * @param tableName
     * @param key
     * @param start
     * @param count 
     * @param reversed
     * @see http://wiki.apache.org/cassandra/API#SliceRange
     * @return List of supercolumns
     * @throws InternalBackEndException
     * @throws IOBackEndException
     */
    List<Map<byte[], byte[]>> selectList(final String tableName, final String key, String start, int count, Boolean reversed) throws InternalBackEndException,
                    IOBackEndException, UnsupportedEncodingException;

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
     */
    Map<byte[], byte[]> selectRange(String tableName, String primaryKey, List<String> columnNames)
                    throws IOBackEndException, InternalBackEndException;

    /**
     * Count columns in record
     * 
     * @param key
     * @param parent
     * @return
     * @throws InternalBackEndException
     */
    public int countColumns(final String tableName, final String key) throws InternalBackEndException;

    /**
     * Remove a specific column defined by the columnName
     * 
     * @param keyspace
     * @param columnFamily
     * @param key
     * @param columnName
     */
    void removeColumn(String tableName, String key, String columnName) throws IOBackEndException,
                    InternalBackEndException;

    /**
     * @param tableName
     * @param key
     * @param superColumnName
     * @throws InternalBackEndException
     */
    public void removeSuperColumn(final String tableName, final String key, final String superColumnName)
                    throws InternalBackEndException;

    /**
     * Remove an entry in the columnFamily
     * 
     * @param keyspace
     * @param columnFamily
     * @param key
     * @throws InternalBackEndException
     */
    void removeAll(String tableName, String key) throws InternalBackEndException;
    
    public Map<String, Map<String, String>> multiSelectList(final String tableName, final List<String> keys,
    		final String start, final String finish) 
    			throws IOBackEndException, InternalBackEndException, UnsupportedEncodingException;
    
    /** Decode a byte array into a string, using the default encoding */
    public String decode(byte[] value);
    
    /** Decode a byte array into a string, using the default encoding */
    public byte[] encode(String value);
    
    public byte[] encode(int value);

}
