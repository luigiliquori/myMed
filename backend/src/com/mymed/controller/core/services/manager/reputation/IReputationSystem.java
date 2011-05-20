package com.mymed.controller.core.services.manager.reputation;

import com.mymed.model.data.MTransactionBean;
import com.mymed.model.data.MUserBean;

/**
 * Represent the interface of the reputation system for the Service manager
 * @author lvanni
 *
 */
public interface IReputationSystem {
	
	/**
	 * Get the reputation of the user related to the servceID
	 * @param user
	 * @param ServiceID
	 * @return
	 */
	public IReputation getReputation(MUserBean user, String serviceID);
	
	/**
	 * Notify the reputation system for a new transaction
	 * @param transaction
	 */
	public void notifyTransaction(MTransactionBean transaction);
}
