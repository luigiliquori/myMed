/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table.facade;

import java.util.Collection;
import java.util.Date;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationProducer;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
import com.mymed.controller.core.manager.reputation.db.table.VerdictAggregation;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 *
 * @author piccolo
 */
public class DbTableAdapter {
    private UserApplicationConsumerFacade userApplicationConsumerFacade;
    private UserApplicationProducerFacade userApplicationProducerFacade;
    private VerdictFacade verdictFacade;
    private VerdictAggregationFacade verdictAggregationFacade;
    private TransactionManager transManager;
    private boolean autoCommit = true;
    
    public DbTableAdapter(CassandraWrapper w){
        userApplicationConsumerFacade = new UserApplicationConsumerFacade(w);
        userApplicationProducerFacade = new UserApplicationProducerFacade(w);
        verdictFacade = new VerdictFacade(w);
        verdictAggregationFacade = new VerdictAggregationFacade(w);
        transManager = TransactionManager.getNewInstance(w);
    }
    
    public void createTransaction(){
        if(autoCommit){
            autoCommit = false;
            transManager.createTransaction();
        }
        else{
            throw new RuntimeException("Transaction already created");
        }
    }
    
    public void commit() throws InternalBackEndException{
        transManager.commit();
        autoCommit = true;
    }
   
    public void clear(){
        transManager.clear();
        autoCommit = true;
    }
    
    public Collection<UserApplicationConsumer> getAllUserApplicationConsumer() throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<UserApplicationConsumer> allRecords = userApplicationConsumerFacade.getAllRecords();
        if(autoCommit){
            transManager.commit();
        }
        return allRecords;
    }
    
    public void persistUserApplicationConsumer(UserApplicationConsumer u) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        userApplicationConsumerFacade.persist(u);
        if(autoCommit){
            transManager.commit();
        }
    }
    
    public UserApplicationConsumer getRecordUserApplicationConsumerById(String idUser, String idApp) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        UserApplicationConsumer recordById = userApplicationConsumerFacade.getRecordById(idUser, idApp);
        if(autoCommit){
            transManager.commit();
        }
        return recordById;
    }
    
    public Collection<UserApplicationProducer> getAllRecordsUserApplicationProducer() throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<UserApplicationProducer> allRecords = userApplicationProducerFacade.getAllRecords();
        if(autoCommit){
            transManager.commit();
        }
        return allRecords;
    }
    
    public void persistUserApplicationProducer(UserApplicationProducer u) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }        
        userApplicationProducerFacade.persist(u);
        if(autoCommit){
            transManager.commit();
        }
    }
    
    public UserApplicationProducer getRecordByIdUserApplicationProducer(String idUser,String idApp) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        UserApplicationProducer recordById = userApplicationProducerFacade.getRecordById(idUser, idApp);
        if(autoCommit){
            transManager.commit();
        }
        return recordById;
    }
    
    public Collection<Verdict> getAllRecordsVerdict() throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<Verdict> allRecords = verdictFacade.getAllRecords();
        if(autoCommit){
            transManager.commit();
        }
        return allRecords;
    }
    
    public void persistVerdict(Verdict tc) throws PersistException, InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        verdictFacade.persist(tc);
        if(autoCommit){
            transManager.commit();
        }
    }
    
    public Collection<VerdictAggregation> getAllRecordsVerdictAggregation() throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<VerdictAggregation> allRecords = verdictAggregationFacade.getAllRecords();
        if(autoCommit){
            transManager.commit();
        }
        return allRecords;
    }
    
    public Collection<VerdictAggregation> getListOfAggregationByVerdict(String idVerdict) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<VerdictAggregation> allRecords = verdictAggregationFacade.getListOfAggregationByVerdict(idVerdict);
        if(autoCommit){
            transManager.commit();
        }
        return allRecords;
    }
    
    public VerdictAggregation getRecordVerdictAggregationById(String idAggregation) throws InternalBackEndException{
        if(autoCommit){
        	transManager.createTransaction();
        }
        VerdictAggregation recordById = verdictAggregationFacade.getRecordById(idAggregation);
        if(autoCommit){
        	transManager.commit();
        }
        return recordById;
    }
    
    public void persistVerdictAggregation(VerdictAggregation tc) throws InternalBackEndException, PersistException{
        if(autoCommit){
            transManager.createTransaction();
        }
        verdictAggregationFacade.persist(tc);
        if(autoCommit){
        	transManager.commit();
        }
    }
    
    public void persistVerdictToAggregation(VerdictAggregation va, Verdict v) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        verdictAggregationFacade.persist(va, v);
        if(autoCommit){
        	transManager.commit();
        }
    }
    
    public void deleteAggregation(String idAggregation) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        verdictFacade.deleteVerdictAggregationList(idAggregation);              
        verdictAggregationFacade.deleteAggregation(idAggregation);
        
        if(autoCommit){
        	transManager.commit();
        }
    }
    
    public Collection<Verdict> getVerdictsByEvaluatedToProducerAndDate(String idUserService, Date LowerBoundFilter, Date UpperBoundFilter) throws InternalBackEndException {
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<Verdict> recordsByEvaluatedToProducerAndDate = verdictFacade.getRecordsByEvaluatedToProducerAndDate(idUserService, LowerBoundFilter, UpperBoundFilter);
        if(autoCommit){
            transManager.commit();
        }
        return recordsByEvaluatedToProducerAndDate;
    }
    
    public Collection<Verdict> getVerdictsByEvaluatedToConsumerAndDate(String idUserServiceConsumer, Date LowerBoundFilter, Date UpperBoundFilter) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<Verdict> recordsByEvaluatedToConsumerAndDate = verdictFacade.getRecordsByEvaluatedToConsumerAndDate(idUserServiceConsumer, LowerBoundFilter, UpperBoundFilter);
        if(autoCommit){
            transManager.commit();
        }
        return recordsByEvaluatedToConsumerAndDate;
    }

    public Collection<Verdict> getVerdictsByIdAggregation(String idAggr) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<Verdict> verdictsByIdAggregation = verdictFacade.getVerdictsByIdAggregation(idAggr);
        if(autoCommit){
            transManager.commit();
        }
        return verdictsByIdAggregation;
    }
    
    public Collection<Verdict> getVerdictByUserReputation(String idUserReputation) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<Verdict> verdictByUserReputation = verdictFacade.getVerdictByUserReputation(idUserReputation);
        if(autoCommit){
            transManager.commit();
        }
        return verdictByUserReputation;
    }

     public Collection<Verdict> getProducerVerdictByApplication(String idApplicationReputation) throws InternalBackEndException{
         if(autoCommit){
            transManager.createTransaction();
        }
        Collection<Verdict> producerVerdictByApplication = verdictFacade.getProducerVerdictByApplication(idApplicationReputation);
         if(autoCommit){
            transManager.commit();
        }
         return producerVerdictByApplication;
    }
    
    public Collection<Verdict> getConsumerVerdictByApplication(String idApplicationReputation) throws InternalBackEndException{
        if(autoCommit){
            transManager.createTransaction();
        }
        Collection<Verdict> consumerVerdictByApplication = verdictFacade.getConsumerVerdictByApplication(idApplicationReputation);
        if(autoCommit){
            transManager.commit();
        }
        return consumerVerdictByApplication;
    }
}
