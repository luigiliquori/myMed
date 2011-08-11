package com.mymed.android.myjam.service;

import java.util.ArrayList;
import java.util.List;


import com.mymed.android.myjam.controller.MyJamCallManager;
import com.mymed.android.myjam.controller.MyJamCallManager.MyJamCallAttributes;
import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.android.myjam.provider.MyJamContract;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.ReportsSearch;
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


public class RestCallService extends IntentService{
    private static final String TAG = "MyJamCallService";
    
    public static final String EXTRA_STATUS_RECEIVER =
            "com.mymed.android.myjam.extra.STATUS_RECEIVER";
    
    public static final String EXTRA_REQUEST_CODE =
            "com.mymed.android.myjam.extra.REQUEST_CODE";
    
    public static final String EXTRA_REQUEST_ID =
            "com.mymed.android.myjam.extra.REQUEST_ID";
    
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
    	int SEARCH_REPORTS = 0x1;
    	int GET_REPORT = 0x2;
    }
	
	
	public RestCallService() {
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
        
		int reqId = intent.getIntExtra(EXTRA_REQUEST_ID, 0);
        final Bundle bundle = new Bundle();
        bundle.putInt(EXTRA_REQUEST_ID, reqId);
		if (receiver != null) receiver.send(STATUS_RUNNING, bundle);
        try {
    		int reqCode = intent.getIntExtra(EXTRA_REQUEST_CODE,0);
        	Bundle contBundle = intent.getBundleExtra(EXTRA_ATTRIBUTES);
        	switch (reqCode){
        	case (RequestCode.SEARCH_REPORTS):
        		onHandleSearch(contBundle);
				break;
        	case (RequestCode.GET_REPORT):
        		onHandleGetReport(contBundle);
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
    			bundle.getString(MyJamCallAttributes.LATITUDE),
    			bundle.getString(MyJamCallAttributes.LONGITUDE),
    			bundle.getString(MyJamCallAttributes.RADIUS));
			batch = new ArrayList<ContentProviderOperation> (listSearchRep.size());
			batch.add(ContentProviderOperation.newDelete(ReportsSearch.CONTENT_URI).build());
			for (MSearchReportBean currShortRep:listSearchRep){
				ContentValues currVal = new ContentValues();
				currVal.put(ReportsSearch.REPORT_TYPE, currShortRep.getReportType());
				currVal.put(ReportsSearch.REPORT_ID, currShortRep.getReportId());
				currVal.put(ReportsSearch.LATITUDE, currShortRep.getLatitude());
				currVal.put(ReportsSearch.LONGITUDE, currShortRep.getLongitude());
				currVal.put(ReportsSearch.DISTANCE, currShortRep.getDistance());
				currVal.put(ReportsSearch.DATE, currShortRep.getDate());
				batch.add(ContentProviderOperation.newInsert(ReportsSearch.CONTENT_URI).withValues(currVal).build());
			}
			resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
	}
	
	private void onHandleGetReport(Bundle bundle) 
			throws InternalBackEndException, IOBackEndException, InternalClientException {
		
		String reportId = bundle.getString(MyJamCallAttributes.ID);
		/** If the report is present on the database the rest call is not performed. */
		Uri uri = Report.CONTENT_URI;
		MReportBean report = myJamRestCall.getReport(reportId);
		ContentValues reportVal = new ContentValues();
		reportVal.put(Report.REPORT_ID, reportId);
		reportVal.put(Report.REPORT_TYPE, report.getReportType());
		reportVal.put(Report.TRAFFIC_FLOW, report.getTrafficFlowType());
		reportVal.put(Report.TRANSIT_TYPE, report.getTransitType());
		reportVal.put(Report.COMMENT, report.getComment());
		reportVal.put(Report.USER_ID, report.getUserId());
		reportVal.put(Report.USER_NAME, report.getUserName());
		resolver.insert(uri, reportVal);
	}
}
