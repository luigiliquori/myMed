package com.mymed.android.myjam.service;

import java.util.ArrayList;
import java.util.List;


import com.mymed.android.myjam.controller.AuthenticationCallFactory;
import com.mymed.android.myjam.controller.ICallAttributes;
import com.mymed.android.myjam.controller.MyJamCallFactory;
import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.android.myjam.provider.MyJamContract;
import com.mymed.android.myjam.provider.MyJamContract.Feedback;
import com.mymed.android.myjam.provider.MyJamContract.FeedbacksRequest;
import com.mymed.android.myjam.provider.MyJamContract.Login;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.SearchResult;
import com.mymed.android.myjam.provider.MyJamContract.Update;
import com.mymed.android.myjam.provider.MyJamContract.UpdatesRequest;
import com.mymed.android.myjam.provider.MyJamContract.User;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MSearchBean;
import com.mymed.model.data.user.MUserBean;

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
    
    public static final String EXTRA_UPDATE_ID =
            "com.mymed.android.myjam.extra.UPDATE_ID";
    
    public static final String EXTRA_ATTRIBUTES =
            "com.mymed.android.myjam.extra.ATTRIBUTES";
    
    public static final String EXTRA_OBJECT =
            "com.mymed.android.myjam.extra.OBJECT";   
    
    public static final int STATUS_RUNNING = 0x1;
    public static final int STATUS_ERROR = 0x2;
    public static final int STATUS_FINISHED = 0x3;        
    
    private MyJamCallFactory myJamRestCall;
    private ContentResolver resolver;
    
    public interface RequestCode{
    	int LOG_IN = 0x0;
    	int PARTIAL_LOG_OUT = 0x1;
    	int COMPLETE_LOG_OUT = 0x2;
    	//TODO Creates a new myMed profile int REGISTER_USER = 0x2;
    	/*
    	 *Don't change the order, because there the following five values are used to
    	 *find a string inside the array type_obj in array.xml. 
    	 */
    	int SEARCH_REPORTS = 0x3;
    	int GET_REPORT = 0x4;
    	int GET_UPDATES = 0x5;
    	int GET_REPORT_FEEDBACKS = 0x6;
    	int GET_UPDATE_FEEDBACKS = 0x7;
    	
    	int INSERT_REPORT = 0x8;
    	int INSERT_UPDATE = 0x9;
    	int INSERT_REPORT_FEEDBACK = 0x10;
    	int INSERT_UPDATE_FEEDBACK = 0x11;
    }
	
	
	public MyJamCallService() {
		super(TAG);
		// TODO Auto-generated constructor stub
	}
	
    @Override
    public void onCreate() {
        super.onCreate();

        myJamRestCall = MyJamCallFactory.getInstance();
        resolver = getContentResolver();
    }

	@Override
	protected void onHandleIntent(Intent intent) {
		final ResultReceiver receiver = intent.getParcelableExtra(EXTRA_STATUS_RECEIVER);
        
		final Bundle contBundle = intent.getBundleExtra(EXTRA_ATTRIBUTES);
		final int reqCode = intent.getIntExtra(EXTRA_REQUEST_CODE, 0);
		/** Contains a value only if request is one among: GET_REPORT_FEEDBACKS, GET_UPDATE_FEEDBACKS, GET_UPDATES */
		final String reportOrUpdateId = contBundle.getString(ICallAttributes.REPORT_ID);
		/** Contains a value only if request is SEARCH_REPORTS */
		final int searchId = contBundle.getInt(ICallAttributes.SEARCH_ID);
        final Bundle bundle = new Bundle();
        bundle.putInt(EXTRA_REQUEST_CODE, reqCode);
        
 		if (receiver != null) receiver.send(STATUS_RUNNING, bundle);
        try {
        	ContentValues currVal;
        	switch (reqCode){
        	case (RequestCode.LOG_IN):
        		logIn(contBundle);
        		break;
        	case (RequestCode.COMPLETE_LOG_OUT):
        	case (RequestCode.PARTIAL_LOG_OUT):
        		logOut(contBundle,reqCode);
        		break;
        	case (RequestCode.SEARCH_REPORTS):
        		/** Set a flag indicating that the request is running */
        		currVal = new ContentValues();
    			currVal.put(Search.SEARCHING, true);	// The search operation is ongoing.
    			getContentResolver().update(Search.CONTENT_URI, currVal, 
    					Search.SEARCH_ID_SELECTION, new String[]{String.valueOf(searchId)});
        		search(contBundle);
				break;
        	case (RequestCode.GET_REPORT):
        		getReport(contBundle);
        		break;
        	case (RequestCode.GET_UPDATES):
        		/** Set a flag indicating that the request is running */
    			currVal = new ContentValues();
    			currVal.put(FeedbacksRequest.REPORT_ID, reportOrUpdateId);
    			currVal.put(FeedbacksRequest.UPDATING, true);
    			currVal.put(FeedbacksRequest.LAST_UPDATE, System.currentTimeMillis());
        		getContentResolver().insert(UpdatesRequest.CONTENT_URI, currVal);
        		getUpdates(contBundle);
        		break;
        	case (RequestCode.GET_REPORT_FEEDBACKS):
        	case (RequestCode.GET_UPDATE_FEEDBACKS):
        		/** Set a flag indicating that the request is running */
        		currVal = new ContentValues();
				currVal.put(reqCode==RequestCode.GET_REPORT_FEEDBACKS?UpdatesRequest.REPORT_ID:
					UpdatesRequest.UPDATE_ID, reportOrUpdateId);
				currVal.put(UpdatesRequest.UPDATING, true);
				currVal.put(UpdatesRequest.LAST_UPDATE, System.currentTimeMillis());
				getContentResolver().insert(FeedbacksRequest.CONTENT_URI, currVal);
        		getFeedbacks(contBundle,reqCode);
        		break;
        	case (RequestCode.INSERT_REPORT):
        		MReportBean report = (MReportBean) intent.getSerializableExtra(EXTRA_OBJECT);
        		insertReport(contBundle,report);
        		break;
        	case (RequestCode.INSERT_UPDATE):
        		MReportBean update = (MReportBean) intent.getSerializableExtra(EXTRA_OBJECT);
        		insertUpdate(contBundle,update);
        		break;
        	case (RequestCode.INSERT_REPORT_FEEDBACK):
        	case (RequestCode.INSERT_UPDATE_FEEDBACK):
        		MFeedBackBean feedback = (MFeedBackBean) intent.getSerializableExtra(EXTRA_OBJECT);
        		insertFeedback(contBundle,reqCode,feedback);
        		break;
        	default:
        		break;
        	}
			if (receiver != null) receiver.send(STATUS_FINISHED, bundle);
		} catch (Exception e) {
            bundle.putString(Intent.EXTRA_TEXT, e.getMessage());
			if (receiver != null) receiver.send(STATUS_ERROR, bundle);
		} finally {
			/** Reset synchronization flag */
			if ((reqCode == RequestCode.SEARCH_REPORTS)){
        		ContentValues currVal = new ContentValues();
				currVal.put(Search.SEARCHING, false);	// The Updates are no more under update.
				getContentResolver().update(Search.CONTENT_URI, currVal, 
						Search.SEARCH_ID_SELECTION, new String[]{String.valueOf(searchId)});

			}
			if ((reqCode == RequestCode.GET_REPORT_FEEDBACKS) || (reqCode == RequestCode.GET_UPDATE_FEEDBACKS)){
				ContentValues currVal = new ContentValues();
				currVal.put(FeedbacksRequest.UPDATING, false);
				getContentResolver().update(FeedbacksRequest.CONTENT_URI, currVal, 
						reqCode == RequestCode.GET_REPORT_FEEDBACKS?FeedbacksRequest.REPORT_SELECTION:
							FeedbacksRequest.UPDATE_SELECTION, new String[]{reportOrUpdateId});
			}
			if ((reqCode == RequestCode.GET_UPDATES)){
        		ContentValues currVal = new ContentValues();
				currVal.put(UpdatesRequest.UPDATING, false);	// The Updates are no more under update.
				getContentResolver().update(UpdatesRequest.CONTENT_URI, currVal, 
						UpdatesRequest.REPORT_SELECTION, new String[]{reportOrUpdateId});

			}
		}
		
	}

	/**
	 * Logs in to myMed.
	 * @param bundle
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 * @throws OperationApplicationException 
	 * @throws RemoteException 
	 */
	private void logIn(Bundle bundle)
			throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException{
//		final ArrayList<ContentProviderOperation> batch = new ArrayList<ContentProviderOperation> (2);
//		final AuthenticationManager authManager = new AuthenticationManager();
//		
//		MUserBean user = authManager.authenticate(
//			bundle.getString(ICallAttributes.LOGIN),
//			bundle.getString(ICallAttributes.PASSWORD));
//		//authManager.logIn(user.getId(), 
//		//		bundle.getString(ICallAttributes.IP));
//		ContentValues currVal = new ContentValues();
//		currVal.put(User.USER_ID, user.getId());
//		currVal.put(User.LOGIN_ID, user.getLogin());
//		currVal.put(User.USER_NAME, user.getName());
//		currVal.put(User.FIRST_NAME, user.getFirstName());
//		currVal.put(User.LAST_NAME, user.getLastName());
//		currVal.put(User.GENDER, user.getGender());
//		//TODO add eventually other values.
//		batch.add(ContentProviderOperation.newInsert(User.CONTENT_URI).withValues(currVal).build());
//		currVal = new ContentValues();
//		currVal.put(Login.LOGIN_ID, user.getLogin());
//		currVal.put(Login.USER_ID, user.getId());
//		currVal.put(Login.PASSWORD, bundle.getString(ICallAttributes.PASSWORD));
//		currVal.put(Login.DATE, System.currentTimeMillis());
//		currVal.put(Login.LOGGED, true);
//		batch.add(ContentProviderOperation.newInsert(Login.CONTENT_URI).withValues(currVal).build());
//		resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
	}
	
	/**
	 * Logs out from myMed.
	 * @param bundle
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 * @throws OperationApplicationException 
	 * @throws RemoteException 
	 */
	private void logOut(Bundle bundle,int reqCode)
			throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException{
//		final ArrayList<ContentProviderOperation> batch = new ArrayList<ContentProviderOperation> (2);
//		final AuthenticationManager authManager = new AuthenticationManager();
//		
//		authManager.logOut(bundle.getString(ICallAttributes.USER_ID));
//		if (reqCode == RequestCode.COMPLETE_LOG_OUT)
//			batch.add(ContentProviderOperation.newDelete(Login.CONTENT_URI).build());
//		else if (reqCode == RequestCode.PARTIAL_LOG_OUT){
//			ContentValues val = new ContentValues();
//			val.put(Login.LOGGED, false);
//			batch.add(ContentProviderOperation.newUpdate(Login.CONTENT_URI).withValues(val).build());
//		}		
//		resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
	}	
	
	/**
	 * Searches reports in myJam database
	 * @param bundle Contains parameters {@link LATITUDE}, {@link LONGITUDE} and {@link RADIUS}.  
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 * @throws RemoteException
	 * @throws OperationApplicationException
	 */
	private void search(Bundle bundle) 
			throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException{
		final ArrayList<ContentProviderOperation> batch;
		
//		List<MSearchBean> listSearchRep = myJamRestCall.searchReports(
//    			bundle.getInt(ICallAttributes.LATITUDE),
//    			bundle.getInt(ICallAttributes.LONGITUDE),
//    			bundle.getInt(ICallAttributes.RADIUS));
//			batch = new ArrayList<ContentProviderOperation> ((2*listSearchRep.size())+3);
//			int searchId = bundle.getInt(ICallAttributes.SEARCH_ID);
//			/** 
//			 * If it's a {@link NEW_SEARCH} the following operations are done:
//			 * -The search results with flag OLD_SEARCH are deleted.
//			 * -The search results with flag NEW_SEARCH are marked as old (OLD_SEARCH).
//			 * -The new search results are inserted, with flag NEW_SEARCH.
//			 * -The reports that are no more pointed by entries in SearchResults table and that doesn't belong to
//			 * the current user are deleted.   
//			 **/
//			if (searchId == Search.NEW_SEARCH){
//				batch.add(ContentProviderOperation.newDelete(SearchResult.CONTENT_URI).withSelection(SearchResult.SEARCH_ID_SELECTION,
//						new String[]{String.valueOf(Search.OLD_SEARCH)}).build());
//				batch.add(ContentProviderOperation.newUpdate(SearchResult.CONTENT_URI).withSelection(SearchResult.SEARCH_ID_SELECTION,
//						new String[]{String.valueOf(Search.NEW_SEARCH)}).withValue(SearchResult.SEARCH_ID, Search.OLD_SEARCH).build());
//				/** 
//				 * If it's a {@link INSERT_SEARCH} the following operations are done:
//				 * -The search results with flag INSERT_SEARCH are deleted.
//				 * -The new search results are inserted, with flag INSERT_SEARCH.
//				 * -The reports that are no more pointed by entries in SearchResults table and that doesn't belong to
//				 * the current user are deleted.   
//				 **/
//			}else if(searchId == Search.INSERT_SEARCH){
//				batch.add(ContentProviderOperation.newDelete(SearchResult.CONTENT_URI).withSelection(SearchResult.SEARCH_ID_SELECTION,
//						new String[]{String.valueOf(Search.INSERT_SEARCH)}).build());
//			}else
//				throw new InternalClientException("The search id is not valid.");
//			ContentValues currVal = new ContentValues();
//			currVal.put(Search.SEARCH_ID, searchId);
//			currVal.put(Search.DATE, System.currentTimeMillis());
//			currVal.put(Search.LATITUDE, bundle.getInt(ICallAttributes.LATITUDE));
//			currVal.put(Search.LONGITUDE, bundle.getInt(ICallAttributes.LONGITUDE));
//			currVal.put(Search.RADIUS, bundle.getInt(ICallAttributes.RADIUS));
//			currVal.put(Search.SEARCHING, false);
//			batch.add(ContentProviderOperation.newInsert(Search.CONTENT_URI).withValues(currVal).build());
//			for (MSearchBean currShortRep:listSearchRep){
//				currVal = new ContentValues();
//				currVal.put(SearchResult.REPORT_ID, currShortRep.getId());
//				currVal.put(SearchResult.DISTANCE, currShortRep.getDistance());
//				currVal.put(SearchResult.SEARCH_ID, searchId);
//				batch.add(ContentProviderOperation.newInsert(SearchResult.CONTENT_URI).withValues(currVal).build());
//				currVal = new ContentValues();
//				currVal.put(Report.REPORT_ID,	currShortRep.getId());
//				currVal.put(Report.REPORT_TYPE,	currShortRep.getValue());
//				currVal.put(Report.LATITUDE, currShortRep.getLatitude());
//				currVal.put(Report.LONGITUDE, currShortRep.getLongitude());
//				currVal.put(Report.DATE, currShortRep.getDate());
//				batch.add(ContentProviderOperation.newInsert(Report.CONTENT_URI).withValues(currVal).build());
//			}
//			batch.add(ContentProviderOperation.newDelete(Report.CONTENT_URI).
//					withSelection(Report.STALE_ENTRIES_SELECTION, null).build());
//			resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
//			resolver.notifyChange(SearchReports.buildSearchUri(String.valueOf(searchId)), null);
	}
	
	private void getReport(Bundle bundle) 
			throws InternalBackEndException, IOBackEndException, InternalClientException {
		
//		String reportId = bundle.getString(ICallAttributes.REPORT_ID);
//		/** If the report is present on the database the rest call is not performed. */
//		Uri uri = Report.CONTENT_URI;
//		MReportBean report = myJamRestCall.getReport(reportId);
//		ContentValues reportVal = new ContentValues();
//		reportVal.put(Report.TRAFFIC_FLOW, report.getTrafficFlowType());
//		reportVal.put(Report.COMMENT, report.getComment());
//		reportVal.put(Report.USER_ID, report.getUserId());
//		reportVal.put(Report.USER_NAME, report.getUserName());
//		reportVal.put(Report.FLAG_COMPLETE, true);
//		/**
//		 * I use update because the report is inserted during the search, here I add only the details that were not present.
//		 */
//		resolver.update(uri, reportVal, Report.REPORT_SELECTION, new String[]{reportId});
	}
	
	private void getUpdates(Bundle bundle) 
			throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException {
//		final String reportId = bundle.getString(ICallAttributes.REPORT_ID);
//		final int numUpdates = myJamRestCall.getNumberUpdates(bundle.getString(ICallAttributes.REPORT_ID));
//		final ArrayList<ContentProviderOperation> batch;
//		final int numNewUpdates = numUpdates - bundle.getInt(ICallAttributes.NUM)<0? 0:(numUpdates - bundle.getInt(ICallAttributes.NUM));
//		List<MReportBean> listUpdates = myJamRestCall.getUpdates(reportId, numNewUpdates);
//		batch = new ArrayList<ContentProviderOperation> (listUpdates.size());
//		ContentValues currVal = new ContentValues();
//		for (MReportBean currUpdate:listUpdates){
//			currVal = new ContentValues();
//			currVal.put(Update.UPDATE_ID, currUpdate.getId());
//			currVal.put(Update.REPORT_ID, reportId);
//			currVal.put(Update.TRAFFIC_FLOW, currUpdate.getTrafficFlowType());
//			currVal.put(Update.COMMENT, currUpdate.getComment());
//			currVal.put(Update.DATE, currUpdate.getTimestamp());
//			currVal.put(Update.USER_ID, currUpdate.getUserId());
//			currVal.put(Update.USER_NAME, currUpdate.getUserName());
//			batch.add(ContentProviderOperation.newInsert(Update.CONTENT_URI).withValues(currVal).build());
//		}
//		resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
//		if (listUpdates.size()>0)
//			resolver.notifyChange(Update.CONTENT_URI, null);
//		
	}
	
	private void getFeedbacks(Bundle bundle,int reqCode) 
			throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException {
//		final ArrayList<ContentProviderOperation> batch;
//		final String reportOrUpdateId = bundle.getString(ICallAttributes.REPORT_ID);
//		
//		List<MFeedBackBean> listFeedBacks = myJamRestCall.getFeedBacks(reportOrUpdateId);
//		batch = new ArrayList<ContentProviderOperation> (listFeedBacks.size());
//		for (MFeedBackBean currFeedBack:listFeedBacks){
//			ContentValues currVal = new ContentValues();
//			currVal.put(Feedback.USER_ID, currFeedBack.getUserId());
//			currVal.put(reqCode==RequestCode.GET_REPORT_FEEDBACKS?Feedback.REPORT_ID:Feedback.UPDATE_ID, reportOrUpdateId);
//			currVal.put(Feedback.VALUE, currFeedBack.getValue());
//			batch.add(ContentProviderOperation.newInsert(Feedback.CONTENT_URI).withValues(currVal).build());
//		}
//		resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
//		resolver.notifyChange(reqCode==RequestCode.GET_REPORT_FEEDBACKS?
//					Feedback.buildReportIdUri(null):
//						Feedback.buildUpdateIdUri(null), null);
	}
	
	private void insertReport(Bundle bundle,MReportBean report)
		throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException {
//		int latitude = bundle.getInt(ICallAttributes.LATITUDE);
//		int longitude = bundle.getInt(ICallAttributes.LONGITUDE);
//		MReportBean reportRes = myJamRestCall.insertReport(
//    			latitude,
//    			longitude,
//				report);
//		//TODO Use user_name and user_id in my_reports (to add after log-in is done).
//		ContentValues currVal = new ContentValues();
//		currVal.put(Report.REPORT_ID, reportRes.getId());
//		currVal.put(Report.USER_ID, reportRes.getUserId());
//		currVal.put(Report.USER_NAME, reportRes.getUserName());
//		currVal.put(Report.LATITUDE, latitude);
//		currVal.put(Report.LONGITUDE, longitude);
//		currVal.put(Report.DATE, reportRes.getTimestamp());
//		currVal.put(Report.REPORT_TYPE, reportRes.getReportType());
//		currVal.put(Report.TRAFFIC_FLOW, reportRes.getTrafficFlowType());
//		currVal.put(Report.COMMENT, reportRes.getComment());
//		currVal.put(Report.FLAG_COMPLETE, true);
//		resolver.insert(Report.CONTENT_URI, currVal);
	}


	private void insertUpdate(Bundle bundle,MReportBean update)
		throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException {
//		String reportId = bundle.getString(ICallAttributes.REPORT_ID);
//		MReportBean updateRes = myJamRestCall.insertUpdate(reportId,update);
//		//TODO Use user_name and user_id in my_reports (to add after log-in is done).
//		ContentValues currVal = new ContentValues();
//		currVal.put(Update.UPDATE_ID, updateRes.getId());
//		currVal.put(Update.REPORT_ID, reportId);
//		currVal.put(Update.USER_ID, updateRes.getUserId());
//		currVal.put(Update.USER_NAME, updateRes.getUserName());
//		currVal.put(Update.DATE, updateRes.getTimestamp());
//		currVal.put(Update.TRAFFIC_FLOW, updateRes.getTrafficFlowType());
//		currVal.put(Update.COMMENT, updateRes.getComment());
//		resolver.insert(Update.CONTENT_URI, currVal);
	}
	
	private void insertFeedback(Bundle bundle, int reqCode, MFeedBackBean feedback)
			throws InternalBackEndException, IOBackEndException, InternalClientException, RemoteException, OperationApplicationException {
//			String reportId = bundle.getString(ICallAttributes.REPORT_ID);
//			String updateId = bundle.getString(ICallAttributes.UPDATE_ID);
//			myJamRestCall.insertFeedBack(reportId, updateId, feedback);
//			//TODO Use user_name and user_id in my_reports (to add after log-in is done).
//			ContentValues currVal = new ContentValues();
//			currVal.put(Feedback.USER_ID, feedback.getUserId()); //TODO fix this
//			currVal.put(reqCode==RequestCode.INSERT_REPORT_FEEDBACK?Feedback.REPORT_ID:Feedback.UPDATE_ID, 
//					reqCode==RequestCode.INSERT_REPORT_FEEDBACK?reportId:updateId);
//			currVal.put(Feedback.VALUE, feedback.getValue());
//			resolver.insert(Feedback.CONTENT_URI, currVal);
//			resolver.notifyChange(reqCode==RequestCode.INSERT_REPORT_FEEDBACK?
//					Feedback.buildReportIdUri(null):
//						Feedback.buildUpdateIdUri(null), null);
		}
	
	
}
