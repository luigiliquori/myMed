package com.mymed.utils.locator;

import ch.qos.logback.classic.Logger;

import com.mymed.utils.MLogger;

/**
 * Class representing a location on the Earth.
 * 
 * @author iacopo
 * 
 */
public class Location {
  public final static int DEGREES = 0;
  public final static int RADIANS = 1;

  protected static final Logger LOGGER = MLogger.getLogger();
  public static double earthRadius = 6371.01d * 1E3;

  /**
   * Constructor which takes as arguments {@link latitude} and {@link longitude}
   * expressed in radiant.
   * 
   * @param latitude
   * @param longitude
   * @throws GeoLocationOutOfBoundException
   */
  public Location(final double latitude, final double longitude) throws GeoLocationOutOfBoundException {
    radLat = latitude;
    radLon = longitude;
    checkBounds();
  }

  /**
   * Constructor which takes as arguments {@link latitude} and {@link longitude}
   * expressed in micro-degrees.
   * 
   * @param latitude
   * @param longitude
   * @throws GeoLocationOutOfBoundException
   */
  public Location(final int latitude, final int longitude) throws GeoLocationOutOfBoundException {
    radLat = Math.toRadians(latitude / 1E6);
    radLon = Math.toRadians(longitude / 1E6);
    checkBounds();
  }

  private final double radLat; // latitude in radians
  private final double radLon; // longitude in radians

  /*
   * The latitude is limited to the range (-80,+80)
   */
  protected static final double MIN_LAT = Math.toRadians(-80d); //
  protected static final double MAX_LAT = Math.toRadians(80d); //
  protected static final double MIN_LON = Math.toRadians(-180d); // -PI
  protected static final double MAX_LON = Math.toRadians(180d); // PI

  private void checkBounds() throws GeoLocationOutOfBoundException {
    if (radLat < MIN_LAT || radLat > MAX_LAT || radLon < MIN_LON || radLon > MAX_LON) {
      throw new GeoLocationOutOfBoundException("Location is out of bound. Latitude: "
          + String.valueOf(this.getLatitude(DEGREES)) + " Longitude: " + String.valueOf(this.getLongitude(DEGREES)));
    }
  }

  /**
   * Get the latitude.
   * 
   * @param format
   *          {@link DEGREES} or {@link RADIANS}
   * @return The latitude in the specified format.
   */
  public double getLatitude(final int format) {
    return format == DEGREES ? Math.toDegrees(radLat) : radLat;
  }

  /**
   * Get the longitude.
   * 
   * @param format
   *          {@link DEGREES} or {@link RADIANS}
   * @return The longitude in the specified format.
   */
  public double getLongitude(final int format) {
    return format == DEGREES ? Math.toDegrees(radLon) : radLon;
  }

  /**
   * Get the latitude in micro-degrees.
   * 
   * @return
   */
  public int getLatitude() {
    return (int) (Math.toDegrees(radLat) * 1E6);
  }

  /**
   * Get the longitude in micro-degrees
   * 
   * @return
   */
  public int getLongitude() {
    return (int) (Math.toDegrees(radLon) * 1E6);
  }

  @Override
  public String toString() {
    return "(" + this.getLatitude(DEGREES) + "\u00B0, " + this.getLongitude(DEGREES) + "\u00B0) = (" + radLat
        + " rad, " + radLon + " rad)";
  }

  /**
   * Computes the great circle distance between this GeoLocation instance and
   * the location argument.
   * 
   * @param radius
   *          the radius of the sphere, e.g. the average radius for a spherical
   *          approximation of the figure of the Earth is approximately 6371.01
   *          kilometers.
   * 
   * @return the distance, measured in meters
   */
  public double distanceGCTo(final Location location) {
    return Math.acos(Math.sin(radLat) * Math.sin(location.radLat) + Math.cos(radLat) * Math.cos(location.radLat)
        * Math.cos(radLon - location.radLon))
        * earthRadius;
  }

  /**
   * Finds the coordinates of the bounding box of the circle with radius
   * distance
   * 
   * @param distance
   *          The radius of the circle whose bounding box is found.
   * @return The bottom-left and the top-right corner of the bounding box.
   * @throws GeoLocationOutOfBoundException
   */
  public Location[] boundingCoordinates(final double distance) {

    if (distance < 0d) {
      throw new IllegalArgumentException("Distance cannot be negative: " + String.valueOf(distance));
    }

    // angular distance in radians on a great circle
    final double radDist = distance / earthRadius;

    double minLat = radLat - radDist;
    double maxLat = radLat + radDist;

    double minLon, maxLon;

    final double deltaLon = Math.asin(Math.sin(radDist) / Math.cos(radLat));
    minLon = radLon - deltaLon;
    if (minLon < MIN_LON) {
      minLon += 2d * Math.PI;
    }
    maxLon = radLon + deltaLon;
    if (maxLon > MAX_LON) {
      maxLon -= 2d * Math.PI;
    }
    if (maxLat > MAX_LAT) {
      maxLat = Math.min(maxLat, MAX_LAT);
    }
    if (minLat < MIN_LAT) {
      minLat = Math.max(minLat, MIN_LAT);
    }
    try {
      return new Location[] {new Location(minLat, minLon), new Location(maxLat, maxLon)};
    } catch (final GeoLocationOutOfBoundException e) {
      // Never happen because maxLat is limited.
      LOGGER.debug(e.toString());
      return null;
    }
  }

  /**
   * Returns the four corners of the bounding box given the bottom-left and the
   * top-right corner
   * 
   * @param leftBottomCorner
   * @param rightBottomCorner
   * @return the four corners of the bounding box given the bottom-left and the
   *         top-right corner
   * @throws GeoLocationOutOfBoundException
   */
  protected static Location[] getCorners(final Location leftBottomCorner, final Location rightBottomCorner) {
    Location corner1, corner2, corner3, corner4;
    try {
      corner1 = new Location(leftBottomCorner.getLatitude(RADIANS), leftBottomCorner.getLongitude(RADIANS));
      corner2 = new Location(leftBottomCorner.getLatitude(RADIANS), rightBottomCorner.getLongitude(RADIANS));
      corner3 = new Location(rightBottomCorner.getLatitude(RADIANS), rightBottomCorner.getLongitude(RADIANS));
      corner4 = new Location(rightBottomCorner.getLatitude(RADIANS), leftBottomCorner.getLongitude(RADIANS));
      return new Location[] {corner1, corner2, corner3, corner4};
    } catch (final GeoLocationOutOfBoundException e) {
      /*
       * Never happens, because the leftBottomCorner and the rightBottomCorner
       * are inside the limits.
       */
      LOGGER.debug(e.toString());
      return null;
    }
  }
}
