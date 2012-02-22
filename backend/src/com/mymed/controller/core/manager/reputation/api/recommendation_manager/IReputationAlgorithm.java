/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.api.recommendation_manager;

import com.mymed.model.data.reputation.MReputationBean;

/**
 *The interface for a reputation computation algorithm.
 *
 * @author piccolo, neuss
 */
public interface IReputationAlgorithm {
    public MReputationBean computeReputation(String repEntityId);
}
