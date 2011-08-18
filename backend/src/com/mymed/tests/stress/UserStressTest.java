package com.mymed.tests.stress;

import java.io.File;

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
public class UserStressTest extends StressTestValues {
	/*
	 * A static counter needed to verify how many user bean are created
	 */
	private static int counter = 0;

	private ProfileManager profileManager;

	/**
	 * Initialize the class and set up the {link ProfileManager}.
	 */
	public UserStressTest() {
		super();

		try {
			profileManager = new ProfileManager(new StorageManager(new WrapperConfiguration(new File(CONF_FILE))));
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}

	/**
	 * Create a new user bean. The ID is a progressive number based on the value
	 * {@code NUMBER_OF_ELEMENTS} defined in {@link StressTestValues}
	 * 
	 * @return the newly created user bean, or {@code null} if the maximum
	 *         NUMBER_OF_ELEMENTS is reached
	 */
	public MUserBean createUserBean() {
		MUserBean userBean = null;

		if (!(counter >= NUMBER_OF_ELEMENTS)) {
			userBean = new MUserBean();

			userBean.setBirthday(getRandomDate());
			userBean.setBuddyList(String.format(USR_LIST_ID, counter));
			userBean.setEmail(String.format(EMAIL, counter));
			userBean.setFirstName(String.format(FIRST_NAME, counter));
			userBean.setLastName(String.format(LAST_NAME, counter));
			userBean.setGender(getRandomGender());
			userBean.setId(String.format(USR_ID, counter));
			userBean.setLastConnection(System.currentTimeMillis());
			userBean.setLogin(String.format(LOGIN, counter));
			userBean.setName(String.format(NAME, counter));
			userBean.setSession(String.format(SESSION, counter));
			userBean.setSubscribtionList(String.format(SUB_LIST_ID, counter));
		}

		counter++;

		return userBean;
	}

	/**
	 * Create the user in the dabatase
	 * 
	 * @param user
	 *            the user bean to use
	 */
	public void createUser(final MUserBean user) {
		try {
			profileManager.create(user);
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}

	/**
	 * Remove a user from the database
	 * 
	 * @param user
	 *            the user bean to use
	 */
	public void removeUser(final MUserBean user) {
		try {
			profileManager.delete(user.getId());
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}
}
