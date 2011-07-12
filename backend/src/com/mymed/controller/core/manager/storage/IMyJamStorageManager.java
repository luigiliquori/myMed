package com.mymed.controller.core.manager.storage;

import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
/**
 * 
 * @author iacopo
 *
 */
public interface IMyJamStorageManager extends IStorageManager  {

	public byte[] selectColumnInSuperColumn(String tableName, String primaryKey,
			byte[] superColumn, byte[] column) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException;
	
	public void insertColumn(String tableName, String primaryKey,
			byte[] columnName, byte[] value) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException;
	
	public void insertExpiringSuperColumn(String tableName, String key,
			byte[] superColumn, byte[] columnName, byte[] value,int expiringTime)
			throws ServiceManagerException, InternalBackEndException;
	
	public Map<byte[], byte[]> selectRange(String tableName, String primaryKey,
			byte[] startColumn, byte[] stopColumn) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException;
	
	public Map<byte[],Map<byte[], byte[]>> selectSCRange(String tableName, List<String> primaryKeys,
			byte[] startColumn, byte[] stopColumn) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException; 
	
	public Map<byte[], byte[]> getFirstN(String tableName, String primaryKey,
			Integer n) throws ServiceManagerException,
			IOBackEndException, InternalBackEndException;
}
