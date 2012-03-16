/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.recommendation_manager;

import com.mymed.controller.core.manager.reputation.api.recommendation_manager.IReputationAlgorithm;
import com.mymed.controller.core.manager.reputation.db.table.VerdictQueries;
import java.util.ArrayList;
import com.mymed.model.data.reputation.MReputationBean;

/**
 * This implementation simply adds the votes of the most recent N verdicts and
 * averages them.
 * 
 * @author piccolo, neuss
 */
public class AverageReputationAlgorithm implements IReputationAlgorithm {
    private int lastN;      /** The number of verdicts to average over */

    /**
     * Create algorithm to average over most recent verdicts
     * @param lastN - The number of verdicts to average over
     */
    public AverageReputationAlgorithm(int lastN) {
        this.lastN = lastN;
    }

    @Override
    public MReputationBean computeReputation(String repEntityId) {
        MReputationBean bean =  new MReputationBean(1.0,0);
        try{
            ArrayList<Double> expressedFeedbacks = VerdictQueries.getVotesOfJudged(repEntityId, getLastN());
            int noOfFeedbacks =expressedFeedbacks.size();
            //System.out.println("***ef: " + expressedFeedbacks);
            if(noOfFeedbacks != 0){
                double total = 0;
                for(Double d : expressedFeedbacks){
                    total += d.doubleValue();
                }
                bean = new MReputationBean(total/noOfFeedbacks, noOfFeedbacks);
            }
        }
        catch(Exception e){
            e.printStackTrace();
        }
        return bean;
    }

    /**
     * @return read accessor for lastN
     */
    public int getLastN() {
        return lastN;
    }

    /**
     * @param write accessor for lastN
     */
    public void setLastN(int lastN) {
        this.lastN = lastN;
    }

//    @Override
//    public double computeReputation(AggregationId idAggregation, DbTableAdapter adapter) {
//    	try{
//            Collection<Verdict> expressedFeedbacks = adapter.getVerdictsByIdAggregation(idAggregation.getIdAggregation());
//            int noOfFeedbacks = 0;
//
//            double total = 0;
//            for(Verdict v : expressedFeedbacks){
//                total += v.getVote();
//                ++noOfFeedbacks;
//            }
//
//            if(noOfFeedbacks != 0){
//                return total/noOfFeedbacks;
//            }
//            else{
//                return 1;
//            }
//    	}
//    	catch(Exception e){
//            e.printStackTrace();
//        }
//        return 0;
//    }
}
