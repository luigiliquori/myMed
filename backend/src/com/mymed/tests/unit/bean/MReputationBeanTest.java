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
package com.mymed.tests.unit.bean;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertFalse;
import static org.junit.Assert.fail;

import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;

import com.mymed.model.data.reputation.MReputationBean;

public class MReputationBeanTest {

  private static MReputationBean reputationBean;

  @BeforeClass
  public static void setUpBefore() {
    reputationBean = new MReputationBean(0.5, 10);
  }

  @Test
  public void attributeToMapTest() {
    fail("Not implemented yet");
  }

  @Test
  public void notEqualsTest() {
    final MReputationBean actual = new MReputationBean(1.2, 12);

    assertFalse("The session beans are the same", reputationBean.equals(actual));
  }

  @Test
  public void equalsTest() {
    final MReputationBean actual = new MReputationBean(0.5, 10);

    assertEquals("The session beans are not the same", reputationBean, actual);
  }

  @AfterClass
  public static void cleanUpAfter() {
    reputationBean = null;
  }
}
