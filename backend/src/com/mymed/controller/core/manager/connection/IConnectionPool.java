package com.mymed.controller.core.manager.connection;

/**
 * Common interface for the implementation of a pool
 * 
 * @author Milo Casagrande
 * 
 */
public interface IConnectionPool {

	/**
	 * Get a connection from the pool
	 * 
	 * @return the first available connection
	 */
	IConnection checkOut();

	/**
	 * Give a connection back to the pool
	 * 
	 * @param connection
	 *            the connection to return to the pool
	 */
	void checkIn(IConnection connection);

	/**
	 * Get the size of the pool, or how many connections are in it
	 * 
	 * @return the actual size of the pool
	 */
	int getSize();

	/**
	 * Get the maximum capacity of the pool
	 * 
	 * @return the maximum capacity of the pool
	 */
	int getCapacity();
}
