package com.mymed.controller.core.services.pubsub;

import com.mymed.model.core.data.dht.IDHTClient.ClientType;
import com.mymed.model.core.wrapper.Wrapper;

/**
 * 
 * @author lvanni
 *
 */
public class DHTManager implements IDHTManager {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** DAO pattern */
	private Wrapper wrapper;
	
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public DHTManager(ClientType type) {
		this.wrapper = new Wrapper(type);
	}
	
	public DHTManager() {
		this(ClientType.CASSANDRA);
	}
	

	/* --------------------------------------------------------- */
	/* Put_Get */
	/* --------------------------------------------------------- */
	public void put(String key, String value){
		wrapper.put(key, value);
	}
	
	public String get(String key){
		return wrapper.get(key);
	}
	
}
