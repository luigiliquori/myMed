package com.mymed.tests.stress;

import java.util.ArrayList;
import java.util.List;

import com.mymed.model.data.session.MAuthenticationBean;

public class AuthStressTest extends StressTestValues {

	private final List<MAuthenticationBean> authList = new ArrayList<MAuthenticationBean>(NUMBER_OF_ELEMENTS);

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
		}
	}

	/**
	 * @return the authList
	 */
	public List<MAuthenticationBean> getAuthList() {
		return authList;
	}
}
