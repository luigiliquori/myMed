package com.mymed.tests.stress;


import java.util.LinkedList;
import java.util.List;

import ch.qos.logback.classic.Logger;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.utils.MLogger;
import com.mymed.utils.locator.Location;

public class GeoLocationThread extends Thread {

	private static final Logger LOGGER = MLogger.getLogger();
	private final int numInsertion;
	private final int numSearch;
	private final Location center;
	private final int radius;
	private final List<MSearchBean> cleanItem;

	/**
	 * Create the new session thread
	 * @throws GeoLocationOutOfBoundException 
	 */
	public GeoLocationThread() throws GeoLocationOutOfBoundException {
		this(100 , 100, 44000000, 7000000, 50000);
	}

	/**
	 * Create the new session thread, but do not perform the remove thread, only
	 * add new session to the database
	 * 
	 * @param remove
	 *          if to perform the remove thread or not
	 * @param maxElements
	 *          the maximum number of elements to create
	 * @throws GeoLocationOutOfBoundException 
	 */
	public GeoLocationThread(final int numInsertion, final int numSearch, final int centerLat, final int centerLon, final int radius) throws GeoLocationOutOfBoundException {
		super();
		this.numInsertion = numInsertion;
		this.numSearch = numSearch;
		this.center = new Location(centerLat,centerLon);
		this.radius = radius;	
		this.cleanItem = new LinkedList<MSearchBean>();
	}
		
	@Override
	public void run() {
		GeoLocationTest geoLocationTest = new GeoLocationTest(center,radius);
		
		try {
			//Insert the items in the database
			for (int i=0;i<numInsertion;i++){
				MSearchBean currItem = geoLocationTest.insertLocalizedItem();
				if (currItem!=null)
					cleanItem.add(currItem);
			}
			//Perform the test.
			long startTime = System.currentTimeMillis();
			for (int i=0;i<numSearch;i++){
				geoLocationTest.searchItems(center, radius);
			}
			long endTime = System.currentTimeMillis();
			//Clean up
			for(MSearchBean currItem:cleanItem){
				geoLocationTest.deleteItem(currItem.getLocationId(), currItem.getId());
			}
			LOGGER.info("Test conditions: \n" +
					"	Num items: {}\n" +
					"	Num search operations performed: {}\n"+
					"	Search radius [m]: {}\n"+
					"	First search duration [ms]: {}\n"+
					"	Search operation average duration [ms]: {}\n"+
					"	Test duration [ms]: {}\n",
					
					new Object[]{String.valueOf(numInsertion),String.valueOf(numSearch),String.valueOf(radius),
					String.valueOf(geoLocationTest.getFirstSearchDuration()),String.valueOf(geoLocationTest.getDurationAverage()),
					String.valueOf(endTime-startTime)});
		} catch (Exception e) {
			e.printStackTrace();
			LOGGER.debug("Error :\n	{}", e.toString());
		}
		
	}
}