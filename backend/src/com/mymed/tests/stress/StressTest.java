package com.mymed.tests.stress;

/**
 * Class to run all the stress test on Cassandra. It is just necessary to run
 * this class.
 * 
 * @author Milo Casagrande
 * 
 */
public class StressTest {
	public static void main(final String[] args) {
		// Perform the stress test, all in their own thread
		// final UserThread userThread = new UserThread(false);
		final SessionThread sessionThread = new SessionThread();

		// userThread.start();
		sessionThread.start();
	}
}
