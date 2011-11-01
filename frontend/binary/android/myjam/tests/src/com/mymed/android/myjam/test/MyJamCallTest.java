package com.mymed.android.myjam.test;

import java.util.LinkedList;
import java.util.List;
import java.util.Random;

import com.mymed.android.myjam.controller.MyJamCallManager;

import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MSearchReportBean;
import com.mymed.model.data.myjam.MyJamId;
import com.mymed.model.data.myjam.MyJamTypes.ReportType;
import com.mymed.model.data.myjam.MyJamTypes.TrafficFlowType;
import com.mymed.model.data.myjam.WrongFormatException;
import com.mymed.myjam.locator.GeoLocationOutOfBoundException;
import com.mymed.myjam.locator.Location;
import com.mymed.myjam.locator.Locator;

import junit.framework.TestCase;

public class MyJamCallTest extends TestCase {
	private MyJamCallManager restCall;
	public static double earthRadius = 6371.01d*1E3;
	private final static double centerLat = 45.15;
	private final static double centerLon = 7.5;
	private final static double degreeRange = 2.0;
	private final static int meterRange = 50000;
	private List<MyJamId> reportsInRange;
	private MReportBean firstRep;
	private double maxError;
	Location centerL;
	Random rand;
	
	private final static String userId = "google.com/profiles/iacopo.rzz";
	
    protected void setUp() {
    	if (restCall==null)
    		this.restCall = MyJamCallManager.getInstance();
        if (reportsInRange==null)
        	reportsInRange = new LinkedList<MyJamId>();
        if (rand == null)
        	rand = new Random(19580427);
    }
    
