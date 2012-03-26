/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.api.recommendation_manager;

import java.util.logging.Level;
import java.util.logging.Logger;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import com.mymed.controller.core.manager.reputation.db.table.ReputationEntity;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.IMymedRepId;
import com.mymed.model.data.reputation.MReputationBean;
import java.util.ArrayList;
import java.util.Iterator;

/**
 * This class contains methods allowing the application level to store feedbacks
 * concerning an atomic interaction between producer and consumer.
 * 
 * @author piccolo
 */
public class VerdictManager {

    private IReputationAlgorithm reputationAlgorithm;
    private boolean calculateImmediately;

    /**
     * Create a VerdictManager with the given algorithm.  Reputations will
     * be calculated as each new Verdict is given.
     * @param alg
     */
    public VerdictManager(IReputationAlgorithm alg) {
        reputationAlgorithm = alg;
        calculateImmediately = true;
    }

    /**
     * Create a VerdictManager which will store the verdict, but will not recalculate
     * any reputations.
     */
    public VerdictManager() {
        reputationAlgorithm = null;
        this.calculateImmediately = false;
    }

    /**
     * Add a new verdict with a weight and a comment
     *
     * @param judge - The entity voting.
     * @param judged - The entity being evaluated.
     * @param vote - The vote.
     * @param weight - The weight of the verdict.
     * @param comment - judge's comment on the vote.
     * @return - true iff successful
     */
    public boolean update(IMymedRepId judge, IMymedRepId judged, final double vote, final double weight, String comment) {
        try {
            // insure all ReputationEntities exist
            ArrayList<String> judgedIds = judged.getEntityIds();
            for (Iterator i = judgedIds.iterator(); i.hasNext();) {
                ReputationEntity.getCreating((String) i.next());
            }

            // create and persist verdict
            Verdict v = new Verdict(judge.getPrimaryId(), judged.getPrimaryId(), vote, weight);
            v.setComment(comment);
            v.persist();
            TransactionManager.getInstance().commit();
            // associate verdict with entities
            for (Iterator i = judgedIds.iterator(); i.hasNext();) {
                String id = (String) i.next();
                TransactionManager.getInstance().associateVerdictWithEntity(id, v.getTimeUuid(), v.getVote());
                calculate(id, 100);
            }
        } catch (final InternalBackEndException ex) {
            Logger.getLogger(VerdictManager.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }

        return true;
    }

    /**
     * Overload with default weight 1.0
     */
    public boolean update(IMymedRepId judge, IMymedRepId judged, final double vote, String comment) {
        return update(judge, judged, vote, 1.0, comment);
    }

    /**
     * Overload with default empty comment
     */
    public boolean update(IMymedRepId judge, IMymedRepId judged, final double vote, double weight) {
        return update(judge, judged, vote, weight, "");
    }

    /**
     * Overload with default weight 1.0 and default empty comment
     */
    public boolean update(IMymedRepId judge, IMymedRepId judged, final double vote) {
        return update(judge, judged, vote, 1.0, "");
    }

    /**
     * Get the reputation algorithm being used.
     * @return the reputationAlgorithm
     */
    public IReputationAlgorithm getReputationAlgorithm() {
        return reputationAlgorithm;
    }

    /**
     * Set the reputation algorithm to use (in the case that reputations
     * are being recalculated as new verdicts are stored)
     * @param alg
     */
    public void setReputationAlgorithm(IReputationAlgorithm alg) {
        reputationAlgorithm = alg;
    }

    private void calculate(String repEntityId, int lastN) {
        try {
            TransactionManager.getInstance().createTransaction();
            ReputationEntity re = ReputationEntity.getCreating(repEntityId);
            if (isCalculateImmediately() && reputationAlgorithm != null) {
                MReputationBean newInfo = reputationAlgorithm.computeReputation(repEntityId);
                re.setReputation(newInfo.getReputation());
                re.setNumberOfVerdicts(newInfo.getNoOfRatings());
                re.setDirty(false);
            } else {
                re.setDirty(true);
            }
            re.persist();
            TransactionManager.getInstance().commit();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    /**
     * See if the reputations are being calculated upon receipt of verdicts.
     * @return true if reputation is being calculated
     */
    public boolean isCalculateImmediately() {
        return calculateImmediately;
    }

    /**
     * Set whether or not reputations are being calculated unpn receipt of verdicts.
     * @param If set to true, reputations will be recalculated when new verdicts are added.
     */
    public void setCalculateImmediately(boolean calculateImmediately) {
        this.calculateImmediately = calculateImmediately;
    }
}
