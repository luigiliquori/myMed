package com.mymed.controller.core.manager.myjam;

import java.nio.ByteBuffer;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.WrongFormatException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.geolocation.GeoLocationManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.GeoLocStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.id.MyMedId;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MyJamTypeValidator;
import com.mymed.model.data.myjam.MyJamTypes.ReportType;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MConverter;
import com.mymed.utils.locator.Locator;

/**
 * Controls the insertion and the retrieving of data to/from the database.
 * 
 * @author iacopo
 * 
 */
public class MyJamManager extends AbstractManager{
	/**
	 * TODO The identity of the users must be checked. Authorization.
	 */

	public MyJamManager(GeoLocStorageManager storageManager) {
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
			long locId = Locator.getLocationId(latitude,longitude);

			/** The user profile is received ProfileManager */
			final ProfileManager profileManager = new ProfileManager(new StorageManager());

			final GeoLocationManager locatorManager = new GeoLocationManager();
			MUserBean userProfile = profileManager.read(report.getUserId()); //TODO Not secure. The server trust the user identity.
			/** Insert a new located object into Location column family. */
			MSearchBean searchBean = locatorManager.create("myJam", "report", userProfile.getLogin(), latitude, longitude, report.getReportType(), 
					ReportType.valueOf(report.getReportType()).permTime);
			/** The convention is to use milliseconds since 1 Jenuary 1970. */
			long timestamp = searchBean.getDate();
			MyMedId reportId = MyMedId.parseString(searchBean.getId());

			report.setId(searchBean.getId());
			report.setUserName(userProfile.getName());
			report.setUserId(userProfile.getId());
			report.setLocationId(locId);
			report.setTimestamp(timestamp);
			/** In cassandra is used the convention of microseconds since 1 Jenuary 1970*/
			timestamp = (long) (timestamp*1E3);

			/** No more used because I inverted value and name, the id now is the column name and the report type the value. */
			//			/** Check if the position is already occupied.*/
			//			try{
			//				((GeoLocStorageManager) storageManager).selectColumn("Location", areaId, 
			//						MConverter.longToByteBuffer(locId).array(), 
			//						MConverter.stringToByteBuffer(report.getReportType()).array());
			//				throw new InternalBackEndException("Position occupied.");
			//			}catch(IOBackEndException e){} // If the exception is thrown the position is not occupied.

			/**
			 * Column insertion in CF ActiveReport 
			 **/
			((GeoLocStorageManager) storageManager).insertExpiringColumn("ActiveReport", userProfile.getId(), null, reportId.asByteBuffer().array(), 
					new byte[0], //The value field is not used.
					timestamp, ReportType.valueOf(report.getReportType()).permTime);
			/**
			 * Columns insertion in CF Report 
			 **/
			((GeoLocStorageManager) storageManager).insertSlice("Report", reportId.toString(), report.getAttributeToMap());
			/**
			 * Columns insertion in CF UserReport 
			 **/
			((GeoLocStorageManager) storageManager).insertColumn("UserReport", userProfile.getId(), reportId.asByteBuffer().array(), new byte[0]);
			return report;
		} catch (InternalBackEndException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (GeoLocationOutOfBoundException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (IllegalArgumentException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e){
			throw new IOBackEndException(e.getMessage(),404);
		} catch (WrongFormatException e) {
			throw new InternalBackEndException("Wrong id format: "+e.getMessage());
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
	public List<MSearchBean> searchReports(int latitude,int longitude,int radius) throws InternalBackEndException,IOBackEndException{

		final GeoLocationManager locatorManager = new GeoLocationManager();

		return locatorManager.read("myJam", "report", latitude, longitude, radius, true);
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
			Map<byte[],byte[]> reportMap = ((GeoLocStorageManager) storageManager).selectAll("Report", reportId);
			MReportBean report = (MReportBean) introspection(MReportBean.class,reportMap);			
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
			Map<byte[],byte[]> res = ((GeoLocStorageManager) storageManager).selectAll("ActiveReport", userId);
			for(byte[] key:res.keySet()){
				activeReports.add(MyMedId.parseByteBuffer(ByteBuffer.wrap(key)).toString());
			}
			return activeReports;
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
	public List<MReportBean> getUpdates(String reportId, long timestamp) throws InternalBackEndException{
		List<String> updateIds = new LinkedList<String>();
		List<MReportBean> updatesList = new LinkedList<MReportBean>();
		try{
			/**
			 * Gets the ids of the last numberUpdates updates.
			 */
			MyMedId start = new MyMedId('u', timestamp, " "); // The user id is set to " " because is the first possible string in lexicographical order. 
			Map<byte[],byte[]> updatesMap = ((GeoLocStorageManager) storageManager).getFrom("ReportUpdate", reportId, start.asByteBuffer().array());
			for (byte[] key: updatesMap.keySet()){
				updateIds.add(MyMedId.parseByteBuffer(ByteBuffer.wrap(key)).toString());
			}			
			/**
			 * Insert the updates details in the list.
			 */
			Map<String,Map<byte[],byte[]>> updateMap = ((GeoLocStorageManager) storageManager).selectAll("Report", updateIds);
			Map<byte[],byte[]> updateCont;
			for(String currUpdate:updateIds){ //In this way the list updatesList is filled in order of time.
				if ((updateCont = updateMap.get(currUpdate))!=null)//
					updatesList.add((MReportBean) introspection(MReportBean.class,updateCont));
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
			Map<byte[],byte[]> feedBacksMap = ((GeoLocStorageManager) storageManager).selectAll("Feedback", reportId);
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


			final GeoLocationManager locatorManager = new GeoLocationManager();
			/**
			 * Data preparation
			 */
			Map<byte[],byte[]> reportMap = ((GeoLocStorageManager) storageManager).selectAll("Report", reportId);
			if (reportMap.isEmpty())
				throw new IOBackEndException("Report not present",404);
			MReportBean reportDetails = (MReportBean) introspection(MReportBean.class,reportMap);
			if (!reportDetails.getReportType().equals(update.getReportType()))
				throw new InternalBackEndException("Report and update types don't match.");
			long locationId = reportDetails.getLocationId();
			/** The user profile is received ProfileManager */
			final ProfileManager profileManager = new ProfileManager(new StorageManager());
			MUserBean userProfile = profileManager.read(update.getUserId()); //TODO Not secure. The server trust the user identity.
			/** The convention is to use milliseconds since 1 Jenuary 1970*/
			long timestamp = System.currentTimeMillis();									
			MyMedId updateId = new MyMedId(MyJamTypeValidator.UPDATE_ID,timestamp,userProfile.getLogin());

			update.setId(updateId.toString());
			update.setUserId(userProfile.getId());
			update.setUserName(userProfile.getName());
			update.setTimestamp(timestamp);

			/**
			 * Check if the report is expired or not.
			 * If it is expired an exception is thrown.
			 */
			locatorManager.read("myJam", "report", locationId, reportId);

			//TODO Update expiring time.
			/**
			 * Report is not expired.
			 * Columns insertion in CF Report 
			 **/
			((GeoLocStorageManager) storageManager).insertSlice("Report", updateId.toString(), update.getAttributeToMap());
			/**
			 * Column insertion in the CF ReportUpdate
			 */
			((GeoLocStorageManager) storageManager).insertColumn("ReportUpdate", reportId, updateId.asByteBuffer().array(), new byte[0]);
			/**
			 * Column insertion in the CF UserReport
			 */
			((GeoLocStorageManager) storageManager).insertColumn("UserReport", userProfile.getId(), updateId.asByteBuffer().array(), new byte[0]);
			/**
			 * Update expiration time.
			 */
			//TODO To update the expiration time is sufficient to reinsert the column in CF Location, changing the TTL.
			return update;
		} catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e) {
			throw new IOBackEndException("The report is expired, you cannot insert an update.",404);
		}
	}

	public MFeedBackBean insertFeedback(String reportId, String updateId, MFeedBackBean feedback) throws InternalBackEndException, IOBackEndException{
		try{
			final GeoLocationManager locatorManager = new GeoLocationManager();
			/**
			 * Data preparation
			 */
			Map<byte[],byte[]> reportMap = ((GeoLocStorageManager) storageManager).selectAll("Report", reportId);
			if (reportMap.isEmpty())
				throw new IOBackEndException(" Report not valid. ",404);
			MReportBean reportDetails = (MReportBean) introspection(MReportBean.class,reportMap);
			long locationId = reportDetails.getLocationId();
			if (updateId!=null){
				Map<byte[],byte[]> updateMap = ((GeoLocStorageManager) storageManager).selectAll("Report", updateId);
				if (reportMap.isEmpty())
					throw new IOBackEndException(" Update not valid. ",404);
				MReportBean updateDetails = (MReportBean) introspection(MReportBean.class,updateMap);
				/** Check if the user posting the feedback is the same who posted the update. */
				if (updateDetails.getUserId().equals(feedback.getUserId()))
					throw new InternalBackEndException(" You cannot insert a feedback on your own update. ");
			}else{
				/** Check if the user posting the feedback is the same who posted the report. */
				if (reportDetails.getUserId().equals(feedback.getUserId()))
					throw new InternalBackEndException(" You cannot insert a feedback on your own report. ");
			}
			
			/**
			 * Check if the report is expired or not.
			 * If it is expired an exception is thrown.
			 */
			locatorManager.read("myJam", "report", locationId, reportId);
			
			/**
			 * Check if the user has already put a feedback on this report.
			 */
			//TODO Check if the user yet inserted a feedback. Check the userId.
			try{
				((GeoLocStorageManager) storageManager).selectColumn("Feedback", updateId==null?reportId:updateId, 
						null, MConverter.stringToByteBuffer(feedback.getUserId()).array());
				throw new InternalBackEndException("Feedback already present.");
			}catch(IOBackEndException e){}
			/**
			 * Report is not expired.
			 * Columns insertion in CF Report 
			 **/
			((GeoLocStorageManager) storageManager).insertColumn("Feedback", updateId==null?reportId:updateId, 
					MConverter.stringToByteBuffer(feedback.getUserId()).array(), 
					MConverter.intToByteBuffer(feedback.getValue()).array());

			return feedback;
		} catch(InternalBackEndException e){
			throw new InternalBackEndException(" "+e.getMessage());
		} catch (IOBackEndException e) { //TODO Check the possible causes of IOException.
			throw new IOBackEndException("The report is expired, you cannot insert a feedback.",404);
		}
	}

	public void deleteReport(String reportId) throws InternalBackEndException{
		try{
			MReportBean report = this.getReport(reportId);
			long locationId = report.getLocationId();
			String areaId = String.valueOf(Locator.getAreaId(locationId));
			MyMedId id = MyMedId.parseString(reportId);
			/**
			 * Removes the column by ActiveReport CF, if present.
			 */
			((GeoLocStorageManager) storageManager).removeColumn("ActiveReport", report.getUserId(), null,
					id.asByteBuffer().array());
			/**
			 * Removes the column by Location CF, if present.
			 */
			((GeoLocStorageManager) storageManager).removeColumn("Location", areaId, 
					MConverter.longToByteBuffer(locationId).array(), 
					id.asByteBuffer().array());
		}catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		}catch(WrongFormatException e){
			throw new InternalBackEndException("Wrong id format: "+e.getMessage());
		}
	}

}