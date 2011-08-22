package com.mymed.android.myjam.ui;


import com.mymed.android.myjam.R;
import com.mymed.android.myjam.provider.MyJamContract.Feedback;
import com.mymed.android.myjam.provider.MyJamContract.FeedbacksRequest;
import com.mymed.android.myjam.provider.MyJamContract.UpdatesRequest;

import com.mymed.android.myjam.provider.MyJamContract.Report;

import com.mymed.android.myjam.provider.MyJamContract.Update;

import com.mymed.android.myjam.service.MyJamCallService;
import com.mymed.android.myjam.service.MyJamCallService.RequestCode;
import com.mymed.android.myjam.controller.IMyJamCallAttributes;

import android.app.ProgressDialog;
import android.content.ContentResolver;
import android.content.ContentValues;
import android.content.Intent;
import android.database.ContentObserver;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.SystemClock;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.RatingBar;
import android.widget.TextView;
import android.widget.Toast;

import com.mymed.utils.GlobalStateAndUtils;
import com.mymed.utils.NotifyingAsyncQueryHandler;
import com.mymed.utils.MyResultReceiver;

/**
 * This activity has the following tasks:
 * - Shows the details of the report chosen on the {@link SearchActivity}.
 * - Shows the updates related to the report.
 * @author iacopo
 *
 */
