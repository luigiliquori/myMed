package com.mymed.model.core.data.dht.factory;

/**
 * Represent what a DhT must do
 * @author lvanni
 */
public interface IDHTClient {
	/**
	 * Different type of DHT
	 */
	public enum ClientType {
	    CASSANDRA, CHORD, KAD, SYNAPSE
	}

	/**
	 * Default DHT put operation
	 * @param key
	 * @param value
	 */
	public void put(String key, byte[] value);
	
	/**
	 * Default DHT get operation
	 * @param key
	 */
	public byte[] getValue(String key);
}
