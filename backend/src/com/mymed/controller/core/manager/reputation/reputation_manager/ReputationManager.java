/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputation_manager;

import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationProducer;
import com.mymed.controller.core.manager.reputation.db.table.VerdictAggregation;
import com.mymed.controller.core.manager.reputation.db.table.facade.DbTableAdapter;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 * This class contains methods allowing the MyMed system to read the 
 * reputation value of a given userservice.
 * @author piccolo
 */
public class ReputationManager {
   
    DbTableAdapter adapter;
        
    public ReputationManager(CassandraWrapper w){
        adapter = new DbTableAdapter(w);
    }
    
    public static class ReputationObject{
        private double reputation;
        private int noOfRatings;
        
        public ReputationObject(double reputation,int noOfRatings) {
            this.reputation = reputation;
            this.noOfRatings = noOfRatings;
        }

        public double getReputation() {
            return reputation;
        }
        
        public int getNoOfRatings(){
            return noOfRatings;
        }
    }
    
    /**
     * 
     * @param idUser
     * @param idRequester
     * @param idApp
     * @param isProducer
     * @return
     */
    public ReputationObject readReputation(String idUser, String idRequester, String idApp, boolean isProducer){
    	return readReputation(idUser, idApp, isProducer);
    }
    
   /**
     * This method returns reputation information about a given user using 
     * a given application and having a specified role (between Producer and Consumer)
     * @param idUser the id of the user
     * @param idApp the id of the application
     * @param isProducer a flag telling us whether the user is a producer or a consumer
     * @return reputation information about the given user
     */
    public ReputationObject readReputation(String idUser, String idApp, boolean isProducer){
        try{
            adapter.createTransaction();
            if(isProducer){
                UserApplicationProducer producer = adapter.getRecordByIdUserApplicationProducer(idUser, 
                        idApp);
                
                if(producer == null){
                    //creating a new UserApplicationProducer
                    producer = new UserApplicationProducer();
                    producer.setUserId(idUser);
                    producer.setApplicationId(idApp);
                    producer.setScore(1);
                    producer.setSize(0);
                    adapter.persistUserApplicationProducer(producer);
                }
                adapter.commit();
                return new ReputationObject(producer.getScore(),producer.getSize());
            }
            else{
                UserApplicationConsumer consumer = adapter.getRecordUserApplicationConsumerById(idUser, 
                        idApp);
                
                if(consumer == null){
                    //creating a new UserApplicationConsumer
                    consumer = new UserApplicationConsumer();
                    consumer.setUserId(idUser);
                    consumer.setApplicationId(idApp);
                    consumer.setScore(1);
                    consumer.setSize(0);
                    adapter.persistUserApplicationConsumer(consumer);
                }
                adapter.commit();
                return new ReputationObject(consumer.getScore(),consumer.getSize());
            }
        }
        catch(Exception e){
            e.printStackTrace();
            return null;
        }
    }
    
    /**
     * This method returns reputation information about a given aggregation of judgments
     * @param idAggregation the id of the aggregation of judgments
     * @return reputation information about the aggregation
     */
    public ReputationObject readAggregationReputation(String idAggregation){
    	try{
            adapter.createTransaction();
            VerdictAggregation va = adapter.getRecordVerdictAggregationById(idAggregation);
            if (va == null) {
            	adapter.clear();
            	return new ReputationObject(0,-1);
            }
            adapter.commit();
            return new ReputationObject(va.getScore(),va.getSize());
        }
        catch(Exception e){
            e.printStackTrace();
            return null;
        }
    }
}
