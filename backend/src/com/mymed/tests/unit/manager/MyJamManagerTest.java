package com.mymed.tests.unit.manager;

import java.util.LinkedList;
import java.util.List;
import java.util.Random;

import junit.framework.TestCase;

import com.mymed.controller.core.manager.myjam.MyJamManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MyJamTypes.ReportType;
import com.mymed.model.data.myjam.MyJamTypes.TrafficFlowType;
import com.mymed.utils.locator.Location;
import com.mymed.utils.locator.Locator;

public class MyJamManagerTest extends TestCase {
  private final static String TAG = "MyJamTest";
  private MyJamManager manager;
  public static double earthRadius = 6371.01d * 1E3;
  private final static double centerLat = 45.15;
  private final static double centerLon = 7.5;
  private final static double degreeRange = 2.0;
  private final static int meterRange = 50000;
  private List<String> idList;
  private double maxError;
  Location centerL;
  Random rand;

  private final static String[] userId = {"google.com/profiles/iacopo.rzz", "google.com/profiles/rossi.claudio.82"};
  private final static String[] comment = {"Hello!!", "Ciao!!", "Salut!!", "Hallo!", "Ola!!"};
  private final static int NUM_INSERTIONS = 10;
  private final static int MAX_NUM_UPDATES = 20;

  @Override
  protected void setUp() {
    try {
      if (manager == null) {
        manager = new MyJamManager(new MyJamStorageManager());
      }
      if (idList == null) {
        idList = new LinkedList<String>();
      } else {
        idList.clear();
      }
      if (rand == null) {
        rand = new Random(19580427);
      }
    } catch (final Exception e) {
      e.printStackTrace();
    }
  }

  public void testSearch() {
    List<MSearchBean> listSearchRep = null;
    double err = 10.0;
    Location currLocDec = null;

    try {
      listSearchRep = manager.searchReports((int) (centerLat * 1E6), (int) (centerLon * 1E6), meterRange);
      for (final MSearchBean sRep : listSearchRep) {
        idList.add(sRep.getId());
      }

      for (int i = 0; i < NUM_INSERTIONS; i++) {
        final double lat = rand.nextDouble() * degreeRange + (centerLat - degreeRange / 2);
        final double lon = rand.nextDouble() * degreeRange + (centerLon - degreeRange / 2);

        centerL = new Location(centerLat, centerLon);
        final Location currLoc = new Location(lat, lon);
        currLocDec = Locator.getLocationFromId(Locator.getLocationId(lat, lon));
        if ((err = currLoc.distanceGCTo(currLocDec)) > maxError) {
          maxError = err;
        }

        String currRepId = null;

        final MReportBean repB = manager.insertReport(generateReport(rand), (int) (lat * 1E6), (int) (lon * 1E6));
        currRepId = repB.getId();

        if (currRepId != null) {
          System.out.println(TAG + "	MyJamTest 	All : " + currRepId.toString());
        }
        if (currLocDec != null) {
          final double distance = currLocDec.distanceGCTo(centerL);
          System.out.println(TAG + "	MyJamTest 	Report " + i + ": " + String.valueOf(distance));
          if (distance <= meterRange) {
            if (currRepId != null) {
              idList.add(currRepId);
              System.out.println(TAG + "	MyJamTest 	Inserted : " + currRepId.toString());
            }
          }
        }
      }
      System.out.println(TAG + "	Max Error: " + String.valueOf(maxError));
      final long startTime = System.currentTimeMillis();
      listSearchRep = manager.searchReports((int) (centerLat * 1E6), (int) (centerLon * 1E6), meterRange);
      final long stopTime = System.currentTimeMillis();
      System.out.println(TAG + "	Search execution time : " + String.valueOf((stopTime - startTime) / 1E3));
      System.out.println(TAG + "	Search test => Given: " + String.valueOf(listSearchRep.size()) + "  Attended: "
          + String.valueOf(idList.size()));
      if (listSearchRep.size() != idList.size()) {
        fail("Wrong number of results.");
      }
      for (final MSearchBean sRep : listSearchRep) {
        if (!idList.contains(sRep.getId())) {
          fail("Item found " + sRep.getId() + " doesn't belongs to items in range.");
        }
      }
    } catch (final Exception e) {
      fail(e.getMessage());
    }
  }

