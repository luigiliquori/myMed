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
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.primary_key.UserApplicationConsumerId;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 *
 * @author piccolo
 */
public class UserApplicationConsumerFacade {
    
    TransactionManager transManager;
    
    public UserApplicationConsumerFacade(CassandraWrapper w){
        transManager = TransactionManager.getNewInstance(w);
    }
    
    /**
     *   it returns the collection of all entries of the table UserApplicationConsumer. 
     * In an SQL-like sintax, it returns the result of the query
     * 
     * SELECT * FROM UserApplicationConsumer
     */
    public Collection<UserApplicationConsumer> getAllRecords() throws InternalBackEndException{
        List<Object> loadTable = transManager.loadTable("UserApplicationConsumer");
            
        ArrayList<UserApplicationConsumer> listResult = new ArrayList<UserApplicationConsumer>();
            
        for(Object obj : loadTable){
            listResult.add(((UserApplicationConsumer) obj));
        }
            
        return listResult;
    }


    
    /**
     * this methods persists the object given in input in the database 
     * (he could do an INSERT statement or an UPDATE statement depending on 
     * the fact that the object given in input has a new id or not). 
     * It throws an Exception if the persist operation fails. If it inserts a 
     * new item, then it updates the corresponding primary key
     * @param u the object to be persisted
     */
    public void persist(UserApplicationConsumer u){
        CassandraDescTable descTable = CassandraDescTable.getNewInstance();
            
            //generating the primary key for the given record
        String primaryKey = descTable.generateKeyForColumnFamily(u);
        u.setUserApplicationConsumerId(primaryKey);
            //we use the same primary key also for the list of user charging 
            //the given user (plus a suffix to distinguish the other role)
        u.setVerdictList_userCharging(primaryKey + "|0");
                    
        transManager.insertDbTableObject(u);
    }
        
    /**
     * it returns the entry of the table UserServiceConsumer having the 
     * specified id. In an SQL-like syntax, it returns the result of the query
     * 
     * SELECT * FROM UserServiceConsumer WHERE id = $id;
     * @param id
     * @return 
     */    
    public UserApplicationConsumer getRecordById(String idUser, String idApp) throws InternalBackEndException{
        UserApplicationConsumer result = (UserApplicationConsumer)transManager.loadRow("UserApplicationConsumer", 
                new UserApplicationConsumerId(idUser, idApp).toString());
        return result;
    }
}
