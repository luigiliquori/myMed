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

import com.mymed.model.data.interaction.MInteractionBean;

/**
 * MInteractionBean unit tests
 * 
 * @author Milo Casagrande
 * 
 */
public class MInteractionBeanTest {

  private static MInteractionBean interactionBean;

  @BeforeClass
  public static void setUpBefore() {
    interactionBean = new MInteractionBean();

    interactionBean.setId("ID");
    interactionBean.setProducer("PRODUCER");
    interactionBean.setConsumer("CONSUMER");
    interactionBean.setFeedback(0.5);
  }

  @Test
  public void attributeToMapTest() {
    try {
      final Map<String, byte[]> attributeMap = interactionBean.getAttributeToMap();
      assertEquals(9, attributeMap.size());
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }

  @Test
  public void notEqualsTest() {
    final MInteractionBean actual = new MInteractionBean();
    actual.setId("ANOTHER_ID");
    actual.setProducer("ANOTHER_PRODUCER");
    actual.setConsumer("ANOTHER_CONSUMER");
    actual.setFeedback(1.2);

    assertFalse("The session beans are the same", interactionBean.equals(actual));
  }

  @Test
  public void equalsTest() {
    final MInteractionBean actual = interactionBean.clone();
    assertEquals("The session beans are not the same", interactionBean, actual);
  }

  @AfterClass
  public static void cleanUpAfter() {
    interactionBean = null; // NOPMD
  }
}
