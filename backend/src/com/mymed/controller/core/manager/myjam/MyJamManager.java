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
import com.mymed.myjam.locator.GeoLocationOutOfBoundException;
import com.mymed.myjam.locator.Location;
import com.mymed.myjam.locator.Locator;
import com.mymed.myjam.type.MFeedBackBean;
import com.mymed.myjam.type.MReportBean;
import com.mymed.myjam.type.MShortReportBean;
import com.mymed.myjam.type.ReportId;
import com.mymed.myjam.type.ReportInfo;
import com.mymed.myjam.type.WrongFormatException;
import com.mymed.myjam.type.MyJamTypes.ReportType;
import com.mymed.utils.MConverter;

public class MyJamManager extends AbstractManager{
	/**
	 * TODO This class must be aware of the username and the userId.
	 */
	private final String userId = "iacopoId";
	private final String userName = "iacopo"; 
	private final int numUpdates = 3;

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
	 */
	public void insertReport(MReportBean report,int latitude,int longitude) throws InternalBackEndException{
		try {
			/**
			 * Data preparation
			 */
			report.setUserName(userName);
			long locId = Locator.getLocationId((double) (latitude/1E6),
					(double) (longitude/1E6));
			String areaId = String.valueOf(Locator.getAreaId(locId));
			/** The convention is to use microseconds since 1 Jenuary 1970*/
			long timestamp = (long) (System.currentTimeMillis()*1E3);									
			ReportId reportId = new ReportId(timestamp,userId);
			
			/** Check if the position is already occupied.*/
			if 	(myJamStorageManager.selectColumnInSuperColumn("Location", areaId, 
					MConverter.longToByteBuffer(locId).array(), 
					MConverter.stringToByteBuffer(report.getReportType()).array())!=null)
				throw new InternalBackEndException("Position occupied.");
			/**
			 * SuperColumn insertion in CF Location 
			 **/
			myJamStorageManager.insertExpiringSuperColumn("Location", areaId, MConverter.longToByteBuffer(locId).array(), 
					MConverter.stringToByteBuffer(report.getReportType()).array(),
					reportId.ReportIdAsByteBuffer().array(), 
					ReportType.valueOf(report.getReportType()).permTime);
			/**
			 * Columns insertion in CF Report 
			 **/
			myJamStorageManager.insertSlice("Report", reportId.toString(), report.getAttributeToMap());
			/**
			 * Columns insertion in CF UserReport 
			 **/
			myJamStorageManager.insertColumn("UserReport", userId, reportId.ReportIdAsByteBuffer().array(), new byte[0]);
		} catch (InternalBackEndException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (GeoLocationOutOfBoundException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (IllegalArgumentException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}

	/**
	 * Returns a list of reports, located in the circular area defined by latitude,
	 * longitude and radius.
	 * @param latitude
	 * @param longitude
	 * @param diameter
	 * @return
	 * @throws InternalBackEndException
	 */
	public List<MShortReportBean> getReports(double latitude,double longitude,int diameter) throws InternalBackEndException{
		List<MShortReportBean> resultReports = new LinkedList<MShortReportBean>();
		try{
			/**
			 * Data structures preparation
			 */
			Map<byte[],Map<byte[],byte[]>> reports = new HashMap<byte[],Map<byte[],byte[]>>();
			List<long[]> covId = Locator.getCoveringLocationId(latitude, longitude, diameter);
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
				}//TODO 
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
				if (distance <= diameter){
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
			throw new InternalBackEndException(e.getMessage());
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
	public List<ReportInfo> getReportDetails(ReportId reportId) throws InternalBackEndException{
		List<ReportInfo> resultDetails = new LinkedList<ReportInfo>();
		/**
		 * Insert the report details in the list.		
		 */
		try{
			Map<byte[],byte[]> reportMap = myJamStorageManager.selectAll("Report", reportId.toString());
			MReportBean reportDetails = (MReportBean) introspection(new MReportBean(),reportMap);
			List<MFeedBackBean> feedBacksList = new LinkedList<MFeedBackBean>();
			Map<byte[],byte[]> feedBacksMap = myJamStorageManager.selectAll("Feedback", reportId.toString());
			for (byte[] key: feedBacksMap.keySet()){
				MFeedBackBean currFeedBack = new MFeedBackBean();
				currFeedBack.setUserId(MConverter.byteBufferToString(ByteBuffer.wrap(key)));
				currFeedBack.setGrade(MConverter.byteBufferToInteger(ByteBuffer.wrap(feedBacksMap.get(key))));
				feedBacksList.add(currFeedBack);
			}
			ReportInfo reportInfo = new ReportInfo();
			reportInfo.setReport(reportDetails);
			reportInfo.setFeedbacks(feedBacksList);
			resultDetails.add(reportInfo);
		/**
		 * Insert the updates details in the list.
		 */
			Map<byte[],byte[]> updatesMap = myJamStorageManager.getFirstN("ReportUpdate", reportId.toString(),numUpdates);
			for (byte[] key: updatesMap.keySet()){
				Map<byte[],byte[]> updateMap = myJamStorageManager.selectAll("Report", MConverter.byteBufferToString(ByteBuffer.wrap(key)));
				MReportBean updateDetails = (MReportBean) introspection(new MReportBean(),updateMap);
				feedBacksList = new LinkedList<MFeedBackBean>();
				feedBacksMap = myJamStorageManager.selectAll("Feedback", reportId.toString());
				for (byte[] fbKey: feedBacksMap.keySet()){
					MFeedBackBean currFeedBack = new MFeedBackBean();
					currFeedBack.setUserId(MConverter.byteBufferToString(ByteBuffer.wrap(fbKey)));
					currFeedBack.setGrade(MConverter.byteBufferToInteger(ByteBuffer.wrap(feedBacksMap.get(fbKey))));
					feedBacksList.add(currFeedBack);
				}
				reportInfo = new ReportInfo();
				reportInfo.setReport(updateDetails);
				reportInfo.setFeedbacks(feedBacksList);
				resultDetails.add(reportInfo);
			}
			
			return resultDetails;
		}catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (IOBackEndException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(e.getMessage());
		}		
	}

}
