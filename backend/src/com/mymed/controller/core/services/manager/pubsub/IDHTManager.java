package com.mymed.controller.core.services.manager.pubsub;

import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;

/**
 * The common DHT operations
 * @author lvanni
 */
public interface IDHTManager {
	
	/**
	 * 
	 * @param key
	 * @param value
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public void put(String key, String value)  throws IOBackEndException, InternalBackEndException;
	
	/**
	 * 
	 * @param key
	 * @return
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public String get(String key)  throws IOBackEndException, InternalBackEndException;
	
}
