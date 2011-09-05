package com.mymed.controller.core.manager.connection;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

/**
 * 
 * @author Milo Casagrande
 * 
 */
public class ConnectionPool {

	private static final ConnectionPool INSTANCE = new ConnectionPool();

	private final List<Connection> availableConnections = Collections.synchronizedList(new ArrayList<Connection>());
	private final List<Connection> usedConnections = Collections.synchronizedList(new ArrayList<Connection>());

	private ConnectionPool() {
	}

	public ConnectionPool getInstance() {
		return INSTANCE;
	}

	public ConnectionPool(final String address, final int port) throws Exception {
		availableConnections.add(getConnection(address, port));
	}

	private Connection getConnection(final String address, final int port) throws Exception {
		return new Connection(address, port);
	}

	public void checkOut() {
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

			final Connection con;

			if (index >= 0) {
				con = usedConnections.get(index);
				con.setUsed(false);

				synchronized (availableConnections) {
					((ArrayList<Connection>) availableConnections).remove(con);
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
