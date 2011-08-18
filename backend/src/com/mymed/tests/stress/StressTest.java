package com.mymed.tests.stress;

import java.util.LinkedList;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.user.MUserBean;

public class StressTest {
	public static void main(final String[] args) throws InternalBackEndException {
		final LinkedList<MUserBean> usersList = new LinkedList<MUserBean>();
		final UserStressTest userTest = new UserStressTest();

		try {
			final Thread addUsers = new Thread("addUsers") {
				@Override
				public void run() {
					synchronized (usersList) {
						while (usersList.isEmpty()) {
							final MUserBean user = userTest.createUserBean();

							if (user == null) {
								break;
							}

							usersList.add(user);
							userTest.createUser(user);
							// usersList.pop();

							usersList.notifyAll();
							try {
								usersList.wait();
							} catch (final InterruptedException ex) {
								ex.printStackTrace();
							}
						}

						usersList.notifyAll();
					}
				}
			};

			final Thread removeUsers = new Thread("removeUsers") {
				@Override
				public void run() {
					synchronized (usersList) {
						while (usersList.isEmpty()) {
							try {
								usersList.wait();
							} catch (final InterruptedException ex) {
								ex.printStackTrace();
							}
						}

						while (!usersList.isEmpty()) {
							final MUserBean user = usersList.pop();
							userTest.removeUser(user);
							usersList.notifyAll();
							try {
								usersList.wait();
							} catch (final InterruptedException ex) {
								ex.printStackTrace();
							}
						}

						System.err.println("End of Games!");
						usersList.notifyAll();
					}
				}
			};

			System.err.println("Starting thread " + addUsers.getName());
			addUsers.start();
			System.err.println("Starting thread " + removeUsers.getName());
			removeUsers.start();
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}
}
