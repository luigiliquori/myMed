package com.mymed.controller.core.manager.connection;

import com.mymed.controller.core.exception.InternalBackEndException;

/**
 * Interface to have a common set of methods in case we want to implement a
 * different class of connections for mymed.
 * 
 * @author Milo Casagrande
 * 
 */
public interface IConnection {

	/**
	 * Open the connection to the database
	 * 
	 * @throws InternalBackEndException
	 *             when there is a problem opening the connection
	 */
	void open() throws InternalBackEndException;

	/**
	 * Close the connection to the database
	 */
	void close();

	/**
	 * Check if the connection is open or not
	 * 
	 * @return true if the connection is open, false otherwise
	 */
	boolean isOpen();

	/**
	 * Get the address in use with the connection
	 * 
	 * @return the address associated with the connection
	 */
	String getAddress();

	/**
	 * Get the port in use with the connection
	 * 
	 * @return the port associated with the connection
	 */
	int getPort();
}
