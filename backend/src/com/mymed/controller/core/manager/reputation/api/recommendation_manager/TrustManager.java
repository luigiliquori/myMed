/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.api.recommendation_manager;

import com.mymed.controller.core.manager.reputation.api.mymed_ids.IMymedRepId;
import com.mymed.model.data.reputation.MReputationBean;

/**
 *
 * @author piccolo
 */
public class TrustManager implements ITrustManager {

    @Override
    public MReputationBean calculate(IMymedRepId judged, IMymedRepId requester, ITrustAlgorithm alg) {
        throw new UnsupportedOperationException("Not supported yet.");
    }

   
}
