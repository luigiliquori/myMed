package com.mymed.model.core.data.dht.protocol;

/**
 * 
 * @author lvanni
 *
 */
public abstract class AbstractDHTClient {

	/**
	 * Setup and start the node
	 * @param ip
	 * @param port
	 */
	public abstract void setup(String ip, int port);
}
