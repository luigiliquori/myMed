package com.mymed.model.core.factory;


/**
 * Represent what a DhT must do
 * @author lvanni
 */
public interface IDHTWrapperFactory {
	/**
	 * Different type of DHT
	 */
	public enum WrapperType {
	    CASSANDRA, CHORD, KAD, SYNAPSE
	}
}
