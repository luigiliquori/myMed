package com.mymed.controller.core.manager.myjam;

import java.nio.ByteBuffer;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.WrongFormatException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.geolocation.GeoLocationManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.id.MyMedId;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MyJamTypeValidator;
import com.mymed.model.data.myjam.MyJamTypes.ReportType;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MConverter;
import com.mymed.utils.locator.GeoLocationOutOfBoundException;
import com.mymed.utils.locator.Locator;

/**
 * Controls the insertion and the retrieving of data to/from the database.
 * 
 * @author iacopo
 * 
 */
public class MyJamManager extends AbstractManager {
  /**
   * TODO The identity of the users must be checked. Authorization.
   */

  public MyJamManager(final MyJamStorageManager storageManager) {
    super(storageManager);
  }

  /* --------------------------------------------------------- */
  /* public methods */
  /* --------------------------------------------------------- */

  /**
   * Insert a new report on the DB
   * 
   * @param report
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  public MReportBean insertReport(final MReportBean report, final int latitude, final int longitude)
      throws InternalBackEndException, IOBackEndException {
    try {
      /**
       * Data preparation
       */
      final long locId = Locator.getLocationId(latitude / 1E6, longitude / 1E6);

      /** The user profile is received ProfileManager */
      final ProfileManager profileManager = new ProfileManager(new StorageManager());

      final GeoLocationManager locatorManager = new GeoLocationManager();
      final MUserBean userProfile = profileManager.read(report.getUserId()); // TODO
                                                                             // Not
                                                                             // secure.
                                                                             // The
                                                                             // server
                                                                             // trust
                                                                             // the
                                                                             // user
                                                                             // identity.
      /** Insert a new located object into Location column family. */
      final MSearchBean searchBean = locatorManager.create("myJam", "report", userProfile.getLogin(), latitude,
          longitude, report.getReportType(), ReportType.valueOf(report.getReportType()).permTime);
      /** The convention is to use milliseconds since 1 Jenuary 1970. */
      long timestamp = searchBean.getDate();
      final MyMedId reportId = MyMedId.parseString(searchBean.getId());

      report.setId(searchBean.getId());
      report.setUserName(userProfile.getName());
      report.setUserId(userProfile.getId());
      report.setLocationId(locId);
      report.setTimestamp(timestamp);
      /**
       * In cassandra is used the convention of microseconds since 1 Jenuary
       * 1970
       */
      timestamp = (long) (timestamp * 1E3);

      /**
       * No more used because I inverted value and name, the id now is the
       * column name and the report type the value.
       */
      // /** Check if the position is already occupied.*/
      // try{
      // ((MyJamStorageManager) storageManager).selectColumn("Location", areaId,
      // MConverter.longToByteBuffer(locId).array(),
      // MConverter.stringToByteBuffer(report.getReportType()).array());
      // throw new InternalBackEndException("Position occupied.");
      // }catch(IOBackEndException e){} // If the exception is thrown the
      // position is not occupied.

