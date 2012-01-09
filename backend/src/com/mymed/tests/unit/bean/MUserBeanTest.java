package com.mymed.tests.unit.bean;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertFalse;
import static org.junit.Assert.fail;

import java.util.Map;

import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;

import com.mymed.model.data.user.MUserBean;

public class MUserBeanTest {

  private static MUserBean userBean;

  @BeforeClass
  public static void setUpBefore() {
    userBean = new MUserBean();
    userBean.setId("ID");
    userBean.setName("NAME");
    userBean.setLastName("LAST_NAME");
    userBean.setFirstName("FIRST_NAME");
    userBean.setEmail("EMAIL@PROVA");
    userBean.setLogin("LOGIN");
  }

  /**
   * Check that the getAttributeToMap() method works
   */
  @Test
  public void attributeToMapTest() {
    try {
      final Map<String, byte[]> attributesMap = userBean.getAttributeToMap();
      // TODO needs to find a better way to structure this, we need to create
      // enums for each beans and check that the
      // fields are valid through that
      assertEquals(20, attributesMap.size());
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }

  @Test
  public void notEqualsTest() {
    final MUserBean actual = new MUserBean();
    actual.setId("ANOTHER_ID");
    actual.setName("ANOTHER_NAME");

    assertFalse("The user beans are the same", userBean.equals(actual));
  }

  @Test
  public void cloneEqualsTest() {
    final MUserBean actual = userBean.clone();
    assertEquals("The user beans are not the same", userBean, actual);
  }

  @AfterClass
  public static void cleanUpAfter() {
    userBean = null;
  }
}
