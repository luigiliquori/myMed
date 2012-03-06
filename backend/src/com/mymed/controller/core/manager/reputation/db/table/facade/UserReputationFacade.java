/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table.facade;

import java.util.Collection;

import com.mymed.controller.core.manager.reputation.db.table.UserReputation;

/**
 *
 * @author piccolo
 */
public class UserReputationFacade {
    
        /**
     * It returns all the entries of the table UserReputation
     * @return 
     */
    Collection<UserReputation> getAllRecords(){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    /**
     * it returns the entry of the table User having a 
     * specific id
     * @param appRepId
     * @return 
     */
    UserReputation getById(String appRepId){
        throw new UnsupportedOperationException("Not yet implemented");
    }
    
    public void persist(UserReputation u) throws PersistException{
        throw new UnsupportedOperationException("Not yet implemented");
    }
}
