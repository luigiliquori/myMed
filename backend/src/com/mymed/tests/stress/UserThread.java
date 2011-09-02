package com.mymed.tests.stress;

import java.util.LinkedList;

import com.mymed.model.data.user.MUserBean;

/**
 * This is the class that implements the threads that are executed and the
 * simple sync logic for the User test
 * 
 * @author Milo Casagrande
 * 
 */
public class UserThread extends Thread {
	private final LinkedList<MUserBean> usersList = new LinkedList<MUserBean>();
	private final UserTest userTest;
	private final Thread addUser;
	private final Thread removeUser;
	private final boolean remove;

	/**
	 * Create the new user thread
	 */
	public UserThread() {
		this(true, StressTestValues.NUMBER_OF_ELEMENTS);
	}

	/**
	 * Create the new user thread, but do not perform the remove thread, only
	 * add new user to the database
	 * 
	 * @param remove
	 *            if to perform the remove thread or not
	 * @param maxElements
	 *            the maximum number of elements to create
	 */
	public UserThread(final boolean remove, final int maxElements) {
		super();
		this.remove = remove;

		userTest = new UserTest(maxElements);

		addUser = new Thread("addUser") {
			@Override
			public void run() {
				System.err.println("Starting the add user thread...");

				synchronized (usersList) {
					try {
						while (usersList.isEmpty()) {
							final MUserBean user = userTest.createUserBean();

							if (user == null) {
								interrupt();
								break;
							}

							usersList.add(user);

							try {
								userTest.createUser(user);
							} catch (final Exception ex) {
								ex.printStackTrace();
								interrupt();
								break;
							}

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

							try {
								userTest.removeUser(user);
							} catch (final Exception ex) {
								ex.printStackTrace();
								interrupt();
								break;
							}

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
