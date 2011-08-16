package com.mymed.tests.stress;

import static org.junit.Assert.fail;

import java.io.File;
import java.util.List;

import org.junit.After;
import org.junit.Before;
import org.junit.Test;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.session.SessionManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.session.MSessionBean;
import com.mymed.model.data.user.MUserBean;

public class StressTest extends StressTestValues {

	private List<MUserBean> userList;
	private List<MSessionBean> sessionList;
	private List<MAuthenticationBean> authList;

	private SessionManager sessionManager;
	private StorageManager storageManager;
	private ProfileManager profileManager;

	@Before
	public void setUp() throws InternalBackEndException {
		storageManager = new StorageManager(new WrapperConfiguration(new File(CONF_FILE)));
		sessionManager = new SessionManager(storageManager);
		profileManager = new ProfileManager(storageManager);

		final UserStressTest userTest = new UserStressTest();
		userList = userTest.getUserList();
	}

	@After
	public void cleanUp() {
		sessionManager = null;
		storageManager = null;
		profileManager = null;
	}

	@Test
	public void testUser() {
		for (final MUserBean bean : userList) {
			try {
				profileManager.create(bean);
			} catch (final Exception ex) {
				fail(ex.getMessage());
			}
		}
	}
}
