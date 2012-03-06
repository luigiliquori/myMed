package com.mymed.utils;

import android.location.Location;
import com.google.android.maps.GeoPoint;

public class GeoUtils {
	public static double earthRadius = 6371.01d*1E3;
	protected static final double MIN_LAT = Math.toRadians(-80d);  // 
	protected static final double MAX_LAT = Math.toRadians(80d);   //
	protected static final double MIN_LON = Math.toRadians(-180d); // -PI
	protected static final double MAX_LON = Math.toRadians(180d);  //  PI

	/**
	 * Computes the circle of radius @param radius around the current location .
	 * @param radius the radius of the sphere, e.g. the average radius for a
	 * @return 360 points of the circle as GeoPoint[]
	 */
	public static GeoPoint[] getCircle(GeoPoint center,double radius)
	{ 	
		if (radius < 0d)
			throw new IllegalArgumentException("Radius cannot be negative: "+String.valueOf(radius));

		double[] centerDegree = toDegrees(center);
		double locLat = Math.toRadians(centerDegree[0]);
		double locLon = Math.toRadians(centerDegree[1]);
		//angular distance covered on earth's surface
		double d = (double)(radius)/earthRadius;  
		GeoPoint[] points = new GeoPoint[360];
		for (int i = 0; i < 360; i++) 
		{             
			double bearing = i * Math.PI / 180; //rad
			double lat = Math.asin(Math.sin(locLat)*Math.cos(d) + 
					Math.cos(locLat)*Math.sin(d)*Math.cos(bearing));
			double lon = ((locLon + Math.atan2(Math.sin(bearing)*Math.sin(d)*Math.cos(locLat),
					Math.cos(d)-Math.sin(locLat)*Math.sin(lat))) * 180) / Math.PI;
			lat = Math.toDegrees(lat);
			GeoPoint point = new GeoPoint((int) (lat*1E6), (int) (lon*1E6));
			points[i]=point;
		}
		return points;
	}

	/**
	 * Get the bounding box coordinates of the circle with center in {@link center} 
	 * and radius {@link radius}.
	 * @param center	Center of the bounded circle.
	 * @param radius	Radius of the bounded circle.
	 * @return
	 */
	public static GeoPoint[] getBoundingBox(GeoPoint center,double radius) {

		if (radius < 0d)
			throw new IllegalArgumentException("Radius cannot be negative: "+String.valueOf(radius));

		double[] centerDegree = toDegrees(center);
		double radLat = Math.toRadians(centerDegree[0]);
		double radLon = Math.toRadians(centerDegree[1]);

		// angular distance in radians on a great circle
		double radDist = radius / earthRadius;

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
		return boxToGeoPoints(Math.toDegrees(minLat),Math.toDegrees(minLon),
				Math.toDegrees(maxLat),Math.toDegrees(maxLon));
	}

	private static GeoPoint[] boxToGeoPoints(double minLat,double minLon,
			double maxLat,double maxLon){
		GeoPoint point1 = new GeoPoint((int) (minLat*1E6),
				(int) (minLon*1E6));
		GeoPoint point2 = new GeoPoint((int) (minLat*1E6),
				(int) (maxLon*1E6));
		GeoPoint point3 = new GeoPoint((int) (maxLat*1E6),
				(int) (maxLon*1E6));
		GeoPoint point4 = new GeoPoint((int) (maxLat*1E6),
				(int) (minLon*1E6));
		return new GeoPoint[]{point1,point2,point3,point4};
	}
	
	/**
	 * Computes the great circle distance between {@link src}
	 * and {@link dest}.
	 * @param radius the radius of the sphere, e.g. the average radius for a
	 * spherical approximation of the figure of the Earth is approximately
	 * 6371.01 kilometers.
	 * @return the distance, measured in meters
	 */
	public static double getGCDistance(GeoPoint src,GeoPoint dst){
		double[] srcDeg = toRadians(src);
		double[] dstDeg = toRadians(dst);
		
		return Math.acos(Math.sin(dstDeg[0]) * Math.sin(srcDeg[0]) +
				Math.cos(dstDeg[0]) * Math.cos(srcDeg[0]) *
				Math.cos(dstDeg[1] - srcDeg[1])) * earthRadius;
	}

	/**
	 * Converts the {@link GeoPoint} coordinates, from micro-degrees to degrees.
	 * @param p
	 * @return
	 * @throws IllegalArgumentException
	 */
	private static double[] toDegrees(GeoPoint p) throws IllegalArgumentException {
		double degLat = (double) (p.getLatitudeE6()*1.0/1E6);
		double degLon = (double) (p.getLongitudeE6()*1.0/1E6);
		double radLat = Math.toRadians(degLat);
		double radLon = Math.toRadians(degLon);

		if (radLat < MIN_LAT || radLat > MAX_LAT ||
				radLon < MIN_LON || radLon > MAX_LON)
			throw new IllegalArgumentException("Location is out of bound. Latitude: "+String.valueOf(degLat)
					+" Longitude: "+String.valueOf(degLat));
		return new double[]{degLat,degLon};
	}
	
	/**
	 * Converts the {@link GeoPoint} coordinates, from micro-degrees to radians.
	 * @param p
	 * @return
	 * @throws IllegalArgumentException
	 */
	private static double[] toRadians(GeoPoint p) throws IllegalArgumentException {
		double degLat = (double) (p.getLatitudeE6()*1.0/1E6);
		double degLon = (double) (p.getLongitudeE6()*1.0/1E6);
		double radLat = Math.toRadians(degLat);
		double radLon = Math.toRadians(degLon);

		if (radLat < MIN_LAT || radLat > MAX_LAT ||
				radLon < MIN_LON || radLon > MAX_LON)
			throw new IllegalArgumentException("Location is out of bound. Latitude: "+String.valueOf(degLat)
					+" Longitude: "+String.valueOf(degLat));
		return new double[]{radLat,radLon};
	}
	/**
	 * Convert the coordinates of {@link loc} in micro-degrees and return a {@link GeoPoint}.
	 * @param loc Location to convert.
	 * @return
	 */
	public static GeoPoint toGeoPoint(Location loc){
		return new GeoPoint((int) (loc.getLatitude()*1E6),(int) (loc.getLongitude()*1E6));
	}

}
