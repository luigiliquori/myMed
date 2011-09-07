package com.mymed.controller.core.manager.connection;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.concurrent.ConcurrentLinkedQueue;

/**
 * 
 * @author Milo Casagrande
 * 
 */
public class ConnectionPool {

	private static final ConnectionPool INSTANCE = new ConnectionPool();
	private static final int INTIAL_CAP = 100;

	private final List<IConnection> availableConnections;
	private final List<IConnection> usedConnections;
	private final ConcurrentLinkedQueue<IConnection> availableQueue;
	private final ConcurrentLinkedQueue<IConnection> usedQueue;

	private ConnectionPool() {
		availableQueue = new ConcurrentLinkedQueue<IConnection>();
		usedQueue = new ConcurrentLinkedQueue<IConnection>();
		availableConnections = Collections.synchronizedList(new ArrayList<IConnection>(INTIAL_CAP));
		usedConnections = Collections.synchronizedList(new ArrayList<IConnection>(INTIAL_CAP));
	}

	public ConnectionPool getInstance() {
		return INSTANCE;
	}

	public void checkOut(final String ip, final int port) {
	}

	/**
	 * Return a connection to the pool
	 * 
	 * @param connection
	 *            the connection to return
	 */
	public void checkIn(final Connection connection) {

		usedConnections.remove(connection);

		synchronized (usedConnections) {
			final int index = usedConnections.indexOf(connection);

			final IConnection con;

			if (index >= 0) {
				con = usedConnections.get(index);
				((Connection) con).setUsed(false);

				synchronized (availableConnections) {
					((ArrayList<IConnection>) availableConnections).remove(con);
				}
			}

			usedConnections.notifyAll();
			availableConnections.notifyAll();
		}

	}

	public static void main(final String[] args) {
		for (int i = 0; i < 100; i++) {
			try {
				final Connection con = new Connection("127.0.0.1", 9160);
				System.err.println(con.hashCode());
			} catch (final Exception ex) {
				// TODO Auto-generated catch block
				ex.printStackTrace();
			}
		}
	}
}
