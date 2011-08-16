package com.mymed.tests.stress;

import java.io.File;
import java.util.LinkedList;
import java.util.List;

import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.data.user.MUserBean;

/**
 * Create all the necessary user beans in order to set up the Cassandra stress
 * test.
 * 
 * @author Milo Casagrande
 * 
 */
public class UserStressTest extends StressTestValues implements Runnable {

	private final List<MUserBean> userList = new LinkedList<MUserBean>();

	private StorageManager storageManager;
	private ProfileManager profileManager;

	public UserStressTest() {
		MUserBean userBean;

		for (int i = 0; i < NUMBER_OF_ELEMENTS; i++) {
			userBean = new MUserBean();
			userBean.setBirthday(getRandomDate());
			userBean.setBuddyList(String.format(USR_LIST_ID, i));
			userBean.setEmail(String.format(EMAIL, i));
			userBean.setFirstName(String.format(FIRST_NAME, i));
			userBean.setLastName(String.format(LAST_NAME, i));
			userBean.setGender(getRandomGender());
			userBean.setId(String.format(USR_ID, i));
			userBean.setLastConnection(System.currentTimeMillis());
			userBean.setLogin(String.format(LOGIN, i));
			userBean.setName(String.format(NAME, i));
			userBean.setSession(String.format(SESSION, i));
			userBean.setSubscribtionList(String.format(SUB_LIST_ID, i));

			userList.add(userBean);
		}
	}

	public void setUp() {
		try {
			storageManager = new StorageManager(new WrapperConfiguration(new File(CONF_FILE)));
			profileManager = new ProfileManager(storageManager);
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}

	/**
	 * @return the userList
	 */
	public List<MUserBean> getUserList() {
		return userList;
	}

	@Override
	public void run() {
		while (!userList.isEmpty()) {
			final MUserBean user = ((LinkedList<MUserBean>) userList).pop();
			try {
				profileManager.create(user);
			} catch (final Exception ex) {
				ex.printStackTrace();
			}
		}

		System.err.println(userList.isEmpty());
	}
}
