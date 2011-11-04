package com.mymed.android.myjam.ui;


import com.google.android.maps.GeoPoint;
import com.mymed.android.myjam.R;
import com.mymed.android.myjam.provider.MyJamContract.Feedback;
import com.mymed.android.myjam.provider.MyJamContract.FeedbacksRequest;
import com.mymed.android.myjam.provider.MyJamContract.UpdatesRequest;

import com.mymed.android.myjam.provider.MyJamContract.Report;

import com.mymed.android.myjam.provider.MyJamContract.Update;

import com.mymed.android.myjam.service.MyJamCallService;
import com.mymed.android.myjam.service.MyJamCallService.RequestCode;
import com.mymed.android.myjam.controller.ICallAttributes;

import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.ContentResolver;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.ContentObserver;
import android.database.Cursor;
import android.location.Location;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MyJamTypes.ReportType;
import com.mymed.utils.GeoUtils;
import com.mymed.utils.GlobalStateAndUtils;
import com.mymed.utils.NotifyingAsyncQueryHandler;
import com.mymed.utils.MyResultReceiver;

/**
 * This activity does the following:
 * - Shows the details of the report chosen on the {@link SearchActivity}.
 * - Shows the updates related to the report.
 * @author iacopo
 *
 */
public class ReportDetailsActivity extends AbstractLocatedActivity implements
NotifyingAsyncQueryHandler.AsyncQueryListener, MyResultReceiver.Receiver, View.OnClickListener{
	private static final String TAG = "ReportDetailsActivity";
	
	/** String used to access the set radius preference */
	private static final String SYNC_RATE_PREFERENCE = "sync_rate_preference";
	
	private static final int REPORT = 0x0;
	private static final int UPDATE = 0x1;
	
	/** Feedbacks values */
	private static final int DENY = 0x0;
	private static final int CONFIRM = 0x1;
	
	private static final int DIALOG_REPORT_UNAVAILABLE_ID = 0x0;
	private static final int DIALOG_LOC_UNAVAILABLE_ID = 0x1;
	private static final int DIALOG_NOT_IN_RANGE_ID = 0x2;
	private static final int DIALOG_FEEDBACK_ALREADY_PRESENT_ID = 0x3;
	
	private static final int DISTANCE_RESOLUTION = 50; //meters
	
	private GlobalStateAndUtils mUtils;
    private Handler mMessageQueueHandler = new Handler();
	
    private Cursor 	mReportCursor,
    				mUpdatesCursor;
    private NotifyingAsyncQueryHandler mHandler;
    private MyResultReceiver mResultReceiver;
	private ProgressDialog mDialog;
	private ImageButton mNextButton;
	private ImageButton mPreviousButton;
	private TextView 	mUpdateIndexTextView;
	private Button 	mInsertButton,
					mMapButton,
					mSyncButton; 
	private View 	mFeedbacksView,
					mButtonsView,
					mAddPositiveFeedback,
					mAddNegativeFeedback;
	
	private int mDistance; //meters
	
	private final String[] demandQueryProjection = new String[]{
		UpdatesRequest.LAST_UPDATE,
		UpdatesRequest.UPDATING
	};
	
	
    private String mReportId; /** The Id of the currently pointed report. */
    private String mUpdateId; /** The Id of the currently pointed update. */
    
	/** Flag used to force a refresh even if the time elapsed wouldn't be sufficient.*/
	private boolean mForceRefreshFlag = false;
	
	private SharedPreferences mSettings;
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.details_view);
		
		mSettings = PreferenceManager.getDefaultSharedPreferences(this);
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
		mMapButton = (Button) findViewById(R.id.buttonViewOnMap);
		mMapButton.setOnClickListener(this);
		mInsertButton = (Button) findViewById(R.id.buttonInsertUpdate);
		mInsertButton.setOnClickListener(this);
		mSyncButton = (Button) findViewById(R.id.buttonSync);
		mSyncButton.setOnClickListener(this);
		mUpdateIndexTextView = (TextView) findViewById(R.id.textViewIndex);
		/** Prepares the feedback views */
		mFeedbacksView  = (View) findViewById(R.id.feedbacks_indicator);
		mAddPositiveFeedback = mFeedbacksView.findViewById(R.id.imageAddPosFeedback);
		mAddPositiveFeedback.setOnClickListener(this);
		mAddNegativeFeedback = mFeedbacksView.findViewById(R.id.imageAddNegFeedback);
		mAddNegativeFeedback.setOnClickListener(this);
		mButtonsView = (View) findViewById(R.id.linearLayoutButtons);
		
		mDistance = -1;
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
	}
	
	@Override
	public void onResume(){
		super.onResume();
		
		mResultReceiver.setReceiver(this);
		mHandler.setQueryListener(this);
		/** Starts the report query. */
		mReportCursor = getContentResolver().query(getIntent().getData(), ReportQuery.PROJECTION, null, null, null);
		/** If the pointed report is not present in the db then is requested. */
		if (mReportCursor.moveToFirst()){
			if (mReportCursor.getInt(ReportQuery.FLAG_COMPLETE)==0)	
				requestReport();
			if (mReportCursor.getString(ReportQuery.REPORT_TYPE).equals(ReportType.FIXED_SPEED_CAM.name()))
				mInsertButton.setEnabled(false);
		}
		/** This query is not asynchronous because before retrieving the updates, we want to know how many Updates are presents in the db*/
		mUpdatesCursor = getContentResolver().query(Update.buildReportIdUri(mReportId)
				, UpdateQuery.PROJECTION,null , null, Update.DEFAULT_SORT_ORDER);
		if (mUpdatesCursor!=null){
			if (mUpdatesCursor.moveToFirst())
				mUpdateId = mUpdatesCursor.getString(UpdateQuery.UPDATE_ID);
			refreshButtons();
		}
		/** Update the details informations. */
		refreshDetailsView();
		mMessageQueueHandler.post(mRefreshRunnable);
		/** The content observer triggers the refresh*/
		getContentResolver().registerContentObserver(
                Update.CONTENT_URI, false, mUpdatesChangesObserver);
		getContentResolver().registerContentObserver(
				Feedback.buildReportIdUri(null),
					false, mReportFeedbacksChangesObserver);
		getContentResolver().registerContentObserver(
				Feedback.buildUpdateIdUri(null),
					false, mUpdateFeedbacksChangesObserver);
		
		if (!mResultReceiver.ismSyncing())
			updateRefreshStatus(false,null);
	}
	
	@Override
	public void onClick(View v) {
		/** Handles the buttons to navigate the updates. */
		if (v.equals(mPreviousButton) || v.equals(mNextButton)){
			if (mUpdatesCursor != null){
				if (v.equals(mPreviousButton))
					mUpdatesCursor.moveToPrevious();
				else
					mUpdatesCursor.moveToNext();
				try{
					mUpdateId = mUpdatesCursor.getString(UpdateQuery.UPDATE_ID);
					requestFeedbacks(UPDATE,mUpdateId,false);
				}catch(Exception e){
					mUpdateId = null;
					requestFeedbacks(REPORT,mReportId,false);
				}
				refreshButtons();
				refreshDetailsView();
			}
		}else if (v.equals(mMapButton)){
        	if (mReportId != null){
		        Intent intent = new Intent(ReportDetailsActivity.this,ViewOnMapActivity.class);
		        Uri uri = Report.buildReportIdUri(mReportId);
		        intent.setData(uri);   	
		        startActivity(intent);
        	}else
        		Log.e(TAG, "mReportId not set or checkInsert failed");
		}else if (v.equals(mInsertButton)){
        	if (mReportId != null &&  checkInsert()){
            	Intent intent = new Intent(this,InsertActivity.class);
            	intent.putExtra(InsertActivity.EXTRA_INSERT_TYPE, InsertActivity.UPDATE);
            	intent.setData(Report.buildReportIdUri(mReportId));
            	startActivity(intent);
        	}else
        		Log.e(TAG, "mReportId not set or checkInsert failed");
		}else if(v.equals(mSyncButton)){
			mForceRefreshFlag = true;
			mMessageQueueHandler.removeCallbacks(mRefreshRunnable);
			mMessageQueueHandler.post(mRefreshRunnable);
		}else if(v.equals(mAddPositiveFeedback) && checkFeedbackInsert() && checkInsert())
			//TODO
			insertFeedback(mReportId,mUpdateId,CONFIRM);
		else if (v.equals(mAddNegativeFeedback) && checkFeedbackInsert() && checkInsert())
			//TODO
			insertFeedback(mReportId,mUpdateId,DENY);
	}
	
    private Runnable mRefreshRunnable = new Runnable() {
        public void run() {
        	requestUpdates(mForceRefreshFlag);
    	    if (mUpdateId!=null)
    	    	requestFeedbacks(UPDATE,mUpdateId,mForceRefreshFlag);
    	    else
    	    	requestFeedbacks(REPORT,mReportId,mForceRefreshFlag);
    	    mForceRefreshFlag = false;
            mMessageQueueHandler.postDelayed(mRefreshRunnable, Integer.parseInt(mSettings.getString(SYNC_RATE_PREFERENCE,"120000")) + 10000); //10 seconds  are added to take into account the time needed to MyJamCallService to start the request.
        }
    };
    
    private Runnable mRefreshDistance = new Runnable() {
		@Override
		public void run() {
			mDistance = refreshDistance();
			mMessageQueueHandler.postDelayed(mRefreshDistance, 2000);
		}
    };
    
    /**
     * Sends an intent to {@link MyJamCallService} to receive a report.
     */
    private void requestReport(){
		Intent intent = new Intent(ReportDetailsActivity.this, MyJamCallService.class);
        intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
        intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.GET_REPORT);
        Bundle bundle = new Bundle();
        bundle.putString(ICallAttributes.REPORT_ID, mReportId);
        intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);			
        Log.d(TAG,"Intent sent: "+intent.toString());
        startService(intent);
    }
    
    /**
     * 
     * @param forceRefresh
     */
    private void requestUpdates(boolean forceRefresh){
    	ContentResolver contentResolver = getContentResolver();
    	
    	if (mUpdatesCursor!=null){
    		int syncRate = Integer.parseInt(mSettings.getString(SYNC_RATE_PREFERENCE,"120000"));
    		String[] args = new String[] {String.valueOf(System.currentTimeMillis()-syncRate)};
        	Cursor cursor  = contentResolver.query(Uri.withAppendedPath(UpdatesRequest.CONTENT_URI, mReportId)
    				, demandQueryProjection, UpdatesRequest.REFRESH_SELECTION, 
    				args,null);
        	startManagingCursor(cursor);
        	if (!cursor.moveToFirst() || forceRefresh){
            	/** Performs a request to receive the updates. */
            	int numUpdates = mUpdatesCursor.getCount();
        		Intent intent = new Intent(ReportDetailsActivity.this, MyJamCallService.class);
        	    intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
        	    intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.GET_UPDATES);
        	    Bundle bundle = new Bundle();
        	    bundle.putString(ICallAttributes.REPORT_ID, mReportId);
        	    bundle.putInt(ICallAttributes.NUM, numUpdates);
        	    intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);			
        	    Log.d(TAG,"Intent sent: "+intent.toString());
        	    startService(intent);
        	}
    	}else{
    		Log.d(TAG, "Updates cursor not initialized.");
    	}
    }
    
    /**
     * Dispatch a request to synchronize the feedbacks either of the report, or one of its updates to the {@link CallService}. 
     * @param code	To distinguish between reports and feedbacks.
     * @param reportOrUpdateId The identifier either of the report or update.
     * @param forceRefresh Force the request even if the elapsed time is not sufficient.
     */
    private void requestFeedbacks(int code ,String reportOrUpdateId ,boolean forceRefresh){
    	ContentResolver contentResolver = getContentResolver();
    	
    	int syncRate = Integer.parseInt(mSettings.getString(SYNC_RATE_PREFERENCE,"120000"));
    	String[] args = new String[] {String.valueOf(System.currentTimeMillis()-syncRate)};
    	Cursor cursor  = contentResolver.query( 
    			code==REPORT?FeedbacksRequest.buildReportIdUri(reportOrUpdateId):
    				FeedbacksRequest.buildUpdateIdUri(reportOrUpdateId)
				, demandQueryProjection, FeedbacksRequest.REFRESH_SELECTION, 
				args,null);
    	if (!cursor.moveToFirst() || forceRefresh){
        	/** Performs a request to receive the updates. */
    		Intent intent = new Intent(ReportDetailsActivity.this, MyJamCallService.class);
            intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
            intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, code==REPORT?RequestCode.GET_REPORT_FEEDBACKS:RequestCode.GET_UPDATE_FEEDBACKS);
            Bundle bundle = new Bundle();
            bundle.putString(ICallAttributes.REPORT_ID, reportOrUpdateId);
            intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);			
            Log.d(TAG,"Intent sent: "+intent.toString());
            startService(intent);
    	}else
    		refreshFeedbacks(code);
    	cursor.close();
    }
    
    private ContentObserver mUpdatesChangesObserver = new ContentObserver(new Handler()) {
        @Override
        public void onChange(boolean selfChange) {
            if (mHandler != null) {
            	/** An asynchronous query is launched. */
            	mHandler.startQuery(UpdateQuery._TOKEN, null, Update.buildReportIdUri(mReportId), 
            			UpdateQuery.PROJECTION, null, null, Update.DEFAULT_SORT_ORDER);
            }
        }
    };
    
    private ContentObserver mReportFeedbacksChangesObserver = new ContentObserver(new Handler()) {
        @Override
        public void onChange(boolean selfChange) {
            	refreshFeedbacks(REPORT);
        }
    };
    
    private ContentObserver mUpdateFeedbacksChangesObserver = new ContentObserver(new Handler()) {
        @Override
        public void onChange(boolean selfChange) {
            	refreshFeedbacks(UPDATE);
        }
    };
	
	@Override
	public void onReceiveResult(int resultCode, Bundle resultData) {
		boolean mSyncing = false;
		String dialogText = null;
		final String[] types = getResources().getStringArray(R.array.type_obj);
		
		int reqCode = resultData.getInt(MyJamCallService.EXTRA_REQUEST_CODE);
		switch (resultCode){
			case MyJamCallService.STATUS_RUNNING:
				/*
				 * The Strings on the array "types" are on the same order of the request code corresponding request codes, 
				 * index 0 corresponds to RequestCode.SEARCH_REPORTS.
				 */
				switch (reqCode){
					case (RequestCode.GET_REPORT):
						dialogText = String.format(getResources().getString(R.string.sync_msg, types[1]));
						break;
					case (RequestCode.GET_UPDATES):
						dialogText = String.format(getResources().getString(R.string.sync_msg, types[2]));
						break;
					case (RequestCode.GET_REPORT_FEEDBACKS):
						dialogText = String.format(getResources().getString(R.string.sync_msg, types[3]));
						break;
					case (RequestCode.GET_UPDATE_FEEDBACKS):
						dialogText = String.format(getResources().getString(R.string.sync_msg, types[4]));
						break;
					case (RequestCode.SEARCH_REPORTS):
						dialogText = getResources().getString(R.string.searching_reports_msg);
						break;
					case (RequestCode.INSERT_REPORT_FEEDBACK):
					case (RequestCode.INSERT_UPDATE_FEEDBACK):
						dialogText = getResources().getString(R.string.inserting_feedback); //TODO Change string.
						
				}
				//Toast.makeText(this, searchText, Toast.LENGTH_SHORT).show();
				Log.d(TAG,dialogText);
				mSyncing = true;
				break;
			case MyJamCallService.STATUS_ERROR:
				String errMsg = resultData.getString(Intent.EXTRA_TEXT);
				final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
				Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
				Log.d(TAG,errorText);
				/** break has not been put intentionally, because there are operations that must be executed both in the cases (STATUS_ERROR 
				 *  and STATUS_FINISHED)
				 */
			case MyJamCallService.STATUS_FINISHED:
				mSyncing = false;
				if  (resultCode == MyJamCallService.STATUS_FINISHED){
					//Toast.makeText(this, getResources().getString(R.string.reception_finished),Toast.LENGTH_LONG).show();
					/** Once is received the report never changes, then is not used a content observer, but the query is triggered here.  */
					if (reqCode == RequestCode.GET_REPORT)
						mHandler.startQuery(ReportQuery._TOKEN, Report.buildReportIdUri(mReportId), ReportQuery.PROJECTION);
				}
				break;
		}
		updateRefreshStatus(mSyncing,dialogText);
	}
	
	/**
	 * Makes appear and disappear the progress dialog.
	 * @param refreshing 	If true then the dialog appear, 
	 * 						if false the dialog disappear.
	 */
    private void updateRefreshStatus(boolean refreshing,String message) {
        if (refreshing){
        	if (message==null)
        		message = String.format(getResources().getString(R.string.sync_msg, ""));
        	mDialog = ProgressDialog.show(this, "", 
					message, true);
         	mDialog.setOnKeyListener(new DialogInterface.OnKeyListener() {
				@Override
				public boolean onKey(DialogInterface dialog, int keyCode, KeyEvent event) {
					if (keyCode == KeyEvent.KEYCODE_SEARCH && event.getRepeatCount() == 0) {
						return true; // Pretend we processed it
					}
					return false; // Any other keys are still processed as normal
				}
         	});
        }else{
			if (mDialog != null)
				mDialog.dismiss();
        }
        	
    }

	@Override
	protected void onLocServiceConnected() {
		mMessageQueueHandler.postDelayed(mRefreshDistance, 5000); //Refresh the distance every 5 seconds.
	}

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
				refreshDetailsView();
			}
		}
	}
	
	/**
	 * Method called when Update query completes.
	 * @param cursor Cursor pointing the results of the query.
	 */
	private void onUpdateQueryComplete(Cursor cursor) {
		if (mUpdatesCursor!=null)
			mUpdatesCursor.close();
		mUpdatesCursor = cursor;
		if (mUpdatesCursor!=null){
			if (mUpdatesCursor.moveToFirst()){
				mUpdateId = mUpdatesCursor.getString(UpdateQuery.UPDATE_ID);
				requestFeedbacks(UPDATE,mUpdateId,false);
				refreshDetailsView();
			}else 
				mUpdateId = null;			
			refreshButtons();
		}
	}
	
    /**
     * Refresh the status of the buttons to scroll updates.
     */
	private void refreshButtons(){
		boolean originalReport = false;
		
		int numUpdates = mUpdatesCursor.getCount();
		if (numUpdates>0)
			mButtonsView.setVisibility(View.VISIBLE);
		else
			mButtonsView.setVisibility(View.GONE);
		mPreviousButton.setEnabled(true);
		mNextButton.setEnabled(true);
		if (mUpdatesCursor.isAfterLast()){
			mNextButton.setEnabled(false);
			originalReport = true;
		}
		if (mUpdatesCursor.isFirst() || mUpdatesCursor.isBeforeFirst())
			mPreviousButton.setEnabled(false);
		String index = originalReport?getResources().getString(R.string.original_report):
			String.format(this.getResources().getString(R.string.cursor_position, 
				numUpdates- mUpdatesCursor.getPosition() , numUpdates));
		mUpdateIndexTextView.setText(index);
	}
	
	/**
	 * Refreshes the rating bar and the text view specifying the number of feedbacks.
	 * @param code
	 */
	private void refreshFeedbacks(int code){
		final String id = code==REPORT?mReportId:mUpdateId;
		if (id == null){
			Log.e(TAG, "refreshFeedbacks: null id.");
			return;
		}
		final Cursor cursor = getContentResolver().query(code==REPORT?Feedback.buildReportIdUri(mReportId):
			Feedback.buildUpdateIdUri(mUpdateId), null, null, null, null);
		
		int positiveFeedbacks=0,
			negativeFeedbacks=0,
			val;
		
		if (cursor!=null && cursor.moveToFirst()){
			while (!cursor.isAfterLast()){
				if ((val=cursor.getInt(Feedback.VALUE_INDEX)) == 0)
					negativeFeedbacks = cursor.getInt(Feedback.COUNT_INDEX);
				else if (val == 1)
					positiveFeedbacks = cursor.getInt(Feedback.COUNT_INDEX);
				else {
					Log.e(TAG, "refreshFeedbacks: wrong value");
					return;
				}
				cursor.moveToNext();
			}
		}
		TextView posFeedbacks = (TextView) mFeedbacksView.findViewById(R.id.textViewPositiveFeedbacks);
		posFeedbacks.setText(String.valueOf(positiveFeedbacks));
		TextView negFeedbacks = (TextView) mFeedbacksView.findViewById(R.id.textViewNegativeFeedbacks);
		negFeedbacks.setText(String.valueOf(negativeFeedbacks));
		cursor.close();
		mFeedbacksView.setVisibility(View.VISIBLE);
	}
	
	
	/**
	 * Refreshes the view with the current pointed report or update.
	 * When is called the cursors must point valid items,
	 * or the method does nothing. 
	 */
	private void refreshDetailsView(){
		TextView nameTextView;
		TextView valueTextView;
		View view;
		String value;

		if (mReportCursor!=null && !mReportCursor.isBeforeFirst() && !mReportCursor.isAfterLast()){
			/** Shows the report type. (if REPORT) */
			view = findViewById(R.id.linearLayoutType);
			ImageView iconView = (ImageView) view.findViewById(R.id.typeIcon);
			valueTextView = (TextView) view.findViewById(R.id.textViewType);
			String type = mReportCursor.getString(ReportQuery.REPORT_TYPE);
			iconView.setImageResource(mUtils.getIconByReportType(type));
			valueTextView.setText(mUtils.formatType(type));
			view.setVisibility(View.VISIBLE);
						
			/** Shows the post date. */
			view = findViewById(R.id.posted_detail);
			nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
			valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
			nameTextView.setText(getResources().getText(R.string.post_date));
			valueTextView.setText(mUtils.formatDate(mReportCursor.getLong(ReportQuery.DATE)));
			view.setVisibility(View.VISIBLE);
			
			/** Shows the user. */
			view = findViewById(R.id.posted_by_detail);
			nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
			valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
			nameTextView.setText(getResources().getText(R.string.post_by));
			valueTextView.setText(mReportCursor.getString(ReportQuery.USER_NAME));
			view.setVisibility(View.VISIBLE);
			
			/** Shows the traffic flow type if present. */
			view = findViewById(R.id.traffic_flow_detail);
			if ((value = mReportCursor.getString(ReportQuery.TRAFFIC_FLOW))!=null){
				nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
				valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
				nameTextView.setText(getResources().getText(R.string.traffic_flow_type));
				valueTextView.setTextColor(getResources().getColor(R.color.black));
				valueTextView.setText(mUtils.formatType(value));
				view.setVisibility(View.VISIBLE);
			}else
				view.setVisibility(View.GONE);			
			
			/** Shows the comment. */
			view = findViewById(R.id.comment_detail);
			if ((value = mReportCursor.getString(ReportQuery.COMMENT))!=null && value.trim().length() > 0){
				nameTextView = (TextView)view.findViewById(R.id.textViewCommentName);
				valueTextView = (TextView)view.findViewById(R.id.textViewCommentValue);
				nameTextView.setText(getResources().getText(R.string.report_comment));
				value = mReportCursor.getString(ReportQuery.COMMENT);
				valueTextView.setText(value);
				valueTextView.setTextColor(getResources().getColor(R.color.black));
				view.setVisibility(View.VISIBLE);
			}else
				view.setVisibility(View.GONE);			
		}
		if (mUpdatesCursor!=null && !mUpdatesCursor.isBeforeFirst() && !mUpdatesCursor.isAfterLast()){
			/** Shows the post date. */
			view = findViewById(R.id.updated_detail);
			nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
			valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
			nameTextView.setText(getResources().getText(R.string.update_date));
			valueTextView.setTextColor(getResources().getColor(R.color.blue));
			valueTextView.setText(mUtils.formatDate(mUpdatesCursor.getLong(UpdateQuery.DATE)));
			view.setVisibility(View.VISIBLE);
			
			/** Shows the user. */
			view = findViewById(R.id.updated_by_detail);
			nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
			valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
			nameTextView.setText(getResources().getText(R.string.update_by));
			valueTextView.setText(mUpdatesCursor.getString(UpdateQuery.USER_NAME));
			valueTextView.setTextColor(getResources().getColor(R.color.blue));
			view.setVisibility(View.VISIBLE);
			
			/** Shows the traffic flow type if present. */
			view = findViewById(R.id.traffic_flow_detail);
			if ((value = mUpdatesCursor.getString(UpdateQuery.TRAFFIC_FLOW))!=null){
				nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
				valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
				nameTextView.setText(getResources().getText(R.string.traffic_flow_type));
				valueTextView.setText(mUtils.formatType(value));
				valueTextView.setTextColor(getResources().getColor(R.color.blue));
				view.setVisibility(View.VISIBLE);
			}else
				view.setVisibility(View.GONE);			
			
			/** Shows the comment. */
			view = findViewById(R.id.comment_detail);
			if ((value = mUpdatesCursor.getString(UpdateQuery.COMMENT))!=null && value.trim().length() > 0){
				nameTextView = (TextView)view.findViewById(R.id.textViewCommentName);
				valueTextView = (TextView)view.findViewById(R.id.textViewCommentValue);
				nameTextView.setText(getResources().getText(R.string.report_comment));
				value = mUpdatesCursor.getString(UpdateQuery.COMMENT);
				valueTextView.setText(value);
				valueTextView.setTextColor(getResources().getColor(R.color.blue));
				view.setVisibility(View.VISIBLE);
			}else
				view.setVisibility(View.GONE);			
		}else{
			view = findViewById(R.id.updated_by_detail);
			view.setVisibility(View.GONE);
			view = findViewById(R.id.updated_detail);
			view.setVisibility(View.GONE);
		}

	}
	
	/**
	 * Refresh the distance between the user and the position of the report.
	 */
	private int refreshDistance(){
		/** Shows the post date. */
		int dist;
		
		if (mReportCursor!=null && !mReportCursor.isBeforeFirst() && !mReportCursor.isAfterLast()){
			View view = findViewById(R.id.distance_detail);
			TextView nameTextView = (TextView) view.findViewById(R.id.textViewDetailName);
			TextView valueTextView = (TextView) view.findViewById(R.id.textViewDetailValue);
			nameTextView.setText(getResources().getText(R.string.distance_label));
			
			Location currLoc;
			dist = (mService !=null && (currLoc = mService.getCurrentLocation())!=null)?(int) GeoUtils.getGCDistance(GeoUtils.toGeoPoint(currLoc),
					new GeoPoint(mReportCursor.getInt(ReportQuery.LATITUDE),mReportCursor.getInt(ReportQuery.LONGITUDE))):-1;
			// If the user location is available shows the distance, if not shows '-'.
			valueTextView.setText(dist==-1? "-":
				mUtils.formatDistance(dist,50));
			view.setVisibility(View.VISIBLE);
			return dist;
		}
		return -1;
	}
	
	@Override
	/**
	 * Inflate the menu,from the XML description.
	 */
	public boolean onCreateOptionsMenu(Menu menu) {
		super.onCreateOptionsMenu(menu);

        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.preference_menu, menu);		
		return true;
	}	
	
	@Override
    public boolean onOptionsItemSelected(MenuItem item) {
		switch (item.getItemId()) {
        case R.id.preferences:
        	startActivity(new Intent(this,MyPreferenceActivity.class));
        	break;
        }
        return false;
    }
	
	/**
	 * Checks if a new update or feedback can be inserted with the following rules:
	 * - The data of the report must be available (in particular the location).
	 * - The current user location must be available.
	 * - The report must be within {@link MAX_INSERTION_DISTANCE} 
	 * @return
	 */
	private boolean checkInsert(){
		if (mReportCursor==null || !mReportCursor.moveToFirst() || mReportCursor.isClosed()){
			showDialog(DIALOG_REPORT_UNAVAILABLE_ID);
			return false;
		}
		// If distance is not available.
		if (mDistance == -1){
			showDialog(DIALOG_LOC_UNAVAILABLE_ID);
			return false;
		}
		// If distance is greater then treshold.
		int distance =  (int) (mDistance / DISTANCE_RESOLUTION) * DISTANCE_RESOLUTION;
		if (distance > GlobalStateAndUtils.MAX_INSERTION_DISTANCE){
			showDialog(DIALOG_NOT_IN_RANGE_ID);
			return false;
		}
		return true;
	}
	
	/**
	 * Checks if the user has already inserted a feedback on the report/update
	 * visualized.
	 * @return {@value false} if the logged user has already put his feedback on the currently shown report/update.
	 * @return {@value true} if the logged user has not already put his feedback on the currently shown report/update.
	 */
	private boolean checkFeedbackInsert(){

	Cursor cursor = getContentResolver().query(mUpdateId==null?Feedback.buildReportIdUri(mReportId):
		Feedback.buildUpdateIdUri(mUpdateId), 
					null, Feedback.USER_ID_SELECTION, new String[]{mUtils.getUserId()}, null);
	if (cursor.moveToFirst()){
		showDialog(DIALOG_FEEDBACK_ALREADY_PRESENT_ID);
		return false;
	}else
		return true;
	}
	
	/**
	 * Creates the dialog to display.
	 */
	@Override
    protected Dialog onCreateDialog(int id) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        
        builder.setCancelable(false) 
		.setTitle(R.string.dialog_title)
		.setIcon(R.drawable.myjam_icon)
        .setPositiveButton(getResources().getString(R.string.positive_button_label), new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog, int id) {
				dialog.cancel();
			};
        });
        switch(id) {
        case DIALOG_REPORT_UNAVAILABLE_ID:
    		builder.setMessage(R.string.dialog_report_unavailable_text);            
    		break;
        case DIALOG_LOC_UNAVAILABLE_ID:
    		builder.setMessage(R.string.dialog_location_unavailable_text);
            break;
        case DIALOG_NOT_IN_RANGE_ID:
        	builder.setMessage(R.string.dialog_not_in_range_text);        		
        	break;
        case DIALOG_FEEDBACK_ALREADY_PRESENT_ID:
        	builder.setMessage(R.string.dialog_feedback_already_present_text);
        	break;
        default:
            return null;
        }
        return builder.create();
    }
	
	/**
	 * TODO Not used anymore.
	 * @param code
	 * @return
	 */
