package com.mymed.android.myjam.service;

import java.util.ArrayList;
import java.util.List;


import com.mymed.android.myjam.controller.IMyJamCallAttributes;
import com.mymed.android.myjam.controller.MyJamCallManager;
import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.android.myjam.provider.MyJamContract;
import com.mymed.android.myjam.provider.MyJamContract.Feedback;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.SearchResult;
import com.mymed.android.myjam.provider.MyJamContract.Update;
import com.mymed.android.myjam.type.MFeedBackBean;
import com.mymed.android.myjam.type.MReportBean;
import com.mymed.android.myjam.type.MSearchReportBean;
import android.app.IntentService;
import android.content.ContentProviderOperation;
import android.content.ContentResolver;
import android.content.ContentValues;
import android.content.Intent;
import android.content.OperationApplicationException;
import android.net.Uri;
import android.os.Bundle;
import android.os.RemoteException;
import android.os.ResultReceiver;

/**
 * Service that manages the reception and the storage of the data.
 * @author iacopo
 *
 */
public class MyJamCallService extends IntentService{
    private static final String TAG = "MyJamCallService";
    
    public static final String EXTRA_STATUS_RECEIVER =
            "com.mymed.android.myjam.extra.STATUS_RECEIVER";
    
    public static final String EXTRA_REQUEST_CODE =
            "com.mymed.android.myjam.extra.REQUEST_CODE";
    
    public static final String EXTRA_ATTRIBUTES =
            "com.mymed.android.myjam.extra.ATTRIBUTES";
    
    public static final String EXTRA_OBJECT =
            "com.mymed.android.myjam.extra.OBJECT";   
    
    public static final int STATUS_RUNNING = 0x1;
    public static final int STATUS_ERROR = 0x2;
    public static final int STATUS_FINISHED = 0x3;        
    
    private MyJamCallManager myJamRestCall;
    private ContentResolver resolver;
    
    public interface RequestCode{
    	int SEARCH_REPORTS = 0x0;
    	int GET_REPORT = 0x1;
    	int GET_UPDATES = 0x2;
    	int GET_REPORT_FEEDBACKS = 0x3;
    	int GET_UPDATE_FEEDBACKS = 0x4;
    	
    	int INSERT_REPORT = 0x5;
    }
	
	
	public MyJamCallService() {
		super(TAG);
		// TODO Auto-generated constructor stub
	}
	
    @Override
    public void onCreate() {
        super.onCreate();

        myJamRestCall = MyJamCallManager.getInstance();
        resolver = getContentResolver();
    }

	@Override
	protected void onHandleIntent(Intent intent) {
		final ResultReceiver receiver = intent.getParcelableExtra(EXTRA_STATUS_RECEIVER);
        
		int reqCode = intent.getIntExtra(EXTRA_REQUEST_CODE, 0);
        final Bundle bundle = new Bundle();
        bundle.putInt(EXTRA_REQUEST_CODE, reqCode);
		if (receiver != null) receiver.send(STATUS_RUNNING, bundle);
        try {
        	Bundle contBundle = intent.getBundleExtra(EXTRA_ATTRIBUTES);
        	switch (reqCode){
        	case (RequestCode.SEARCH_REPORTS):
        		onHandleSearch(contBundle);
				break;
        	case (RequestCode.GET_REPORT):
        		onHandleGetReport(contBundle);
        		break;
        	case (RequestCode.GET_UPDATES):
        		onHandleGetUpdates(contBundle);
        		break;
        	case (RequestCode.GET_REPORT_FEEDBACKS):
        	case (RequestCode.GET_UPDATE_FEEDBACKS):
        		onHandleGetFeedbacks(contBundle,reqCode);
        		break;
        	case (RequestCode.INSERT_REPORT):
        		MReportBean report = (MReportBean) intent.getSerializableExtra(EXTRA_OBJECT);
        		onHandleInsertReport(contBundle,report);
        		break;
        	default:
        		break;
        	}
			if (receiver != null) receiver.send(STATUS_FINISHED, bundle);
		} catch (Exception e) {
            bundle.putString(Intent.EXTRA_TEXT, e.getMessage());
			if (receiver != null) receiver.send(STATUS_ERROR, bundle);
		}
		
	}

