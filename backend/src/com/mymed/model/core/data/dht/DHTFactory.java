package com.mymed.model.core.data.dht;

import com.mymed.model.core.data.dht.protocol.Cassandra;

/**
 * Represent a factory of no-sql database node
 * @author lvanni
 *
 */
public class DHTFactory {
	
	/**
	 * Return a node of type IDHT
	 * @param source
	 * 		Class of the node
	 * @return IDHT 
	 * 		No-sql database node
	 */
	public static IDHT createDHT(IDHT.Type type){
		switch (type) {
		case CASSANDRA:
			return Cassandra.getInstance();
		case CHORD:
		case KAD:
		default:
			return null;
		}
	}
}
