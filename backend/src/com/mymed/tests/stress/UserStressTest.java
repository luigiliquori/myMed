package com.mymed.tests.stress;

import java.util.Collections;
import java.util.LinkedList;
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

	private final List<MUserBean> userList = Collections.synchronizedList(new LinkedList<MUserBean>());

	public UserStressTest() {
		MUserBean userBean;

		for (int i = 0; i < NUMBER_OF_ELEMENTS; i++) {
			userBean = new MUserBean();
			userBean.setBirthday(getRandomDate());
			userBean.setBuddyList(String.format(USR_LIST_ID, i));
			userBean.setEmail(String.format(EMAIL, i));
			userBean.setFirstName(String.format(FIRST_NAME, i));
			userBean.setGender(getRandomGender());
			userBean.setId(String.format(USR_ID, i));
			userBean.setLastConnection(System.currentTimeMillis());
			userBean.setLogin(String.format(LOGIN, i));
			userBean.setName(String.format(NAME, i));
			userBean.setSession(String.format(SESSION, i));

			userList.add(userBean);
		}
	}
	/**
	 * @return the userList
	 */
	public List<MUserBean> getUserList() {
		return userList;
	}
}
