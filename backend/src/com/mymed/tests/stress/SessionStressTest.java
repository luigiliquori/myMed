package com.mymed.tests.stress;

import java.util.Collections;
import java.util.LinkedList;
import java.util.List;

import com.mymed.model.data.session.MSessionBean;

/**
 * Create all the necessary session beans in order to set up the Cassandra
 * stress test.
 * 
 * @author Milo Casagrande
 * 
 */
public class SessionStressTest extends StressTestValues {

	private final List<MSessionBean> sessionList = Collections.synchronizedList(new LinkedList<MSessionBean>());

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

	/**
	 * @return the sessionList
	 */
	public List<MSessionBean> getSessionList() {
		return sessionList;
	}
}
