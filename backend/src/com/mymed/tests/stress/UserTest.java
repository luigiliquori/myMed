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
public class UserTest extends StressTestValues {
	/*
	 * A static counter needed to verify how many user beans are created
	 */
	private static int counter = 0;

	/*
	 * Maximum number of elements to create
	 */
	private final int maxElements;

	/**
	 * Initialize the class and set up the {link ProfileManager}.
	 * 
	 * @param maxElements
	 *            the maximum number of elements to create
	 */
	public UserTest(final int maxElements) {
		super();

		this.maxElements = maxElements;
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

		if (counter < maxElements) {
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

			counter++;
		}

		return userBean;
	}

	/**
	 * Create the user in the database
	 * 
	 * @param user
	 *            the user bean to use
	 * @throws Exception
	 */
	public void createUser(final MUserBean user) throws Exception {
		try {
			final ProfileManager profileManager = new ProfileManager(new StorageManager(new WrapperConfiguration(
			        new File(CONF_FILE))));
			profileManager.create(user);
		} catch (final Exception ex) {
			throw new Exception(ex);
		}
	}

	/**
	 * Remove a user from the database
	 * 
	 * @param user
	 *            the user bean to use
	 * @throws Exception
	 */
	public void removeUser(final MUserBean user) throws Exception {
		try {
			final ProfileManager profileManager = new ProfileManager(new StorageManager(new WrapperConfiguration(
			        new File(CONF_FILE))));
			profileManager.delete(user.getId());
		} catch (final Exception ex) {
			throw new Exception(ex);
		}
	}
}
