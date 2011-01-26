package com.mymed.model.core.wrapper;

import com.mymed.model.core.data.dht.DHTFactory;
import com.mymed.model.core.data.dht.IDHT.Type;
import com.mymed.model.core.data.dht.protocol.Cassandra;
import com.mymed.model.datastructure.User;

/**
 * This class represent the DAO pattern 
 * @author lvanni
 *
 */
public class Wrapper {

	/**
	 * no-args constructor
	 */
	public Wrapper() { }
	
	/**
	 * @see com.mymed.model.core.data.dht.protocol.Cassandra#getProfile(id)
	 */
	public User getProfile(String id){
		Cassandra node = (Cassandra) DHTFactory.createDHT(Type.CASSANDRA); // CHOSE YOUR NODE TYPE
		return node.getProfile(id);
	}
	
	/**
	 * @see com.mymed.model.core.data.dht.protocol.Cassandra#setProfile(User)
	 */
	public void setProfile(User user){
		Cassandra node = (Cassandra) DHTFactory.createDHT(Type.CASSANDRA); // CHOSE YOUR NODE TYPE
		node.setProfile(user);
	}

}
