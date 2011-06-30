package com.mymed.model.core.wrappers;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;

/**
 * 
 * @author lvanni
 *
 */
public abstract class AbstractDHTWrapper {

	/**
	 * Setup and start the node
	 * @param ip
	 * @param port
	 */
	public abstract void setup(String ip, int port);
	
	
	/**
	 * Default DHT put operation
	 * @param key
	 * @param value
	 */
	public abstract void put(String key, byte[] value) throws IOBackEndException, InternalBackEndException;
	
	/**
	 * Default DHT get operation
	 * @param key
	 */
	public abstract byte[] getValue(String key) throws IOBackEndException, InternalBackEndException;
}
