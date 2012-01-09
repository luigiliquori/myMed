package com.mymed.tests.unit.bean;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertFalse;
import static org.junit.Assert.fail;

import java.util.Map;

import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;

import com.mymed.model.data.interaction.MInteractionBean;

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
    interactionBean = null;
  }

}
