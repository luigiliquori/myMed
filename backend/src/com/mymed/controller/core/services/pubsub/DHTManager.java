package com.mymed.controller.core.services.pubsub;

import java.io.UnsupportedEncodingException;

import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;
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
		try {
			wrapper.put(key, value.getBytes("UTF8"));
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public String get(String key){
		try {
			return new String(wrapper.get(key), "UTF8");
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			return null;
		}
	}
	
}
