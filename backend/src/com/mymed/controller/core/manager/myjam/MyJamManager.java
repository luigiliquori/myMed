package com.mymed.controller.core.manager.myjam;

import java.nio.ByteBuffer;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager.ExpColumnBean;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MSearchReportBean;
import com.mymed.model.data.myjam.MyJamId;
import com.mymed.model.data.myjam.WrongFormatException;
import com.mymed.model.data.myjam.MyJamTypes.ReportType;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MConverter;
import com.mymed.utils.locator.GeoLocationOutOfBoundException;
import com.mymed.utils.locator.Location;
import com.mymed.utils.locator.Locator;

/**
 * Controls the insertion and the retrieving of data to/from the database.
 * @author iacopo
 *
 */
public class MyJamManager extends AbstractManager{
	/**
	 * TODO The identity of the users must be checked.
	 */
	//private final String userId = "iacopoId";
	//private final String userName = "iacopo"; 

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
	public MReportBean insertReport(MReportBean report,int latitude,int longitude) throws InternalBackEndException, IOBackEndException{
		try {
			/**
			 * Data preparation
			 */
			long locId = Locator.getLocationId((double) (latitude/1E6),
					(double) (longitude/1E6));
			String areaId = String.valueOf(Locator.getAreaId(locId));
			/** The user profile is received ProfileManager */
			final ProfileManager profileManager = new ProfileManager(new StorageManager());
			MUserBean userProfile = profileManager.read(report.getUserId()); //TODO Not secure. The server trust the user identity.  
			/** The convention is to use milliseconds since 1 Jenuary 1970*/
			long timestamp = System.currentTimeMillis();				
			MyJamId reportId = new MyJamId(MyJamId.REPORT_ID,timestamp,userProfile.getLogin());
			
			report.setId(reportId.toString());
			report.setUserName(userProfile.getName());
			report.setUserId(userProfile.getId());
			report.setLocationId(locId);
			report.setTimestamp(timestamp);
			/** In cassandra is used the convention of microseconds since 1 Jenuary 1970*/
			timestamp = (long) (timestamp*1E3);
			
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
			myJamStorageManager.insertExpiringColumn("ActiveReport", userProfile.getId(), null, reportId.ReportIdAsByteBuffer().array(), 
					new byte[0], //The value field is not used.
					timestamp, ReportType.valueOf(report.getReportType()).permTime);
			/**
			 * Columns insertion in CF Report 
			 **/
			myJamStorageManager.insertSlice("Report", reportId.toString(), report.getAttributeToMap());
			/**
			 * Columns insertion in CF UserReport 
			 **/
			myJamStorageManager.insertColumn("UserReport", userProfile.getId(), reportId.ReportIdAsByteBuffer().array(), new byte[0]);
			return report;
		} catch (InternalBackEndException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (GeoLocationOutOfBoundException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (IllegalArgumentException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e){
			throw new IOBackEndException(e.getMessage(),404);
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
						reportBean.setDate((repId.getTimestamp()));
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
			throw new IOBackEndException(e.getMessage(),404);
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
		} catch (WrongFormatException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
	
	/**
	 * Returns an integer array with: 
	 * 	- The number of negative feedbacks with index 0.
	 *  - The number of positive feedbacks with index 1.
	 * @param reportId
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public List<MFeedBackBean> getFeedbacks(String reportId) throws InternalBackEndException, IOBackEndException{
		List<MFeedBackBean> feedBacksList = new LinkedList<MFeedBackBean>();  
		try{
			Map<byte[],byte[]> feedBacksMap = myJamStorageManager.selectAll("Feedback", reportId);
			for (byte[] key: feedBacksMap.keySet()){
				MFeedBackBean currFeedBack = new MFeedBackBean();
				currFeedBack.setUserId(MConverter.byteBufferToString(ByteBuffer.wrap(key)));
				currFeedBack.setValue(MConverter.byteBufferToInteger(ByteBuffer.wrap(feedBacksMap.get(key))));
				feedBacksList.add(currFeedBack);				
			}
			return feedBacksList;
		}catch(InternalBackEndException e){
				throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		}
	}
	
	public MReportBean insertUpdate(String reportId,MReportBean update) throws InternalBackEndException, IOBackEndException{
		try{
		/**
		 * Data preparation
		 */
		Map<byte[],byte[]> reportMap = myJamStorageManager.selectAll("Report", reportId);
		if (reportMap.isEmpty())
			throw new IOBackEndException("Report not present",404);
		MReportBean reportDetails = (MReportBean) introspection(new MReportBean(),reportMap);
		if (!reportDetails.getReportType().equals(update.getReportType()))
			throw new InternalBackEndException("Report and update types don't match.");
		long locationId = reportDetails.getLocationId();
		String areaId = String.valueOf(Locator.getAreaId(locationId));
		/** The user profile is received ProfileManager */
		final ProfileManager profileManager = new ProfileManager(new StorageManager());
		MUserBean userProfile = profileManager.read(update.getUserId()); //TODO Not secure. The server trust the user identity.
		/** The convention is to use milliseconds since 1 Jenuary 1970*/
		long timestamp = System.currentTimeMillis();									
		MyJamId updateId = new MyJamId(MyJamId.UPDATE_ID,timestamp,userProfile.getLogin());
		
		update.setId(updateId.toString());
		update.setUserId(userProfile.getId());
		update.setUserName(userProfile.getName());
		update.setTimestamp(timestamp);
		
		/**
		 * Check if the report is expired or not.
		 * If it is expired an exception is thrown.
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
		myJamStorageManager.insertColumn("UserReport", userProfile.getId(), updateId.ReportIdAsByteBuffer().array(), new byte[0]);
		/**
		 * Update expiration time.
		 */
		//TODO To update the expiration time is sufficient to reinsert the column in CF Location, changing the TTL.
		return update;
		} catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (WrongFormatException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e) {
			throw new IOBackEndException("The report is expired, you cannot insert an update.",404);
		}
	}
	
	public void insertFeedback(String reportId, String updateId, MFeedBackBean feedback) throws InternalBackEndException, IOBackEndException{
		try{
		
		/**
		 * Data preparation
		 */
		Map<byte[],byte[]> reportMap = myJamStorageManager.selectAll("Report", reportId);
		if (reportMap.isEmpty())
			throw new IOBackEndException(" Report not valid. ",404);
		MReportBean reportDetails = (MReportBean) introspection(new MReportBean(),reportMap);
		long locationId = reportDetails.getLocationId();
		String areaId = String.valueOf(Locator.getAreaId(locationId));								
		/**
		 * Check if the report is expired or not.
		 * If it is expired an exception is thrown.
		 */
		myJamStorageManager.selectExpiringColumn("Location", areaId, 
				MConverter.longToByteBuffer(locationId).array(), 
				MyJamId.parseString(reportId).ReportIdAsByteBuffer().array());
		/**
		 * Check if the user has already put a feedback on this report.
		 */
		//TODO Check if the user yet inserted a feedback. Check the userId.
		try{
			myJamStorageManager.selectColumn("Feedback", updateId==null?reportId:updateId, 
				null, MConverter.stringToByteBuffer(feedback.getUserId()).array());
			throw new InternalBackEndException("Feedback already present.");
		}catch(IOBackEndException e){}
		/**
		 * Report is not expired.
		 * Columns insertion in CF Report 
		 **/
		
			myJamStorageManager.insertColumn("Feedback", updateId==null?reportId:updateId, MConverter.stringToByteBuffer(feedback.getUserId()).array(), 
					MConverter.intToByteBuffer(feedback.getValue()).array());
		
		} catch(InternalBackEndException e){
			throw new InternalBackEndException(" "+e.getMessage());
		} catch (WrongFormatException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		}  catch (IOBackEndException e) { //TODO Check the possible causes of IOException.
			throw new IOBackEndException("The report is expired, you cannot insert a feedback.",404);
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
			myJamStorageManager.removeColumn("ActiveReport", report.getUserId(), null,
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
