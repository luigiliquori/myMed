package com.mymed.model.core.data.dht;

/**
 * Represent what a DhT must do
 * @author lvanni
 */
public interface IDHT {
	
	/**
	 * Different type of DHT
	 */
	public enum Type {
	    CASSANDRA, CHORD, KAD
	}

	/**
	 * Default DHT put operation
	 * @param key
	 * @param value
	 */
	public void put(String key, String value);
	
	/**
	 * Default DHT get operation
	 * @param key
	 */
	public String get(String key);
}
