package com.mymed.tests.unit.manager;

import static org.junit.Assert.*;

import java.util.LinkedList;
import java.util.List;
import java.util.Random;

import org.junit.Before;
import org.junit.Test;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.myjam.MyJamManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MyJamTypes.ReportType;
import com.mymed.model.data.myjam.MyJamTypes.TrafficFlowType;
import com.mymed.utils.locator.Location;
import com.mymed.utils.locator.Locator;

public class MyJamManagerTest extends GeneralTest{
	private final static String TAG = "MyJamTest";
	private MyJamManager manager;
	public static double earthRadius = 6371.01d*1E3;
	private final static int centerLat = 45150000;
	private final static int centerLon = 7500000;
	private final static int degreeRange = 2000000;
	private final static int meterRange = 50000;
	private List<String> idList;
	private double maxError;
	Location centerL;
	Random rand;
	
	private final static String[] userId = {"google.com/profiles/iacopo.rzz","google.com/profiles/rossi.claudio.82"};
	private final static String[] comment = {"Hello!!","Ciao!!","Salut!!","Hallo!","Ola!!"};
	private final static int NUM_INSERTIONS = 10;
	private final static int MAX_NUM_UPDATES = 20;
	
	@Before
	public void setUp() throws InternalBackEndException {
		super.setUp();
		try {
			if (manager==null)
				this.manager = new MyJamManager(new MyJamStorageManager());
			if (idList==null)
				idList = new LinkedList<String>();
			else
				idList.clear();
			if (rand == null)
				rand = new Random(19580427);
		}catch(Exception e){
			e.printStackTrace();
		}
	}
    
	@Test
	public void testSearch(){
		List<MSearchBean> listSearchRep = null;
		double err = 10.0;
		Location currLocDec = null;

		try{
			listSearchRep = this.manager.searchReports(centerLat,centerLon, meterRange);
			for (MSearchBean sRep :listSearchRep){
				idList.add(sRep.getId());
			} 

			for (int i=0;i<NUM_INSERTIONS;i++){
				int lat = (int) (rand.nextDouble()*degreeRange +(centerLat-degreeRange/2)); 
				int lon = (int) (rand.nextDouble()*degreeRange +(centerLon-degreeRange/2));

				centerL = new Location(centerLat,centerLon);
				Location currLoc = new Location(lat,lon);
				currLocDec = Locator.getLocationFromId(Locator.getLocationId(lat, lon));
				if((err = currLoc.distanceGCTo(currLocDec))>maxError)
					maxError = err;

				String currRepId = null;

				MReportBean repB = this.manager.insertReport(generateReport(rand), lat, lon);
				currRepId = repB.getId();

				if (currRepId!=null)
					System.out.println(TAG+"	MyJamTest 	All : "+currRepId.toString());
				if (currLocDec!=null){
					double distance = currLocDec.distanceGCTo(centerL);
					System.out.println(TAG+"	MyJamTest 	Report "+i+": "+String.valueOf(distance));
					if (distance<=meterRange){
						if (currRepId!=null){
							idList.add(currRepId);
							System.out.println(TAG+"	MyJamTest 	Inserted : "+currRepId.toString());	
						}
					}
				}
			}
			System.out.println(TAG+"	Max Error: "+String.valueOf(maxError));
			long startTime = System.currentTimeMillis();
			listSearchRep = this.manager.searchReports(centerLat,centerLon, meterRange);
			long stopTime = System.currentTimeMillis();
			System.out.println(TAG+"	Search execution time : "+String.valueOf((stopTime-startTime)/1E3));
			System.out.println(TAG+"	Search test => Given: "+String.valueOf(listSearchRep.size())+"  Attended: "+String.valueOf(idList.size()));
			if (listSearchRep.size() != idList.size())
				fail("Wrong number of results.");
			for(MSearchBean sRep : listSearchRep){
				if (!idList.contains(sRep.getId())){
					fail("Item found "+sRep.getId()+" doesn't belongs to items in range.");
				}			
			}
		}catch(final Exception e){
			fail(e.getMessage());
		}
	}

	@Test
	public void testGetReport(){
        Location currLocDec = null;
		try {
			centerL = new Location(centerLat,centerLon);
	        currLocDec = Locator.getLocationFromId(Locator.getLocationId(centerLat, centerLon));
	        maxError = centerL.distanceGCTo(currLocDec);
	        MReportBean repRes = this.manager.insertReport(generateReport(rand),centerLat, centerLon);
	        String reportId = repRes.getId();
			MReportBean res = this.manager.getReport(reportId);
			if (res.getReportType()==null?repRes.getReportType()==null:res.getReportType()==repRes.getReportType())
				fail ("Different report type.");			
			
			
		} catch (final Exception e) {
			fail(e.getMessage());
		}
    }
    
