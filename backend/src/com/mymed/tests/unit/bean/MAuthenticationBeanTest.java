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

import java.util.Map;

import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.session.MAuthenticationBean;

/**
 * {@link MAuthenticationBean} unit test
 * 
 * @author Milo Casagrande
 * 
 */
public class MAuthenticationBeanTest {

  private static MAuthenticationBean authenticationBean;

  @BeforeClass
  public static void setUpBeforeClass() throws Exception {
    authenticationBean = new MAuthenticationBean();
    authenticationBean.setLogin("LOGIN");
    authenticationBean.setPassword("PASSWORD");
    authenticationBean.setUser("USER");
  }

  @AfterClass
  public static void tearDownAfterClass() throws Exception {
    authenticationBean = null; // NOPMD
  }

  @Test
  public void attributeToMapTest() {
    try {
      final Map<String, byte[]> attributeMap = authenticationBean.getAttributeToMap();
      assertEquals(3, attributeMap.size());
    } catch (final InternalBackEndException ex) {
      fail(ex.getMessage());
    }
  }

  @Test
  public void notEqualsTest() {
    final MAuthenticationBean actual = new MAuthenticationBean();
    actual.setLogin("NEW_LOGIN");
    actual.setPassword("NEW_PASSWORD");
    actual.setUser("NEW_USER");

    assertFalse("The beans are the same", authenticationBean.equals(actual));
  }

  @Test
  public void nullEqualsTest() {
    assertFalse("The beans are the same", authenticationBean.equals(null));
  }

  @Test
  public void cloneEqualsTest() {
    final MAuthenticationBean actual = authenticationBean.clone();
    assertEquals("The beans are not the same", authenticationBean, actual);
  }
}
