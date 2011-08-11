package com.mymed.android.myjam.ui;

import android.location.Location;
import com.google.android.maps.GeoPoint;

public class GeoPointsFactory {
	public static double earthRadius = 6371.01d*1E3;
	
	/**
	 * Computes the circle of radius @param radius around the current location .
	 * @param radius the radius of the sphere, e.g. the average radius for a
	 * @return 360 points of the circle as GeoPoint[]
	 */
	public static GeoPoint[] getCircle(Location loc,double radius)
	{ 	
	  double locLat;
	  double locLon;
	  //angular distance covered on earth's surface
	  double d = (double)(radius)/earthRadius;  
	  GeoPoint[] points = new GeoPoint[360];
	  locLat = Math.toRadians(loc.getLatitude());
	  locLon = Math.toRadians(loc.getLongitude());
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
	
	public static GeoPoint[] boxToGeoPoints(double minLat,double minLon,
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
	
	public static GeoPoint getGeoPoint(Location point){
		return new GeoPoint((int) (point.getLatitude()*1E6),(int) (point.getLongitude()*1E6));
	}
	
	public static GeoPoint toGeoPoint(Location loc){
		return new GeoPoint((int) (loc.getLatitude()*1E6),(int) (loc.getLongitude()*1E6));
	}
	
	public static double[] fromGeoPoint(GeoPoint geoP){
		return new double[]{(double) (geoP.getLatitudeE6()*1.0/1E6),
				(double) (geoP.getLongitudeE6()*1.0/1E6)};
	}

}
