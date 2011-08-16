package com.mymed.tests.stress;

import java.util.ArrayList;
import java.util.List;

import com.mymed.model.data.user.MUserBean;

/**
 * Create all the necessary user beans in order to set up the Cassandra stress
 * test.
 * 
 * @author Milo Casagrande
 * 
 */
public class UserStressTest extends StressTestValues {

	private final List<MUserBean> userList = new ArrayList<MUserBean>(NUMBER_OF_ELEMENTS);

	public UserStressTest() {
		MUserBean bean;

		for (int i = 0; i < NUMBER_OF_ELEMENTS; i++) {
			bean = new MUserBean();
			bean.setBirthday(getRandomDate());
			bean.setBuddyList(String.format(USR_LIST_ID, i));
			bean.setEmail(String.format(EMAIL, i));
			bean.setFirstName(String.format(FIRST_NAME, i));
			bean.setGender(getRandomGender());
			bean.setId(String.format(USR_ID, i));
			bean.setLastConnection(System.currentTimeMillis());
			bean.setLogin(String.format(LOGIN, i));
			bean.setName(String.format(NAME, i));
			bean.setSession(String.format(SESSION, i));
		}
	}

	/**
	 * @return the userList
	 */
	public List<MUserBean> getUserList() {
		return userList;
	}
}
