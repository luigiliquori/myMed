package com.mymed.controller.core.manager.connection;

import java.util.concurrent.ArrayBlockingQueue;
import java.util.concurrent.atomic.AtomicInteger;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.utils.MLogger;

/**
 * Implementation of a connection pool
 * 
 * @author Milo Casagrande
 * 
 */
public class ConnectionPool implements IConnectionPool {
	/*
	 * The default capacity of the pool, this is set to a power of 2: 2^7
	 */
	private static final int DEFAULT_CAP = 128;

	/*
	 * The maximum capacity of the pool, this is set to a power of 2: 2^12
	 */
	private static final int MAX_CAP = 4096;

	/*
	 * The maximum capacity of the pool, when set to zero it means limit-less
	 */
	private final int capacity;

	/*
	 * Atomic value used for concurrency access to the number of connection
	 * created
	 */
	private final AtomicInteger checkedOut = new AtomicInteger(0);

	/*
	 * The address for the connection
	 */
	private final String address;

	/*
	 * The port of the connection
	 */
	private final int port;

	/*
	 * The real pool, implemented as a list
	 */
	private final ArrayBlockingQueue<IConnection> available;

	/*
	 * Object used to sync operations
	 */
	private final Object SYNC = new Object();

	/**
	 * Create a new connection pool with a maximum capacity of 100.
	 * 
	 * @param address
	 *            the address where to connect to
	 * @param port
	 *            the port to use for the connection
	 */
	public ConnectionPool(final String address, final int port) {
		this(address, port, DEFAULT_CAP);
	}

	/**
	 * Create a new connection pool with initial capacity defined by
	 * {@code capacity}. If {@code capacity} is zero, the pool is limit-less.
	 * 
	 * @param address
	 *            the address where to connect to
	 * @param port
	 *            the port to use for the connection
	 * @param capacity
	 *            the maximum capacity of the pool
	 */
	public ConnectionPool(final String address, final int port, final int capacity) {
		if (capacity > MAX_CAP || capacity == 0) {
			this.capacity = MAX_CAP;
		} else {
			this.capacity = capacity;
		}

		this.address = address;
		this.port = port;

		available = new ArrayBlockingQueue<IConnection>(this.capacity, true);
	}

	/**
	 * Create a new connection and open it
	 * 
	 * @return the opened connection or null if there are errors opening the
	 *         connection
	 */
	private IConnection newConnection() {
		IConnection con = null;

		try {
			con = new Connection(address, port);
			con.open();
		} catch (final InternalBackEndException ex) {
			// If we cannot open the connection, we return null
			MLogger.getLog().info(ex.getMessage());
			MLogger.getDebugLog().debug(ex.getMessage(), ex.getCause());
			con = null; // NOPMD
		}

		return con;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * com.mymed.controller.core.manager.connection.IConnectionPool#checkOut()
	 */
	@Override
	public IConnection checkOut() {
		IConnection con = null;

		synchronized (SYNC) {
			if (getSize() > 0) {
				con = available.poll();

				if (!con.isOpen()) {
					// If we had a closed or null connection we try again
					MLogger.getLog().info("Got a closed connection. Retrying...");
					con = checkOut();
				}
			} else if (capacity == 0 || checkedOut.get() < capacity) {
				con = newConnection();
			}

			if (con != null) {
				checkedOut.incrementAndGet();
			}

			SYNC.notifyAll();
		}

		return con;
	}
	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * com.mymed.controller.core.manager.connection.IConnectionPool#checkIn(
	 * com.mymed.controller.core.manager.connection.IConnection)
	 */
	@Override
	public void checkIn(final IConnection connection) {
		synchronized (SYNC) {
			available.add(connection);
			checkedOut.decrementAndGet();

			SYNC.notifyAll();
		}
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * com.mymed.controller.core.manager.connection.IConnectionPool#getSize()
	 */
	@Override
	public int getSize() {
		return available.size();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * com.mymed.controller.core.manager.connection.IConnectionPool#getCapacity
	 * ()
	 */
	@Override
	public int getCapacity() {
		return capacity;
	}
}
