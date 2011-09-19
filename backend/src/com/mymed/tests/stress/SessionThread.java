package com.mymed.tests.stress;

import java.util.LinkedList;
import java.util.List;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.session.MSessionBean;
import com.mymed.utils.MLogger;

/**
 * This is the class that implements the threads that are executed and the
 * simple sync logic for the Session test
 * 
 * @author Milo Casagrande
 * 
 */
public class SessionThread extends Thread {
	private final List<MSessionBean> sessionList = new LinkedList<MSessionBean>();
	private final SessionTest sessionTest;
	private final Thread addSession;
	private final Thread removeSession;
	private final boolean remove;

	/**
	 * Create the new session thread
	 */
	public SessionThread() {
		this(true, StressTestValues.NUMBER_OF_ELEMENTS);
	}

	/**
	 * Create the new session thread, but do not perform the remove thread, only
	 * add new session to the database
	 * 
	 * @param remove
	 *            if to perform the remove thread or not
	 * @param maxElements
	 *            the maximum number of elements to create
	 */
	public SessionThread(final boolean remove, final int maxElements) {
		super();
		this.remove = remove;

		sessionTest = new SessionTest(maxElements);

		addSession = new Thread("addSession") {
			@Override
			public void run() {
				MLogger.getLog().info("Starting thread '{}'", getName());

				synchronized (sessionList) {
					try {
						while (sessionList.isEmpty()) {
							final MSessionBean sessionBean = sessionTest.createSessionBean();

							if (sessionBean == null) {
								break;
							}

							sessionList.add(sessionBean);

							try {
								sessionTest.createSession(sessionBean);
							} catch (final InternalBackEndException ex) {
								interrupt();
								MLogger.getLog().info("Thread '{}' interrupted", getName());
								break;
							}

							// To execute only if we do not perform the remove
							// thread
							if (!remove) {
								((LinkedList<MSessionBean>) sessionList).pop();
							}

							sessionList.notifyAll();

							if (remove) {
								sessionList.wait();
							}
						}

						sessionList.notifyAll();
					} catch (final Exception ex) {
						MLogger.getDebugLog().debug("Error in thread '{}'", getName(), ex.getCause());
					}
				}
			}
		};

		removeSession = new Thread() {
			@Override
			public void run() {
				MLogger.getLog().info("Starting thread '{}'", getName());

				synchronized (sessionList) {
					try {
						while (sessionList.isEmpty()) {
							sessionList.wait();
						}

						while (!sessionList.isEmpty()) {
							final MSessionBean sessionBean = ((LinkedList<MSessionBean>) sessionList).pop();

							try {
								sessionTest.removeSession(sessionBean);
							} catch (final Exception ex) {
								interrupt();
								MLogger.getLog().info("Thread '{}' interrupted", getName());
								break;
							}

							sessionList.notifyAll();
							sessionList.wait();
						}

						sessionList.notifyAll();
					} catch (final Exception ex) {
						MLogger.getDebugLog().debug("Error in thread '{}'", getName(), ex.getCause());
					}
				}
			}
		};
	}
	@Override
	public void run() {
		addSession.start();
		if (remove) {
			removeSession.start();
		}
	}
}