//	private Dialog createFeedbackDialog(final int code){
//		final Dialog dialog = new Dialog(ReportDetailsActivity.this);
//		dialog.requestWindowFeature(Window.FEATURE_LEFT_ICON);
//		dialog.setContentView(R.layout.confirm_deny_dialog);
//        dialog.setFeatureDrawableResource(Window.FEATURE_LEFT_ICON, R.drawable.myjam_icon);
//        dialog.setTitle(R.string.dialog_title);
//        ((TextView) dialog.findViewById(R.id.textViewInsertFeedback)).setText(code==REPORT?
//        		R.string.dialog_insert_report_feedback_text:R.string.dialog_insert_update_feedback_text);
//        dialog.setCancelable(true);
//        dialog.setOnShowListener(new OnShowListener(){
//
//			@Override
//			public void onShow(final DialogInterface dialog) {
//				final String reportId = mReportId;
//				final String updateId = mUpdateId;
//				if (reportId == null){
//					Log.e(TAG, "createFeedbackDialog: reportId not set.");
//					dialog.dismiss();
//				}
//				if (code == UPDATE && updateId == null){
//					Log.e(TAG, "createFeedbackDialog: updateId not set.");
//					dialog.dismiss();
//				}
//		    	((Dialog) dialog).findViewById(R.id.buttonConfirm).setOnClickListener(new OnClickListener(){
//					@Override
//					public void onClick(View v) {
//						insertFeedback(code,reportId,updateId,CONFIRM);
//						dialog.dismiss();			
//					}
//		    	});
//		    	((Dialog) dialog).findViewById(R.id.buttonDeny).setOnClickListener(new OnClickListener(){
//					@Override
//					public void onClick(View v) {
//						insertFeedback(code,reportId,updateId,DENY);
//						dialog.dismiss();
//					}
//		    	});
//				
//			}
//			
//		});
//    	return dialog;
//	}
	
	/**
	 * Prepares the feedback to be inserted.
	 * @param code	REPORT or UPDATE
	 * @param id	reportId or updateId
	 * @param value CONFIRM or DENY
	 */
	private void insertFeedback(String reportId,String updateId,int value){
		try{					
			MFeedBackBean feedback = new MFeedBackBean();
			feedback.setUserId(GlobalStateAndUtils.getInstance(getApplicationContext()).getUserId());
			feedback.setValue(value);
			Bundle bundle = new Bundle();
			bundle.putString(ICallAttributes.REPORT_ID, reportId);
			bundle.putString(ICallAttributes.UPDATE_ID, updateId);
			requestInsertFeedback(updateId == null?REPORT:UPDATE,bundle,feedback);
		} catch (Exception e){
			Toast.makeText(ReportDetailsActivity.this, "Wrong parameters.",Toast.LENGTH_LONG).show();
		}
	}
	
	/**
	 * Performs the request to {@link MyJamCallService}
	 * @param code		REPORT or UPDATE
	 * @param bundle	{@link Bundle} containing the parameters.
	 * @param feedback	{@link MFeedbackBeen} to be inserted.
	 */
	private void requestInsertFeedback(int code,Bundle bundle,
			MFeedBackBean feedback) {
		final Intent intent = new Intent(ReportDetailsActivity.this, MyJamCallService.class);
		intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
		intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, code==REPORT?RequestCode.INSERT_REPORT_FEEDBACK:
			RequestCode.INSERT_UPDATE_FEEDBACK);
		intent.putExtra(MyJamCallService.EXTRA_OBJECT, feedback);
		intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);
		Log.d(TAG,"Intent sent: "+intent.toString());
		startService(intent);	
	}

	/**
     * Parameters used to perform the report query.
     */
    private interface ReportQuery {
        int _TOKEN = 0x1;

        String[] PROJECTION = {
                Report.REPORT_TYPE,
                Report.TRAFFIC_FLOW,
                Report.COMMENT,
                Report.USER_NAME,
                Report.DATE,
                Report.FLAG_COMPLETE,
                Report.LATITUDE,
                Report.LONGITUDE
        };
        
        int REPORT_TYPE = 0;
        int TRAFFIC_FLOW = 1;
        int COMMENT = 2;
        int USER_NAME = 3;
        int DATE = 4;
        int FLAG_COMPLETE = 5;
        int LATITUDE = 6;
        int LONGITUDE = 7;
    }

    /**
     * Parameters used to perform the update query.
     */
    private interface UpdateQuery {
        int _TOKEN = 0x2;

        String[] PROJECTION = {
                Update.UPDATE_ID,
                Update.TRAFFIC_FLOW,
                Update.COMMENT,
                Update.USER_NAME,
                Update.DATE
        };

        int UPDATE_ID = 0;
        int TRAFFIC_FLOW = 1;
        int COMMENT = 2;
        int USER_NAME = 3;
        int DATE = 4;
    }
}
