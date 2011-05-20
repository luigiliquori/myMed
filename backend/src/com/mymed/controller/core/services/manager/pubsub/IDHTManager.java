package com.mymed.controller.core.services.manager.pubsub;

import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;

/**
 * @author lvanni
 */
public interface IDHTManager {
	
	// COMMON DHT OPERATIONS
	public void put(String key, String value)  throws IOBackEndException, InternalBackEndException;
	public String get(String key)  throws IOBackEndException, InternalBackEndException;
	
}