  public void testGetReport() {
    Location currLocDec = null;
    try {
      centerL = new Location(centerLat, centerLon);
      currLocDec = Locator.getLocationFromId(Locator.getLocationId(centerLat, centerLon));
      maxError = centerL.distanceGCTo(currLocDec);
      final MReportBean repRes = manager.insertReport(generateReport(rand), (int) (centerLat * 1E6),
          (int) (centerLon * 1E6));
      final String reportId = repRes.getId();
      final MReportBean res = manager.getReport(reportId);
      if (res.getReportType() == null ? repRes.getReportType() == null : res.getReportType() == repRes.getReportType()) {
        fail("Different report type.");
      }

    } catch (final Exception e) {
      fail(e.getMessage());
    }
  }

  public void testGetUpdate() {
    try {
      MReportBean rep = generateReport(rand);
      rep = generateReport(rand);
      final MReportBean repRes = manager.insertReport(rep, (int) (44.512 * 1E6), (int) (7.926 * 1E6));
      final String repId = repRes.getId();
      for (final String element : userId) {
        final MFeedBackBean currFeed = new MFeedBackBean();
        currFeed.setUserId(element);
        currFeed.setValue(rand.nextInt(2));
        manager.insertFeedback(repId, repId, currFeed);
      }
      for (int i = 0; i < rand.nextInt(MAX_NUM_UPDATES); i++) {
        final MReportBean updRes = manager.insertUpdate(repId,
            generateUpdate(ReportType.valueOf(rep.getReportType()), rand));
        final String updId = updRes.getId();
        idList.add(updId);

        for (int j = 0; j < userId.length; j++) {
          final MFeedBackBean currFeed = new MFeedBackBean();
          currFeed.setUserId(String.valueOf(j));
          currFeed.setValue(rand.nextInt(2));
          manager.insertFeedback(repId, updId, currFeed);
        }
      }
      final List<MReportBean> updates = manager.getUpdates(repId, rep.getTimestamp());
      if (updates.size() != idList.size()) {
        fail("The number of received updates doesn't match the number of inserted updates!!");
      }
      for (final MReportBean update : updates) {
        if (!idList.contains(update.getId())) {
          fail(TAG + "	Update not found: " + update);
        }
      }
    } catch (final Exception e) {
      fail(e.getMessage());
    }
  }

  public void testGetActive() {
    try {
      final MReportBean rep = generateReport(rand);
      final MReportBean repRes = manager.insertReport(rep, (int) (32.10 * 1E6), (int) (52.15 * 1E6));

      List<String> results = manager.getActiveReport(repRes.getUserId());
      final String repId = repRes.getId();
      if (!results.contains(repId)) {
        fail("Report " + repId + " must be active.");
      }
      manager.deleteReport(repId);
      results = manager.getActiveReport(userId[0]);
      if (results.contains(repId)) {
        fail("Report " + repId + " must be not active.");
      }
      manager.deleteReport(repId); // Only to check what happens.
    } catch (final Exception e) {
      fail();
    }
  }

  public void testGetFeedback() {
    try {
      final MReportBean rep = generateReport(rand);
      final MReportBean repRes = manager.insertReport(rep, (int) (40.10 * 1E6), (int) (-2.15 * 1E6));
      final String repId = repRes.getId();

      for (final String element : userId) {
        final MFeedBackBean currFeed = new MFeedBackBean();
        currFeed.setValue(rand.nextInt(2));
        currFeed.setUserId(element);
        manager.insertFeedback(repId, null, currFeed);
      }
      final List<MFeedBackBean> feeds = manager.getFeedbacks(repId);
      if (feeds.size() != userId.length) {
        fail("Number of feedbacks doesn't match the inserted ones");
      }
    } catch (final Exception e) {
      fail();
    }
  }

  private MReportBean generateReport(final Random rand) {
    final MReportBean currRep = new MReportBean();
    final ReportType repT = ReportType.values()[rand.nextInt(ReportType.values().length - 1)];
    currRep.setReportType(repT.name());
    switch (repT) {
      case CAR_CRASH :
      case WORK_IN_PROGRESS :
      case JAM :
        final TrafficFlowType flowT = TrafficFlowType.values()[rand.nextInt(TrafficFlowType.values().length)];
        currRep.setTrafficFlowType(flowT.name());
      default :
    }
    currRep.setComment(comment[rand.nextInt(comment.length)]);
    currRep.setUserId(userId[rand.nextInt(userId.length)]);
    return currRep;
  }

  private MReportBean generateUpdate(final ReportType type, final Random rand) {
    final MReportBean currRep = new MReportBean();
    currRep.setReportType(type.name());

    currRep.setComment(comment[rand.nextInt(comment.length)]);
    currRep.setUserId(userId[rand.nextInt(userId.length)]);

    return currRep;
  }
}
