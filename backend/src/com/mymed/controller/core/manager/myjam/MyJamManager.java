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
import com.mymed.myjam.type.MShortReportBean;
import com.mymed.myjam.type.ReportId;
import com.mymed.myjam.type.WrongFormatException;
import com.mymed.myjam.type.MyJamTypes.ReportType;
import com.mymed.utils.MConverter;

public class MyJamManager extends AbstractManager{
	/**
	 * TODO This class must be aware of the userName and the userId.
	 */
	private final String userId = "iacopoId";
	private final String userName = "iacopo"; 

	public MyJamManager(MyJamStorageManager storageManager) {
		super(storageManager);
		// TODO Auto-generated constructor stub
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
			ReportId reportId = new ReportId(timestamp,userId);
			
			report.setUserName(userName);
			report.setUserId(userId);
			report.setLocationId(locId);
			
			/** Check if the position is already occupied.*/
			try{
				myJamStorageManager.selectColumn("Location", areaId, 
						MConverter.longToByteBuffer(locId).array(), 
						MConverter.stringToByteBuffer(report.getReportType()).array());
				throw new InternalBackEndException("Position occupied.");
			}catch(IOBackEndException e){} // If the exception is thrown the position is not occupied.
			
			/**
			 * SuperColumn insertion in CF Location 
			 **/
			myJamStorageManager.insertExpiringColumn("Location", areaId, MConverter.longToByteBuffer(locId).array(), 
					MConverter.stringToByteBuffer(report.getReportType()).array(),
					reportId.ReportIdAsByteBuffer().array(),
					timestamp, ReportType.valueOf(report.getReportType()).permTime);
			/**
			 * Column insertion in CF ActiveReport 
			 **/
			myJamStorageManager.insertExpiringColumn("ActiveReport", userId,null, reportId.ReportIdAsByteBuffer().array(), 
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
	public List<MShortReportBean> getReports(double latitude,double longitude,int radius) throws InternalBackEndException,IOBackEndException{
		List<MShortReportBean> resultReports = new LinkedList<MShortReportBean>();
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
				int num =(int) (range[1] - range[0]+1);
				for (long ind=startAreaId;ind<=endAreaId;ind++){
					areaIds.add(String.valueOf(ind));
				}//TODO Check
				Map<byte[],Map<byte[],byte[]>> mapRep = myJamStorageManager.selectSCRange("Location", areaIds, MConverter.longToByteBuffer(range[0]).array(),
						MConverter.longToByteBuffer(range[1]).array(),num); 
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
						MShortReportBean reportBean = new MShortReportBean();
						reportBean.setReportType(MConverter.byteBufferToString(
								ByteBuffer.wrap(colName)));
						ReportId repId = ReportId.parseByteBuffer(ByteBuffer.wrap(reports.get(scName).get(colName)));
						reportBean.setReportId(repId.toString());
						reportBean.setLatitude((int) (reportLoc.getLatitude()*1E6));
						reportBean.setLongitude((int) (reportLoc.getLongitude()*1E6));
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
				activeReports.add(ReportId.parseByteBuffer(ByteBuffer.wrap(key)).toString());
			}
			return activeReports;
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (WrongFormatException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
	
	public List<String> getUpdateId(String reportId) throws InternalBackEndException{
		List<String> updateIds = new LinkedList<String>();
		try{
			Map<byte[],byte[]> updatesMap = myJamStorageManager.selectAll("ReportUpdate", reportId);
			for (byte[] key: updatesMap.keySet()){
				updateIds.add(ReportId.parseByteBuffer(ByteBuffer.wrap(key)).toString());
			}			
			return updateIds;
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
	
	/**
	 * Returns a list of updates.
	 * @param updateIds List of update ids.
	 * @return
	 * @throws InternalBackEndException
	 */
	public List<MReportBean> getUpdates(List<String> updateIds) throws InternalBackEndException{
		List<MReportBean> updatesList = new LinkedList<MReportBean>();
		try{
			/**
			 * Insert the updates details in the list.
			 */
			Map<String,Map<byte[],byte[]>> updateMap = myJamStorageManager.selectAll("Report", updateIds);
			for(String currUpdate:updateMap.keySet()){
				updatesList.add((MReportBean) introspection(new MReportBean(),updateMap.get(currUpdate)));
			}
			return updatesList;
		}catch(InternalBackEndException e){
				throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e){
				throw new InternalBackEndException(e.getMessage());
		} catch (ServiceManagerException e) {
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
		long locationId = reportDetails.getLocationId();
		String areaId = String.valueOf(Locator.getAreaId(locationId));
		/** The convention is to use microseconds since 1 Jenuary 1970*/
		long timestamp = (long) (System.currentTimeMillis()*1E3);									
		ReportId updateId = new ReportId(timestamp,userId);
		/**
		 * Check if the report is expired or not.
		 */
		ExpColumnBean expCol = myJamStorageManager.selectExpiringColumn("Location", areaId, 
				MConverter.longToByteBuffer(locationId).array(), 
				MConverter.stringToByteBuffer(reportDetails.getReportType()).array());
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
		myJamStorageManager.selectColumn("Location", areaId, 
				MConverter.longToByteBuffer(locationId).array(), 
				MConverter.stringToByteBuffer(reportDetails.getReportType()).array());
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
		}
	}
	
	public void deleteReport(ReportId reportId) throws InternalBackEndException{
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
				MConverter.stringToByteBuffer(report.getReportType()).array());
		}catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		}
	}

}
