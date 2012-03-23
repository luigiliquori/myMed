package com.mymed.tests.stress;

import java.util.List;
import java.util.Random;

import ch.qos.logback.classic.Logger;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;
import com.mymed.controller.core.manager.geolocation.GeoLocationManager;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.utils.MLogger;
import com.mymed.utils.locator.Location;

/**
 * Generate random locations within a bounding box defined by center and radius.
 * 
 * @author iacopo
 * 
 */
public class GeoLocationTest {
  private static String TAG = "GeoLocationTest";
  private static final Logger LOGGER = MLogger.getLogger();
  private static long SEED = 19580427;
  private final int minLat;
  private final int maxLat;
  private final int minLon;
  private final int maxLon;
  // Statistics
  private boolean first = true;
  private int firstSearchTime;
  private long cumulantTime;
  private long cumulantSquareTime;
  private int numSuccess;
  private int numFailures;
  private int numTotal;

  private final Random rand;

  public GeoLocationTest(final Location center, final int radius) {
    rand = new Random(SEED);
    final Location[] loc = center.boundingCoordinates(radius);
    minLat = loc[0].getLatitude();
    minLon = loc[0].getLongitude();
    maxLat = loc[1].getLatitude();
    maxLon = loc[1].getLongitude();
    // Statistics initialization.
    cumulantTime = 0;
    cumulantSquareTime = 0;
    numSuccess = 0;
    numFailures = 0;
    firstSearchTime = 0;
  }

  public Location getRandomLocation() throws GeoLocationOutOfBoundException {
    final int latitude = (int) (minLat + (maxLat - minLat) * rand.nextDouble());
    final int longitude = (int) (minLon + (maxLon - minLon) * rand.nextDouble());
    return new Location(latitude, longitude);
  }

  public MSearchBean insertLocalizedItem() {
    try {
      final GeoLocationManager geoLocationManager = new GeoLocationManager();

      final Location loc = getRandomLocation();
      return geoLocationManager.create(TAG, TAG + "Item", "user", loc.getLatitude(), loc.getLongitude(), "testVal", 0);
    } catch (final Exception e) {
      e.printStackTrace();
      LOGGER.debug("Error insertLocalizedItem: \n	{}", e.toString());
      return null;
    }
  }

  public List<MSearchBean> searchItems(final Location center, final int radius) {
    try {
      final GeoLocationManager geoLocationManager = new GeoLocationManager();
      final long startTime = System.currentTimeMillis();
      numTotal++;
      final List<MSearchBean> res = geoLocationManager.read(TAG, TAG + "Item", center.getLatitude(),
          center.getLongitude(), radius, true);
      final long duration = System.currentTimeMillis() - startTime;
      if (first) {
        firstSearchTime = (int) duration;
        first = false;
      } else {
        cumulantTime += duration;
        cumulantSquareTime += duration * duration;
        numSuccess++;
      }
      return res;
    } catch (final Exception e) {
      e.printStackTrace();
      LOGGER.debug("Error searchItems: \n	{}", e.toString());
      numFailures++;
      return null;
    }
  }

  public void deleteItem(final long locationId, final String itemId) {
    try {
      final GeoLocationManager geoLocationManager = new GeoLocationManager();
      geoLocationManager.delete(TAG, TAG + "Item", locationId, itemId);
    } catch (final Exception e) {
      e.printStackTrace();
      LOGGER.debug("Error deleteItem: \n	{}", e.toString());
    }
  }

  public int getFirstSearchDuration() {
    return firstSearchTime;
  }

  public double getDurationAverage() {
    return numSuccess > 0 ? cumulantTime * 1.0 / numSuccess : 0.0;
  }

  public double getDurationVariance() {
    return numSuccess > 1 ? (cumulantSquareTime * 1.0 - numSuccess * getDurationAverage() * getDurationAverage())
        / (numSuccess - 1) : 0.0;
  }

  public double getFailRate() {
    return numTotal > 0 ? numFailures * 1.0 / numTotal : 0.0;
  }

}
