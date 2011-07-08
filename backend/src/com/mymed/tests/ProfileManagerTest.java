package com.mymed.tests;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.fail;

import java.io.File;

import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.data.user.MUserBean;

/**
 * Test class for the {@link ProfileManager}.
 * 
 * @author Milo Casagrande
 * 
 */
public class ProfileManagerTest extends TestValues {

	private static long date;
	private static MUserBean testUser;

	private ProfileManager profileManager;
	private StorageManager storageManager;

	/**
	 * Set up the {@link ProfileManager} connection
	 * 
	 * @throws InternalBackEndException
	 */
	@Before
	public void setUp() throws InternalBackEndException {
		storageManager = new StorageManager(new WrapperConfiguration(new File(CONF_FILE)));
		profileManager = new ProfileManager(storageManager);
	}

	/**
	 * Method used only once to set up the static objects
	 */
	@BeforeClass
	public static void setUpOnce() {
		CAL_INSTANCE.set(1971, 1, 1);
		date = CAL_INSTANCE.getTimeInMillis();

		testUser = new MUserBean();

		testUser.setBirthday(date);
		testUser.setSocialNetworkID(NAME);
		testUser.setBuddyList(BUDDY_LST_ID);
		testUser.setEmail(EMAIL);
		testUser.setFirstName(FIRST_NAME);
		testUser.setGender(GENDER);
		testUser.setHometown(HOMETOWN);
		testUser.setLastName(LAST_NAME);
		testUser.setLink(LINK);
		testUser.setId(KEY);
		testUser.setName(LOGIN);
		testUser.setSession(SESSION_ID);
		testUser.setInteractionList(INTERACTION_LST_ID);
		testUser.setLastConnection(date);
		testUser.setReputation(REPUTATION_ID);
		testUser.setSubscribtionList(SUBSCRIPTION_LST_ID);
	}

	/**
	 * Method used at the end of all the tests. Remove all the columns inserted
	 * 
	 * @throws InternalBackEndException
	 * @throws ServiceManagerException
	 */
	@AfterClass
	public static void endOnce() throws InternalBackEndException, ServiceManagerException {
		final StorageManager manager = new StorageManager(new WrapperConfiguration(new File(CONF_FILE)));
		manager.removeAll(TABLE_NAME, KEY);
	}

	/**
	 * Perform a insert user with the created {@link MUserBean}.
	 * <p>
	 * The expected behavior is the normal execution of the program
	 */
	@Test
	public void testInsertUser() {
		try {
			profileManager.create(testUser);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Perform a select of the newly inserted user and compare it with the local
	 * {@link MUserBean} used to create the user.
	 */
	@Test
	public void testSelectAll() {
		try {
			final MUserBean userRead = profileManager.read(KEY);
			assertEquals("User beans are not the same\n", testUser, userRead);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}
}