public class ReportDetailsActivity extends AbstractLocatedActivity implements
NotifyingAsyncQueryHandler.AsyncQueryListener, MyResultReceiver.Receiver, View.OnClickListener{
	private static final String TAG = "ReportDetailsActivity";
	
	private static final int REPORT = 0x0;
	private static final int UPDATE = 0x1;
	
	private static final int FIVE_MINUTES = 300000;
	private static final int TWO_MINUTES = 120000;
	
	private static final int MAX_RATING = 10;
	
	private GlobalStateAndUtils mUtils;
    private Handler mMessageQueueHandler = new Handler();
	
    
    private Cursor 	mReportCursor,
    				mUpdatesCursor,
    				mReportFeedbacksCursor,
    				mUpdateFeedbacksCursor;
    private NotifyingAsyncQueryHandler mHandler;
    private MyResultReceiver mResultReceiver;
	private ProgressDialog mDialog;
	private ImageButton mNextButton;
	private ImageButton mPreviousButton;
	private TextView 	mUpdateCursorIndexTextView,
						mReportFeedbacksTextView,
						mUpdateFeedbacksTextView;
	private RatingBar 	mReportRatingBar,
						mUpdateRatingBar;
	
	private final String[] demandQueryProjection = new String[]{
		UpdatesRequest.LAST_UPDATE,
		UpdatesRequest.UPDATING
	};
	
	
    private String mReportId; /** The Id of the currently pointed report. */
    private String mUpdateId; /** The Id of the currently pointed update. */
    
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.details_view);
		
		mUtils = GlobalStateAndUtils.getInstance(getApplicationContext());
		mResultReceiver = MyResultReceiver.getInstance();
		mHandler = new NotifyingAsyncQueryHandler(ReportDetailsActivity.this
				.getContentResolver(), this);
		
		Intent intent = getIntent();
		/** If no URI has been specified in the intent the activity is closed.*/
		Uri reportUri = null;
		if ((reportUri = intent.getData())==null)
			finish();
		mReportId = Report.getReportId(reportUri);
		/** Initialize the widget references. */
		mNextButton = (ImageButton) findViewById(R.id.imageButtonNext);
		mNextButton.setOnClickListener(this);
		mPreviousButton = (ImageButton) findViewById(R.id.imageButtonPrevious);
		mPreviousButton.setOnClickListener(this);
		mUpdateCursorIndexTextView = (TextView) findViewById(R.id.textViewIndex);
		LayoutInflater mInflater = getLayoutInflater();
		mReportRatingBar  = (RatingBar) mInflater.inflate(R.layout.rating_bar_indicator_item, 
				(LinearLayout) findViewById(R.id.linearLayoutReport), false);
		mUpdateRatingBar  = (RatingBar) mInflater.inflate(R.layout.rating_bar_indicator_item, 
				(LinearLayout) findViewById(R.id.linearLayoutUpdate), false);
		mReportFeedbacksTextView = (TextView) mInflater.inflate(R.layout.feedbacks_text_view_item, 
				(LinearLayout) findViewById(R.id.linearLayoutReport), false);
		mUpdateFeedbacksTextView = (TextView) mInflater.inflate(R.layout.feedbacks_text_view_item, 
				(LinearLayout) findViewById(R.id.linearLayoutReport), false);;
		/** Starts the report query. */
		Cursor cursor = getContentResolver().query(reportUri, ReportQuery.PROJECTION, null, null, null);
		startManagingCursor(cursor);
		/** If the pointed report is not present in the db then is requested. */
		if (!cursor.moveToFirst()){
			// Never happens. I hope
		}else{
			if (cursor.getInt(ReportQuery.FLAG_COMPLETE)==0){		
				requestReport();
			}else{
				mReportCursor = cursor;
				refreshReportOrUpdateView(REPORT);
			}
		}
	}
	
	@Override
	public void onPause(){
		super.onPause();
		mResultReceiver.clearReceiver();
		mHandler.clearQueryListener();
        mMessageQueueHandler.removeCallbacks(mRefreshRunnable);
        getContentResolver().unregisterContentObserver(mUpdatesChangesObserver);
        getContentResolver().unregisterContentObserver(mReportFeedbacksChangesObserver);
        getContentResolver().unregisterContentObserver(mUpdateFeedbacksChangesObserver);
        /** Deactivates the cursors. */
        if (mReportCursor!=null){
        	mReportCursor.close();
        	mReportCursor = null;
        }
        if (mUpdatesCursor!=null){
        	mUpdatesCursor.close();
        	mUpdatesCursor = null;
        }
        if (mReportFeedbacksCursor!=null){
        	mReportFeedbacksCursor.close();
        	mReportFeedbacksCursor = null;
        }
        if (mUpdateFeedbacksCursor!=null){
        	mUpdateFeedbacksCursor.close();
        	mUpdateFeedbacksCursor = null;
        }
	}
	
	@Override
	public void onResume(){
		super.onResume();
		mResultReceiver.setReceiver(this);
		mHandler.setQueryListener(this);
		/** This query is not asynchronous because before retrieving the updates, we want to know how many Updates are presents in the db*/
		mUpdatesCursor = getContentResolver().query(Uri.withAppendedPath(Update.CONTENT_URI, mReportId)
				, UpdateQuery.PROJECTION,null , null, Update.DEFAULT_SORT_ORDER);
		if (mUpdatesCursor!=null){
			if (mUpdatesCursor.moveToFirst())
				mUpdateId = mUpdatesCursor.getString(UpdateQuery.UPDATE_ID);
			refreshReportOrUpdateView(UPDATE);
			refreshButtons();
		}
		mMessageQueueHandler.post(mRefreshRunnable);
		/** The content observer trigger the refresh*/
		getContentResolver().registerContentObserver(
                Update.CONTENT_URI, false, mUpdatesChangesObserver);
		getContentResolver().registerContentObserver(
				Feedback.buildReportIdUri(null),
					false, mReportFeedbacksChangesObserver);
		getContentResolver().registerContentObserver(
				Feedback.buildUpdateIdUri(null),
					false, mUpdateFeedbacksChangesObserver);

		/** Disable the rating bars. */
		mReportRatingBar.setEnabled(false);
		mUpdateRatingBar.setEnabled(false);
		
		//TODO check if is necessary.updateRefreshStatus(mResultReceiver.ismSyncing());
	}
	
	@Override
	public void onClick(View v) {
		if (v.equals(mPreviousButton) || v.equals(mNextButton)){
			if (mUpdatesCursor != null){
				if (v.equals(mPreviousButton))
					mUpdatesCursor.moveToPrevious();
				else
					mUpdatesCursor.moveToNext();
				mUpdateId = mUpdatesCursor.getString(UpdateQuery.UPDATE_ID);
				requestFeedbacks(UPDATE,mUpdateId);
				refreshButtons();
				refreshReportOrUpdateView(UPDATE);
			}
		}
		
	}
	
    private Runnable mRefreshRunnable = new Runnable() {
        public void run() {
        	ContentResolver contentResolver = getContentResolver();
        	
        	if (mUpdatesCursor!=null){
        		String[] args = new String[] {String.valueOf(System.currentTimeMillis()-TWO_MINUTES)};
            	Cursor cursor  = contentResolver.query(Uri.withAppendedPath(UpdatesRequest.CONTENT_URI, mReportId)
        				, demandQueryProjection, UpdatesRequest.REFRESH_SELECTION, 
        				args,null);
            	startManagingCursor(cursor);
            	if (!cursor.moveToFirst()){
               		/** Insert the update entry. */
            		ContentValues currVal = new ContentValues();
    				currVal.put(UpdatesRequest.REPORT_ID, mReportId);
    				currVal.put(UpdatesRequest.UPDATING, 1);
    				currVal.put(UpdatesRequest.LAST_UPDATE, System.currentTimeMillis());
            		contentResolver.insert(UpdatesRequest.CONTENT_URI, currVal);
                	/** Performs a request to receive the updates. */
                	requestUpdate();
            	}
        	    if (mUpdateId!=null){
        	    	requestFeedbacks(UPDATE,mUpdateId);
        	    }
                if (cursor != null)
                	cursor = null;
        	}else{
        		Log.d(TAG, "Updates cursor not initialized.");
        	}
    	    requestFeedbacks(REPORT,mReportId);
            // The runnable starts again after 5 minutes
            long nextFiveMinutes = SystemClock.uptimeMillis() + FIVE_MINUTES; //TODO Use a setting. 
            mMessageQueueHandler.postAtTime(mRefreshRunnable, nextFiveMinutes);
        }
    };
    
    private void requestReport(){
		Intent intent = new Intent(ReportDetailsActivity.this, MyJamCallService.class);
        intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
        intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.GET_REPORT);
        Bundle bundle = new Bundle();
        bundle.putString(IMyJamCallAttributes.ID, mReportId);
        intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);			
        Log.d(TAG,"Intent sent: "+intent.toString());
        startService(intent);
    }
    
    private void requestUpdate(){
    	if (mUpdatesCursor!=null){
        	int numUpdates = mUpdatesCursor.getCount();
    		Intent intent = new Intent(ReportDetailsActivity.this, MyJamCallService.class);
    	    intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
    	    intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.GET_UPDATES);
    	    Bundle bundle = new Bundle();
    	    bundle.putString(IMyJamCallAttributes.ID, mReportId);
    	    bundle.putInt(IMyJamCallAttributes.NUM, numUpdates);
    	    intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);			
    	    Log.d(TAG,"Intent sent: "+intent.toString());
    	    startService(intent);
    	}
    }
    
    private void requestFeedbacks(int code,String reportOrUpdateId){
    	ContentResolver contentResolver = getContentResolver();
    	
    	String[] args = new String[] {String.valueOf(System.currentTimeMillis()-TWO_MINUTES)};
    	Cursor cursor  = contentResolver.query( 
    			code==REPORT?FeedbacksRequest.buildReportIdUri(reportOrUpdateId):
    				FeedbacksRequest.buildUpdateIdUri(reportOrUpdateId)
				, demandQueryProjection, FeedbacksRequest.REFRESH_SELECTION, 
				args,null);
    	startManagingCursor(cursor);
    	if (!cursor.moveToFirst()){
       		/** Insert the update entry. */
    		ContentValues currVal = new ContentValues();
			currVal.put(code==REPORT?FeedbacksRequest.REPORT_ID:FeedbacksRequest.UPDATE_ID, reportOrUpdateId);
			currVal.put(FeedbacksRequest.UPDATING, true);
			currVal.put(FeedbacksRequest.LAST_UPDATE, System.currentTimeMillis());
    		contentResolver.insert(FeedbacksRequest.CONTENT_URI, currVal);
        	/** Performs a request to receive the updates. */
    		Intent intent = new Intent(ReportDetailsActivity.this, MyJamCallService.class);
            intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
            intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, code==REPORT?RequestCode.GET_REPORT_FEEDBACKS:RequestCode.GET_UPDATE_FEEDBACKS);
            Bundle bundle = new Bundle();
            bundle.putString(IMyJamCallAttributes.ID, reportOrUpdateId);
            intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);			
            Log.d(TAG,"Intent sent: "+intent.toString());
            startService(intent);
    	} else {
    		if (code==REPORT){
    			mReportFeedbacksCursor = getContentResolver().query(Feedback.buildReportIdUri(mReportId),
    					ReportFeedbacksQuery.PROJECTION,null, null,null);
    		}
    		else{
    			mUpdateFeedbacksCursor = getContentResolver().query(Feedback.buildUpdateIdUri(mUpdateId),
    					UpdateFeedbacksQuery.PROJECTION,null, null,null);
    		}
    		refreshRating(code);
    	}
        if (cursor != null)
        	cursor = null;
    }
    
    private ContentObserver mUpdatesChangesObserver = new ContentObserver(new Handler()) {
        @Override
        public void onChange(boolean selfChange) {
            if (mHandler != null) {
            	/** An asynchronous query is launched. */
            	mHandler.startQuery(UpdateQuery._TOKEN, Update.buildReportIdUri(mReportId), UpdateQuery.PROJECTION);
            }
        }
    };
    
    private ContentObserver mReportFeedbacksChangesObserver = new ContentObserver(new Handler()) {
        @Override
        public void onChange(boolean selfChange) {
            if (mHandler != null) {
            	/** An asynchronous query is launched. */
            	mHandler.startQuery(ReportFeedbacksQuery._TOKEN, Feedback.buildReportIdUri(mReportId), ReportFeedbacksQuery.PROJECTION);
            }
        }
    };
    
    private ContentObserver mUpdateFeedbacksChangesObserver = new ContentObserver(new Handler()) {
        @Override
        public void onChange(boolean selfChange) {
            if (mHandler != null) {
            	/** An asynchronous query is launched. */
            	mHandler.startQuery(UpdateFeedbacksQuery._TOKEN, Feedback.buildUpdateIdUri(mUpdateId), UpdateFeedbacksQuery.PROJECTION);
            }
        }
    };
	
	@Override
	public void onReceiveResult(int resultCode, Bundle resultData) {
		boolean mSyncing = false;
		String searchText = null;
		final String[] types = getResources().getStringArray(R.array.type_obj);
		
		int reqCode = resultData.getInt(MyJamCallService.EXTRA_REQUEST_CODE);
		switch (resultCode){
			case MyJamCallService.STATUS_RUNNING:
				searchText = String.format(getResources().getString(R.string.receiving_obj, types[reqCode]));
				Toast.makeText(this, searchText, Toast.LENGTH_SHORT).show();
				Log.d(TAG,searchText);
				mSyncing = true;
				break;
			case MyJamCallService.STATUS_ERROR:
				String errMsg = resultData.getString(Intent.EXTRA_TEXT);
				final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
				Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
				Log.d(TAG,errorText);
				/** break has not been put intentionally, because there are operations that must be executed both in the cases (@link STATUS_ERROR) 
				 *  and (@link STATUS_FINISHED)
				 */
			case MyJamCallService.STATUS_FINISHED:
				mSyncing = false;
				if (reqCode == RequestCode.GET_UPDATES){
               		/** Insert the update entry. */
            		ContentValues currVal = new ContentValues();
    				currVal.put(UpdatesRequest.REPORT_ID, mReportId);
    				currVal.put(UpdatesRequest.UPDATING, 0);	// The Updates are no more under update.
    				currVal.put(UpdatesRequest.LAST_UPDATE, System.currentTimeMillis());
            		getContentResolver().insert(UpdatesRequest.CONTENT_URI, currVal);
				} else if (reqCode == RequestCode.GET_REPORT_FEEDBACKS){
              		/** Insert the update entry. */
            		ContentValues currVal = new ContentValues();
    				currVal.put(FeedbacksRequest.REPORT_ID, mReportId);
    				currVal.put(FeedbacksRequest.UPDATING, 0);	// The Updates are no more under update.
    				currVal.put(FeedbacksRequest.LAST_UPDATE, System.currentTimeMillis());
            		getContentResolver().insert(FeedbacksRequest.CONTENT_URI, currVal);
				} else if (reqCode == RequestCode.GET_UPDATE_FEEDBACKS){
					/** Insert the update entry. */
            		ContentValues currVal = new ContentValues();
    				currVal.put(FeedbacksRequest.UPDATE_ID, mUpdateId);
    				currVal.put(FeedbacksRequest.UPDATING, 0);	// The Updates are no more under update.
    				currVal.put(FeedbacksRequest.LAST_UPDATE, System.currentTimeMillis());
            		getContentResolver().insert(FeedbacksRequest.CONTENT_URI, currVal);
				}
				if  (resultCode == MyJamCallService.STATUS_FINISHED){
					Toast.makeText(this, getResources().getString(R.string.reception_finished),Toast.LENGTH_LONG).show();
					/** Once is received the report never changes, then is not used a content observer, but the query is triggered here.  */
					if (reqCode == RequestCode.GET_REPORT)
						mHandler.startQuery(ReportQuery._TOKEN, Report.buildReportIdUri(mReportId), ReportQuery.PROJECTION);
				}
				break;
		}

		updateRefreshStatus(mSyncing,searchText);
	}
	
	/**
	 * Makes appear and disappear the progress dialog.
	 * @param refreshing 	If true then the dialog appear, 
	 * 						if false the dialog disappear.
	 */
    private void updateRefreshStatus(boolean refreshing,String message) {
        if (refreshing){
        	mDialog = ProgressDialog.show(this, "", 
					message, true);
        }else{
			if (mDialog != null)
				mDialog.dismiss();
        }
        	
    }

	@Override
	protected void onLocServiceConnected() {}

	@Override
	protected void onLocServiceDisconnected() {}

	@Override
	protected void onLocationAvailable() {
		Toast.makeText(ReportDetailsActivity.this, getResources().getString(R.string.location_available), Toast.LENGTH_SHORT).show();
	}

	@Override
	protected void onLocationNoMoreAvailable() {
		Toast.makeText(ReportDetailsActivity.this, getResources().getString(R.string.location_unavailable), Toast.LENGTH_SHORT).show();
	}

	@Override
	public void onQueryComplete(int token, Object cookie, Cursor cursor) {
        switch (token) {
        case ReportQuery._TOKEN:
        	onReportQueryComplete(cursor);
        	break;
        case UpdateQuery._TOKEN:            
        	onUpdateQueryComplete(cursor);
        	break;
        case ReportFeedbacksQuery._TOKEN:
        	onReportFeedbacksQueryComplete(cursor);
        	break;
        case UpdateFeedbacksQuery._TOKEN:
        	onUpdateFeedbacksQueryComplete(cursor);
        	break;        	
        }
        
	}

	private void onReportQueryComplete(Cursor cursor) {
		if (!cursor.moveToFirst()){
			// Never happens. I hope
		}else{
			if (cursor.getInt(ReportQuery.FLAG_COMPLETE)==0){		
				requestReport();
			}else{
				if (mReportCursor!=null)
					mReportCursor.close();
				mReportCursor = cursor;
				refreshReportOrUpdateView(REPORT);
			}
		}
	}
	
	private void onUpdateQueryComplete(Cursor cursor) {
		if (mUpdatesCursor!=null)
			mUpdatesCursor.close();
		mUpdatesCursor = cursor;
		if (mUpdatesCursor!=null){
			if (mUpdatesCursor.moveToFirst()){
				mUpdateId = mUpdatesCursor.getString(UpdateQuery.UPDATE_ID);
				requestFeedbacks(UPDATE,mUpdateId);
			}
			refreshReportOrUpdateView(UPDATE);
			refreshButtons();
		}
	}
	
    private void onReportFeedbacksQueryComplete(Cursor cursor) {
    	if (mReportFeedbacksCursor != null)
			mReportFeedbacksCursor.close();
		if (mReportFeedbacksCursor!=null){
			refreshRating(REPORT);
		}
	}
    
    private void onUpdateFeedbacksQueryComplete(Cursor cursor) {
		if (mUpdateFeedbacksCursor != null)
			mUpdateFeedbacksCursor.close();
    	mUpdateFeedbacksCursor = cursor;
		if (mUpdateFeedbacksCursor!=null){
			refreshRating(UPDATE);
		}
	}
	
	private void refreshButtons(){
		mPreviousButton.setEnabled(true);
		mNextButton.setEnabled(true);
		if (mUpdatesCursor.isLast() || mUpdatesCursor.isAfterLast())
			mNextButton.setEnabled(false);
		if (mUpdatesCursor.isFirst() || mUpdatesCursor.isBeforeFirst())
			mPreviousButton.setEnabled(false);
		int numUpdates = mUpdatesCursor.getCount();
		final String index = String.format(this.getResources().getString(R.string.cursor_position, 
				numUpdates>0?mUpdatesCursor.getPosition() + 1:0 , numUpdates));
		mUpdateCursorIndexTextView.setText(index);
		
	}
	
	private void refreshRating(int code){
		float sum = 0;
		final RatingBar ratingBar = code==REPORT?mReportRatingBar:mUpdateRatingBar;
		final TextView textView = code==REPORT?mReportFeedbacksTextView:mUpdateFeedbacksTextView;
		final Cursor cursor = code==REPORT?mReportFeedbacksCursor:mUpdateFeedbacksCursor;
		final int columnIndex = code==REPORT?ReportFeedbacksQuery.GRADE:UpdateFeedbacksQuery.GRADE;
		final int numFeedbacks;
		
		if (cursor!=null && cursor.moveToFirst()){
			numFeedbacks = cursor.getCount();
			while (!cursor.isAfterLast()){
				sum += cursor.getInt(columnIndex);
				cursor.moveToNext();
			}
			ratingBar.setEnabled(true);
			ratingBar.setRating(sum/numFeedbacks*ratingBar.getMax()/MAX_RATING); //TODO fix this.
			textView.setText(this.getResources().getQuantityString(R.plurals.num_feedbacks, numFeedbacks, numFeedbacks));
		} else {
			ratingBar.setRating(0);
			ratingBar.setEnabled(false);
		}
	}
	
	
	/**
	 * Refreshes the view with the current pointed report or update.
	 * When is called the cursor involved must point a valid item,
	 * or the method does nothing. 
	 * @param code REPORT or UPDATE
	 */
	private void refreshReportOrUpdateView(int code){
		TextView nameTextView;
		TextView valueTextView;
		View view;
		String value;
		
		LayoutInflater mInflater = getLayoutInflater();
		LinearLayout reportLinearLayout = code==REPORT?
				(LinearLayout) findViewById(R.id.linearLayoutReport):(LinearLayout) findViewById(R.id.linearLayoutUpdate);
		Cursor cursor = code==REPORT?mReportCursor:mUpdatesCursor;		
		reportLinearLayout.setOrientation(1);
		if (cursor!=null && !cursor.isBeforeFirst() && !cursor.isAfterLast()){
			reportLinearLayout.removeAllViews();			
			/** Shows the post date. */
			view = mInflater.inflate(R.layout.report_detail_item, reportLinearLayout,false);
			nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
			valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
			nameTextView.setText(getResources().getText(R.string.post_date));
			valueTextView.setText(mUtils.formatDate(cursor.getLong(code==REPORT?ReportQuery.DATE:UpdateQuery.DATE)));
			reportLinearLayout.addView(view);
			
			/** Shows the user. */
			view = mInflater.inflate(R.layout.report_detail_item, reportLinearLayout,false);
			nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
			valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
			nameTextView.setText(getResources().getText(R.string.post_by));
			valueTextView.setText(cursor.getString(code==REPORT?ReportQuery.USER_NAME:UpdateQuery.USER_NAME));
			reportLinearLayout.addView(view);
			
			/** Shows the report type. (if REPORT) */
			if (code == REPORT){
				view = mInflater.inflate(R.layout.report_detail_item, reportLinearLayout,false);
				nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
				valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
				nameTextView.setText(getResources().getText(R.string.report_type));
				valueTextView.setText(mUtils.formatType(cursor.getString(ReportQuery.REPORT_TYPE)));
				reportLinearLayout.addView(view);
			}
			
			/** Shows the traffic flow type if present. */
			if ((value = cursor.getString(code==REPORT?ReportQuery.TRAFFIC_FLOW:UpdateQuery.TRAFFIC_FLOW))!=null){
				view = mInflater.inflate(R.layout.report_detail_item, reportLinearLayout,false);
				nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
				valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
				nameTextView.setText(getResources().getText(R.string.traffic_flow_type));
				valueTextView.setText(mUtils.formatType(value));
				reportLinearLayout.addView(view);
			}
			
			/** Shows the transit flow type if present. */
			if ((value = cursor.getString(code==REPORT?ReportQuery.TRANSIT_TYPE:UpdateQuery.TRANSIT_TYPE))!=null){
				view = mInflater.inflate(R.layout.report_detail_item, reportLinearLayout,false);
				nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
				valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
				nameTextView.setText(getResources().getText(R.string.transit_type));
				valueTextView.setText(mUtils.formatType(value));
				reportLinearLayout.addView(view);
			}
			
			/** Shows the comment. */			
			view  = mInflater.inflate(R.layout.comment_item, reportLinearLayout, false);
			nameTextView = (TextView)view.findViewById(R.id.textViewCommentName);
			valueTextView = (TextView)view.findViewById(R.id.textViewCommentValue);
			nameTextView.setText(getResources().getText(R.string.report_comment));
			valueTextView.setText(cursor.getString(code==REPORT?ReportQuery.COMMENT:UpdateQuery.COMMENT));
			reportLinearLayout.addView(view);
			
			reportLinearLayout.addView(code==REPORT?mReportRatingBar:mUpdateRatingBar);
			reportLinearLayout.addView(code==REPORT?mReportFeedbacksTextView:mUpdateFeedbacksTextView);
		}
	}
	


	/**
     * Parameters used to perform the report query.
     */
    private interface ReportQuery {
        int _TOKEN = 0x1;

        String[] PROJECTION = {
                Report.REPORT_TYPE,
                Report.TRAFFIC_FLOW,
                Report.TRANSIT_TYPE,
                Report.COMMENT,
                Report.USER_NAME,
                Report.LATITUDE,
                Report.LONGITUDE,
                Report.DATE,
                Report.FLAG_COMPLETE
        };
        
        int REPORT_TYPE = 0;
        int TRAFFIC_FLOW = 1;
        int TRANSIT_TYPE = 2;
        int COMMENT = 3;
        int USER_NAME = 4;
        int LATITUDE = 5;
        int LONGITUDE = 6;
        int DATE = 7;
        int FLAG_COMPLETE = 8;
    }

    /**
     * Parameters used to perform the update query.
     */
    private interface UpdateQuery {
        int _TOKEN = 0x2;

        String[] PROJECTION = {
                Update.UPDATE_ID,
                Update.REPORT_ID,
                Update.TRAFFIC_FLOW,
                Update.TRANSIT_TYPE,
                Update.COMMENT,
                Update.USER_NAME,
                Update.USER_ID,
                Update.DATE
        };

        int UPDATE_ID = 0;
        int REPORT_ID = 1;
        int TRAFFIC_FLOW = 2;
        int TRANSIT_TYPE = 3;
        int COMMENT = 4;
        int USER_NAME = 5;
        int USER_ID = 6;
        int DATE = 7;
    }

    /**
     * Parameters used to perform the report feedbacks query.
     */
    private interface ReportFeedbacksQuery {
        int _TOKEN = 0x3;

        String[] PROJECTION = {
                Feedback.GRADE
        };

        int GRADE = 0;
    }
    
    /**
     * Parameters used to perform the report feedbacks query.
     */
    private interface UpdateFeedbacksQuery {
        int _TOKEN = 0x4;

        String[] PROJECTION = {
                Feedback.GRADE
        };

        int GRADE = 0;
    }
}
