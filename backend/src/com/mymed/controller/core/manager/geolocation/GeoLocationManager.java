package com.mymed.controller.core.manager.geolocation;


import java.nio.ByteBuffer;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.WrongFormatException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.id.MyMedId;

import com.mymed.model.data.storage.ExpColumnBean;
import com.mymed.utils.MConverter;
import com.mymed.utils.locator.GeoLocationOutOfBoundException;
import com.mymed.utils.locator.Location;
import com.mymed.utils.locator.Locator;


/**
 * Manages the geo-localization.
 * 
 * @author iacopo
 *
 */
public class GeoLocationManager extends AbstractManager {

	public GeoLocationManager() throws InternalBackEndException {
		this(new MyJamStorageManager());
	}

	public GeoLocationManager(final MyJamStorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	/**
	 * Insert a new located object into the database.
	 * 
	 * @param applicationId Id of the application.
	 * @param objId	Id of the located object. Its scope is the application which it belongs to.
	 * @param latitude Latitude in micro-degrees.
	 * @param longitude Longitude in micro-degrees.
	 * @param value Textual value (Can be null.)
	 * @param permTime Permanence time in seconds.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public MSearchBean create(final String applicationId, final String objType, final String userLogin, final int latitude, final int longitude, 
			final String value, final int permTime) throws InternalBackEndException, IOBackEndException {
		try {
			/**
			 * Data preparation
			 */
			long locId;

			locId = Locator.getLocationId((double) (latitude/1E6),
					(double) (longitude/1E6));
			String areaId = String.valueOf(Locator.getAreaId(locId));
			long timestamp = System.currentTimeMillis();				
			MyMedId id = new MyMedId(Character.toLowerCase(objType.charAt(0)),timestamp,userLogin);

			/** Create a MSearchBean instance to be returned. */
			MSearchBean searchBean = new MSearchBean();
			searchBean.setId(id.toString());
			searchBean.setLocationId(locId);
			searchBean.setLatitude(latitude);
			searchBean.setLatitude(longitude);
			searchBean.setValue(value);
			searchBean.setDate(timestamp);
			/** If the TTL has been specified, the expiration time is set on the bean. */
			if (permTime != 0)
				searchBean.setExpirationDate(timestamp + (long) (permTime*1000000));
			/** In cassandra is used the convention of microseconds since 1 Jenuary 1970. */
			timestamp = (long) (timestamp*1E3);

			/**
			 * SuperColumn insertion in CF Location 
			 **/
			myJamStorageManager.insertExpiringColumn("Location", applicationId+objType+areaId, MConverter.longToByteBuffer(locId).array(), 
					id.AsByteBuffer().array(),
					MConverter.stringToByteBuffer(value).array(),
					timestamp, permTime);
			
			return searchBean;
		} catch (GeoLocationOutOfBoundException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}

