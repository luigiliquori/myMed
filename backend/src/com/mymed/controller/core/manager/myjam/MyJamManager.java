package com.mymed.controller.core.manager.myjam;

import java.nio.ByteBuffer;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager.ExpColumnBean;
import com.mymed.myjam.locator.GeoLocationOutOfBoundException;
import com.mymed.myjam.locator.Location;
import com.mymed.myjam.locator.Locator;
import com.mymed.myjam.type.MFeedBackBean;
import com.mymed.myjam.type.MReportBean;
import com.mymed.myjam.type.MSearchReportBean;
import com.mymed.myjam.type.MyJamId;
import com.mymed.myjam.type.WrongFormatException;
import com.mymed.myjam.type.MyJamTypes.ReportType;
import com.mymed.utils.MConverter;

/**
 * Controls the insertion and the retrieving of data to/from the database.
 * @author iacopo
 *
 */
public class MyJamManager extends AbstractManager{
	/**
	 * TODO This class must be aware of the userName and the userId.
	 */
	private final String userId = "iacopoId";
	private final String userName = "iacopo"; 

	public MyJamManager(MyJamStorageManager storageManager) {
		super(storageManager);
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */

	/**
	 * Insert a new report on the DB
	 * @param report
	 * @throws InternalBackEndException
	 * @throws IOBackEndException 
	 */
	public String insertReport(MReportBean report,int latitude,int longitude) throws InternalBackEndException, IOBackEndException{
		try {
			/**
			 * Data preparation
			 */
			long locId = Locator.getLocationId((double) (latitude/1E6),
					(double) (longitude/1E6));
			String areaId = String.valueOf(Locator.getAreaId(locId));
			/** The convention is to use microseconds since 1 Jenuary 1970*/
			long timestamp = (long) (System.currentTimeMillis()*1E3);									
			MyJamId reportId = new MyJamId(MyJamId.REPORT_ID,timestamp,userId);
			
			report.setUserName(userName);
			report.setUserId(userId);
			report.setLocationId(locId);
			report.setTimestamp(timestamp);
			
/** No more used because I inverted value and name, the id now is the column name and the report type the value. */
//			/** Check if the position is already occupied.*/
//			try{
//				myJamStorageManager.selectColumn("Location", areaId, 
//						MConverter.longToByteBuffer(locId).array(), 
//						MConverter.stringToByteBuffer(report.getReportType()).array());
//				throw new InternalBackEndException("Position occupied.");
//			}catch(IOBackEndException e){} // If the exception is thrown the position is not occupied.
			
			/**
			 * SuperColumn insertion in CF Location 
			 **/
			myJamStorageManager.insertExpiringColumn("Location", areaId, MConverter.longToByteBuffer(locId).array(), 
					reportId.ReportIdAsByteBuffer().array(),
					MConverter.stringToByteBuffer(report.getReportType()).array(),
					timestamp, ReportType.valueOf(report.getReportType()).permTime);
			/**
			 * Column insertion in CF ActiveReport 
			 **/
			myJamStorageManager.insertExpiringColumn("ActiveReport", userId, null, reportId.ReportIdAsByteBuffer().array(), 
					new byte[0], //The value field is not used.
					timestamp, ReportType.valueOf(report.getReportType()).permTime);
			/**
			 * Columns insertion in CF Report 
			 **/
			myJamStorageManager.insertSlice("Report", reportId.toString(), report.getAttributeToMap());
			/**
			 * Columns insertion in CF UserReport 
			 **/
			myJamStorageManager.insertColumn("UserReport", userId, reportId.ReportIdAsByteBuffer().array(), new byte[0]);
			return reportId.toString();
		} catch (InternalBackEndException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (GeoLocationOutOfBoundException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (IllegalArgumentException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e){
			throw new IOBackEndException(e.getMessage());
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}

	/**
	 * Returns a list of reports, located in the circular area defined by latitude,
	 * longitude and radius.
	 * @param latitude
	 * @param longitude
	 * @param radius
	 * @return
	 * @throws InternalBackEndException
	 */
	public List<MSearchReportBean> searchReports(double latitude,double longitude,int radius) throws InternalBackEndException,IOBackEndException{
		List<MSearchReportBean> resultReports = new LinkedList<MSearchReportBean>();
		try{
			/**
			 * Data structures preparation
			 */
			Map<byte[],Map<byte[],byte[]>> reports = new HashMap<byte[],Map<byte[],byte[]>>();
			List<long[]> covId = Locator.getCoveringLocationId(latitude, longitude, radius);
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
					areaIds.add(String.valueOf(ind));
				}//TODO Check
				Map<byte[],Map<byte[],byte[]>> mapRep = myJamStorageManager.selectSCRange("Location", areaIds, MConverter.longToByteBuffer(range[0]).array(),
						MConverter.longToByteBuffer(range[1]).array()); 
				reports.putAll(mapRep);
				areaIds.clear();
			}
			/**
			 * Data processing
			 */
			for (byte[] scName:reports.keySet()){
				long posId = MConverter.byteBufferToLong(ByteBuffer.wrap(scName));
				Location reportLoc = Locator.getLocationFromId(posId);
				double distance = reportLoc.distanceGCTo(new Location(latitude,longitude));
				/** Distance check */
				if (distance <= radius){
					for (byte[] colName : reports.get(scName).keySet()){
						MSearchReportBean reportBean = new MSearchReportBean();
						MyJamId repId = MyJamId.parseByteBuffer(ByteBuffer.wrap(colName));
						reportBean.setReportType(MConverter.byteBufferToString(
								ByteBuffer.wrap(reports.get(scName).get(colName))));
						reportBean.setReportId(repId.toString());
						reportBean.setLatitude((int) (reportLoc.getLatitude()*1E6));
						reportBean.setLongitude((int) (reportLoc.getLongitude()*1E6));
						reportBean.setDistance((int) distance);
						/**The timestamp is set with the convention used in Java (milliseconds from 1 January 1970)*/
						reportBean.setDate((repId.getTimestamp()/1000));
						resultReports.add(reportBean);
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
			throw new InternalBackEndException("Wrong report Id: "+e.getMessage());
		} catch (IOBackEndException e){
			throw new IOBackEndException(e.getMessage());
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
	
	/**
	 * 
	 * @param reportId
	 * @return
	 * @throws InternalBackEndException
	 */
	public MReportBean getReport(String reportId) throws InternalBackEndException{
		/**
		 * Insert the report details in the list.		
		 */
		try{
			Map<byte[],byte[]> reportMap = myJamStorageManager.selectAll("Report", reportId);
			MReportBean report = (MReportBean) introspection(new MReportBean(),reportMap);			
			return report;
		}catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
	
	public List<String> getActiveReport(String userId) throws InternalBackEndException, IOBackEndException{
		List<String> activeReports = new LinkedList<String>();
		try {
			Map<byte[],byte[]> res = myJamStorageManager.selectAll("ActiveReport", userId);
			for(byte[] key:res.keySet()){
				activeReports.add(MyJamId.parseByteBuffer(ByteBuffer.wrap(key)).toString());
			}
			return activeReports;
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (WrongFormatException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
	
	public int getNumUpdates(String reportId) throws InternalBackEndException{
		
		try{
			return myJamStorageManager.countColumns("ReportUpdate", reportId, null);					
		}catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
	
	/**
	 * Returns a list of updates.
	 * @param updateIds List of update ids.
	 * @return
	 * @throws InternalBackEndException
	 */
	public List<MReportBean> getUpdates(String reportId,int numberUpdates) throws InternalBackEndException{
		List<String> updateIds = new LinkedList<String>();
		List<MReportBean> updatesList = new LinkedList<MReportBean>();
		try{
			/**
			 * Gets the ids of the last numberUpdates updates.
			 */
			Map<byte[],byte[]> updatesMap = myJamStorageManager.getLastN("ReportUpdate", reportId, numberUpdates);
			for (byte[] key: updatesMap.keySet()){
				updateIds.add(MyJamId.parseByteBuffer(ByteBuffer.wrap(key)).toString());
			}			
			/**
			 * Insert the updates details in the list.
			 */
			Map<String,Map<byte[],byte[]>> updateMap = myJamStorageManager.selectAll("Report", updateIds);
			Map<byte[],byte[]> updateCont;
			for(String currUpdate:updateIds){ //In this way the list updatesList is filled in order of time.
				if ((updateCont = updateMap.get(currUpdate))!=null)//
					updatesList.add((MReportBean) introspection(new MReportBean(),updateCont));
			}
			return updatesList;
		}catch(InternalBackEndException e){
				throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e){
				throw new InternalBackEndException(e.getMessage());
		} catch (ServiceManagerException e) {
				throw new InternalBackEndException(e.getMessage());
		} catch (WrongFormatException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
	
	public List<MFeedBackBean> getFeedbacks(String reportId) throws InternalBackEndException, IOBackEndException{
		List<MFeedBackBean> feedBacksList = new LinkedList<MFeedBackBean>();
		try{
			Map<byte[],byte[]> feedBacksMap = myJamStorageManager.selectAll("Feedback", reportId);
			for (byte[] key: feedBacksMap.keySet()){
				MFeedBackBean currFeedBack = new MFeedBackBean();
				currFeedBack.setUserId(MConverter.byteBufferToString(ByteBuffer.wrap(key)));
				currFeedBack.setGrade(MConverter.byteBufferToInteger(ByteBuffer.wrap(feedBacksMap.get(key))));
				feedBacksList.add(currFeedBack);
			}
			return feedBacksList;
		}catch(InternalBackEndException e){
				throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (ServiceManagerException e) {
				throw new InternalBackEndException(e.getMessage());
		}
	}
	
	public void insertUpdate(String reportId,MReportBean update) throws InternalBackEndException, IOBackEndException{
		try{
		/**
		 * Data preparation
		 */
		Map<byte[],byte[]> reportMap = myJamStorageManager.selectAll("Report", reportId);
		if (reportMap.isEmpty())
			throw new IOBackEndException("Report not present");
		MReportBean reportDetails = (MReportBean) introspection(new MReportBean(),reportMap);
		if (!reportDetails.getReportType().equals(update.getReportType()))
			throw new InternalBackEndException("Report and update types don't match.");
		long locationId = reportDetails.getLocationId();
		String areaId = String.valueOf(Locator.getAreaId(locationId));
		/** The convention is to use microseconds since 1 Jenuary 1970*/
		long timestamp = (long) (System.currentTimeMillis()*1E3);									
		MyJamId updateId = new MyJamId(MyJamId.UPDATE_ID,timestamp,userId);
		
		update.setUserId(userId);
		update.setUserName(userName);
		update.setTimestamp(timestamp);
		
		/**
		 * Check if the report is expired or not.
		 */
		ExpColumnBean expCol = myJamStorageManager.selectExpiringColumn("Location", areaId, 
				MConverter.longToByteBuffer(locationId).array(), 
				MyJamId.parseString(reportId).ReportIdAsByteBuffer().array());
		//TODO Update expiring time.
		/**
		 * Report is not expired.
		 * Columns insertion in CF Report 
		 **/
		myJamStorageManager.insertSlice("Report", updateId.toString(), update.getAttributeToMap());
		/**
		 * Column insertion in the CF ReportUpdate
		 */
		myJamStorageManager.insertColumn("ReportUpdate", reportId, updateId.ReportIdAsByteBuffer().array(), new byte[0]);
		/**
		 * Column insertion in the CF UserReport
		 */
		myJamStorageManager.insertColumn("UserReport", userId, updateId.ReportIdAsByteBuffer().array(), new byte[0]);
		/**
		 * Update expiration time.
		 */
		//TODO To update the expiration time is sufficient to reinsert the column in CF Location, changing the TTL.
		}catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (WrongFormatException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		}
	}
	
	public void insertFeedback(String reportId,MFeedBackBean feedback) throws InternalBackEndException, IOBackEndException{
		try{
		/**
		 * Data preparation
		 */
		Map<byte[],byte[]> reportMap = myJamStorageManager.selectAll("Report", reportId);
		if (reportMap.isEmpty())
			throw new IOBackEndException(" Report not valid. ");
		MReportBean reportDetails = (MReportBean) introspection(new MReportBean(),reportMap);
		long locationId = reportDetails.getLocationId();
		String areaId = String.valueOf(Locator.getAreaId(locationId));								
		/**
		 * Check if the report is expired or not.
		 */
		myJamStorageManager.selectExpiringColumn("Location", areaId, 
				MConverter.longToByteBuffer(locationId).array(), 
				MyJamId.parseString(reportId).ReportIdAsByteBuffer().array());
		/**
		 * Report is not expired.
		 * Columns insertion in CF Report 
		 **/
		//TODO Check if the current user has already inserted a feedback.
		myJamStorageManager.insertColumn("Feedback", reportId, MConverter.stringToByteBuffer(userId).array(), 
				MConverter.intToByteBuffer(feedback.getGrade()).array());
		
		}catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (WrongFormatException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		}
	}
	
	public void deleteReport(MyJamId reportId) throws InternalBackEndException{
		try{
			MReportBean report = this.getReport(reportId.toString());
			long locationId = report.getLocationId();
			String areaId = String.valueOf(Locator.getAreaId(locationId));
			/**
			 * Removes the column by ActiveReport CF, if present.
			 */
			myJamStorageManager.removeColumn("ActiveReport", userId, null,
				reportId.ReportIdAsByteBuffer().array());
			/**
			 * Removes the column by ActiveReport CF, if present.
			 */
			myJamStorageManager.removeColumn("Location", areaId, 
				MConverter.longToByteBuffer(locationId).array(), 
				reportId.ReportIdAsByteBuffer().array());
		}catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		}
	}

}
