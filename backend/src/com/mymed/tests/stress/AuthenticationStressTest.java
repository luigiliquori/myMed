/*
 * Copyright 2012 INRIA 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
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
		AuthenticationThread authenticationThread;

		if (args.length == 1) {
			authenticationThread = new AuthenticationThread(Math.abs(Integer.parseInt(args[0])));
		} else {
			authenticationThread = new AuthenticationThread();
		}

		authenticationThread.start();
	}
}
