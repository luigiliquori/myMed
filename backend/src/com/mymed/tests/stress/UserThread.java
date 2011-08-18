package com.mymed.tests.stress;

import java.util.LinkedList;

import com.mymed.model.data.user.MUserBean;

public class UserThread extends Thread {
	private final LinkedList<MUserBean> usersList = new LinkedList<MUserBean>();
	private final UserStressTest userTest = new UserStressTest();
	private final Thread addUser;
	private final Thread removeUser;
	private boolean remove = true;

	/**
	 * Create the new user thread, but do not perform the remove thread, only
	 * add new user to the database
	 * 
	 * @param remove
	 *            if to perform the remove thread or not
	 */
	public UserThread(final boolean remove) {
		this();
		this.remove = remove;
	}

	/**
	 * Create the new user thread
	 */
	public UserThread() {
		super();

		addUser = new Thread("addUser") {
			@Override
			public void run() {
				System.err.println("Starting the add user thread...");

				synchronized (usersList) {
					try {
						while (usersList.isEmpty()) {
							final MUserBean user = userTest.createUserBean();

							if (user == null) {
								break;
							}

							usersList.add(user);
							userTest.createUser(user);

							// To execute only if we do not perform the remove
							// thread
							if (!remove) {
								usersList.pop();
							}

							usersList.notifyAll();

							if (remove) {
								usersList.wait();
							}
						}

						usersList.notifyAll();
					} catch (final Exception ex) {
						ex.printStackTrace();
					}
				}
			}
		};

		removeUser = new Thread("removeUser") {
			@Override
			public void run() {
				System.err.println("Starting remove user thread...");

				synchronized (usersList) {
					try {
						while (usersList.isEmpty()) {
							usersList.wait();
						}

						while (!usersList.isEmpty()) {
							final MUserBean user = usersList.pop();

							userTest.removeUser(user);
							usersList.notifyAll();
							usersList.wait();
						}

						usersList.notifyAll();
					} catch (final Exception ex) {
						ex.printStackTrace();
					}
				}
			}
		};
	}

	@Override
	public void run() {
		addUser.start();
		if (remove) {
			removeUser.start();
		}
	}
}
