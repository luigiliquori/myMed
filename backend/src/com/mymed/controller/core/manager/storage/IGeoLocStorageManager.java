package com.mymed.controller.core.manager.storage;

import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.storage.ExpColumnBean;
/**
 * 
 * @author iacopo
 *
 */
public interface IGeoLocStorageManager extends IStorageManager  {
	/**
	 * Can be used to select columns in both SuperCF and CF.
	 * @param tableName 	Name of the CF.
	 * @param primaryKey 	Row key.
	 * @param superColumn 	Name of the super column, can be safely put to null when you deal with a CF						
	 * @param column		Name of the column. 
	 * @return				The column value as byte array.
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public byte[] selectColumn(String tableName, String primaryKey,
			byte[] superColumn, byte[] column) throws IOBackEndException, InternalBackEndException ;
	
	/**
	 * Equals to the preceding, but apart from the name-value pair returns also the timestamp and the TTL.
	 * Useful to update the expiration time.
	 * @param tableName
	 * @param primaryKey
	 * @param superColumn
	 * @param column
	 * @return
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public ExpColumnBean selectExpiringColumn(String tableName, String primaryKey,
			byte[] superColumn, byte[] column) throws IOBackEndException, InternalBackEndException;
	
	/**
	 * Is more general then the method insertColumn of IStorageManager, the name of the column
	 * can be something different from a String. (e.g. In my application I use long values as 
	 * Column names, I cannot use string because I need that the columns was ordered.)
	 * @param tableName		Name of the CF.
	 * @param primaryKey	Row key.
	 * @param columnName	Column name.
	 * @param value			Column value.
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public void insertColumn(String tableName, String primaryKey,
			byte[] columnName, byte[] value) throws IOBackEndException, InternalBackEndException;
	
	
	/**
	 * It allows to insert an expiring column. It permits to deal with both CF and SuperCF.
	 * Timestamp could be evaluated inside the method, but I need to insert a column in two
	 * different CF, that expire at the same time. 
	 * @param tableName		Name of the CF.
	 * @param primaryKey	Row key.
	 * @param superColumn	SuperColumn name, null if insertion is done on a CF.
	 * @param columnName	Column name.
	 * @param value			Column value.
	 * @param timestamp		Timestamp in microseconds since 1 Jenuary 1970.
	 * @param expiringTime	TTL in seconds.
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public void insertExpiringColumn(String tableName, String key,
			byte[] superColumn, byte[] columnName, byte[] value,long timestamp,int expiringTime)
			throws InternalBackEndException;
	
	/**
	 * Returns a map of name-value pairs corresponding to a certain range of columns.
	 * @param tableName		Name of the CF.
	 * @param primaryKey	Row key.
	 * @param startColumn	Start column (columns are ordered).
	 * @param stopColumn	Stop column.
	 * @param maxNum		Maximum number of returned values,
	 * @return
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public Map<byte[], byte[]> selectRange(String tableName, String primaryKey,
			byte[] startColumn, byte[] stopColumn,int maxNum) throws IOBackEndException, InternalBackEndException;
	/**
	 * Select all on a list of rows.
	 * @param tableName		Name of the CF.
	 * @param primaryKeys	List of row keys.
	 * @return
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	 public Map<String,Map<byte[], byte[]>> selectAll(String tableName, List<String> primaryKeys)
			throws IOBackEndException,
			InternalBackEndException;
	
	 /**
	  * Select a range of columns on a list of rows.
	  * @param tableName	Name of the CF.
	  * @param primaryKeys	List of row keys.
	  * @param startColumn	Start column.
	  * @param stopColumn	End column.
	  * @param maxNum		Maximum number of returned values (columns).
	  * @return
	  * @throws ServiceManagerException
	  * @throws IOBackEndException
	  * @throws InternalBackEndException
	  */
	public Map<byte[],Map<byte[], byte[]>> selectSCRange(String tableName, List<String> primaryKeys,
			byte[] startColumn, byte[] stopColumn) throws IOBackEndException, InternalBackEndException; 
	
	/**
	 * Returns the first n columns of a given CF.
	 * @param tableName		Name of the CF.
	 * @param primaryKey	Row key.
	 * @param n				Number of columns (). 
	 * @return
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public Map<byte[], byte[]> getLastN(String tableName, String primaryKey,
			Integer n) throws IOBackEndException, InternalBackEndException;
	
	/**
	 * Counts the number of colums of a given row or SuperColumn.
	 * @param tableName 	Name of the CF.
	 * @param primaryKey	Row key.
	 * @param superColumn	Number of columns ().
	 * @return
	 * @throws ServiceManagerException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public int countColumns(String tableName, String primaryKey,byte[] superColumn) throws 
			IOBackEndException, InternalBackEndException;		

	/**
	 * Removes the specified column (if present) from the CF.
	 * @param tableName		Name of the CF.
	 * @param primaryKey	Row key.
	 * @param superColumn	SuperColumn name. Can be set to null, if the CF is not super.
	 * @param column		Column name. Can be set to null to delete the entire row.
	 * @throws InternalBackEndException
	 */
	public void removeColumn(String tableName, String primaryKey,
			byte[] superColumn, byte[] column) throws InternalBackEndException;

	/**
	 * Gets the columns which have name following {@value start} on the given CF row.
	 * @param tableName		ColumnFamily.
	 * @param primaryKey	Key of the row.
	 * @param start		    Starting value.
	 * @return
	 */
	public Map<byte[], byte[]> getFrom(String string, String reportId,
			byte[] array) throws IOBackEndException, InternalBackEndException;
}
