package com.mymed.controller.core.services.manager.pubsub;

import java.io.UnsupportedEncodingException;

import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;
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
	public DHTManager(ClientType type) throws InternalBackEndException {
		this.wrapper = new Wrapper(type);
	}
	
	public DHTManager() throws InternalBackEndException {
		this(ClientType.CASSANDRA);
	}
	

	/* --------------------------------------------------------- */
	/* Put_Get */
	/* --------------------------------------------------------- */
	public void put(String key, String value) throws IOBackEndException, InternalBackEndException {
		try {
			wrapper.put(key, value.getBytes("UTF8"));
		} catch (UnsupportedEncodingException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
	
	public String get(String key) throws IOBackEndException, InternalBackEndException {
		try {
			return new String(wrapper.get(key), "UTF8");
		} catch (UnsupportedEncodingException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
	
}
