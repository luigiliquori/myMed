/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.api.recommendation_manager;

import com.mymed.controller.core.manager.reputation.api.mymed_ids.IMymedRepId;
import com.mymed.model.data.reputation.MReputationBean;

/**
 * The interface for a ReputationManager
 * @author piccolo, neuss
 */
public interface IReputationManager {

    /**
     * A reputation is read from the cache.
     * @param repEntityId - The entity whole reputation to read.
     * @return
     */
    public MReputationBean read(IMymedRepId repEntityId);

    /**
     * A reputation is calculated according to the given algorithm.
     * @param repEntityId - The entity whole reputation to read.
     * @param alg - The algorithm to use to calculate the reputation.
     * @return
     */
    public MReputationBean calculate(IMymedRepId repEntityId, IReputationAlgorithm alg);

}
