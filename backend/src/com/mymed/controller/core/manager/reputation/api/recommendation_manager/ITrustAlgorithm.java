package com.mymed.controller.core.manager.reputation.api.recommendation_manager;

import com.mymed.controller.core.manager.reputation.api.mymed_ids.IMymedRepId;
import com.mymed.model.data.reputation.MReputationBean;

/**
 * The interface a trust calculation algorithm must implement.
 * @author piccolo, neuss
 */
public interface ITrustAlgorithm {
    public MReputationBean computeReputation(IMymedRepId judged, IMymedRepId requester);
}
