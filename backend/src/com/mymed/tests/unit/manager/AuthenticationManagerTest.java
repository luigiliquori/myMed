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
import static org.junit.Assert.fail;

import org.junit.Test;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.authentication.AuthenticationManager;
import com.mymed.model.data.user.MUserBean;

/**
 * Test class for the {@link AuthenticationManager}
 * 
 * @author Milo Casagrande
 * 
 */
public class AuthenticationManagerTest extends GeneralTest {

  /**
   * Create a simple Authentication entry in the database
   */
  @Test
  public void createAuthTest() {
    try {
      authenticationManager.create(userBean, authenticationBean);
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }

  /**
   * Read the just created authentication entry from the database, and compare
   * the {@link MUserBean} returned
   */
  @Test
  public void readAuthTest() {
    try {
      authenticationManager.create(userBean, authenticationBean);
    } catch (final IOBackEndException ex) {
      // NOPMD
      // Do nothing in this case, we are creating a user, but if it exists
      // already we want the test to continue
    } catch (final InternalBackEndException ex) {
      fail(ex.getMessage());
    }

    try {
      final MUserBean readValue = authenticationManager.read(authenticationBean.getLogin(),
          authenticationBean.getPassword());
      assertEquals("The user beans are not the same\n", readValue, userBean);
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }
}
