package com.mymed.controller.core.services;

import com.mymed.controller.core.services.engine.reputation.IReputation;
import com.mymed.controller.core.services.engine.reputation.IReputationSystem;
import com.mymed.controller.core.services.engine.reputation.ReputationSystem;
import com.mymed.model.core.wrapper.Wrapper;
import com.mymed.model.datastructure.Transaction;
import com.mymed.model.datastructure.User;

/**
 * Manage all the request from the RequestHandler
 * @author lvanni
 *
 */
public class ServiceManager {
	
	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	/** DAO pattern */
	private Wrapper wrapper;
	
	/** WPF3 - UNITO - Reputation Systems and Security */
	private IReputationSystem reputation;
	
	
	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
	/**
	 * no-args constructor
	 */
	public ServiceManager() {
		this.wrapper = new Wrapper();
		this.reputation = new ReputationSystem();
	}
	
	/* --------------------------------------------------------- */
	/*                   User Profile Management                 */
	/* --------------------------------------------------------- */
	/**
	 * @see com.mymed.model.core.wrapper.Wrapper#getProfile(id)
	 * @param user
	 */
	public User getProfile(String id){
		return wrapper.getProfile(id);
	}
	
	/**
	 * @see com.mymed.model.core.wrapper.Wrapper#setProfile(User)
	 * @param user
	 */
	public void setProfile(User user) {
		wrapper.setProfile(user);
	}
	
	/* --------------------------------------------------------- */
	/*               Reputation System Management                */
	/* --------------------------------------------------------- */
	/**
	 * @see com.mymed.controller.core.services.engine.reputation.ReputationSystem#getReputation(User, String)
	 * @param user
	 * @param serviceID
	 */
	public IReputation getReputation(User user, String serviceID){
		return reputation.getReputation(user, serviceID);
	}
	
	/**
	 * @see com.mymed.controller.core.services.engine.reputation.ReputationSystem#notifyTransaction(Transaction)
	 * @param transaction
	 */
	public void notifyTransaction(Transaction transaction){
		reputation.notifyTransaction(transaction);
	}
}
