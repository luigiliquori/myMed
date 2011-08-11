package com.mymed.android.myjam.ui;


import com.mymed.android.myjam.R;
import com.mymed.android.myjam.provider.MyJamContract.Feedback;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.ReportsSearch;
import com.mymed.android.myjam.provider.MyJamContract.Update;
import com.mymed.android.myjam.service.RestCallService;
import com.mymed.android.myjam.service.RestCallService.RequestCode;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.mymed.utils.NotifyingAsyncQueryHandler;
import com.mymed.utils.MyResultReceiver;


public class ReportDetailsActivity extends AbstractLocatedActivity implements
NotifyingAsyncQueryHandler.AsyncQueryListener, MyResultReceiver.Receiver{
	private static final String TAG = "ReportDetailsActivity";
	
    private Cursor 	mReportCursor,
    				mReportFeedbacksCursor;
    private NotifyingAsyncQueryHandler mHandler;
    private MyResultReceiver mResultReceiver;
	private ProgressDialog mDialog;
	
    private String reportId;
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.details_view);
		
		mResultReceiver = new MyResultReceiver(new Handler());
		mHandler = new NotifyingAsyncQueryHandler(ReportDetailsActivity.this
				.getContentResolver(), this);
		Intent intent = getIntent();
		Uri reportUri = null;
		if ((reportUri = intent.getData())==null)
			finish();
		mHandler.startQuery(ReportQuery._TOKEN, reportUri, ReportQuery.PROJECTION);
		reportId = reportUri.getPathSegments().get(1);
	}
	
	@Override
	public void onPause(){
		super.onPause();
		mResultReceiver.clearReceiver();
		mHandler.clearQueryListener();
	}
	
	@Override
	public void onResume(){
		super.onResume();
		mResultReceiver.setReceiver(this);
		mHandler.setQueryListener(this);
		
	}
	
	@Override
	public void onReceiveResult(int resultCode, Bundle resultData) {
		boolean mSyncing = false;
		String searchText = null;
		final String[] types = getResources().getStringArray(R.array.type_obj);
		
		int reqId = resultData.getInt(RestCallService.EXTRA_REQUEST_ID);
		switch (resultCode){
			case RestCallService.STATUS_RUNNING:
				searchText = String.format(getResources().getString(R.string.receiving_obj, types[reqId]));
				mSyncing = true;
				break;
			case RestCallService.STATUS_FINISHED:
				mSyncing = false;
				Toast.makeText(this, getResources().getString(R.string.reception_finished),Toast.LENGTH_LONG).show();
				mHandler.startQuery(ReportQuery._TOKEN, Uri.withAppendedPath(Report.CONTENT_URI, reportId), ReportQuery.PROJECTION);
				break;
			case RestCallService.STATUS_ERROR:
				mSyncing = false;
				String errMsg = resultData.getString(Intent.EXTRA_TEXT);
				final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
				Toast.makeText(this, errorText, Toast.LENGTH_LONG).show();
				Log.d(TAG,errorText);
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
		Toast.makeText(ReportDetailsActivity.this, getResources().getString(R.string.location_available), Toast.LENGTH_LONG).show();
	}

	@Override
	protected void onLocationNoMoreAvailable() {
		Toast.makeText(ReportDetailsActivity.this, getResources().getString(R.string.location_unavailable), Toast.LENGTH_LONG).show();
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
        	onFeedbacksQueryComplete(cursor);
        	break;
        case UpdateFeedbacksQuery._TOKEN:
        	onFeedbacksQueryComplete(cursor);
        	break;
        	
        }
        
	}
	
    private void onFeedbacksQueryComplete(Cursor cursor) {
		// TODO Auto-generated method stub
		
	}

	private void onUpdateQueryComplete(Cursor cursor) {
		// TODO Auto-generated method stub
		
	}

	private void onReportQueryComplete(Cursor cursor) {
		if (cursor == null || !cursor.moveToFirst()){		
			Intent intent = new Intent(ReportDetailsActivity.this, RestCallService.class);
	        intent.putExtra(RestCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
	        intent.putExtra(RestCallService.EXTRA_REQUEST_CODE, RequestCode.GET_REPORT);
	        Bundle bundle = new Bundle();
	        bundle.putString(com.mymed.android.myjam.controller.MyJamCallManager.MyJamCallAttributes.ID, reportId);
	        intent.putExtra(RestCallService.EXTRA_ATTRIBUTES, bundle);			
	        Log.d(TAG,"Intent sent: "+intent.toString());
	        startService(intent);
		}else{
			mReportCursor = cursor;
			updateReportView();
		}
		
	}
	
	private void updateReportView(){
		TextView name;
		TextView value;
		View view;
		
		LayoutInflater mInflater = getLayoutInflater();
		LinearLayout reportLinearLayout = (LinearLayout) findViewById(R.id.linearLayoutReport);
		reportLinearLayout.setOrientation(1);
		if (mReportCursor!=null){
			view = mInflater.inflate(R.layout.report_detail_item, reportLinearLayout,false);
			name = (TextView) view.findViewById(R.id.textViewDetailName);
			value = (TextView) view.findViewById(R.id.textViewDetailValue);
			name.setText(getResources().getText(R.string.report_type));
			value.setText(mReportCursor.getString(ReportQuery.REPORT_TYPE));
			reportLinearLayout.addView(view);
			view  = mInflater.inflate(R.layout.report_detail_item, reportLinearLayout, false);
			name = (TextView)view.findViewById(R.id.textViewDetailName);
			value = (TextView)view.findViewById(R.id.textViewDetailValue);
			name.setText(getResources().getText(R.string.report_comment));
			value.setText(mReportCursor.getString(ReportQuery.COMMENT));
			reportLinearLayout.addView(view);
		}
	}
	


	/**
     * Parameters used to perform the report query.
     */
    private interface ReportQuery {
        int _TOKEN = 0x1;

        String[] PROJECTION = {
                Report.QUALIFIER + Report.REPORT_ID,
                Report.QUALIFIER + Report.REPORT_TYPE,
                Report.QUALIFIER + Report.TRAFFIC_FLOW,
                Report.QUALIFIER + Report.TRANSIT_TYPE,
                Report.QUALIFIER + Report.COMMENT,
                ReportsSearch.QUALIFIER + ReportsSearch.LATITUDE,
                ReportsSearch.QUALIFIER + ReportsSearch.LONGITUDE,
                ReportsSearch.QUALIFIER + ReportsSearch.DATE
        };
        
        int REPORT_ID = 0;
        int REPORT_TYPE = 1;
        int TRAFFIC_FLOW = 2;
        int TRANSIT_TYPE = 3;
        int COMMENT = 4;
        int LATITUDE = 5;
        int LONGITUDE = 6;
        int DATE = 7;
    }

    /**
     * Parameters used to perform the update query.
     */
    private interface UpdateQuery {
        int _TOKEN = 0x2;

        String[] PROJECTION = {
                Update._ID,
                Update.UPDATE_ID,
                Update.REPORT_ID,
                Update.TRAFFIC_FLOW,
                Update.TRANSIT_TYPE,
                Update.COMMENT,
                Update.DATE
        };

        int _ID = 0;
        int UPDATE_ID = 1;
        int REPORT_ID = 2;
        int TRAFFIC_FLOW = 3;
        int TRANSIT_TYPE = 4;
        int COMMENT = 5;
        int DATE = 6;
    }

    /**
     * Parameters used to perform the report feedbacks query.
     */
    private interface ReportFeedbacksQuery {
        int _TOKEN = 0x3;

        String[] PROJECTION = {
                Feedback._ID,
                Feedback.FEEDBACK_ID,
                Feedback.GRADE
        };

        int _ID = 0;
        int FEEDBACK_ID = 1;
        int GRADE = 2;
    }
    
    /**
     * Parameters used to perform the report feedbacks query.
     */
    private interface UpdateFeedbacksQuery {
        int _TOKEN = 0x4;

        String[] PROJECTION = {
                Feedback.GRADE
        };

        int _ID = 0;
        int FEEDBACK_ID = 1;
        int GRADE = 2;
    }

}
