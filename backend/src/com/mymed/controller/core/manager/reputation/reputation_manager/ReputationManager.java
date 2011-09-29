/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputation_manager;

import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationProducer;
import com.mymed.controller.core.manager.reputation.db.table.facade.DbTableAdapter;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.model.data.reputation.MReputationBean;

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
    
    /**
     * 
     * @param idUser
     * @param idRequester
     * @param idApp
     * @param isProducer
     * @return
     */
    public MReputationBean read(String idUser, String idRequester, String idApp, boolean isProducer){
    	return read(idUser, idApp, isProducer);
    }
    
   /**
     * This method returns reputation information about a given user using 
     * a given application and having a specified role (between Producer and Consumer)
     * @param idUser the id of the user
     * @param idApp the id of the application
     * @param isProducer a flag telling us whether the user is a producer or a consumer
     * @return reputation information about the given user
     */
    public MReputationBean read(String idUser, String idApp, boolean isProducer){
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
                return new MReputationBean(producer.getScore(),producer.getSize());
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
                return new MReputationBean(consumer.getScore(),consumer.getSize());
            }
        }
        catch(Exception e){
            e.printStackTrace();
            return null;
        }
    }
    

}
