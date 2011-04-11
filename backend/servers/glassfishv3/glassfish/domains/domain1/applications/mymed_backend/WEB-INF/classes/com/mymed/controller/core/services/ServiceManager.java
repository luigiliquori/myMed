package com.mymed.controller.core.services;

import com.mymed.controller.core.services.pubsub.DHTManager;
import com.mymed.controller.core.services.pubsub.IDHTManager;
import com.mymed.controller.core.services.pubsub.IProfileManager;
import com.mymed.controller.core.services.pubsub.ProfileManager;
import com.mymed.controller.core.services.reputation.IReputationSystem;
import com.mymed.controller.core.services.reputation.ReputationSystem;
import com.mymed.model.core.data.dht.IDHTClient.ClientType;
import com.mymed.model.datastructure.User;

/**
 * Manage all the request from the RequestHandler
 * @author lvanni
 * 
 */
public class ServiceManager {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** ProfileManager */
	private IProfileManager profileManager;

	/** DHTManager */
	private IDHTManager dhtManager;
	
	/** WPF3 - UNITO - Reputation Systems and Security */
	private IReputationSystem reputation;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ServiceManager(ClientType type) {
		this.dhtManager = new DHTManager();
		this.profileManager = new ProfileManager();
		this.reputation = new ReputationSystem();
	}
	
	public ServiceManager() {
		this(ClientType.CASSANDRA);
	}
	
	/* --------------------------------------------------------- */
	/* Users Profile Management  */
	/* --------------------------------------------------------- */
	public User getUserProfile(String id) {
		return this.profileManager.read(id);
	}
	
	public void setUserProfile(User user) {
		this.profileManager.create(user);
	}
	
	/* --------------------------------------------------------- */
	/* Simple DHT Operations  */
	/* --------------------------------------------------------- */
	public void put(String key, String value) {
		dhtManager.put(key, value);
	}
	
	public String get(String key){
		return dhtManager.get(key);
	}
}