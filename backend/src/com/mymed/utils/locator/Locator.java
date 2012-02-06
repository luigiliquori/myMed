package com.mymed.utils.locator;

import java.util.List;

/**
 * Expose the methods to handle the geographical identifiers.
 * @author iacopo
 *
 */
public class Locator {
	/**
	 * Returns the location Id that corresponds to the specified position.
	 * @param latitude	Latitude of the position.
	 * @param longitude Longitude of the position.
	 * @return
	 * @throws GeoLocationOutOfBoundException
	 */
	public static long getLocationId(double latitude,double longitude) throws GeoLocationOutOfBoundException{
		Location loc= new Location(latitude,longitude);		
		return HilbertQuad.encode(loc, HilbertQuad.maxLevel).getIndex();
	}
	/**
	 * Gets the area Id corresponding to a certain location Id.
	 * @param locationId
	 * @return
	 * @throws IllegalArgumentException
	 */
	public static long getAreaId(long locationId) throws IllegalArgumentException{
		return KeysFinder.getAreaId(locationId);
	}
	
	/**
	 * Returns a list of location Id ranges, that cover the area defined by latitude 
	 * longitude and radius.
	 * @param latitude Latitude of the center of the covered area. (degrees)
	 * @param longitude Longitude of the center of the covered area. (degrees)
	 * @param radius Diameter of the covered area (km)
	 * @return
	 * @throws GeoLocationOutOfBoundException Center of the area is out of bounds.
	 * @throws IllegalArgumentException The radius exceeds the maximum size.
	 */
	public static List<long[]> getCoveringLocationId(double latitude,double longitude,int radius) throws GeoLocationOutOfBoundException,
			IllegalArgumentException{
		
		Location loc= new Location(latitude,longitude);
		KeysFinder kf = new KeysFinder();		
		return kf.getKeysRanges(loc, radius);		
	}
	
	/**
	 * Decode the location Id and returns the corresponding location (latitude and longitude in degrees).
	 * @param locationId
	 * @return
	 * @throws IllegalArgumentException
	 * @throws GeoLocationOutOfBoundException
	 */
	public static Location getLocationFromId(long locationId) throws IllegalArgumentException,GeoLocationOutOfBoundException{
		HilbertQuad loc = HilbertQuad.decode(locationId);
		return new Location((loc.getCeilLat()+loc.getFloorLat())/2,(loc.getCeilLon()+loc.getFloorLon())/2);
	}
}