    public void testSearch(){
    	List<MSearchReportBean> listShortRep = null;
        double err = 10.0;
        Location currLocDec = null;
        
		try {
			listShortRep = this.restCall.searchReports((int) (centerLat*1E6),(int) (centerLon*1E6), meterRange);
			for (MSearchReportBean sRep :listShortRep){
				reportsInRange.add(MyJamId.parseString(sRep.getReportId()));
			}
		} catch (InternalBackEndException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (IOBackEndException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (InternalClientException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (WrongFormatException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
        
        for (int i=0;i<10000;i++){
        	double lat = rand.nextDouble()*degreeRange +(centerLat-degreeRange/2); 
        	double lon = rand.nextDouble()*degreeRange +(centerLon-degreeRange/2);
        	try {
        		centerL = new Location(centerLat,centerLon);
        		Location currLoc = new Location(lat,lon);
            	currLocDec = Locator.getLocationFromId(Locator.getLocationId(lat, lon));
            	if((err = currLoc.distanceGCTo(currLocDec))>maxError)
            		maxError = err;
    		} catch (GeoLocationOutOfBoundException e) {
    			e.printStackTrace();
    		}
        	MyJamId currRepId = null;
        	try {
        		MReportBean repB = this.restCall.insertReport((int) (lat*1E6), (int) (lon*1E6), generateReport(rand));
				currRepId = MyJamId.parseString(repB.getId());
			} catch (InternalBackEndException e) {
				e.printStackTrace();
			} catch (IOBackEndException e) {
				e.printStackTrace();
			} catch (InternalClientException e) {
				e.printStackTrace();
			} catch (WrongFormatException e) {
				e.printStackTrace();
			}
			if (currRepId!=null)
				android.util.Log.i("MyJamTest", "All : "+currRepId.toString());
			if (currLocDec!=null){
				double distance = currLocDec.distanceGCTo(centerL);
				android.util.Log.i("MyJamTest", "Report "+i+": "+String.valueOf(distance));
				if (distance<=meterRange){
					if (currRepId!=null){
						reportsInRange.add(currRepId);
						android.util.Log.i("MyJamTest", "Inserted : "+currRepId.toString());	
					}
				}
			}
        }
//        android.util.Log.i("MyJamTest", String.valueOf(maxError));
//    	try {
//    		long startTime = System.currentTimeMillis();
//			listShortRep = this.restCall.searchReports((int) (centerLat*1E6),(int) (centerLon*1E6), meterRange);
//			long stopTime = System.currentTimeMillis();
//			android.util.Log.i("MyJamTest", "Search execution time : "+String.valueOf((stopTime-startTime)/1E3));
//		} catch (InternalBackEndException e) {
//			e.printStackTrace();
//		} catch (IOBackEndException e) {
//			e.printStackTrace();
//		} catch (InternalClientException e) {
//			e.printStackTrace();
//		}
//		android.util.Log.i("MyJamTest", "Search test => Given: "+String.valueOf(listShortRep.size())+"  Attended: "+String.valueOf(reportsInRange.size()));
//		if (listShortRep.size() != reportsInRange.size())
//			fail("Wrong number of results.");
//		for(MShortReportBean sRep : listShortRep){
//			try {
//				MyJamId currRepId = MyJamId.parseString(sRep.getReportId());
//				if (!reportsInRange.contains(currRepId)){
//					fail(currRepId.toString()+" not contained in list.");
//				}
//			} catch (WrongFormatException e) {
//				fail("Wrong id");
//			}
//			
//		}
    }
    
//    public void testGetReport(){
//        Location currLocDec = null;
//		try {
//			centerL = new Location(centerLat,centerLon);
//	        currLocDec = Locator.getLocationFromId(Locator.getLocationId(centerLat, centerLon));
//	        maxError = centerL.distanceGCTo(currLocDec);
//	        firstRep = generateReport(rand);
//	        MReportBean repRes = this.restCall.insertReport((int) (centerLat*1E6), (int) (centerLon*1E6), firstRep);
//	        String reportId = repRes.getId();
//			MReportBean res = this.restCall.getReport(reportId);
//			if (res.getReportType()==null?firstRep.getReportType()==null:res.getReportType()==firstRep.getReportType())
//				fail ("Different report type.");			
//			
//			
//		} catch (InternalBackEndException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		} catch (IOBackEndException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		} catch (InternalClientException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		} catch (IllegalArgumentException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		} catch (GeoLocationOutOfBoundException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		}
//    }
//    
//    public void testGetUpdate(){
//		try {
//			MReportBean rep = generateReport(rand);
//			rep = generateReport(rand);
//			rep.setComment(" This is a proof to see how a long comment is shown on the small screen of an Android device.");
//			MReportBean repRes = this.restCall.insertReport((int) (44.512*1E6), (int) (7.926*1E6), rep); 
//			String repId = repRes.getId();
//			int numFeedBacks = rand.nextInt(100);
//			for (int j=0;j<numFeedBacks;j++){
//				MFeedBackBean currFeed = new MFeedBackBean();
//				currFeed.setUserId(String.valueOf(j));
//				currFeed.setValue(rand.nextInt(1));
//				this.restCall.insertFeedBack(repId,repId,currFeed);
//			}
//			for (int i=0;i<20;i++){
//				MReportBean updRes = this.restCall.insertUpdate(repId, 
//						generateUpdate(ReportType.valueOf(rep.getReportType()),rand)); 
//				String updId = updRes.getId();
//				numFeedBacks = rand.nextInt(100);
//				for (int j=0;j<numFeedBacks;j++){
//					MFeedBackBean currFeed = new MFeedBackBean();
//					currFeed.setUserId(String.valueOf(j));
//					currFeed.setValue(rand.nextInt(1));
//					this.restCall.insertFeedBack(repId,updId,currFeed);
//				}
//			}
//			int numUpdates = this.restCall.getNumberUpdates(repId);
//			List<MReportBean> updates = this.restCall.getUpdates(repId,numUpdates);
//			for (MReportBean update:updates){
//				android.util.Log.i("MyJamTest","Update: "+update.getReportType());
//				if (update.getTrafficFlowType()!=null)
//					android.util.Log.i("MyJamTest","Update: "+update.getTrafficFlowType());
//				if (update.getTransitType()!=null)
//					android.util.Log.i("MyJamTest","Update: "+update.getTransitType());
//				android.util.Log.i("MyJamTest","Update: "+update.getComment());				
//			}
//			updates = this.restCall.getUpdates(repId,5);
//			for (MReportBean update:updates){
//				android.util.Log.i("MyJamTest","Update: "+update.getReportType());
//				if (update.getTrafficFlowType()!=null)
//					android.util.Log.i("MyJamTest","Update: "+update.getTrafficFlowType());
//				if (update.getTransitType()!=null)
//					android.util.Log.i("MyJamTest","Update: "+update.getTransitType());
//				android.util.Log.i("MyJamTest","Update: "+update.getComment());				
//			}
//		} catch (InternalBackEndException e) {
//			fail();
//			e.printStackTrace();
//		} catch (IOBackEndException e) {
//			fail();
//			e.printStackTrace();
//		} catch (InternalClientException e) {
//			fail();
//			e.printStackTrace();
//		} catch (IllegalArgumentException e) {
//			fail();
//			e.printStackTrace();
//		}
//    }
//    
//    public void testGetActive(){
//    	try {
//			MReportBean rep = generateReport(rand);
//			MReportBean repRes = this.restCall.insertReport((int) (32.10*1E6), (int) (52.15*1E6), rep);
//			
//			List<String> results = this.restCall.getActiveReports(userId);
//			String repId = repRes.getId();
//			if (!results.contains(repId))
//				fail("Report "+repId+" must be active.");
//			this.restCall.deleteReport(repId);
//			results = this.restCall.getActiveReports(userId);
//			if (results.contains(repId))
//				fail("Report "+repId+" must be not active.");
//			this.restCall.deleteReport(repId); //Only to check what happens.
//		} catch (InternalBackEndException e) {
//			fail();
//			e.printStackTrace();
//		} catch (IOBackEndException e) {
//			fail();
//			e.printStackTrace();
//		} catch (InternalClientException e) {
//			fail();
//			e.printStackTrace();
//		} catch (IllegalArgumentException e) {
//			fail();
//			e.printStackTrace();
//		}
//    }
//    
//    public void testGetFeedback(){
//		try {
//			MReportBean rep = generateReport(rand);
//			MReportBean repRes = this.restCall.insertReport((int) (40.10*1E6), (int) (-2.15*1E6), rep); 
//			String repId = repRes.getId();
//			
//			for (int i=0;i<10;i++){
//				MFeedBackBean currFeed = new MFeedBackBean();
//				currFeed.setValue(rand.nextInt(1));
//				currFeed.setUserId(String.valueOf(i));
//				this.restCall.insertFeedBack(repId,null,currFeed);
//			}
//			List<MFeedBackBean> feeds = this.restCall.getFeedBacks(repId);
//			for (MFeedBackBean feed:feeds){
//				android.util.Log.i("MyJamTest", "FeedBack: UserId = "+feed.getUserId());
//				android.util.Log.i("MyJamTest", "FeedBack: Grade = "+String.valueOf(feed.getValue()));		
//			}
//		} catch (InternalBackEndException e) {
//			fail();
//			e.printStackTrace();
//		} catch (IOBackEndException e) {
//			fail();
//			e.printStackTrace();
//		} catch (InternalClientException e) {
//			fail();
//			e.printStackTrace();
//		} catch (IllegalArgumentException e) {
//			fail();
//			e.printStackTrace();
//		}
//    }
    
    private MReportBean generateReport(Random rand){
    	MReportBean currRep = new MReportBean();
    	ReportType repT = ReportType.values()[rand.nextInt(ReportType.values().length)];
    	currRep.setReportType(repT.name());
    	switch (repT){
    	case CAR_CRASH:
    	case WORK_IN_PROGRESS:
    	case JAM:
    		TrafficFlowType flowT = TrafficFlowType.values()[rand.nextInt(TrafficFlowType.values().length)];
    		currRep.setTrafficFlowType(flowT.name());
    		default:
    		String comment="";
    		switch (rand.nextInt(5)){
    			case 0: comment="Hello!!"; break;
    			case 1: comment="Ciao!!"; break;
    			case 2: comment="Salut!!"; break;
    			case 3: comment="Hallo!"; break;
    			case 4: comment="Ola!!"; break;
    		}
    		currRep.setComment(comment);
    	}
    	currRep.setUserId(userId);
    	return currRep;
    }
    
    private MReportBean generateUpdate(ReportType type,Random rand){
    	MReportBean currRep = new MReportBean();
    	currRep.setReportType(type.name());

    	String comment="";
    	switch (rand.nextInt(5)){
    		case 0: comment="Hello!!"; break;
    		case 1: comment="Ciao!!"; break;
    		case 2: comment="Salut!!"; break;
    		case 3: comment="Hallo!"; break;
    		case 4: comment="Ola!!"; break;
    	}
    	currRep.setComment(comment);
    	currRep.setUserId(userId);
    	
    	return currRep;
    }

    public void tearDown(){
    	
    }
}
