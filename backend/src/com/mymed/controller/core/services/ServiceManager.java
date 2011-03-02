package com.mymed.controller.core.services;

import com.mymed.controller.core.services.engine.reputation.IReputation;
import com.mymed.controller.core.services.engine.reputation.IReputationSystem;
import com.mymed.controller.core.services.engine.reputation.ReputationSystem;
import com.mymed.model.core.data.dht.DHTFactory;
import com.mymed.model.core.data.dht.IDHT.Type;
import com.mymed.model.core.data.dht.protocol.Cassandra;
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
	 * default constructor
	 */
	public ServiceManager() {
		this.wrapper = new Wrapper();
		this.reputation = new ReputationSystem();
	}
	
	/* --------------------------------------------------------- */
	/*                   User Profile Management                 */
	/* --------------------------------------------------------- */
	
	/*
	 
	 public User getProfile(String id) {
		Cassandra node = (Cassandra) DHTFactory.createDHT(Type.CASSANDRA);

		String columnFamily = "Users";
		try {
			// USER NAME
			String name = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "name".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER GENDER
			String gender = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "gender".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER LOCALE
			String locale = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "locale".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER UPTIME
			String updated_time = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "updated_time".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER PROFILE
			String profile = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "profile".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER PROFILE_PICTURE
			String profile_picture = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "profile_picture".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER SOCIAL_NETWORK
			String social_network = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "social_network".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			if(social_network.equals("myMed")){
				// USER EMAIL
				String email = new String(node.getSimpleColumn("Mymed",
						columnFamily, id, "email".getBytes("UTF8"),
						consistencyOnRead), "UTF8");
				// USER PASSWORD
				String password = new String(node.getSimpleColumn("Mymed",
						columnFamily, id, "password".getBytes("UTF8"),
						consistencyOnRead), "UTF8");
				return new User(id, name, gender, locale, updated_time, profile,
						profile_picture, social_network, email, password);
			} else {
				return new User(id, name, gender, locale, updated_time, profile,
						profile_picture, social_network);
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return new User();
	}
	 
	 */
	
	public User getProfile(String id){
		return null;
	}
	
	/**
	 * @see com.mymed.model.core.wrapper.Wrapper#getProfile(id)
	 * @param user
	 */
//	public User getProfile(String id){
//		return wrapper.getProfile(id);
//	}
	
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
	// TBD with UNITO
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
