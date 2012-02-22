/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.recommendation_manager;

import com.mymed.controller.core.manager.reputation.api.recommendation_manager.IReputationAlgorithm;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.table.ReputationEntity;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
//import com.mymed.controller.core.manager.reputation.db.table.facade.DbTableAdapter;
import com.mymed.controller.core.manager.reputation.db.table.VerdictQueries;
import com.mymed.model.data.reputation.MReputationBean;
import java.util.Collection;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author piccolo
 */
public class WeightedReputationAlgorithm implements IReputationAlgorithm{

    private int lastN;  /** number of verdicts to average over */

    public WeightedReputationAlgorithm(int lastN){
        this.lastN = lastN;
    }

    @Override
    public MReputationBean computeReputation(String repId) {
        Collection<Verdict> verdicts;
        try {
            verdicts = VerdictQueries.getVerdictsOfJudged(repId, lastN);
        } catch (InternalBackEndException ex) {
            Logger.getLogger(WeightedReputationAlgorithm.class.getName()).log(Level.SEVERE, null, ex);
            return null;
        }
        if (verdicts == null) {
            return null;
        }
        int noOfFeedbacks = verdicts.size();


        double sumNorm = 0;
        double total = 0;
        MReputationBean answer = new MReputationBean(0.0, noOfFeedbacks);

        //System.out.println("Judging " + repId + " on " + verdicts.size() + " verdicts");
        for (Verdict v : verdicts) {
            ReputationEntity re = ReputationEntity.getCreating(v.getJudgeId());
            double judgeRep = re.getReputation();
            System.out.println("  V: " + v.getVote() + " judge " + v.getJudgeId() + " rep: " + judgeRep);
            total += judgeRep * v.getVote();
            sumNorm += judgeRep;
        }

        if (sumNorm != 0) {
            answer.setReputation(total / sumNorm);
        } else {
            answer.setReputation(0.5);
        }
        //System.out.println("    New Rep:: " + answer);
        return answer;
    }
}
