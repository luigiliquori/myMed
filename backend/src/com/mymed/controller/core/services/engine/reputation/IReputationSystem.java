package com.mymed.controller.core.services.engine.reputation;

import com.mymed.model.datastructure.Transaction;
import com.mymed.model.datastructure.User;

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
	public IReputation getReputation(User user, String serviceID);
	
	/**
	 * Notify the reputation system for a new transaction
	 * @param transaction
	 */
	public void notifyTransaction(Transaction transaction);
}
