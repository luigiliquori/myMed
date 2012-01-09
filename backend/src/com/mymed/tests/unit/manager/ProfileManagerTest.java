/*
 * Copyright 2012 INRIA 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
package com.mymed.tests.unit.manager;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertFalse;
import static org.junit.Assert.fail;

import java.io.File;

import org.junit.AfterClass;
import org.junit.Test;

import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.data.user.MUserBean;

/**
 * Test class for the {@link ProfileManager}.
 * 
 * @author Milo Casagrande
 */
public class ProfileManagerTest extends GeneralTest {

	/**
	 * Perform a insert user with the created {@link MUserBean}.
	 * <p>
	 * The expected behavior is the normal execution of the program
	 */
	@Test
	public void testInsertUser() {
		try {
			profileManager.create(userBean);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Select the newly inserted user and compare it with the local
	 * {@link MUserBean} used to create the user.
	 */
	@Test
	public void testReadUser() {
		try {
			final MUserBean userRead = profileManager.read(USER_ID);
			assertEquals("The user beans are not the same\n", userBean, userRead);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Update the user and check that the new values read are not the same of
	 * the old one
	 */
	@Test
	public void testUpdateUser() {
		try {
			final MUserBean newUserBean = userBean.clone();
			newUserBean.setEmail(EMAIL_2);
			newUserBean.setFirstName(FIRST_NAME_2);
			newUserBean.setLastName(LAST_NAME_2);
			newUserBean.setName(LOGIN_2);

			profileManager.update(newUserBean);
			final MUserBean readUser = profileManager.read(USER_ID);

			assertFalse("The user beans are the same", userBean.equals(readUser));
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Remove all the columns inserted
	 */
	@AfterClass
	public static void endOnce() {
		try {
			final StorageManager manager = new StorageManager(new WrapperConfiguration(new File(CONF_FILE)));
			manager.removeAll(USER_TABLE, USER_ID);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}
}
