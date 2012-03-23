/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.api.recommendation_manager;

import com.mymed.controller.core.manager.reputation.api.mymed_ids.IMymedRepId;
import com.mymed.model.data.reputation.MReputationBean;

/**
 * The interface a TrustManager must implement.
 *
 * @author neuss, piccolo
 */
public interface ITrustManager {

    /**
     * A reputation is calculated according to the given algorithm.
     * @param repEntityId - The entity whole reputation to read.
     * @param alg - The algorithm to use to calculate the reputation.
     * @return
     */

    /**
     * One entity's trust of another is calculated according to the given algorithm.
     * @param judged - The entity being evaluated
     * @param requester - The entity requesting the evaluation.
     * @param alg - The algorithm to use.
     * @return - the trust calculated.
     */
    public MReputationBean calculate(IMymedRepId judged, IMymedRepId requester, ITrustAlgorithm alg);

}
