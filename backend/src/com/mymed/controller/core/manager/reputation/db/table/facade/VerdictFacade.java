/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table.facade;

import java.util.ArrayList;
import java.util.Collection;
import java.util.Date;
import java.util.List;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.facade.CassandraDescTable;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationProducer;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
import com.mymed.controller.core.manager.reputation.db.table.VerdictAggregation;
import com.mymed.controller.core.manager.reputation.primary_key.UserApplicationConsumerId;
import com.mymed.controller.core.manager.reputation.primary_key.UserApplicationProducerId;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
/**
 *
 * @author piccolo
 */
public class VerdictFacade {
    
    TransactionManager transManager;
    
    public VerdictFacade(CassandraWrapper w){
        transManager = TransactionManager.getNewInstance(w);
    }
    /**
     * it returns all the entries of the table AtomicInteraction. In an SQL-like syntax
     * 
     * SELECT * FROM AtomicInteraction;
     */
    public Collection<Verdict> getAllRecords() throws InternalBackEndException{
        List<Object> loadTable = transManager.loadTable("Verdict");
            
        ArrayList<Verdict> listResult = new ArrayList<Verdict>();
            
        for(Object obj : loadTable){
            listResult.add(((Verdict) obj));
        }
            
        return listResult;    
    }
    
    
    /**
     * this methods persists the object given in input in the database (it could 
     * do an INSERT statement or an UPDATE statement depending on the fact that 
     * the object given in input has a new id or not). It throws an Exception if 
     * the persist operation fails. If it inserts a new item, then it updates 
     * the corresponding primary key
     * @param tc the object to be persisted
     * @throws PersistException if the writing operation fails
     */
    public void persist(Verdict tc) throws PersistException, InternalBackEndException{
        CassandraDescTable descTable = CassandraDescTable.getNewInstance();

        //generating primary key
        String primaryKey = descTable.generateKeyForColumnFamily(tc);
        tc.setVerdictId(primaryKey);
        //we use the same primary key also to refer to the list of aggregations  
        //the current verdict belongs to
        tc.setVerdictAggregationList(primaryKey);

        String appId = tc.getApplicationId();
        String judgeId = tc.getJudgeId();
        String chargedId = tc.getChargedId();
        UserApplicationProducer producer = null;
        UserApplicationConsumer consumer = null;

        //inserting the verdict into the list of verdicts charging a given user
        if(tc.getIsJudgeProducer()){
             consumer = (UserApplicationConsumer) transManager.loadRow("UserApplicationConsumer", 
                                                                        new UserApplicationConsumerId(chargedId,appId).toString());

             if(consumer != null){
                consumer.setSize(consumer.getSize() + 1);
                transManager.insertIntoList("TimeOrderVerdictList", consumer.getVerdictList_userCharging(), tc.getVerdictId());
                transManager.insertDbTableObject(consumer);
             }
             else{
                 throw new PersistException("idUser not found");
             }
        }
        else{
             producer = (UserApplicationProducer) transManager.loadRow("UserApplicationProducer", 
                                                                        new UserApplicationProducerId(chargedId,appId).toString());
             if(producer != null){
                producer.setSize(producer.getSize() + 1);
                transManager.insertIntoList("TimeOrderVerdictList", producer.getVerdictList_userCharging(), 
                                        tc.getVerdictId());
                transManager.insertDbTableObject(producer);
             }
             else{
                 throw new PersistException("idUser not found");
             }
        }
        //inserting the new object into the db
        transManager.insertDbTableObject(tc);            
    }

    /**
     * this method returns the collection of all atomic interaction referred to a
     * given idUserService which is a producer (so referred to the table 
     * UserServiceProvider) whose feedback have been provided to the producer 
     * (which means that the field recipientOfTheFeedback corresponds to PRODUCER) 
     * and whose timestamp is within the considered temporal range. In an SQL-like language
     * 
     * SELECT AtomicInteraction.* FROM AtomicInteraction
     * WHERE recipientOfTheFeedback = PRODUCER AND 
     * Range(timestamp,$LowerBoundFilter,$UpperBoundFilter) 
     * AND idUserServiceProducer = $idUserService
     * @param idUserService the id of the producer (referred to the table UserServiceProvider)
     * @param LowerBoundFilter the temporal lower bound
     * @param UpperBoundFilter the temporal upper bound
     * @return the collection after having executed the query
     */
    public Collection<Verdict> getRecordsByEvaluatedToProducerAndDate(String idUserService, Date LowerBoundFilter, Date UpperBoundFilter) throws InternalBackEndException {
        
        UserApplicationProducer producer = (UserApplicationProducer) transManager.loadRow("UserApplicationProducer", 
                                                                idUserService);
        
        List<String> keys = transManager.readSuperColummFamily("TimeOrderVerdictList", 
                                                                producer.getVerdictList_userCharging());
        
        List<Object> objResults = transManager.getListOfObjectFromListOfKeys(keys, "Verdict");
        
        ArrayList<Verdict> results = new ArrayList<Verdict>();
        
        for(int i = 0; i< objResults.size();i++){
            Verdict v = (Verdict) objResults.get(i);
            if(v.getTime() > UpperBoundFilter.getTime()){
                continue;
            }
            else{
                if(v.getTime() < LowerBoundFilter.getTime()){
                    break;
                }
                else{
                    results.add(v);
                }
            }
        }
        return results;
    }

