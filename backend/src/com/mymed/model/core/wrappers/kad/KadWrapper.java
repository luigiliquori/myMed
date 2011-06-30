package com.mymed.model.core.wrappers.kad;

import java.io.UnsupportedEncodingException;

import com.mymed.model.core.wrappers.AbstractDHTWrapper;

import edu.lognet.experiments.current.node.kademlia.KadNode;

/**
 * 
 * @author lvanni
 *
 */
public class KadWrapper extends AbstractDHTWrapper {

	/** The Chord instance */
	private static KadWrapper singleton;
	
	private KadNode node;
	
	/**
	 * Private Constructor to create a singleton
	 */
	private KadWrapper() { }
	
	/**
	 * Chord getter
	 * @return
	 * 		The only one instance of Chord
	 */
	public static KadWrapper getInstance() {
		if (singleton == null) {
			synchronized (KadWrapper.class) {
				if (singleton == null) 
					singleton = new KadWrapper();
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
		this.node = new KadNode(ip, port);
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
	
	public KadNode getNode() {
		return node;
	}

	public void setNode(KadNode node) {
		this.node = node;
	}
}