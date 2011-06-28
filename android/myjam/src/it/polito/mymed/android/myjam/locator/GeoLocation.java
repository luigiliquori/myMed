package it.polito.mymed.android.myjam.locator;
import android.location.Location;

public class GeoLocation extends Location{
	public static double earthRadius = 6371.01d*1E3;

	public GeoLocation(Double latitude,Double longitude,String provider) {
		super(provider);
		this.setLatitude(latitude);
		this.setLongitude(longitude);
		this.radLat = Math.toRadians(latitude);
		this.radLon = Math.toRadians(longitude);
		this.checkBounds();
	}

	private double radLat;  // latitude in radians
	private double radLon;  // longitude in radians
	
	/*
	 * 	The latitude is limited to the range (-80,+80)
	 */
	private static final double MIN_LAT = Math.toRadians(-80d);  // 
	private static final double MAX_LAT = Math.toRadians(80d);   //
	private static final double MIN_LON = Math.toRadians(-180d); // -PI
	private static final double MAX_LON = Math.toRadians(180d);  //  PI



	/**
	 * @param latitude the latitude, in degrees.
	 * @param longitude the longitude, in degrees.
	 */
	public static GeoLocation fromDegrees(double latitude, double longitude, String provider) {
		GeoLocation result = new GeoLocation(latitude,longitude,provider);
		result.radLat = Math.toRadians(latitude);
		result.radLon = Math.toRadians(longitude);
		result.checkBounds();
		return result;
	}

	/**
	 * @param latitude the latitude, in radians.
	 * @param longitude the longitude, in radians.
	 */
	public static GeoLocation fromRadians(double latitude, double longitude, String provider) {
		GeoLocation result = new GeoLocation(Math.toDegrees(latitude),Math.toDegrees(longitude),provider);
		result.checkBounds();
		return result;
	}

	private void checkBounds() {
		if (radLat < MIN_LAT || radLat > MAX_LAT ||
				radLon < MIN_LON || radLon > MAX_LON)
			throw new IllegalArgumentException("Variable out of bonds.");
	}

	/**
	 * @return the latitude, in radians.
	 */
	public double getLatitudeInRadians() {
		return radLat;
	}

	/**
	 * @return the longitude, in radians.
	 */
	public double getLongitudeInRadians() {
		return radLon;
	}

	@Override
	public String toString() {
		return "(" + this.getLatitude() + "\u00B0, " + this.getLongitude() + "\u00B0) = (" +
				 radLat + " rad, " + radLon + " rad)";
	}

	/**
	 * Computes the great circle distance between this GeoLocation instance
	 * and the location argument.
	 * @param radius the radius of the sphere, e.g. the average radius for a
	 * spherical approximation of the figure of the Earth is approximately
	 * 6371.01 kilometers.
	 * @return the distance, measured in meters
	 */
	public double distanceGCTo(GeoLocation location) {
		return Math.acos(Math.sin(radLat) * Math.sin(location.radLat) +
				Math.cos(radLat) * Math.cos(location.radLat) *
				Math.cos(radLon - location.radLon)) * earthRadius;
	}
	
	/**
	 * Returns the distance corresponding to a certain variation of longitude, at a certain latitude.
	 * The max variation of longitude is 180 degree.
	 * This implementation uses a spherical model of the earth.
	 * @param lat	Latitude.
	 * @param deltaLon Variation of longitude.
	 * @param degree If true the measures are expressed in degree, else in radians.
	 * @return The distance in m corresponding to the variation of longitude.
	 */
	public static double getWidth(double lat,double deltaLon,boolean degree){
		double distance = 0;
		if (degree){
			lat = Math.toRadians(lat);
			deltaLon = Math.toRadians(deltaLon);
		}
		if (lat < MIN_LAT || lat > MAX_LAT ||
				deltaLon > Math.PI)
			throw new IllegalArgumentException("Variable out of bonds.");
		distance =Math.acos(1-Math.pow(Math.cos(lat),2)*(1-Math.cos(deltaLon))) * earthRadius;
		return distance;
	}
	
	/**
	 * Returns the distance corresponding to a certain variation of latitude, at a certain latitude.
	 * The max variation of longitude is 180 degree.
	 * This implementation uses a spherical model of the earth.
	 * @param lat	Latitude.
	 * @param deltaLon Variation of longitude.
	 * @param degree If true the measures are expressed in degree, else in radians.
	 * @return The distance in m corresponding to the variation of longitude.
	 */
	public static double getHeigh(double lat,double deltaLat,boolean degree){
		double distance = 0;
		if (degree){
			lat = Math.toRadians(lat);
			deltaLat = Math.toRadians(deltaLat);
		}
		if (lat < MIN_LAT || lat > MAX_LAT ||
				deltaLat > Math.PI/2)
			throw new IllegalArgumentException("Variable out of bonds.");
		distance = deltaLat * earthRadius;
		return distance;
	}
	
	/**
	 * Finds the coordinates of the bounding box of the circle with radius distance 
	 * @param distance The radius of the circle whose bounding box is found.
	 * @return The bottom-left and the top-right corner of the bounding box.
	 */
	public GeoLocation[] boundingCoordinates(double distance) {

		if (distance < 0d)
			throw new IllegalArgumentException("Distance cannot be negative.");

		// angular distance in radians on a great circle
		double radDist = distance / earthRadius;

		double minLat = radLat - radDist;
		double maxLat = radLat + radDist;

		double minLon, maxLon;

		double deltaLon = Math.asin(Math.sin(radDist) /
				Math.cos(radLat));
		minLon = radLon - deltaLon;
		if (minLon < MIN_LON) 
			minLon += 2d * Math.PI;
		maxLon = radLon + deltaLon;
		if (maxLon > MAX_LON) 
			maxLon -= 2d * Math.PI;
		if (maxLat > MAX_LAT) {
			maxLat = Math.min(maxLat, MAX_LAT);
		}
		if (minLat < MIN_LAT) {
			minLat = Math.max(minLat, MIN_LAT);		
		}
		return new GeoLocation[]{fromRadians(minLat,minLon,""),
				fromRadians(maxLat,maxLon,"")};
	}
	
	/**
	 * Returns the four corners of the bounding box given the  bottom-left and the top-right corner 
	 * @param leftBottomCorner
	 * @param rightBottomCorner
	 * @return the four corners of the bounding box given the  bottom-left and the top-right corner
	 */
	public static GeoLocation[] getCorners(GeoLocation leftBottomCorner,
			GeoLocation rightBottomCorner){
		GeoLocation corner1 = new GeoLocation(leftBottomCorner.getLatitude(),
				leftBottomCorner.getLongitude(),"");
		GeoLocation corner2 = new GeoLocation(leftBottomCorner.getLatitude(),
				rightBottomCorner.getLongitude(),"");
		GeoLocation corner3 = new GeoLocation(rightBottomCorner.getLatitude(),
				rightBottomCorner.getLongitude(),"");
		GeoLocation corner4 = new GeoLocation(rightBottomCorner.getLatitude(),
				leftBottomCorner.getLongitude(),"");
		return new GeoLocation[]{corner1,corner2,corner3,corner4};
	}
}
