package com.mymed.tests.stress;

import java.io.File;

import com.mymed.controller.core.manager.session.SessionManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.data.session.MSessionBean;

/**
 * Create all the necessary session beans in order to set up the Cassandra
 * stress test.
 * 
 * @author Milo Casagrande
 * 
 */
public class SessionTest extends StressTestValues {
	/*
	 * A static counter needed to verify how many session beans are created
	 */
	private static int counter = 0;

	private final int maxElements;

	public SessionTest(final int maxElements) {
		super();

		this.maxElements = maxElements;
	}

	public MSessionBean createSessionBean() {
		MSessionBean sessionBean = null;

		if (counter < maxElements) {
			sessionBean = new MSessionBean();

			sessionBean.setIp(IP_ADDRESS);
			sessionBean.setP2P(random.nextBoolean());
			sessionBean.setPort(random.nextInt(65000));
			sessionBean.setTimeout(System.currentTimeMillis());
			sessionBean.setUser(String.format(USR_ID, counter));
			sessionBean.setCurrentApplications(String.format(APP_LIST_ID, counter));

			counter++;
		}

		return sessionBean;
	}

	public void createSession(final MSessionBean sessionBean) throws Exception {
		try {
			final SessionManager sessionManager = new SessionManager(new StorageManager(new WrapperConfiguration(
			        new File(CONF_FILE))));
			sessionManager.create(sessionBean);
		} catch (final Exception ex) {
			throw new Exception(ex);
		}
	}

	public void removeSession(final MSessionBean sessionBean) throws Exception {
		try {
			final SessionManager sessionManager = new SessionManager(new StorageManager(new WrapperConfiguration(
			        new File(CONF_FILE))));
			sessionManager.delete(sessionBean.getUser());
		} catch (final Exception ex) {
			throw new Exception(ex);
		}
	}
}
