/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputation_manager;

import java.util.ArrayList;
import java.util.Collection;
import java.util.Date;

import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationProducer;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
import com.mymed.controller.core.manager.reputation.db.table.facade.DbTableAdapter;

/**
 *
 * @author piccolo
 */
public class AverageReputationAlgorithm implements ReputationAlgorithm {

    @Override
    public double computeReputation(String idUser, String idApp, boolean isProducer, DbTableAdapter adapter) {
        try{
            Collection<Verdict> expressedFeedbacks = null;
            int noOfFeedbacks =0;
        
            if(isProducer){
                UserApplicationProducer prod = adapter.getRecordByIdUserApplicationProducer(
                        idUser, idApp);
                
                if(prod != null){
                    expressedFeedbacks = adapter.getVerdictsByEvaluatedToProducerAndDate(
                            prod.getUserApplicationProducerId(), 
                            new Date(0), 
                            new Date(System.currentTimeMillis()));
                }
                else{ //we were not able to retrieve verdicts
                    expressedFeedbacks = new ArrayList<Verdict>();
                }
                noOfFeedbacks = expressedFeedbacks.size();
            }
            else{
                UserApplicationConsumer cons =  adapter.getRecordUserApplicationConsumerById(
                        idUser, idApp);
                
                if(cons != null){
                    expressedFeedbacks = adapter.getVerdictsByEvaluatedToConsumerAndDate(
                            cons.getUserApplicationConsumerId(), 
                            new Date(0) , 
                            new Date(System.currentTimeMillis()));
                }
                else{//we were not able to retrieve verdicts
                    expressedFeedbacks = new ArrayList<Verdict>();
                }
                noOfFeedbacks = expressedFeedbacks.size();
            }
            
            if(noOfFeedbacks != 0){
                double total = 0;
                for(Verdict v : expressedFeedbacks){
                    total += v.getVote();
                }
                return total/noOfFeedbacks;
            }
            else{
                return 1;
            }
        }
        catch(Exception e){
            e.printStackTrace();
        }
        return 0;
    }
    @Override
    public double computeReputation(String idAggregation, DbTableAdapter adapter) {
    	try{
            Collection<Verdict> expressedFeedbacks = adapter.getVerdictsByIdAggregation(idAggregation);
            int noOfFeedbacks = 0;
            
            double total = 0;
            for(Verdict v : expressedFeedbacks){
                total += v.getVote();
                ++noOfFeedbacks;
            }
            
            if(noOfFeedbacks != 0){
                return total/noOfFeedbacks;
            }
            else{
                return 1;
            }
    	}
    	catch(Exception e){
            e.printStackTrace();
        }
        return 0;
    }
}
