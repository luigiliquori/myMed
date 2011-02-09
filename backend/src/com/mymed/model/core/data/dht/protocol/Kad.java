package com.mymed.model.core.data.dht.protocol;

import com.mymed.model.core.data.dht.AbstractDHT;

import edu.lognet.experiments.current.node.kademlia.KadNode;

/**
 * 
 * @author lvanni
 *
 */
public class Kad extends AbstractDHT{

	/** The Chord instance */
	private static Kad singleton;
	
	/** ChordNode from jSynapse edu.lognet.experiments.current.ChordNode */
	private KadNode node;

	/**
	 * Private Constructor to create a singleton
	 * @param address
	 * @param port
	 */
	private Kad(String address, int port) {
		super(address, port);
		// use the jSynapse Chord implementation
		this.node = new KadNode(address, port);
		// TODO Join to an existing network using the tracker
	}
	
	/**
	 * Chord getter
	 * @return
	 * 		The only one instance of Chord
	 */
	public static Kad getInstance(String address, int port) {
		if (null == singleton) {
			singleton = new Kad(address, port);
		}
		return singleton;
	}
	
	/* --------------------------------------------------------- */
	/*                    DHT OPERATIONS                         */
	/* --------------------------------------------------------- */
	@Override
	public void put(String key, String value) {
		node.put(key, value);
	}

	@Override
	public String get(String key) {
		return node.get(key);
	}

}
