package com.mymed.tests.stress;

import java.util.ArrayList;
import java.util.List;
import java.util.Random;

import com.mymed.model.data.session.MSessionBean;

/**
 * Create all the necessary session beans in order to set up the Cassandra
 * stress test.
 * 
 * @author Milo Casagrande
 * 
 */
public class SessionStressTest extends StressTestValues {

	private final List<MSessionBean> sessionList = new ArrayList<MSessionBean>(NUMBER_OF_ELEMENTS);

	public SessionStressTest() {
		MSessionBean sessionBean;

		final Random rand = new Random(System.currentTimeMillis());

		for (int i = 0; i < NUMBER_OF_ELEMENTS; i++) {
			sessionBean = new MSessionBean();

			sessionBean.setId(String.format(SESSION, i));
			sessionBean.setIp(String.format(IP_ADDRESS, i));
			sessionBean.setP2P(rand.nextBoolean());
			sessionBean.setPort(rand.nextInt(65000));
			sessionBean.setTimeout(System.currentTimeMillis());
			sessionBean.setUser(String.format(USR_ID, i));
		}
	}

	/**
	 * @return the sessionList
	 */
	public List<MSessionBean> getSessionList() {
		return sessionList;
	}
}
