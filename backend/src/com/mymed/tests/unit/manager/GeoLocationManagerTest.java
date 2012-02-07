package com.mymed.tests.unit.manager;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.fail;

import java.util.ArrayList;
import java.util.List;

import org.junit.Test;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.geolocation.MSearchBean;

public class GeoLocationManagerTest extends GeneralTest {

  /**
   * Perform multiple create operations and one read
   * <p>
   * This is a simple test, just check that the number of values read in a
   * defined radius equals the number of insertion
   */
  @Test
  public void multiCreateReadTest() {
    final List<MSearchBean> createList = new ArrayList<MSearchBean>(5);

    try {
      for (int i = 0; i < 5; i++) {
        final MSearchBean result = geolocationManager.create(APPLICATION_ID, ITEM_TYPE, USER_ID, LATITUDE + 2 * i,
            LONGITUDE + 2 * i, GEO_VALUE, EXPIRING_TIME);
        createList.add(result);
      }

      final List<MSearchBean> searchList = geolocationManager.read(APPLICATION_ID, ITEM_TYPE, LATITUDE, LONGITUDE,
          RADIUS);

      assertEquals("The results read do not correspond to the written ones", createList.size(), searchList.size());
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }
  }

  /**
   * Test the delete operation
   * <p>
   * Perform a create, then a delete and in the end a read: the read should
   * throw an exception
   * 
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  @Test(expected = IOBackEndException.class)
  public void deleteTest() throws InternalBackEndException, IOBackEndException {
    MSearchBean createLocation = null;

    try {
      createLocation = geolocationManager.create(APPLICATION_ID, ITEM_TYPE, USER_ID, LATITUDE + 10, LONGITUDE - 10,
          GEO_VALUE, EXPIRING_TIME);
      geolocationManager.delete(APPLICATION_ID, ITEM_TYPE, createLocation.getLocationId(), createLocation.getId());
    } catch (final Exception ex) {
      fail(ex.getMessage());
    }

    geolocationManager.read(APPLICATION_ID, ITEM_TYPE, createLocation.getLocationId(), createLocation.getId());
  }
}
