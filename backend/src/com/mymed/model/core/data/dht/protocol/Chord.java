package com.mymed.model.core.data.dht.protocol;

import java.net.InetAddress;
import java.net.UnknownHostException;

import com.mymed.model.core.data.dht.IDHTClient;

import edu.lognet.experiments.current.node.chord.ChordNode;

/**
 * 
 * @author lvanni
 *
 */
public class Chord extends ChordNode implements IDHTClient {
	
	/** The Chord instance */
	private static Chord singleton;
	
	/**
	 * Private Constructor to create a singleton
	 * @param address
	 * @param port
	 */
	private Chord(String address, int port) {
		super(address, port);
	}
	
	/**
	 * Chord getter
	 * @return
	 * 		The only one instance of Chord
	 * @throws UnknownHostException 
	 */
	public static Chord getInstance() throws UnknownHostException {
		if (null == singleton) {
//			singleton = new Chord(InetAddress.getLocalHost().getHostAddress(), 4211);
			singleton = new Chord(InetAddress.getLocalHost().getHostAddress(), 0);
		}
		return singleton;
	}
	
}
