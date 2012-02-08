package com.mymed.utils.locator;

public class Location {
	public static double earthRadius = 6371.01d*1E3;

	public Location(double latitude,double longitude) throws GeoLocationOutOfBoundException {
		//this.setLatitude(latitude);
		//this.setLongitude(longitude);
		this.radLat = Math.toRadians(latitude);
		this.radLon = Math.toRadians(longitude);
		this.checkBounds();
	}

	private double radLat;  // latitude in radians
	private double radLon;  // longitude in radians
	
	/*
	 * 	The latitude is limited to the range (-80,+80)
	 */
	protected static final double MIN_LAT = Math.toRadians(-80d);  // 
	protected static final double MAX_LAT = Math.toRadians(80d);   //
	protected static final double MIN_LON = Math.toRadians(-180d); // -PI
	protected static final double MAX_LON = Math.toRadians(180d);  //  PI



	/**
	 * @param latitude the latitude, in degrees.
	 * @param longitude the longitude, in degrees.
	 * @throws GeoLocationOutOfBoundException 
	 */
	public static Location fromDegrees(double latitude, double longitude, String provider) throws GeoLocationOutOfBoundException {
		Location result = new Location(latitude,longitude);
		result.checkBounds();
		return result;
	}

	/**
	 * @param latitude the latitude, in radians.
	 * @param longitude the longitude, in radians.
	 * @throws GeoLocationOutOfBoundException 
	 */
	public static Location fromRadians(double latitude, double longitude, String provider) throws GeoLocationOutOfBoundException {
		Location result = new Location(Math.toDegrees(latitude),Math.toDegrees(longitude));
		result.checkBounds();
		return result;
	}

	private void checkBounds() throws GeoLocationOutOfBoundException {
		if (radLat < MIN_LAT || radLat > MAX_LAT ||
				radLon < MIN_LON || radLon > MAX_LON)
			throw new GeoLocationOutOfBoundException("Location is out of bound. Latitude: "+String.valueOf(this.getLatitude())
					+" Longitude: "+String.valueOf(this.getLongitude()));
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
	public double getLongitude() {
		return Math.toDegrees(radLon);
	}
	
	/**
	 * @return the latitude, in radians.
	 */
	public double getLatitude() {
		return Math.toDegrees(radLat);
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
	public double distanceGCTo(Location location) {
		return Math.acos(Math.sin(radLat) * Math.sin(location.radLat) +
				Math.cos(radLat) * Math.cos(location.radLat) *
				Math.cos(radLon - location.radLon)) * earthRadius;
	}	
	
	/**
	 * Finds the coordinates of the bounding box of the circle with radius distance 
	 * @param distance The radius of the circle whose bounding box is found.
	 * @return The bottom-left and the top-right corner of the bounding box.
	 * @throws GeoLocationOutOfBoundException 
	 */
	public Location[] boundingCoordinates(double distance) {

		if (distance < 0d)
			throw new IllegalArgumentException("Distance cannot be negative: "+String.valueOf(distance));

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
		try {
			return new Location[]{fromRadians(minLat,minLon,""),
					fromRadians(maxLat,maxLon,"")};
		} catch (GeoLocationOutOfBoundException e) {
			//Never happen because maxLat is limited.
			e.printStackTrace();
			return null;
		}
	}
	
	/**
	 * Returns the four corners of the bounding box given the  bottom-left and the top-right corner 
	 * @param leftBottomCorner
	 * @param rightBottomCorner
	 * @return the four corners of the bounding box given the  bottom-left and the top-right corner
	 * @throws GeoLocationOutOfBoundException 
	 */
	protected static Location[] getCorners(Location leftBottomCorner,
			Location rightBottomCorner){
		Location corner1,corner2,corner3,corner4;
		try {
			corner1 = new Location(leftBottomCorner.getLatitude(),
					leftBottomCorner.getLongitude());
			corner2 = new Location(leftBottomCorner.getLatitude(),
					rightBottomCorner.getLongitude());
			corner3 = new Location(rightBottomCorner.getLatitude(),
					rightBottomCorner.getLongitude());
			corner4 = new Location(rightBottomCorner.getLatitude(),
					leftBottomCorner.getLongitude());
			return new Location[]{corner1,corner2,corner3,corner4};
		} catch (GeoLocationOutOfBoundException e) {
			/*
			 *  Never happens, because the leftBottomCorner and the rightBottomCorner are inside the
			 *  limits.
			 */
			e.printStackTrace();
			return null;
		}


	}
}