	private void onHandleSearch(Bundle bundle) 
			throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException{
		final ArrayList<ContentProviderOperation> batch;
		
		List<MSearchReportBean> listSearchRep = myJamRestCall.searchReports(
    			bundle.getInt(IMyJamCallAttributes.LATITUDE),
    			bundle.getInt(IMyJamCallAttributes.LONGITUDE),
    			bundle.getInt(IMyJamCallAttributes.RADIUS));
			batch = new ArrayList<ContentProviderOperation> ((2*listSearchRep.size())+3);
			int searchId = bundle.getInt(IMyJamCallAttributes.SEARCH_ID);
			if (searchId == Search.NEW_SEARCH){
				batch.add(ContentProviderOperation.newDelete(SearchResult.CONTENT_URI).withSelection(SearchResult.SEARCH_ID_SELECTION,
						new String[]{String.valueOf(Search.OLD_SEARCH)}).build());
				batch.add(ContentProviderOperation.newUpdate(SearchResult.CONTENT_URI).withSelection(SearchResult.SEARCH_ID_SELECTION,
						new String[]{String.valueOf(Search.NEW_SEARCH)}).withValue(SearchResult.SEARCH_ID, Search.OLD_SEARCH).build());
			}else if(searchId == Search.INSERT_SEARCH){
				batch.add(ContentProviderOperation.newDelete(SearchResult.CONTENT_URI).withSelection(SearchResult.SEARCH_ID_SELECTION,
						new String[]{String.valueOf(Search.INSERT_SEARCH)}).build());
			}else
				throw new InternalClientException("The search id is not valid.");
			ContentValues currVal = new ContentValues();
			currVal.put(Search.SEARCH_ID, searchId);
			currVal.put(Search.DATE, System.currentTimeMillis());
			batch.add(ContentProviderOperation.newInsert(Search.CONTENT_URI).withValues(currVal).build());
			for (MSearchReportBean currShortRep:listSearchRep){
				currVal = new ContentValues();
				currVal.put(SearchResult.REPORT_ID, currShortRep.getReportId());
				currVal.put(SearchResult.DISTANCE, currShortRep.getDistance());
				currVal.put(SearchResult.SEARCH_ID, searchId);
				batch.add(ContentProviderOperation.newInsert(SearchResult.CONTENT_URI).withValues(currVal).build());
				currVal = new ContentValues();
				currVal.put(Report.REPORT_ID,	currShortRep.getReportId());
				currVal.put(Report.REPORT_TYPE,	currShortRep.getReportType());
				currVal.put(Report.LATITUDE, currShortRep.getLatitude());
				currVal.put(Report.LONGITUDE, currShortRep.getLongitude());
				currVal.put(Report.DATE, currShortRep.getDate());
				batch.add(ContentProviderOperation.newInsert(Report.CONTENT_URI).withValues(currVal).build());
			}
			batch.add(ContentProviderOperation.newDelete(Report.CONTENT_URI).
					withSelection(Report.STALE_ENTRIES_SELECTION, null).build());
			resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
			resolver.notifyChange(SearchReports.buildSearchUri(String.valueOf(searchId)), null);
	}
	
	private void onHandleGetReport(Bundle bundle) 
			throws InternalBackEndException, IOBackEndException, InternalClientException {
		
		String reportId = bundle.getString(IMyJamCallAttributes.ID);
		/** If the report is present on the database the rest call is not performed. */
		Uri uri = Report.CONTENT_URI;
		MReportBean report = myJamRestCall.getReport(reportId);
		ContentValues reportVal = new ContentValues();
		reportVal.put(Report.TRAFFIC_FLOW, report.getTrafficFlowType());
		reportVal.put(Report.TRANSIT_TYPE, report.getTransitType());
		reportVal.put(Report.COMMENT, report.getComment());
		reportVal.put(Report.USER_ID, report.getUserId());
		reportVal.put(Report.USER_NAME, report.getUserName());
		reportVal.put(Report.FLAG_COMPLETE, true);
		/**
		 * I use update because the report is inserted during the search, here I add only the details that were not present.
		 */
		resolver.update(uri, reportVal, Report.REPORT_SELECTION, new String[]{reportId});
	}
	
