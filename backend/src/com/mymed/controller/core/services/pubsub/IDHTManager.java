package com.mymed.controller.core.services.pubsub;

/**
 * @author lvanni
 */
public interface IDHTManager {
	
	// COMMON DHT OPERATIONS
	public void put(String key, String value);
	public String get(String key);
	
}
