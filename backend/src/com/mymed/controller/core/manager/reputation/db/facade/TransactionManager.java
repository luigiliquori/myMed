/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.facade;

import java.util.List;

import org.apache.cassandra.thrift.ColumnOrSuperColumn;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.table.ICassandraPersistable;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.utils.MLogger;

/**
 * TODO: Correggere gli errori di INRIA da questo TransactionManager
 * @author piccolo
 */
public class TransactionManager extends StorageManager {

  private CassandraQueryFacade queryFacade = null;
  private int numberOfAllowedConcurrentTransactions;
  private final CassandraWrapper wrapper;

  public final int MAX_NUMBER_OF_CONCURRENT_TRANSACTIONS = 1;

  private static TransactionManager instance;

  public static TransactionManager getNewInstance(final CassandraWrapper w) {
    if (instance == null) {
      instance = new TransactionManager(w);
    }
    return instance;
  }

  public static TransactionManager getInstance() throws InternalBackEndException {
      final WrapperConfiguration conf = new WrapperConfiguration(CONFIG_FILE);

      final String listenAddress = conf.getCassandraListenAddress();
      final int thriftPort = conf.getThriftPort();
      
      final CassandraWrapper wrapper = new CassandraWrapper(listenAddress, thriftPort);
    if (instance == null) {
      instance = new TransactionManager(wrapper);
    }
    return instance;
  }



  private TransactionManager(final CassandraWrapper w) {
    wrapper = w;

    numberOfAllowedConcurrentTransactions = MAX_NUMBER_OF_CONCURRENT_TRANSACTIONS;
  }

  public synchronized void createTransaction() {
    while (numberOfAllowedConcurrentTransactions == 0) {
      try {
        wait();
      } catch (final InterruptedException ex) {
        MLogger.getDebugLog().debug("Thread in waiting state interrupted", ex.getCause());
      }
    }

    numberOfAllowedConcurrentTransactions--;
    checkQueryFacade();
    // queryFacade = CassandraQueryFacade.getNewInstance(wrapper);
  }

  public synchronized void insertDbTableObject(final ICassandraPersistable objToInsert) {
    // if (queryFacade == null) {
    // throw new RuntimeException("you have to start a transaction");
    // } else {
    // }
    checkQueryFacade();
    queryFacade.insertDbTableObject(objToInsert);
  }

  public synchronized void insertIntoList(final String superColumnFamilyName, final String keyOfList, final String cfKey) {
    if (queryFacade == null) {
      throw new RuntimeException("you have to start a transaction");
    } else {
      queryFacade.insertIntoList(superColumnFamilyName, keyOfList, cfKey);
    }
  }

  public synchronized Object loadRow(final String nameOfColumnFamily, final String key) throws InternalBackEndException {
    // if (queryFacade == null) {
    // throw new RuntimeException("you have to start a transaction");
    // } else {
    // }
    checkQueryFacade();
    return queryFacade.loadRow(nameOfColumnFamily, key);
  }

//  public synchronized List<String> readSuperColummFamily(final String nameOfSuperColumnFamily, final String key)
//      throws InternalBackEndException {
//    // if (queryFacade == null) {
//    // throw new RuntimeException("you have to start a transaction");
//    // } else {
//    // }
//    checkQueryFacade();
//    return queryFacade.readSuperColummFamily(nameOfSuperColumnFamily, key);
//  }

  public synchronized List<Object> loadTable(final String columnFamilyName) throws InternalBackEndException {
    // if (queryFacade == null) {
    // throw new RuntimeException("you have to start a transaction");
    // } else {
    // }
    checkQueryFacade();
    return queryFacade.loadTable(columnFamilyName);
  }

  public List<Object> getListOfObjectFromListOfKeys(final List<String> keys, final String columnFamilyName)
      throws InternalBackEndException {
    // if (queryFacade == null) {
    // throw new RuntimeException("you have to start a transaction");
    // } else {
    // }
    checkQueryFacade();
    return queryFacade.getListOfObjectFromListOfKeys(keys, columnFamilyName);
  }

  public synchronized void commit() throws InternalBackEndException {
    // if (queryFacade == null) {
    // throw new RuntimeException("you have to start a transaction");
    // } else {
    // }
    checkQueryFacade();

    if (!queryFacade.isEmpty()) {
      queryFacade.flush();
    }

    numberOfAllowedConcurrentTransactions++;
    queryFacade = null;

    notifyAll();
  }

  public synchronized void clear() {
    // if (queryFacade == null) {
    // throw new RuntimeException("you have to start a transaction");
    // } else {
    // }
    checkQueryFacade();
    queryFacade.clear();
    numberOfAllowedConcurrentTransactions++;
    queryFacade = null;

    notifyAll();
  }

  public synchronized void deleteRow(final String nameOfColumnFamily, final String key) throws InternalBackEndException {
    // if (queryFacade == null) {
    // throw new RuntimeException("you have to start a transaction");
    // } else {
    checkQueryFacade();
    queryFacade.deleteRow(nameOfColumnFamily, key);
    // }

    notifyAll();
  }

  private synchronized void checkQueryFacade() {
    if (queryFacade == null) {
      MLogger.getDebugLog().debug("QueryFaced was null, retrieving a new one");
      queryFacade = CassandraQueryFacade.getInstance(wrapper);
    }

    notifyAll();
  }

  // TODO: temporary hack, pass-thru to CassandraQueryFacade
  public void associateVerdictWithEntity(String judged, java.util.UUID id, double value) throws InternalBackEndException {
      checkQueryFacade();
      queryFacade.insertIntoVerdictListOrdered(judged, id, value);
  }

  public List <ColumnOrSuperColumn> getVerdictListColumns(String repEntityId, int numResults) throws InternalBackEndException {
      checkQueryFacade();
      return queryFacade.getVerdictListColumns(repEntityId, numResults);
  }

  public List <ColumnOrSuperColumn> getVerdictListColumns(String repEntityId) throws InternalBackEndException {
      checkQueryFacade();
      return queryFacade.getVerdictListColumns(repEntityId);
  }

  // for testing
  public void truncate(String columnFamily) throws InternalBackEndException {
      checkQueryFacade();
      queryFacade.wrapper.truncate(columnFamily);
  }
}
