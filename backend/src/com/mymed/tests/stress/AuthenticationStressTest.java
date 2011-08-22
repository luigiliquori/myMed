package com.mymed.tests.stress;

import com.mymed.controller.core.manager.authentication.AuthenticationManager;

/**
 * Perform the stress test on the authentication table. This test uses only a
 * maximum of one thread to create and insert the elements in the database.
 * There is no deletion going on here, since there is no delete method in the
 * {@link AuthenticationManager}.
 * <p>
 * This class accepts no parameters, or one parameter on the command line.
 * <p>
 * If one parameter is passed, it controls the number of elements that will be
 * created:
 * <ol>
 * <li><code>args0</code>: must be a positive integer more than 0
 * </ol>
 * 
 * @author Milo Casagrande
 * 
 */
public class AuthenticationStressTest {
	public static void main(final String[] args) {
		final AuthenticationThread authenticationThread;

		if (args.length == 1) {
			authenticationThread = new AuthenticationThread(Math.abs(Integer.parseInt(args[0])));
		} else {
			authenticationThread = new AuthenticationThread();
		}

		authenticationThread.start();
	}
}
