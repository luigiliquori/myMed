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

import org.junit.Test;

import com.mymed.controller.core.manager.reputation.old.ReputationManager;
import com.mymed.model.data.reputation.old.MReputationBean;

/**
 * Test class for the {@link ReputationManager}
 * 
 * @author Milo Casagrande
 */
public class ReputationManagerTest extends GeneralTest {

  private static final double FEEDBACK = 2;

  @Test
  public void testCreateReputation() {
    try {
      reputationManager.create(reputationBean, APPLICATION_ID + PRODUCER_ID);
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }

  /**
   * Read the reputation back from the database
   */
  @Test
  public void testReadReputation() {
    try {
      final MReputationBean readReputation = reputationManager.read(PRODUCER_ID, CONSUMER_ID, APPLICATION_ID);
      assertEquals("The reputation beans are not the same\n", reputationBean, readReputation);
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }

  /**
   * Perform an update operation
   */
  @Test
  public void testUpdateReputation() {
    try {
      final MReputationBean newReputationBean = reputationBean.clone();
      newReputationBean.setValue(FEEDBACK);

      reputationManager.update(newReputationBean, APPLICATION_ID + PRODUCER_ID);
      final MReputationBean readReputation = reputationManager.read(PRODUCER_ID, CONSUMER_ID, APPLICATION_ID);
      assertFalse("The reputation beans are the same\n", reputationBean.equals(readReputation));
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }
}