	private void onHandleGetUpdates(Bundle contBundle) 
			throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException {
		final String reportId = contBundle.getString(IMyJamCallAttributes.ID);
		final int numUpdates = myJamRestCall.getNumberUpdates(contBundle.getString(IMyJamCallAttributes.ID));
		final ArrayList<ContentProviderOperation> batch;
		final int numNewUpdates = numUpdates - contBundle.getInt(IMyJamCallAttributes.NUM)<0? 0:(numUpdates - contBundle.getInt(IMyJamCallAttributes.NUM));
		List<MReportBean> listUpdates = myJamRestCall.getUpdates(reportId, numNewUpdates);
		batch = new ArrayList<ContentProviderOperation> (listUpdates.size());
		ContentValues currVal = new ContentValues();
	for (MReportBean currUpdate:listUpdates){
			currVal = new ContentValues();
			currVal.put(Update.UPDATE_ID, currUpdate.getId());
			currVal.put(Update.REPORT_ID, reportId);
			currVal.put(Update.TRAFFIC_FLOW, currUpdate.getTrafficFlowType());
			currVal.put(Update.TRANSIT_TYPE, currUpdate.getTransitType());
			currVal.put(Update.COMMENT, currUpdate.getComment());
			currVal.put(Update.DATE, currUpdate.getTimestamp());
			currVal.put(Update.USER_ID, currUpdate.getUserId());
			currVal.put(Update.USER_NAME, currUpdate.getUserName());
			batch.add(ContentProviderOperation.newInsert(Update.CONTENT_URI).withValues(currVal).build());
		}
		resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
		if (listUpdates.size()>0)
			resolver.notifyChange(Update.CONTENT_URI, null);
		
	}
	
	private void onHandleGetFeedbacks(Bundle bundle,int reqCode) 
			throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException {
		final ArrayList<ContentProviderOperation> batch;
		final String reportOrUpdateId = bundle.getString(IMyJamCallAttributes.ID);
		
		List<MFeedBackBean> listFeedBacks = myJamRestCall.getFeedBacks(reportOrUpdateId);
		batch = new ArrayList<ContentProviderOperation> (listFeedBacks.size());
		for (MFeedBackBean currFeedBack:listFeedBacks){
			ContentValues currVal = new ContentValues();
			currVal.put(Feedback.USER_ID, currFeedBack.getUserId());
			currVal.put(reqCode==RequestCode.GET_REPORT_FEEDBACKS?Feedback.REPORT_ID:Feedback.UPDATE_ID, reportOrUpdateId);
			currVal.put(Feedback.GRADE, currFeedBack.getGrade());
			batch.add(ContentProviderOperation.newInsert(Feedback.CONTENT_URI).withValues(currVal).build());
		}
		resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
		if (listFeedBacks.size()>0)
			resolver.notifyChange(reqCode==RequestCode.GET_REPORT_FEEDBACKS?
					Feedback.buildReportIdUri(null):
						Feedback.buildUpdateIdUri(null), null);
	}
	
	private void onHandleInsertReport(Bundle bundle,MReportBean report)
		throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException {
		int latitude = bundle.getInt(IMyJamCallAttributes.LATITUDE);
		int longitude = bundle.getInt(IMyJamCallAttributes.LONGITUDE);
		String reportId = myJamRestCall.insertReport(
    			latitude,
    			longitude,
				report);
		//TODO Use user_name and user_id in my_reports (to add after log-in is done).
		ContentValues currVal = new ContentValues();
		currVal.put(Report.REPORT_ID, reportId);
		currVal.put(Report.USER_ID, "iacopoId");
		currVal.put(Report.USER_NAME, "iacopo");
		currVal.put(Report.LATITUDE, latitude);
		currVal.put(Report.LONGITUDE, longitude);
		currVal.put(Report.DATE, System.currentTimeMillis());
		currVal.put(Report.REPORT_TYPE, report.getReportType());
		currVal.put(Report.TRANSIT_TYPE, report.getTransitType());
		currVal.put(Report.TRAFFIC_FLOW, report.getTrafficFlowType());
		currVal.put(Report.COMMENT, report.getComment());
		currVal.put(Report.FLAG_COMPLETE, true);
		resolver.insert(Report.CONTENT_URI, currVal);
	}
}
