package com.mymed.controller.core.manager.connection;

import java.net.InetAddress;

import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransportException;

/**
 * 
 * @author Milo Casagrande
 * 
 */
public class Connection {

	// Used for the hash code
	private static final int PRIME = 31;

	// The default port to use if nothing is passed as arguments
	private static final int DEFAULT_PORT = 9160;

	// If the connection is open or not
	private boolean isOpen = false;
	// If this connection is in use or not
	private boolean isUsed = false;

	private final String address;
	private final int port;

	private final TSocket socket;

	private final TFramedTransport thriftTransport;

	public Connection() throws Exception {
		this(InetAddress.getLocalHost().getHostAddress(), DEFAULT_PORT);
	}

	/**
	 * Create a new connection with the provided address and port
	 * 
	 * @param address
	 *            the IP address to be associated with this connection
	 * @param port
	 *            the port to use with this connection
	 * @throws Exception
	 */
	public Connection(final String address, final int port) throws Exception {
		if (address == null && (port == 0 || port < 0)) {
			throw new Exception("Address or port must be a valid value.");
		} else {
			socket = new TSocket(address, port);
			thriftTransport = new TFramedTransport(socket);
		}

		this.address = address;
		this.port = port;

	}
	/**
	 * Get the address to use for the connection
	 * 
	 * @return the IP address associated with the connection
	 */
	public String getAddress() {
		return address;
	}

	/**
	 * Get the port to use with the connection
	 * 
	 * @return the port associated with the connection
	 */
	public int getPort() {
		return port;
	}

	/**
	 * Check if the connection is open or not
	 * 
	 * @return true if is open, false otherwise
	 */
	public boolean isOpen() {
		return isOpen;
	}

	/**
	 * Set whether the connection is open or not
	 * 
	 * @param isOpen
	 */
	public void setOpen(final boolean isOpen) {
		this.isOpen = isOpen;
	}

	/**
	 * Check if the connection is in use or not
	 * 
	 * @return true if in use, false otherwise
	 */
	public boolean isUsed() {
		return isUsed;
	}

	/**
	 * Set whether the connection is in use or not
	 * 
	 * @param isUsed
	 */
	public void setUsed(final boolean isUsed) {
		this.isUsed = isUsed;
	}

	public void open() throws TTransportException {
		if (!thriftTransport.isOpen()) {
			thriftTransport.open();
			setOpen(true);
		}
	}

	public void close() {
		if (thriftTransport.isOpen()) {
			thriftTransport.close();
			setOpen(false);
		}
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		int result = 1;
		result = PRIME * result + (address == null ? 0 : address.hashCode());
		result = PRIME * result + port;
		result = PRIME * result + (socket == null ? 0 : socket.hashCode());
		return result;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#equals(java.lang.Object)
	 */
	@Override
	public boolean equals(final Object object) {

		boolean equal = false;

		if (this == object) {
			equal = true;
		} else if (object instanceof Connection) {
			final Connection comparable = (Connection) object;
			equal = true;

			if (address == null && comparable.getAddress() != null) {
				equal &= false;
			} else {
				equal &= address.equals(comparable.getAddress());
			}

			equal &= port == comparable.getPort();
		}

		return equal;
	}
}
