/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.facade;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import java.lang.reflect.InvocationTargetException;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 *
 * @author piccolo
 */
public class TransactionManager {
    
    private CassandraQueryFacade queryFacade;    
    private int numberOfAllowedConcurrentTransactions;
    private CassandraWrapper wrapper;
    
    public final int MAX_NUMBER_OF_CONCURRENT_TRANSACTIONS = 1;
            
    private static TransactionManager instance;
    
    public static TransactionManager getNewInstance(CassandraWrapper w){
        if(instance == null){
            instance = new TransactionManager(w);
        }
        return instance;
    }
    
    
    private TransactionManager(CassandraWrapper w){
        wrapper =w;
        
        numberOfAllowedConcurrentTransactions = MAX_NUMBER_OF_CONCURRENT_TRANSACTIONS;
    }
    
    public synchronized void createTransaction(){
        while(numberOfAllowedConcurrentTransactions == 0){
            try {
                wait();
            } catch (InterruptedException ex) {
                Logger.getLogger(TransactionManager.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
        numberOfAllowedConcurrentTransactions--;
        queryFacade = CassandraQueryFacade.getNewInstance(wrapper);
    }
    
    public synchronized void insertDbTableObject(Object objToInsert) {
        if(queryFacade == null){
            throw new RuntimeException("you have to start a transaction");
        }
        else{
            queryFacade.insertDbTableObject(objToInsert);
        }
    }
    
    public synchronized void insertIntoList(String superColumnFamilyName, String keyOfList, String cfKey) {
        if(queryFacade == null){
            throw new RuntimeException("you have to start a transaction");
        }
        else{
            queryFacade.insertIntoList(superColumnFamilyName, keyOfList, cfKey);
        }
    }
    
    public synchronized Object loadRow(String nameOfColumnFamily, String key) throws InternalBackEndException{
        if(queryFacade == null){
            throw new RuntimeException("you have to start a transaction");
        }
        else{
            return queryFacade.loadRow(nameOfColumnFamily, key);
        }
    }
    
    public synchronized List<String> readSuperColummFamily(String nameOfSuperColumnFamily,String key) throws InternalBackEndException {
        if(queryFacade == null){
            throw new RuntimeException("you have to start a transaction");
        }
        else{
            return queryFacade.readSuperColummFamily(nameOfSuperColumnFamily, key);
        }
    }
    
    public synchronized List<Object> loadTable(String columnFamilyName) throws InternalBackEndException{
        if(queryFacade == null){
            throw new RuntimeException("you have to start a transaction");
        }
        else{
            return queryFacade.loadTable(columnFamilyName);
        }
    }
    
    public List<Object> getListOfObjectFromListOfKeys(List<String> keys, String columnFamilyName) throws InternalBackEndException{
        if(queryFacade == null){
            throw new RuntimeException("you have to start a transaction");
        }
        else{
            return queryFacade.getListOfObjectFromListOfKeys(keys, columnFamilyName);
        }
    }
    
    public synchronized void commit() throws InternalBackEndException{
        if(queryFacade == null){
            throw new RuntimeException("you have to start a transaction");
        }
        else{
            if(!queryFacade.isEmpty()){
                queryFacade.flush();
            }
            numberOfAllowedConcurrentTransactions++;
            queryFacade = null;
            notifyAll();
        }
    }
    
    public synchronized void clear(){
        if(queryFacade == null){
            throw new RuntimeException("you have to start a transaction");
        }
        else{
            queryFacade.clear();
	    numberOfAllowedConcurrentTransactions++;
            queryFacade = null;
            notifyAll();
        }
    }
    
    public synchronized void deleteRow(String nameOfColumnFamily, String key) throws InternalBackEndException {
    	if(queryFacade == null){
            throw new RuntimeException("you have to start a transaction");
        }
        else{
		queryFacade.deleteRow(nameOfColumnFamily, key);	
        }
    }
        
}
