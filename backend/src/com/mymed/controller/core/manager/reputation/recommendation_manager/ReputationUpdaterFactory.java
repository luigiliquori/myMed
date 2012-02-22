/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.recommendation_manager;

import com.mymed.controller.core.manager.reputation.api.recommendation_manager.IReputationAlgorithm;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import com.mymed.controller.core.manager.reputation.db.table.ReputationEntity;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.model.data.reputation.MReputationBean;

/**
 *
 * @author piccolo
 */
public class ReputationUpdaterFactory {
    
    private CassandraWrapper wrapper;
    private IReputationAlgorithm algorithm;

    /**
     * @param algorithm the algorithm to set
     */
    public void setAlgorithm(IReputationAlgorithm algorithm) {
        this.algorithm = algorithm;
    }
    
    private static class ReputationUpdater implements Runnable{
        //private DbTableAdapter adapter;
        private IReputationAlgorithm reputationAlgorithm;
        private String repEntityId;
        private int lastN;
        
        public ReputationUpdater(IReputationAlgorithm alg,String repId, int lastN){
            reputationAlgorithm = alg;
            repEntityId = repId;
            this.lastN = lastN;
        }

        @Override
        public void run() {
            try{
                TransactionManager.getInstance().createTransaction();
                MReputationBean newInfo = reputationAlgorithm.computeReputation(repEntityId);

                ReputationEntity re = ReputationEntity.getCreating(repEntityId);
                re.setReputation(newInfo.getReputation());
                re.setNumberOfVerdicts(newInfo.getNoOfRatings());
                re.persist();
                TransactionManager.getInstance().commit();
            }catch(Exception e){e.printStackTrace();}
        }
    } 
    
    public ReputationUpdaterFactory(IReputationAlgorithm a){
        //wrapper = w;
        algorithm =a;
    }
    
    public Runnable createNewReputationUpdater(String repEntityId, int lastN){
        return new ReputationUpdater(algorithm,repEntityId, lastN);
    }
}
