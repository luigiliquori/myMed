package com.mymed.model.core.data.dht.factory;

import org.apache.thrift.transport.TTransportException;

import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;

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
	public void put(String key, byte[] value) throws IOBackEndException, InternalBackEndException;
	
	/**
	 * Default DHT get operation
	 * @param key
	 */
	public byte[] getValue(String key) throws IOBackEndException, InternalBackEndException;
}
