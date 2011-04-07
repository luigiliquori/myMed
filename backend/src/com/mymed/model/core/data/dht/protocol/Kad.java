package com.mymed.model.core.data.dht.protocol;

import java.net.InetAddress;
import java.net.UnknownHostException;

import com.mymed.model.core.data.dht.IDHTClient;

import edu.lognet.experiments.current.node.kademlia.KadNode;

/**
 * 
 * @author lvanni
 *
 */
public class Kad extends KadNode implements IDHTClient{

	/** The Chord instance */
	private static Kad singleton;
	
	/**
	 * Private Constructor to create a singleton
	 * @param address
	 * @param port
	 */
	private Kad(String address, int port) {
		super(address, port);
	}
	
	/**
	 * Chord getter
	 * @return
	 * 		The only one instance of Chord
	 * @throws UnknownHostException 
	 */
	public static Kad getInstance() throws UnknownHostException {
		if (null == singleton) {
//			singleton = new Kad(InetAddress.getLocalHost().getHostAddress(), 4221);
			singleton = new Kad(InetAddress.getLocalHost().getHostAddress(), 0);
		}
		return singleton;
	}
	
}