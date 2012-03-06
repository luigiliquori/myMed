/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table.facade;

import java.util.ArrayList;
import java.util.Collection;
import java.util.List;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.facade.CassandraDescTable;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationProducer;
import com.mymed.controller.core.manager.reputation.primary_key.UserApplicationProducerId;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 *
 * @author piccolo
 */
public class UserApplicationProducerFacade {

    TransactionManager transManager;
    
    public UserApplicationProducerFacade(CassandraWrapper w){
        transManager = TransactionManager.getNewInstance(w);
    }
    /**
     *   it returns the collection of all entries of the table 
     * UserServiceProvider. In an SQL-like sintax, it returns the 
     * result of the 
     * 
     *   SELECT * FROM UserServiceProvider;
     * @return 
     */
    public Collection<UserApplicationProducer> getAllRecords() throws InternalBackEndException{
        List<Object> loadTable = transManager.loadTable("UserApplicationProducer");
            
        ArrayList<UserApplicationProducer> listResult = new ArrayList<UserApplicationProducer>();
            
        for(Object obj : loadTable){
            listResult.add(((UserApplicationProducer) obj));
        }
            
        return listResult;
    }

    /**
     * this methods persists the object given in input in the database 
     * (he could do an INSERT statement or an UPDATE statement depending on 
     * the fact that the object given in input has a new id or not). 
     * It throws an Exception if the persist operation fails.
     * @param u the object to be persisted
     @throws PersistException if the writing operation fails
    */
    public void persist(UserApplicationProducer u){
            CassandraDescTable descTable = CassandraDescTable.getNewInstance();
            
            //generating the primary key for the given record
            String primaryKey = descTable.generateKeyForColumnFamily(u);
            u.setUserApplicationProducerId(primaryKey);
            //we use the same primary key also for the list of user charging 
            //the given user (plus a suffix to distinguish the other role)
            u.setVerdictList_userCharging(primaryKey + "|1");
        
            transManager.insertDbTableObject(u);
    }

    
   /**
     * it returns the entry of the table UserServiceProvider 
     * having the specified id. In an SQL-like syntax, it returns 
     * the result of the 
     * 
     *   SELECT * FROM UserServiceProvider WHERE id = $id;
     * @param id
     * @return 
     */
    public UserApplicationProducer getRecordById(String idUser,String idApp) throws InternalBackEndException{
        UserApplicationProducer result = (UserApplicationProducer)transManager.loadRow("UserApplicationProducer",
                new UserApplicationProducerId(idUser, idApp).toString());
        return result;    
    }

}
