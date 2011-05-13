package com.mymed.model.core.data.dht.protocol;

import java.io.UnsupportedEncodingException;

import com.mymed.model.core.data.dht.factory.IDHTClient;

import edu.lognet.experiments.current.node.chord.ChordNode;

/**
 * 
 * @author lvanni
 *
 */
public class Chord extends AbstractDHTClient implements IDHTClient {
	
	/** The Chord instance */
	private static Chord singleton;
	
	/** The ChordNode */
	private ChordNode node;
	
	/**
	 * Private Constructor to create a singleton
	 */
	private Chord() { }
	
	/**
	 * Chord getter
	 * @return
	 * 		The only one instance of Chord
	 */
	public static Chord getInstance() {
		if (singleton == null) {
			synchronized (Chord.class) {
				if (singleton == null) 
					singleton = new Chord();
			}
		}
		return singleton;
	}
	
	/**
	 * Setup and start the node
	 * @param host
	 * @param port
	 */
	public void setup(String ip, int port){
		this.node = new ChordNode(ip, port);
	}
	
	/**
	 * Default DHT put operation
	 * @param key
	 * @param value
	 */
	public void put(String key, byte[] value){
		try {
			node.put(key, new String(value, "UTF8"));
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	/**
	 * Default DHT get operation
	 * @param key
	 */
	public byte[] getValue(String key){
		try {
			return (node.get(key)).getBytes("UTF8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
			return null;
		}
	}
	
	public ChordNode getNode() {
		return node;
	}

	public void setNode(ChordNode node) {
		this.node = node;
	}
	
}
