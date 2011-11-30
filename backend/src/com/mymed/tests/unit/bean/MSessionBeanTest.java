package com.mymed.tests.unit.bean;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertFalse;
import static org.junit.Assert.fail;

import java.util.Map;

import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;

import com.mymed.model.data.session.MSessionBean;

public class MSessionBeanTest {

  private static MSessionBean sessionBean;

  @BeforeClass
  public static void setUpBefore() {
    sessionBean = new MSessionBean();

    sessionBean.setId("ID");
    sessionBean.setIp("IP");
    sessionBean.setAccessToken("ACCESS_TOKEN");
    sessionBean.setUser("USER");
  }

  @Test
  public void attributeToMapTest() {
    try {
      final Map<String, byte[]> attributeMap = sessionBean.getAttributeToMap();
      // TODO needs to find a better way to structure this, we need to create
      // enums for each beans and check that the
      // fields are valid through that
      assertEquals(9, attributeMap.size());
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }

  @Test
  public void notEqualsTest() {
    final MSessionBean actual = new MSessionBean();
    actual.setId("ANOTHER_ID");
    actual.setUser("ANOTHER_USER");
    actual.setAccessToken("ANOTHER_ACCESS_TOKEN");
    actual.setIp("ANOTHER_IP");

    assertFalse("The session beans are the same", sessionBean.equals(actual));
  }

  @Test
  public void equalsTest() {
    final MSessionBean actual = new MSessionBean();
    actual.setId("ID");
    actual.setUser("USER");
    actual.setAccessToken("ACCESS_TOKEN");
    actual.setIp("IP");

    assertEquals("The session beans are not the same", sessionBean, actual);
  }

  @AfterClass
  public static void cleanUpAfter() {
    sessionBean = null;
  }
}