    /**
     * this method returns the collection of all atomic interaction referred to a
     * given idUserService which is a consumer (so referred to the table UserServiceConsumer) 
     * whose feedback have been provided to the consumer (which means that the field recipientOfTheFeedback
     * corresponds to CONSUMER) and whose timestamp is within the
     * considered temporal range. In an SQL-like language
     * 
     * SELECT AtomicInteraction.* FROM AtomicInteraction
     * WHERE recipientOfTheFeedback = CONSUMER 
     * AND Range(timestamp,$LowerBoundFilter,$UpperBoundFilter) 
     * AND idUserServiceConsumer = $idUserService
     * @param idUserService the id of the producer (referred to the table UserServiceProvider)
     * @param LowerBoundFilter the temporal lower bound
     * @param UpperBoundFilter the temporal upper bound
     * @return the collection after having executed the query
     */
    public Collection<Verdict> getRecordsByEvaluatedToConsumerAndDate(String idUserServiceConsumer, Date LowerBoundFilter, Date UpperBoundFilter) throws InternalBackEndException {
        
        UserApplicationConsumer consumer = (UserApplicationConsumer) transManager.loadRow("UserApplicationConsumer", 
                                                                idUserServiceConsumer);
        
        List<String> keys = transManager.readSuperColummFamily("TimeOrderVerdictList", 
                                                                consumer.getVerdictList_userCharging());
        
        List<Object> objResults = transManager.getListOfObjectFromListOfKeys(keys, "Verdict");
        
        ArrayList<Verdict> results = new ArrayList<Verdict>();
        
        for(int i = 0;i<objResults.size();i++){
            Verdict v = (Verdict) objResults.get(i);
            if(v.getTime() > UpperBoundFilter.getTime()){
                continue;
            }
            else{
                if(v.getTime() < LowerBoundFilter.getTime()){
                    break;
                }
                else{
                    results.add(v);
                }
            }
        }
        
        return results;
    }
    
    /**
     * it returns the collection of all verdicts belonging to a given aggregation
     * @param idAggr
     * @return 
     */
    public Collection<Verdict> getVerdictsByIdAggregation(String idAggregation) throws InternalBackEndException{
    	 
    	 VerdictAggregation va = (VerdictAggregation)transManager.loadRow("VerdictAggregation", idAggregation);
    	 
    	 List<String> keys = transManager.readSuperColummFamily("TimeOrderVerdictList", va.getVerdictListId());
    	 List<Object> objResults = transManager.getListOfObjectFromListOfKeys(keys, "Verdict");

    	 ArrayList<Verdict> results = new ArrayList<Verdict>();

    	 for(int i = objResults.size() - 1; i>= 0 ;i--){
    		 results.add((Verdict) objResults.get(i));
    	 }
    	 return results;
    }
    
    public void deleteVerdictAggregationList(String idAggregation) throws InternalBackEndException{
   	 
   	 VerdictAggregation va = (VerdictAggregation)transManager.loadRow("VerdictAggregation", idAggregation);
   	 
   	 List<String> keys = transManager.readSuperColummFamily("TimeOrderVerdictList", va.getVerdictListId());
   	 List<Object> objResults = transManager.getListOfObjectFromListOfKeys(keys, "Verdict");

   	 for(int i = objResults.size() - 1; i>= 0 ;i--){
   		transManager.deleteRow("VerdictAggregationList", ((Verdict) objResults.get(i)).getVerdictAggregationList());
   	 }	 
   }

    
    /**
     * it returns the verdicts associated to a given user
     * @param idUserReputation
     * @return 
     */
    public Collection<Verdict> getVerdictByUserReputation(String idUserReputation){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    /**
     * it returns the list of verdicts being the producers associated to a given application
     * @param idApplicationReputation
     * @return 
     */
    public Collection<Verdict> getProducerVerdictByApplication(String idApplicationReputation){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
        /**
     * it returns the list of verdicts being the consumer associated to a given application
     * @param idApplicationReputation
     * @return 
     */
    public Collection<Verdict> getConsumerVerdictByApplication(String idApplicationReputation){
        throw new UnsupportedOperationException("Not yet implemented");
    }
}