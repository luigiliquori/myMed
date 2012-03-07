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

import com.mymed.model.data.application.MOntologyBean;

/**
 * MOntologyBean unit tests
 * 
 * @author Milo Casagrande
 * 
 */
public class MOntologyBeanTest {

  private static MOntologyBean ontologyBean;
  private static MOntologyBean nullActual = null;

  @BeforeClass
  public static void setUpBefore() {
    ontologyBean = new MOntologyBean();

    ontologyBean.setPredicate(true);
    ontologyBean.setName("ONTOLOGY_NAME");
    ontologyBean.setType("ONTOLOGY_TYPE");
  }

  @AfterClass
  public static void cleanUpAfter() {
    ontologyBean = null; // NOPMD
  }

  @Test
  public void attributeToMapTest() {
    try {
      final Map<String, byte[]> attributeMap = ontologyBean.getAttributeToMap();

      assertEquals(3, attributeMap.size());
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }

  @Test
  public void notEqualsTest() {
    final MOntologyBean newOntologyBean = new MOntologyBean();
    newOntologyBean.setPredicate(false);
    newOntologyBean.setName("NEW_ONTOLOGY_NAME");

    assertFalse("The beans are the same", ontologyBean.equals(newOntologyBean));
  }

  /**
   * Test that two new empty objects are the same
   */
  @Test
  public void nullEqualsTest() {
    assertFalse("The beans are the same", ontologyBean.equals(nullActual)); // NOPMD
  }

  @Test
  public void equalsTest() {
    final MOntologyBean actual = ontologyBean.clone();
    assertEquals("The beans are not the same", ontologyBean, actual);
  }

  /**
   * Test two empty objects.
   */
  @Test
  public void emptyEqualsTest() {
    final MOntologyBean nullFirst = new MOntologyBean();
    final MOntologyBean nullSecond = new MOntologyBean();

    assertEquals("The beans are not the same!", nullFirst, nullSecond);
  }
}