      /**
       * Column insertion in CF ActiveReport
       **/
      ((MyJamStorageManager) storageManager).insertExpiringColumn("ActiveReport", userProfile.getId(), null, reportId
          .AsByteBuffer().array(), new byte[0], // The value field is not used.
          timestamp, ReportType.valueOf(report.getReportType()).permTime);
      /**
       * Columns insertion in CF Report
       **/
      ((MyJamStorageManager) storageManager).insertSlice("Report", reportId.toString(), report.getAttributeToMap());
      /**
       * Columns insertion in CF UserReport
       **/
      ((MyJamStorageManager) storageManager).insertColumn("UserReport", userProfile.getId(), reportId.AsByteBuffer()
          .array(), new byte[0]);
      return report;
    } catch (final InternalBackEndException e) {
      throw new InternalBackEndException("Wrong parameter: " + e.getMessage());
    } catch (final GeoLocationOutOfBoundException e) {
      throw new InternalBackEndException(e.getMessage());
    } catch (final IllegalArgumentException e) {
      throw new InternalBackEndException("Wrong parameter: " + e.getMessage());
    } catch (final IOBackEndException e) {
      throw new IOBackEndException(e.getMessage(), 404);
    } catch (final WrongFormatException e) {
      throw new InternalBackEndException("Wrong id format: " + e.getMessage());
    }
  }

  /**
   * Returns a list of reports, located in the circular area defined by
   * latitude, longitude and radius.
   * 
   * @param latitude
   * @param longitude
   * @param radius
   * @return
   * @throws InternalBackEndException
   */
  public List<MSearchBean> searchReports(final int latitude, final int longitude, final int radius)
      throws InternalBackEndException, IOBackEndException {

    final GeoLocationManager locatorManager = new GeoLocationManager();

    return locatorManager.read("myJam", "report", latitude, longitude, radius);
  }

  /**
   * 
   * @param reportId
   * @return
   * @throws InternalBackEndException
   */
  public MReportBean getReport(final String reportId) throws InternalBackEndException {
    /**
     * Insert the report details in the list.
     */
    try {
      final Map<byte[], byte[]> reportMap = ((MyJamStorageManager) storageManager).selectAll("Report", reportId);
      final MReportBean report = (MReportBean) introspection(MReportBean.class, reportMap);
      return report;
    } catch (final InternalBackEndException e) {
      throw new InternalBackEndException("Wrong parameter: " + e.getMessage());
    } catch (final IOBackEndException e) {
      throw new InternalBackEndException(e.getMessage());
    }
  }

  public List<String> getActiveReport(final String userId) throws InternalBackEndException, IOBackEndException {
    final List<String> activeReports = new LinkedList<String>();
    try {
      final Map<byte[], byte[]> res = ((MyJamStorageManager) storageManager).selectAll("ActiveReport", userId);
      for (final byte[] key : res.keySet()) {
        activeReports.add(MyMedId.parseByteBuffer(ByteBuffer.wrap(key)).toString());
      }
      return activeReports;
    } catch (final WrongFormatException e) {
      throw new InternalBackEndException(e.getMessage());
    }
  }

  /**
   * Returns a list of updates.
   * 
   * @param updateIds
   *          List of update ids.
   * @return
   * @throws InternalBackEndException
   */
  public List<MReportBean> getUpdates(final String reportId, final long timestamp) throws InternalBackEndException {
    final List<String> updateIds = new LinkedList<String>();
    final List<MReportBean> updatesList = new LinkedList<MReportBean>();
    try {
      /**
       * Gets the ids of the last numberUpdates updates.
       */
      final MyMedId start = new MyMedId('u', timestamp, " "); // The user id is
                                                              // set to " "
                                                              // because is the
                                                              // first possible
                                                              // string in
                                                              // lexicographical
                                                              // order.
      final Map<byte[], byte[]> updatesMap = ((MyJamStorageManager) storageManager).getFrom("ReportUpdate", reportId,
          start.AsByteBuffer().array());
      for (final byte[] key : updatesMap.keySet()) {
        updateIds.add(MyMedId.parseByteBuffer(ByteBuffer.wrap(key)).toString());
      }
      /**
       * Insert the updates details in the list.
       */
      final Map<String, Map<byte[], byte[]>> updateMap = ((MyJamStorageManager) storageManager).selectAll("Report",
          updateIds);
      Map<byte[], byte[]> updateCont;
      for (final String currUpdate : updateIds) { // In this way the list
                                                  // updatesList is filled in
                                                  // order of time.
        if ((updateCont = updateMap.get(currUpdate)) != null) {
          updatesList.add((MReportBean) introspection(MReportBean.class, updateCont));
        }
      }
      return updatesList;
    } catch (final InternalBackEndException e) {
      throw new InternalBackEndException("Wrong parameter: " + e.getMessage());
    } catch (final IOBackEndException e) {
      throw new InternalBackEndException(e.getMessage());
    } catch (final WrongFormatException e) {
      throw new InternalBackEndException(e.getMessage());
    }
  }

  /**
   * Returns an integer array with: - The number of negative feedbacks with
   * index 0. - The number of positive feedbacks with index 1.
   * 
   * @param reportId
   * @return
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  public List<MFeedBackBean> getFeedbacks(final String reportId) throws InternalBackEndException, IOBackEndException {
    final List<MFeedBackBean> feedBacksList = new LinkedList<MFeedBackBean>();
    try {
      final Map<byte[], byte[]> feedBacksMap = ((MyJamStorageManager) storageManager).selectAll("Feedback", reportId);
      for (final byte[] key : feedBacksMap.keySet()) {
        final MFeedBackBean currFeedBack = new MFeedBackBean();
        currFeedBack.setUserId(MConverter.byteBufferToString(ByteBuffer.wrap(key)));
        currFeedBack.setValue(MConverter.byteBufferToInteger(ByteBuffer.wrap(feedBacksMap.get(key))));
        feedBacksList.add(currFeedBack);
      }
      return feedBacksList;
    } catch (final InternalBackEndException e) {
      throw new InternalBackEndException("Wrong parameter: " + e.getMessage());
    }
  }

  public MReportBean insertUpdate(final String reportId, final MReportBean update) throws InternalBackEndException,
      IOBackEndException {
    try {

      final GeoLocationManager locatorManager = new GeoLocationManager();
      /**
       * Data preparation
       */
      final Map<byte[], byte[]> reportMap = ((MyJamStorageManager) storageManager).selectAll("Report", reportId);
      if (reportMap.isEmpty()) {
        throw new IOBackEndException("Report not present", 404);
      }
      final MReportBean reportDetails = (MReportBean) introspection(MReportBean.class, reportMap);
      if (!reportDetails.getReportType().equals(update.getReportType())) {
        throw new InternalBackEndException("Report and update types don't match.");
      }
      final long locationId = reportDetails.getLocationId();
      /** The user profile is received ProfileManager */
      final ProfileManager profileManager = new ProfileManager(new StorageManager());
      final MUserBean userProfile = profileManager.read(update.getUserId()); // TODO
                                                                             // Not
                                                                             // secure.
                                                                             // The
                                                                             // server
                                                                             // trust
                                                                             // the
                                                                             // user
                                                                             // identity.
      /** The convention is to use milliseconds since 1 Jenuary 1970 */
      final long timestamp = System.currentTimeMillis();
      final MyMedId updateId = new MyMedId(MyJamTypeValidator.UPDATE_ID, timestamp, userProfile.getLogin());

      update.setId(updateId.toString());
      update.setUserId(userProfile.getId());
      update.setUserName(userProfile.getName());
      update.setTimestamp(timestamp);

      /**
       * Check if the report is expired or not. If it is expired an exception is
       * thrown.
       */
      locatorManager.read("myJam", "report", locationId, reportId);

      // TODO Update expiring time.
      /**
       * Report is not expired. Columns insertion in CF Report
       **/
      ((MyJamStorageManager) storageManager).insertSlice("Report", updateId.toString(), update.getAttributeToMap());
      /**
       * Column insertion in the CF ReportUpdate
       */
      ((MyJamStorageManager) storageManager).insertColumn("ReportUpdate", reportId, updateId.AsByteBuffer().array(),
          new byte[0]);
      /**
       * Column insertion in the CF UserReport
       */
      ((MyJamStorageManager) storageManager).insertColumn("UserReport", userProfile.getId(), updateId.AsByteBuffer()
          .array(), new byte[0]);
      /**
       * Update expiration time.
       */
      // TODO To update the expiration time is sufficient to reinsert the column
      // in CF Location, changing the TTL.
      return update;
    } catch (final InternalBackEndException e) {
      throw new InternalBackEndException("Wrong parameter: " + e.getMessage());
    } catch (final IOBackEndException e) {
      throw new IOBackEndException("The report is expired, you cannot insert an update.", 404);
    }
  }

  public void insertFeedback(final String reportId, final String updateId, final MFeedBackBean feedback)
      throws InternalBackEndException, IOBackEndException {
    try {

      final GeoLocationManager locatorManager = new GeoLocationManager();
      /**
       * Data preparation
       */
      final Map<byte[], byte[]> reportMap = ((MyJamStorageManager) storageManager).selectAll("Report", reportId);
      if (reportMap.isEmpty()) {
        throw new IOBackEndException(" Report not valid. ", 404);
      }
      final MReportBean reportDetails = (MReportBean) introspection(MReportBean.class, reportMap);
      final long locationId = reportDetails.getLocationId();

      /**
       * Check if the report is expired or not. If it is expired an exception is
       * thrown.
       */
      locatorManager.read("myJam", "report", locationId, reportId);

      /**
       * Check if the user has already put a feedback on this report.
       */
      // TODO Check if the user yet inserted a feedback. Check the userId.
      try {
        ((MyJamStorageManager) storageManager).selectColumn("Feedback", updateId == null ? reportId : updateId, null,
            MConverter.stringToByteBuffer(feedback.getUserId()).array());
        throw new InternalBackEndException("Feedback already present.");
      } catch (final IOBackEndException e) {
      }
      /**
       * Report is not expired. Columns insertion in CF Report
       **/
      ((MyJamStorageManager) storageManager).insertColumn("Feedback", updateId == null ? reportId : updateId,
          MConverter.stringToByteBuffer(feedback.getUserId()).array(), MConverter.intToByteBuffer(feedback.getValue())
              .array());

    } catch (final InternalBackEndException e) {
      throw new InternalBackEndException(" " + e.getMessage());
    } catch (final IOBackEndException e) { // TODO Check the possible causes of
                                           // IOException.
      throw new IOBackEndException("The report is expired, you cannot insert a feedback.", 404);
    }
  }

  public void deleteReport(final String reportId) throws InternalBackEndException {
    try {
      final MReportBean report = getReport(reportId);
      final long locationId = report.getLocationId();
      final String areaId = String.valueOf(Locator.getAreaId(locationId));
      final MyMedId id = MyMedId.parseString(reportId);
      /**
       * Removes the column by ActiveReport CF, if present.
       */
      ((MyJamStorageManager) storageManager).removeColumn("ActiveReport", report.getUserId(), null, id.AsByteBuffer()
          .array());
      /**
       * Removes the column by ActiveReport CF, if present.
       */
      ((MyJamStorageManager) storageManager).removeColumn("Location", areaId, MConverter.longToByteBuffer(locationId)
          .array(), id.AsByteBuffer().array());
    } catch (final InternalBackEndException e) {
      throw new InternalBackEndException("Wrong parameter: " + e.getMessage());
    } catch (final WrongFormatException e) {
      throw new InternalBackEndException("Wrong id format: " + e.getMessage());
    }
  }

}