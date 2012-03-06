/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputation_manager;

import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationProducer;
import com.mymed.controller.core.manager.reputation.db.table.facade.DbTableAdapter;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 *
 * @author piccolo
 */
public class ReputationUpdaterFactory {
    
    private CassandraWrapper wrapper;
    private ReputationAlgorithm algorithm;
    
    private static class ReputationUpdater implements Runnable{
        private DbTableAdapter adapter;
        private ReputationAlgorithm reputationAlgorithm;
        private String idUser;
        private String idApp;
        private boolean isJudgeProducer;
        
        public ReputationUpdater(CassandraWrapper w,ReputationAlgorithm alg,String idUser,String idApp,boolean isJudgeProducer){
            adapter = new DbTableAdapter(w);
            reputationAlgorithm = alg;
            this.idUser = idUser;
            this.idApp = idApp;
            this.isJudgeProducer = isJudgeProducer;
        }

        @Override
        public void run() {
            try{
                adapter.createTransaction();
                double newScore = reputationAlgorithm.computeReputation(idUser, idApp, !isJudgeProducer,adapter);

                if(isJudgeProducer){
                    UserApplicationConsumer newCons = adapter.getRecordUserApplicationConsumerById(idUser, idApp);
                    newCons.setScore(newScore);
                    adapter.persistUserApplicationConsumer(newCons);
                }
                else{
                    UserApplicationProducer newProd = adapter.getRecordByIdUserApplicationProducer(idUser, idApp);
                    newProd.setScore(newScore);
                    adapter.persistUserApplicationProducer(newProd);
                }
                adapter.commit();
            }catch(Exception e){e.printStackTrace();}
        }
    } 
    
    public ReputationUpdaterFactory(CassandraWrapper w,ReputationAlgorithm a){
        wrapper = w;
        algorithm =a;
    }
    
    public Runnable createNewReputationUpdater(String idUser,String idApp,boolean isJudgeProducer){
        return new ReputationUpdater(wrapper,algorithm,idUser,idApp,isJudgeProducer);
    }
}
