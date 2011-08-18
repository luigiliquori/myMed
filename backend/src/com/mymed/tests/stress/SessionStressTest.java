package com.mymed.tests.stress;

import java.io.File;
import java.util.LinkedList;
import java.util.List;

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
public class SessionStressTest extends StressTestValues {
	/*
	 * A static counter needed to verify how many session beans are created
	 */
	private static int counter = 0;

	private final List<MSessionBean> sessionList = new LinkedList<MSessionBean>();
	private SessionManager sessionManager;

	public SessionStressTest() {
		super();

		try {
			sessionManager = new SessionManager(new StorageManager(new WrapperConfiguration(new File(CONF_FILE))));
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}

	public MSessionBean createSessionBean() {
		MSessionBean sessionBean = null;

		if (!(counter >= NUMBER_OF_ELEMENTS)) {
			System.err.println("Creating: " + counter);
			sessionBean = new MSessionBean();

			sessionBean.setIp(IP_ADDRESS);
			sessionBean.setP2P(random.nextBoolean());
			sessionBean.setPort(random.nextInt(65000));
			sessionBean.setTimeout(System.currentTimeMillis());
			sessionBean.setUser(String.format(USR_ID, counter));
			sessionBean.setCurrentApplications(String.format(APP_LIST_ID, counter));

			sessionList.add(sessionBean);
		}

		counter++;

		return sessionBean;
	}

	public void createSession(final MSessionBean sessionBean) {
		try {
			sessionManager.create(sessionBean);
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}

	public void removeSession(final MSessionBean sessionBean) {
		try {
			sessionManager.delete(sessionBean.getUser());
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}
}
