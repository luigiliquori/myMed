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
public class SessionStressTest extends StressTestValues implements Runnable {

	private final List<MSessionBean> sessionList = new LinkedList<MSessionBean>();

	private StorageManager storageManager;
	protected SessionManager sessionManager;

	public SessionStressTest() {
		MSessionBean sessionBean;

		for (int i = 0; i < NUMBER_OF_ELEMENTS; i++) {
			sessionBean = new MSessionBean();

			sessionBean.setId(String.format(SESSION, i));
			sessionBean.setIp(String.format(IP_ADDRESS, i));
			sessionBean.setP2P(random.nextBoolean());
			sessionBean.setPort(random.nextInt(65000));
			sessionBean.setTimeout(System.currentTimeMillis());
			sessionBean.setUser(String.format(USR_ID, i));

			sessionList.add(sessionBean);
		}
	}

	public void setUp() {
		try {
			storageManager = new StorageManager(new WrapperConfiguration(new File(CONF_FILE)));
			sessionManager = new SessionManager(storageManager);
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}

	/**
	 * @return the sessionList
	 */
	public List<MSessionBean> getSessionList() {
		return sessionList;
	}

	@Override
	public void run() {
		while (!sessionList.isEmpty()) {
			synchronized (sessionList) {
				try {
					final MSessionBean session = ((LinkedList<MSessionBean>) sessionList).pop();
					sessionManager.create(session);
				} catch (final Exception ex) {
					ex.printStackTrace();
				}
			}
		}
	}
}