	/**
	 * Search located objects in a circular region specified by latitude, longitude and radius.
	 * 
	 * @param applicationId Id of the application.
	 * @param objId Id of the located object.
	 * @param latitude Latitude in micro-degrees.
	 * @param longitude Longitude in micro-degrees.
	 * @param radius Radius of the search in meters.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public List<MSearchBean> read(final String applicationId, final String objType, final int latitude, final int longitude,
			final int radius) throws InternalBackEndException, IOBackEndException {
		List<MSearchBean> resultReports = new LinkedList<MSearchBean>();
		try{
			/**
			 * Data structures preparation
			 */
			Map<byte[],Map<byte[],byte[]>> reports = new HashMap<byte[],Map<byte[],byte[]>>();
			List<long[]> covId = Locator.getCoveringLocationId((double) (latitude/1E6), (double) (longitude/1E6), radius);
			List<String> areaIds = new LinkedList<String>();
			Iterator<long[]> covIdIterator = covId.iterator();
			/**
			 * Cassandra calls
			 */
			while(covIdIterator.hasNext()){
				long[] range = covIdIterator.next();
				long startAreaId = Locator.getAreaId(range[0]);
				long endAreaId = Locator.getAreaId(range[1]);
				for (long ind=startAreaId;ind<=endAreaId;ind++){
					areaIds.add(applicationId+objType+String.valueOf(ind));
				}
				Map<byte[],Map<byte[],byte[]>> mapRep = myJamStorageManager.selectSCRange("Location", areaIds, MConverter.longToByteBuffer(range[0]).array(),
						MConverter.longToByteBuffer(range[1]).array()); 
				reports.putAll(mapRep);
				areaIds.clear();
			}
			/**
			 * Data processing: Filters the results of the search.
			 */
			for (byte[] scName:reports.keySet()){
				long posId = MConverter.byteBufferToLong(ByteBuffer.wrap(scName));
				Location reportLoc = Locator.getLocationFromId(posId);
				double distance = reportLoc.distanceGCTo(new Location((double) (latitude/1E6),(double) (longitude/1E6)));
				/** Distance check */
				if (distance <= radius){
					for (byte[] colName : reports.get(scName).keySet()){
						MSearchBean searchBean = new MSearchBean();
						MyMedId repId = MyMedId.parseByteBuffer(ByteBuffer.wrap(colName));
						searchBean.setValue(MConverter.byteBufferToString(
								ByteBuffer.wrap(reports.get(scName).get(colName))));
						searchBean.setId(repId.toString());
						searchBean.setLatitude((int) (reportLoc.getLatitude()*1E6));
						searchBean.setLongitude((int) (reportLoc.getLongitude()*1E6));
						searchBean.setDistance((int) distance);
						/** The timestamp is set with the convention used in Java (milliseconds from 1 January 1970) */
						searchBean.setDate((repId.getTimestamp()));
						resultReports.add(searchBean);
					}
				}
			}			
			return resultReports;
		} catch (InternalBackEndException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (GeoLocationOutOfBoundException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (IllegalArgumentException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (WrongFormatException e){
			throw new InternalBackEndException("Wrong object Id: "+e.getMessage());
		} catch (IOBackEndException e){
			throw new IOBackEndException(e.getMessage(),404);
		}
	}
	
	/**
	 * Returns the MSearchBean or throws an exception if it is not present.
	 * 
	 * @param applicationId
	 * @param objType
	 * @param locationId
	 * @param objId
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public MSearchBean read(final String applicationId, final String objType, final long locationId, final String objId) 
			throws InternalBackEndException, IOBackEndException {
		
		String areaId = String.valueOf(Locator.getAreaId(locationId));
		MSearchBean searchBean;
		
		try {
			ExpColumnBean expCol = myJamStorageManager.selectExpiringColumn("Location", applicationId+objType+areaId, 
					MConverter.longToByteBuffer(locationId).array(), 
					MyMedId.parseString(objId).AsByteBuffer().array());
			
			Location loc = Locator.getLocationFromId(locationId);

			searchBean = new MSearchBean();
			searchBean.setId(objId);
			searchBean.setLatitude((int) (loc.getLatitude()*1E6));
			searchBean.setLongitude((int) (loc.getLongitude()*1E6));
			searchBean.setValue(MConverter.byteBufferToString(ByteBuffer.wrap(expCol.getValue())));
			searchBean.setLocationId(locationId);
			searchBean.setDate(expCol.getTimestamp());
			if (expCol.getTimeToLive()!=0)
				searchBean.setExpirationDate(expCol.getTimestamp() + (long) (expCol.getTimeToLive()*1000000));
			
			return searchBean;
		} catch (WrongFormatException e){
			throw new InternalBackEndException("Wrong report Id: "+e.getMessage());
		} catch (IllegalArgumentException e) {
			throw new InternalBackEndException("Wrong report Id: "+e.getMessage());
		} catch (GeoLocationOutOfBoundException e) {
			throw new InternalBackEndException("Wrong report Id: "+e.getMessage());
		}		
	}
	


}