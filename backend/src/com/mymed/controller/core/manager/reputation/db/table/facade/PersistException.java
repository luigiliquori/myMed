/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table.facade;

/**
 *
 * @author piccolo
 */
public class PersistException extends Exception {

    private String message;
    public PersistException(String m){
        message = m;
    }
    
    public PersistException(Exception e) {
        message = e.getClass().getCanonicalName() + ": " + e.getMessage();
    }
    
    @Override
    public String getMessage(){
        return message;
    }
    
}
