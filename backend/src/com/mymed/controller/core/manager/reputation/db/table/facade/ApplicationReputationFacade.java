/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table.facade;

import java.util.Collection;

import com.mymed.controller.core.manager.reputation.db.table.ApplicationReputation;

/**
 *
 * @author piccolo
 */
public class ApplicationReputationFacade {
    
    /**
     * It returns all the entries of the table ApplicationReputation
     * @return 
     */
    Collection<ApplicationReputation> getAllRecords(){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    /**
     * it returns the entry of the table ApplicationReputation having a 
     * specific id
     * @param appRepId
     * @return 
     */
    ApplicationReputation getById(String appRepId){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    public void persist(ApplicationReputation r) throws PersistException{
        throw new UnsupportedOperationException("Not yet implemented");
    }
}