	@Test
    public void testGetUpdate(){
		try {
			MReportBean rep = generateReport(rand);
			rep = generateReport(rand);
			MReportBean repRes = this.manager.insertReport(rep, (int) (44.512*1E6), (int) (7.926*1E6)); 
			String repId = repRes.getId();
			for (int j=0;j<userId.length;j++){
				MFeedBackBean currFeed = new MFeedBackBean();
				currFeed.setUserId(userId[j]);
				currFeed.setValue(rand.nextInt(2));
				this.manager.insertFeedback(repId,repId,currFeed);
			}
			for (int i=0;i<rand.nextInt(MAX_NUM_UPDATES);i++){
				MReportBean updRes = this.manager.insertUpdate(repId, 
						generateUpdate(ReportType.valueOf(rep.getReportType()),rand)); 
				String updId = updRes.getId();
				idList.add(updId);

				for (int j=0;j<userId.length;j++){
					MFeedBackBean currFeed = new MFeedBackBean();
					currFeed.setUserId(String.valueOf(j));
					currFeed.setValue(rand.nextInt(2));
					this.manager.insertFeedback(repId,updId,currFeed);
				}
			}
			List<MReportBean> updates = this.manager.getUpdates(repId, rep.getTimestamp());
			if (updates.size() != idList.size()){
				fail("The number of received updates doesn't match the number of inserted updates!!");
			}
			for (MReportBean update:updates){
				if (!idList.contains(update.getId()))
					fail(TAG+"	Update not found: "+update);
			}
		} catch (final Exception e){
			fail(e.getMessage());
		}
    }
    
	@Test
    public void testGetActive(){
    	try {
			MReportBean rep = generateReport(rand);
			MReportBean repRes = this.manager.insertReport(rep,(int) (32.10*1E6), (int) (52.15*1E6));
			
			List<String> results = this.manager.getActiveReport(repRes.getUserId());
			String repId = repRes.getId();
			if (!results.contains(repId))
				fail("Report "+repId+" must be active.");
			this.manager.deleteReport(repId);
			results = this.manager.getActiveReport(userId[0]);
			if (results.contains(repId))
				fail("Report "+repId+" must be not active.");
			this.manager.deleteReport(repId); //Only to check what happens.
		} catch (final Exception e) {
			fail();
		} 
    }
    
	@Test
    public void testGetFeedback(){
		try {
			MReportBean rep = generateReport(rand);
			MReportBean repRes = this.manager.insertReport(rep, (int) (40.10*1E6), (int) (-2.15*1E6)); 
			String repId = repRes.getId();
			
			for (int i=0;i<userId.length;i++){
				MFeedBackBean currFeed = new MFeedBackBean();
				currFeed.setValue(rand.nextInt(2));
				currFeed.setUserId(userId[i]);
				this.manager.insertFeedback(repId,null,currFeed);
			}
			List<MFeedBackBean> feeds = this.manager.getFeedbacks(repId);
			if (feeds.size()!=userId.length)
				fail("Number of feedbacks doesn't match the inserted ones");
		} catch (final Exception e) {
			fail();
		}
    }
    
    private MReportBean generateReport(Random rand){
    	MReportBean currRep = new MReportBean();
    	ReportType repT = ReportType.values()[rand.nextInt(ReportType.values().length-1)];
    	currRep.setReportType(repT.name());
    	switch (repT){
    	case CAR_CRASH:
    	case WORK_IN_PROGRESS:
    	case JAM:
    		TrafficFlowType flowT = TrafficFlowType.values()[rand.nextInt(TrafficFlowType.values().length)];
    		currRep.setTrafficFlowType(flowT.name());
    	default:
    	}
    	currRep.setComment(comment[rand.nextInt(comment.length)]);
    	currRep.setUserId(userId[rand.nextInt(userId.length)]);
    	return currRep;
    }
    
    private MReportBean generateUpdate(ReportType type, Random rand){
    	MReportBean currRep = new MReportBean();
    	currRep.setReportType(type.name());


    	currRep.setComment(comment[rand.nextInt(comment.length)]);
    	currRep.setUserId(userId[rand.nextInt(userId.length)]);
    	
    	return currRep;
    }
}
