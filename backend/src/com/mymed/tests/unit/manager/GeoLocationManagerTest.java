package com.mymed.tests.unit.manager;

import static org.junit.Assert.*;

import java.util.LinkedList;
import java.util.List;
import java.util.Random;

import org.junit.After;
import org.junit.Before;
import org.junit.Test;


import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.geolocation.GeoLocationManager;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.id.MyMedId;
import com.mymed.utils.locator.Location;
import com.mymed.utils.locator.Locator;

public class GeoLocationManagerTest extends GeneralTest {
	private final static String TAG = "GeoLocationManagerTest";
	private final static String ITEM = "testItem";
	private static long SEED = 19580427;

	private final static double centerLat = 45.15;
	private final static double centerLon = 7.5;
	private final static double degreeRange = 2.0;
	private final static int meterRange = 20000;
	private final static int numInsertionSearchTest = 100000;
	private final static int numInsertionDeleteTest = 100;

	private GeoLocationManager manager;
	private List<MyMedId> itemsInRange;
	List<MSearchBean> cleanList;
	private Random rand;
	private double maxError;
	Location centerL;

	@Before
	public void setUp() throws InternalBackEndException {
		super.setUp();
		try {
			if (manager==null)
				this.manager = new GeoLocationManager();
			if (itemsInRange==null)
				itemsInRange = new LinkedList<MyMedId>();
			if (rand == null)
				rand = new Random(SEED);
			if (cleanList==null)
				cleanList = new LinkedList<MSearchBean>();
		}catch(final Exception e){
			e.printStackTrace();
		}
	}

	/**
	 * Insert {@value numInsertionSearchTest} values and checks that the search returns the right items.
	 */
	@Test
	public void testSearch(){
		List<MSearchBean> listSearchBean = null;
		double err = 10.0;
		Location currLocDec = null;

		try{
			/** Maybe that some item is already there. */
			listSearchBean = this.manager.read(TAG, ITEM, (int) (centerLat*1E6), (int) (centerLon*1E6), meterRange, true);
			for (MSearchBean sRep :listSearchBean){
				itemsInRange.add(MyMedId.parseString(sRep.getId())); 
			}

			for (int i=0;i<numInsertionSearchTest;i++){
				int lat = (int) ((rand.nextDouble()*degreeRange +(centerLat-degreeRange/2))*1E6); 
				int lon = (int) ((rand.nextDouble()*degreeRange +(centerLon-degreeRange/2))*1E6);
				centerL = new Location((int) (centerLat*1E6),(int) (centerLon*1E6));
				Location currLoc = new Location(lat,lon);
				currLocDec = Locator.getLocationFromId(Locator.getLocationId(lat, lon));
				if((err = currLoc.distanceGCTo(currLocDec))>maxError)
					maxError = err;

				MyMedId currRepId = null;
				MSearchBean repB = this.manager.create(TAG, ITEM, "iacopo",lat,lon, "Ciao", 60*30);
				cleanList.add(repB);
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

			listSearchBean = this.manager.read(TAG, ITEM, (int) (centerLat*1E6), (int) (centerLon*1E6), meterRange, true);
			if (listSearchBean.size()!=itemsInRange.size())
				fail("Number of found items doesn't match the number of inserted.");
			for (MSearchBean tmpSearchBean:listSearchBean){
				if (!itemsInRange.contains(MyMedId.parseString(tmpSearchBean.getId())))					
					fail("Item found doesn't belongs to items in range.");
			}
		}catch(final Exception e){
			fail(e.getMessage());
		}
	}

	//TODO Check delete.
	@Test
	public void testDelete(){
		List<MSearchBean> deleteList = new LinkedList<MSearchBean>();
		try{
			for (int i=0;i<numInsertionDeleteTest;i++){
				double lat = rand.nextDouble()*degreeRange +(centerLat-degreeRange/2); 
				double lon = rand.nextDouble()*degreeRange +(centerLon-degreeRange/2);
				centerL = new Location(Math.toRadians(centerLat),Math.toRadians(centerLon));
				deleteList.add(this.manager.create(TAG, ITEM, "iacopo",(int) (lat*1E6),(int) (lon*1E6), "Ciao", 60*30));
			}

			for (MSearchBean curr: deleteList){
				this.manager.delete(TAG, ITEM, curr.getLocationId(), curr.getId());
				try{
					this.manager.read(TAG, ITEM, curr.getLocationId(), curr.getId());
					fail("Item "+curr.getId()+" has not been properly deleted.");
				}catch(Exception e){	
				}
			}
		}catch(final Exception e){
			fail(e.getMessage());
		}
	}

	@After
	public void cleanUp() {
		super.cleanUp();
		for (MSearchBean tmpSearchBean:cleanList){
			try{
				this.manager.delete(TAG, ITEM, tmpSearchBean.getLocationId(), tmpSearchBean.getId());
			}catch(Exception e){
				e.printStackTrace();
			}
		}
		cleanList.clear();
	}
}
