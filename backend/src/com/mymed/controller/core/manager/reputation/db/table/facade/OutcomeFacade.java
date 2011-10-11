/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table.facade;

import java.util.Collection;

import com.mymed.controller.core.manager.reputation.db.table.Outcome;

/**
 *
 * @author piccolo
 */
public class OutcomeFacade {
    
    /**
     * it returns all entries of the table Outcome
     * @return 
     */
    public Collection<Outcome> getAllRecords(){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    /**
     * it returns the outcome having the specified id
     * @param idOutcome
     * @return 
     */
    public Outcome getById(String idOutcome){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    /**
     * it returns the outcome list associated to a given entry of the table ApplicationReputation
     * @param idAppRep
     * @return 
     */
    public Collection<Outcome> getOutcomeListByApplicationReputation(String idAppRep){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    /**
     * it returns the outcome list associated to a given entry of the table UserReputation
     * @param idUserReputation
     * @return 
     */
    public Collection<Outcome> getOutcomeListByUserReputation(String idUserReputation){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    /**
     * it returns the outcome list associated to a given entry of the table VerdictAggregation
     * @param idVerdictAggregation
     * @return 
     */
    public Collection<Outcome> getOutcomeListByVerdictAggretation(String idVerdictAggregation){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    /**
     * it returns the outcome list associated to a given entry of the table UserApplicationConsumer
     * @param idUserApplicationConsumer
     * @return 
     */
    public Collection<Outcome> getOutcomeListByUserApplicationConsumer(String idUserApplicationConsumer){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    /**
     * it returns the outcome list associated to a given entry in the table UserApplicationProducer
     * @param idUserApplicationProducer
     * @return 
     */
    public Collection<Outcome> getOutcomeListByUserApplicationProducer(String idUserApplicationProducer){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    public void persist(Outcome o) throws PersistException{
        throw new UnsupportedOperationException("Not yet implemented");
    }
}
