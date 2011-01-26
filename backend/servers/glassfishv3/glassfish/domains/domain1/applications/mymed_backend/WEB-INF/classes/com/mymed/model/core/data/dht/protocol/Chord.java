package com.mymed.model.core.data.dht.protocol;

import com.mymed.model.core.data.dht.AbstractDHT;

/**
 * 
 * @author lvanni
 *
 */
public class Chord extends AbstractDHT {

	/**
	 * Default Constructor
	 * @param address
	 * @param port
	 */
	public Chord(String address, int port) {
		super(address, port);
		// TODO Auto-generated constructor stub
	}
	
	/* --------------------------------------------------------- */
	/*                    DHT OPERATIONS                         */
	/* --------------------------------------------------------- */
	@Override
	public void put(String key, String value) {
		// TODO Auto-generated method stub
	}

	@Override
	public String get(String key) {
		// TODO Auto-generated method stub
		return null;
	}

}
