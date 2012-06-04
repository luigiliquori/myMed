/*
 * Copyright 2012 UNITO 
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
package com.mymed.utils.locator;

import java.util.List;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;

/**
 * Expose the methods to handle the geographical identifiers.
 * 
 * @author iacopo
 */
public class Locator {

  /**
   * Returns the locationId that corresponds to the specified position.
   * 
   * @param latitude
   *          Latitude of the position expressed in micro-degrees.
   * @param longitude
   *          Longitude of the position expressed in micro-degrees.
   * @return The locationId of the specified position.
   * @throws GeoLocationOutOfBoundException
   *           If the specified position is out of the indexed space.
   */
  public static long getLocationId(final int latitude, final int longitude) throws GeoLocationOutOfBoundException {
    final Location loc = new Location(latitude, longitude);
    return HilbertQuad.encode(loc, HilbertQuad.MAX_LEVEL).getIndex();
  }

  /**
   * Gets the areaId corresponding to the specified locationId.
   * 
   * @param locationId
   * @return
   * @throws IllegalArgumentException
   *           If the locationId is not valid.
   */
  public static int getAreaId(final long locationId) throws IllegalArgumentException {
    return KeysFinder.getAreaId(locationId);
  }

  /**
   * Returns a list of location Id ranges, that cover the area defined by
   * latitude longitude and radius.
   * 
   * @param latitude
   *          Latitude of the center of the covered area. (micro-degrees)
   * @param longitude
   *          Longitude of the center of the covered area. (micro-degrees)
   * @param radius
   *          Diameter of the covered area (km)
   * @return
   * @throws GeoLocationOutOfBoundException
   *           Center of the area is out of bounds.
   * @throws IllegalArgumentException
   *           The radius exceeds the maximum size.
   */
  public static List<long[]> getCoveringLocationId(final int latitude, final int longitude, final int radius)
      throws GeoLocationOutOfBoundException, IllegalArgumentException {
    final Location loc = new Location(latitude, longitude);
    final KeysFinder kf = new KeysFinder();
    return kf.getKeysRanges(loc, radius);
  }

  /**
   * Decodes the locationId and returns the corresponding {@link Location}
   * instance.
   * 
   * @param locationId
   * @return
   * @throws IllegalArgumentException
   *           If the locationId is not valid.
   * @throws GeoLocationOutOfBoundException
   *           If the location is out of the indexed area.
   */
  public static Location getLocationFromId(final long locationId) throws IllegalArgumentException,
      GeoLocationOutOfBoundException {
    final HilbertQuad loc = HilbertQuad.decode(locationId);
    return new Location((loc.getCeilLat() + loc.getFloorLat()) / 2, (loc.getCeilLon() + loc.getFloorLon()) / 2);
  }
}