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

/**
 * Perform the stress test on the session table. This test uses only a maximum
 * of two threads: one to create and insert the elements in the database, the
 * other to delete the entry from the database.
 * <p>
 * This class accepts no parameters, or two parameters on the command line. If
 * only one parameter, or more than two parameters are passed, it will be
 * treated as no parameters at all are passed.
 * <p>
 * If two parameters are passed, the first one controls whatever to run also the
 * deletion thread, the second one controls the number of elements that will be
 * created:
 * <ol>
 * <li><code>args0</code>: must be a boolean value, <code>true</code> or
 * <code>false</code></li>
 * <li><code>args1</code>: must be a positive integer more than 0
 * </ol>
 * 
 * @author Milo Casagrande
 * 
 */
public class SessionStressTest {

	public static void main(final String[] args) {
		SessionThread sessionThread;

		if (args.length == 0) {
			sessionThread = new SessionThread();
		} else {
			sessionThread = new SessionThread(Boolean.valueOf(args[0]), Math.abs(Integer.parseInt(args[1])));
		}

		sessionThread.start();
	}
}
