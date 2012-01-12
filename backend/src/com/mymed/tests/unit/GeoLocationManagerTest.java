package com.mymed.tests.unit;

import java.util.LinkedList;
import java.util.List;
import java.util.Random;

import junit.framework.TestCase;

import com.mymed.controller.core.manager.geolocation.GeoLocationManager;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.id.MyMedId;
import com.mymed.utils.locator.Location;
import com.mymed.utils.locator.Locator;

public class GeoLocationManagerTest extends TestCase {
	private final static String TAG = "GeoLocationManagerTest";
	private final static String ITEM = "test";
	private static long SEED = 19580427;
	
	private final static double centerLat = 45.15;
	private final static double centerLon = 7.5;
	private final static double degreeRange = 2.0;
	private final static int meterRange = 50000;
	private final static int numInsertionSearchTest = 10000;
	
	private GeoLocationManager manager;
	private List<MyMedId> itemsInRange;
	private Random rand;
	private double maxError;
	Location centerL;
	
	protected void setUp() {
		try {
			if (manager==null)
				this.manager = new GeoLocationManager();
			if (itemsInRange==null)
				itemsInRange = new LinkedList<MyMedId>();
			if (rand == null)
				rand = new Random(SEED);
		}catch(Exception e){
			e.printStackTrace();
		}
	}
	
	/**
	 * Insert {@value numInsertionSearchTest} values and checks that the search returns the right items.
	 */
	public void testSearch(){
		List<MSearchBean> listSearchBean = null;
		double err = 10.0;
		Location currLocDec = null;

		try{
			/** Maybe that some item is already there. */
			listSearchBean = this.manager.read(TAG, ITEM, (int) (centerLat*1E6), (int) (centerLon*1E6), meterRange);
			for (MSearchBean sRep :listSearchBean){
				itemsInRange.add(MyMedId.parseString(sRep.getId())); 
			}

			for (int i=0;i<numInsertionSearchTest;i++){
				double lat = rand.nextDouble()*degreeRange +(centerLat-degreeRange/2); 
				double lon = rand.nextDouble()*degreeRange +(centerLon-degreeRange/2);
				centerL = new Location(centerLat,centerLon);
				Location currLoc = new Location(lat,lon);
				currLocDec = Locator.getLocationFromId(Locator.getLocationId(lat, lon));
				if((err = currLoc.distanceGCTo(currLocDec))>maxError)
					maxError = err;

				MyMedId currRepId = null;
				MSearchBean repB = this.manager.create(TAG, ITEM, "iacopo",(int) (lat*1E6),(int) (lon*1E6), "Ciao", 60*30);
				currRepId = MyMedId.parseString(repB.getId());
				if (currRepId!=null)
					System.out.println(TAG+"	All : "+currRepId.toString());
				if (currLocDec!=null){
					double distance = currLocDec.distanceGCTo(centerL);
					System.out.println(TAG+"	Item "+i+": "+String.valueOf(distance));
					if (distance<=meterRange){
						if (currRepId!=null){
							itemsInRange.add(currRepId);
							System.out.println(TAG+"	Inserted : "+currRepId.toString());	
						}
					}
				}
			}
			
			listSearchBean = this.manager.read(TAG, ITEM, (int) (centerLat*1E6), (int) (centerLon*1E6), meterRange);
			if (listSearchBean.size()!=itemsInRange.size())
				fail("Number of found items doesn't match the number of inserted.");
			for (MSearchBean tmpSearchBean:listSearchBean){
				if (!itemsInRange.contains(MyMedId.parseString(tmpSearchBean.getId())))
					fail("Item found doesn't belongs to items in range.");
			}

		}catch(Exception e){
			fail(e.getMessage());
		}
	}

	//TODO Check delete.
	public void testDelete(){
		
	}
	
//	private int[] getPoint(){
//		int[] point = new int[]{centerLat,centerLon};
//		double deltaLat = (double) distance/earthRadius;
//		double deltaLon = Math.acos(Math.cos((double) distance/earthRadius)/Math.pow(Math.cos((double) centerLat/1E6),2.0) - Math.pow(Math.tan((double) centerLat/1E6),2.0));
//		
//		int latOffset = (int) ((rand.nextDouble()*deltaLat - deltaLat/2)*1E6);
//		int lonOffset = (int) ((rand.nextDouble()*deltaLon - deltaLon/2)*1E6); 
//		point[0] = centerLat + latOffset;
//		point[1] = centerLon + lonOffset;
//		
//		return point;
//	}
}
