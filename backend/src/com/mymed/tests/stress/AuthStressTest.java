package com.mymed.tests.stress;

import java.util.Collections;
import java.util.LinkedList;
import java.util.List;

import com.mymed.model.data.session.MAuthenticationBean;

/**
 * Create all the necessary authentication beans in order to set up the
 * Cassandra stress test
 * 
 * @author Milo Casagrande
 * 
 */
public class AuthStressTest extends StressTestValues {

	private final List<MAuthenticationBean> authList = Collections
	        .synchronizedList(new LinkedList<MAuthenticationBean>());

	public AuthStressTest() {
		MAuthenticationBean authBean;

		for (int i = 0; i < NUMBER_OF_ELEMENTS; i++) {
			authBean = new MAuthenticationBean();

			authBean.setLogin(String.format(AUTH_ID, i));
			authBean.setUser(String.format(USR_ID, i));

			try {
				authBean.setPassword(getRandomPwd());
			} catch (final Exception ex) {
				ex.printStackTrace();
			}

			authList.add(authBean);
		}
	}

	/**
	 * @return the authList
	 */
	public List<MAuthenticationBean> getAuthList() {
		return authList;
	}
}
