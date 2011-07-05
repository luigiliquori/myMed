package com.mymed.model.core.wrappers.cassandra;

public interface IWrapper {
	/**
	 * The port number to use for interacting with Cassandra
	 */
	int PORT_NUMBER = 9160;

	/**
	 * The name of the default keyspace we use for Cassandra
	 */
	String KEYSPACE = "Mymed";
}
