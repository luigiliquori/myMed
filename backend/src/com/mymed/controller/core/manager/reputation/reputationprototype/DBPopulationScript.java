/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputationprototype;

import java.io.File;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.globals.Constants;
import com.mymed.controller.core.manager.reputation.reputation_manager.InteractionManager;
import com.mymed.controller.core.manager.reputation.reputation_manager.ReputationManager;
import com.mymed.controller.core.manager.reputation.reputation_manager.ReputationManager.ReputationObject;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 *
 * @author piccolo
 */
public class DBPopulationScript {
    
    static int getRandom(int maxVal){
        double val = Math.random() * maxVal;
        
        return (int) Math.ceil(val);
    }

    public static void main(String a[]) throws InternalBackEndException{
        final WrapperConfiguration conf = new WrapperConfiguration(new File(Constants.CONFIGURATION_FILE_PATH));
	    
        final String listenAddress = conf.getCassandraListenAddress();
        final int thriftPort = conf.getThriftPort();

        System.out.println("Connection information:");
        System.out.println("\tListen Address: " + listenAddress);
        System.out.println("\tThrift Port   : " + thriftPort);
        System.out.println("\n");

        final CassandraWrapper wrapper = new CassandraWrapper(listenAddress, thriftPort);

        System.out.println("Opening Cassandra connection...");
//        wrapper.open();

        wrapper.set_keyspace(Constants.KEYSPACE);

        InteractionManager interactionManager = new InteractionManager(wrapper);
        ReputationManager reputationManager = new ReputationManager(wrapper);
        

        //reputazione semplice
        for(int i=0;i<100;i++){
            interactionManager.updateReputation("app1", "utente" + String.valueOf(getRandom(10)), 
                    Math.random() < 0.5, "utente" + String.valueOf(getRandom(10)), Math.random());
        }
        
        for(int i =1;i<10;i++){
            System.out.println("Reputazione utente" + i + "come produttore");
            ReputationObject readReputation = reputationManager.readReputation("utente" + String.valueOf(i), "app1", true);
            System.out.println("giudizi: " + readReputation.getNoOfRatings() + "reputazione: " + readReputation.getReputation());
            
            System.out.println("Reputazione utente" + i + "come consumatore");
            readReputation = reputationManager.readReputation("utente" + String.valueOf(i), "app1", false);
            System.out.println("giudizi: " + readReputation.getNoOfRatings() + " reputazione: " + readReputation.getReputation());       
        }
        
        String[] aggregations = new String[10];
        
        for(int i=0;i<10;i++){
            aggregations[i] = interactionManager.createAggregation(String.valueOf(i));
            for(int j=0;j<20;j++){
                interactionManager.updateAggregation(aggregations[i],"app1", "utente" + String.valueOf(getRandom(10)), 
                        Math.random() < 0.5, "utente" + String.valueOf(getRandom(10)), Math.random());
            }
        }
        
        for(int i=0;i<10;i++){
            System.out.println("Reputazione aggregazione " + i);
            ReputationObject readAggregationReputation = reputationManager.readAggregationReputation(aggregations[i]);
            
            System.out.println("giudizi: " + readAggregationReputation.getNoOfRatings() + " reputazione: " + readAggregationReputation.getReputation());
        }
 
    }
    
}
