package com.mymed.controller.core.manager.geolocation;

import java.nio.ByteBuffer;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.WrongFormatException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.id.MyMedId;
import com.mymed.model.data.storage.ExpColumnBean;
import com.mymed.utils.MConverter;
import com.mymed.utils.locator.GeoLocationOutOfBoundException;
import com.mymed.utils.locator.Location;
import com.mymed.utils.locator.Locator;

/**
 * Manages the geo-localization.
 * 
 * @author iacopo
 * 
 */
public class GeoLocationManager extends AbstractManager {

  private static final long A_MILLION = 1000000L;
  private static final String SC_LOCATION = COLUMNS.get("column.sc.location");

  public GeoLocationManager() throws InternalBackEndException {
    this(new MyJamStorageManager());
  }

  public GeoLocationManager(final MyJamStorageManager storageManager) throws InternalBackEndException {
    super(storageManager);
  }

  /**
   * Insert a new located item into the database.
   * 
   * @param applicationId
   *          Id of the application.
   * @param itemType
   *          Id of the located object. Its scope is the application which it
   *          belongs to.
   * @param latitude
   *          Latitude in micro-degrees.
   * @param longitude
   *          Longitude in micro-degrees.
   * @param value
   *          String value (Can be null.)
   * @param permTime
   *          Permanence time in seconds (if 0 the item doesn't expire).
   * @return
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  public MSearchBean create(final String applicationId, final String itemType, final String userLogin,
      final int latitude, final int longitude, final String value, final int permTime) throws InternalBackEndException,
      IOBackEndException {
    try {
      final long locId = Locator.getLocationId(latitude / 1E6, longitude / 1E6);
      final String areaId = String.valueOf(Locator.getAreaId(locId));

      long timestamp = System.currentTimeMillis();
      final MyMedId id = new MyMedId(Character.toLowerCase(itemType.charAt(0)), timestamp, userLogin);

      // Create a MSearchBean instance to be returned.
      final MSearchBean searchBean = new MSearchBean();
      searchBean.setId(id.toString());
      searchBean.setLocationId(locId);
      searchBean.setLatitude(latitude);
      searchBean.setLatitude(longitude);
      searchBean.setValue(value);
      searchBean.setDate(timestamp);
      // If the TTL has been specified, the expiration time is set on the bean.
      if (permTime != 0) {
        searchBean.setExpirationDate(timestamp + A_MILLION * permTime);
      }

      timestamp *= 1000L;

      ((MyJamStorageManager) storageManager).insertExpiringColumn(SC_LOCATION, applicationId + itemType + areaId,
          MConverter.longToByteBuffer(locId).array(), id.AsByteBuffer().array(), MConverter.stringToByteBuffer(value)
              .array(), timestamp, permTime);

      return searchBean;
    } catch (final GeoLocationOutOfBoundException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new InternalBackEndException(e.getMessage());
    }
  }

  /**
   * Search located items in a circular region specified by latitude, longitude
   * and radius.
   * 
   * @param applicationId
   *          Identifier of the application.
   * @param latitude
   *          Latitude in micro-degrees.
   * @param longitude
   *          Longitude in micro-degrees.
   * @param radius
   *          Radius of the search in meters.
   * @return
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  public List<MSearchBean> read(final String applicationId, final String itemType, final int latitude,
      final int longitude, final int radius) throws InternalBackEndException, IOBackEndException {
    final List<MSearchBean> resultReports = new LinkedList<MSearchBean>();
    try {
      /**
       * Data structures preparation
       */
      final Map<byte[], Map<byte[], byte[]>> reports = new HashMap<byte[], Map<byte[], byte[]>>();
      final List<long[]> covId = Locator.getCoveringLocationId(latitude / 1E6, longitude / 1E6, radius);
      final List<String> areaIds = new LinkedList<String>();
      final Iterator<long[]> covIdIterator = covId.iterator();
      /**
       * Cassandra calls
       */
      while (covIdIterator.hasNext()) {
        final long[] range = covIdIterator.next();
        final long startAreaId = Locator.getAreaId(range[0]);
        final long endAreaId = Locator.getAreaId(range[1]);
        for (long ind = startAreaId; ind <= endAreaId; ind++) {
          areaIds.add(applicationId + itemType + ind);
        }
        final Map<byte[], Map<byte[], byte[]>> mapRep = ((MyJamStorageManager) storageManager).selectSCRange(
            SC_LOCATION, areaIds, MConverter.longToByteBuffer(range[0]).array(), MConverter.longToByteBuffer(range[1])
                .array());
        reports.putAll(mapRep);
        areaIds.clear();
      }
      /**
       * Data processing: Filters the results of the search.
       */
      for (final byte[] scName : reports.keySet()) {
        final long posId = MConverter.byteBufferToLong(ByteBuffer.wrap(scName));
        final Location reportLoc = Locator.getLocationFromId(posId);
        final double distance = reportLoc.distanceGCTo(new Location(latitude / 1E6, longitude / 1E6));

        if (distance <= radius) {
          for (final byte[] colName : reports.get(scName).keySet()) {
            final MSearchBean searchBean = new MSearchBean();
            final MyMedId repId = MyMedId.parseByteBuffer(ByteBuffer.wrap(colName));
            searchBean.setValue(MConverter.byteBufferToString(ByteBuffer.wrap(reports.get(scName).get(colName))));
            searchBean.setId(repId.toString());
            searchBean.setLatitude((int) (reportLoc.getLatitude() * 1E6));
            searchBean.setLongitude((int) (reportLoc.getLongitude() * 1E6));
            searchBean.setDistance((int) distance);
            /**
             * The timestamp is set with the convention used in Java
             * (milliseconds from 1 January 1970)
             */
            searchBean.setDate(repId.getTimestamp());
            resultReports.add(searchBean);
          }
        }
      }
      return resultReports;
    } catch (final InternalBackEndException e) {
      throw new InternalBackEndException("Wrong parameter: " + e.getMessage());
    } catch (final GeoLocationOutOfBoundException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new InternalBackEndException(e.getMessage());
    } catch (final IllegalArgumentException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new InternalBackEndException("Wrong parameter: " + e.getMessage());
    } catch (final WrongFormatException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new InternalBackEndException("Wrong object Id: " + e.getMessage());
    } catch (final IOBackEndException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new IOBackEndException(e.getMessage(), 404);
    }
  }

  /**
   * Returns the MSearchBean or throws an exception if it is not present.
   * 
   * @param applicationId
   *          Identifier of the application.
   * @param itemType
   * @param locationId
   * @param itemId
   * @return
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  public MSearchBean read(final String applicationId, final String itemType, final long locationId, final String itemId)
      throws InternalBackEndException, IOBackEndException {

    final String areaId = String.valueOf(Locator.getAreaId(locationId));
    MSearchBean searchBean;

    try {
      final ExpColumnBean expCol = ((MyJamStorageManager) storageManager).selectExpiringColumn(SC_LOCATION,
          applicationId + itemType + areaId, MConverter.longToByteBuffer(locationId).array(),
          MyMedId.parseString(itemId).AsByteBuffer().array());

      final Location loc = Locator.getLocationFromId(locationId);

      searchBean = new MSearchBean();
      searchBean.setId(itemId);
      searchBean.setLatitude((int) (loc.getLatitude() * 1E6));
      searchBean.setLongitude((int) (loc.getLongitude() * 1E6));
      searchBean.setValue(MConverter.byteBufferToString(ByteBuffer.wrap(expCol.getValue())));
      searchBean.setLocationId(locationId);
      searchBean.setDate(expCol.getTimestamp());

      if (expCol.getTimeToLive() != 0) {
        searchBean.setExpirationDate(expCol.getTimestamp() + A_MILLION * expCol.getTimeToLive());
      }

      return searchBean;
    } catch (final WrongFormatException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new InternalBackEndException("Wrong report Id: " + e.getMessage());
    } catch (final IllegalArgumentException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new InternalBackEndException("Wrong report Id: " + e.getMessage());
    } catch (final GeoLocationOutOfBoundException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new InternalBackEndException("Wrong report Id: " + e.getMessage());
    }
  }

  // TODO To be tested.
  /**
   * Deletes one located item.
   * 
   * @param applicationId
   *          Identifier of the application.
   * @param itemType
   *          Type of localized item.
   * @param locationId
   *          Identifier of the location.
   * @param itemId
   *          Identifier of the item.
   * @return
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  public void delete(final String applicationId, final String itemType, final long locationId, final String itemId)
      throws InternalBackEndException, IOBackEndException {

    try {
      final String areaId = String.valueOf(Locator.getAreaId(locationId));
      final MyMedId id = MyMedId.parseString(itemId);

      ((MyJamStorageManager) storageManager).removeColumn(SC_LOCATION, applicationId + itemType + areaId, MConverter
          .longToByteBuffer(locationId).array(), id.AsByteBuffer().array());
    } catch (final WrongFormatException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new InternalBackEndException("Wrong report Id: " + e.getMessage());
    } catch (final IllegalArgumentException e) {
      LOGGER.debug(e.getMessage(), e);
      throw new InternalBackEndException("Wrong location Id: " + e.getMessage());
    }
  }
}