package com.mymed.utils.locator;

import java.util.List;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;

/**
 * Expose the methods to handle the geographical identifiers.
 * @author iacopo
 *
 */
public class Locator {
	
	/**
	 * Returns the locationId that corresponds to the specified position.
	 * 
	 * @param latitude	Latitude of the position expressed in micro-degrees.
	 * @param longitude Longitude of the position expressed in micro-degrees.
	 * @return The locationId of the specified position.
	 * @throws GeoLocationOutOfBoundException If the specified position is out of the indexed space.
	 */
	public static long getLocationId(int latitude,int longitude) throws GeoLocationOutOfBoundException{
		Location loc= new Location(latitude,longitude);		
		return HilbertQuad.encode(loc, HilbertQuad.maxLevel).getIndex();
	}
	/**
	 * Gets the areaId corresponding to the specified locationId.
	 * 
	 * @param locationId
	 * @return
	 * @throws IllegalArgumentException If the locationId is not valid.
	 */
	public static int getAreaId(long locationId) throws IllegalArgumentException{
		return KeysFinder.getAreaId(locationId);
	}
	
	/**
	 * Returns a list of location Id ranges, that cover the area defined by latitude 
	 * longitude and radius.
	 * 
	 * @param latitude Latitude of the center of the covered area. (micro-degrees)
	 * @param longitude Longitude of the center of the covered area. (micro-degrees)
	 * @param radius Diameter of the covered area (km)
	 * @return
	 * @throws GeoLocationOutOfBoundException Center of the area is out of bounds.
	 * @throws IllegalArgumentException The radius exceeds the maximum size.
	 */
	public static List<long[]> getCoveringLocationId(int latitude,int longitude,int radius) throws GeoLocationOutOfBoundException,
			IllegalArgumentException{
		
		Location loc= new Location(latitude,longitude);
		KeysFinder kf = new KeysFinder();		
		return kf.getKeysRanges(loc, radius);		
	}
	
	/**
	 * Decodes the locationId and returns the corresponding {@link Location} instance.
	 * 
	 * @param locationId
	 * @return
	 * @throws IllegalArgumentException If the locationId is not valid.
	 * @throws GeoLocationOutOfBoundException	If the location is out of the indexed area.
	 */
	public static Location getLocationFromId(long locationId) throws IllegalArgumentException,GeoLocationOutOfBoundException{
		HilbertQuad loc = HilbertQuad.decode(locationId);
		return new Location((loc.getCeilLat()+loc.getFloorLat())/2,(loc.getCeilLon()+loc.getFloorLon())/2);
	}
}
