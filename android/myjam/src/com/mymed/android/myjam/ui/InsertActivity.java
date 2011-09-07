package com.mymed.android.myjam.ui;

import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RatingBar;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.mymed.android.myjam.R;
import com.mymed.android.myjam.controller.ICallAttributes;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.Update;
import com.mymed.android.myjam.service.MyJamCallService;
import com.mymed.android.myjam.service.MyJamCallService.RequestCode;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.utils.GlobalStateAndUtils;
import com.mymed.utils.MyResultReceiver;
/**
 * Activity that permits to insert reports or updates.
 * @author iacopo
 *
 */
public class InsertActivity extends AbstractLocatedActivity implements MyResultReceiver.Receiver{
	private static final String TAG = "InsertActivity";

	private interface SearchReportsQuery{
		String[] PROJECTION = new String[] {
				Report.QUALIFIER+Report._ID + " AS _id",		//0
		};	
	}

	/**
	 * Parameters used to perform the report query.
	 */
	private interface ReportQuery {
		String[] PROJECTION = {
				Report.REPORT_TYPE
		};

		int REPORT_TYPE = 0;
	}
	
	/**
	 * Parameters used to perform the update query.
	 */
	private interface UpdateQuery {
		String[] PROJECTION = {
				Update.QUALIFIER+Update.UPDATE_ID,
				Update.QUALIFIER+Update.REPORT_ID,
				Report.QUALIFIER+Report.REPORT_TYPE
		};

		int UPDATE_ID = 0;
		int REPORT_ID = 1;
		int REPORT_TYPE = 2;
	}

	/** The insertion type. */
	protected static final int REPORT = 0x0;
	protected static final int UPDATE = 0x1;
	protected static final int REPORT_FEEDBACK = 0x2;
	protected static final int UPDATE_FEEDBACK = 0x3;


	private static final int INSERT_SEARCH_RANGE = 500;

	/** Dialogs. */
	static final int DIALOG_CHOOSE_REPORT_TYPE_ID = 0;
	static final int DIALOG_SHOW_REPORTS_ID = 1;

	/** TrafficFlow code*/
	static final int COMPROMISED_ID = 2;

	/** Transit code*/
	static final int BLOCKED_ID = 3;

	public static final String EXTRA_INSERT_TYPE =
			"com.mymed.android.myjam.extra.INSERT_TYPE";

	public static final String EXTRA_ATTRIBUTES =
			"com.mymed.android.myjam.extra.ATTRIBUTES";

	private String[] mReportTypes;
	private String[] mReportTypesVal;

	MyResultReceiver mResultReceiver;
	private int mReportTypeId;

	private Spinner mTrafficFlowSpinner,
					mTransitSpinner;
	private Button 	mInsertButton;
	private EditText mCommentEditText;
	private Bundle mBundle;
	private ProgressDialog mDialog;
	private TextView mInsertTypeTextView;
	private RatingBar mRatingBar;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.insert_view);
		mResultReceiver = MyResultReceiver.getInstance();
		mInsertTypeTextView = (TextView) findViewById(R.id.textViewInsertType);
		mReportTypes = getResources().getStringArray(R.array.report_type_list);
		mReportTypesVal = getResources().getStringArray(R.array.report_typevalues_list);

		Intent intent = getIntent();
		int insertType = intent.getIntExtra(EXTRA_INSERT_TYPE, -1);
		Uri uri = intent.getData();
		Cursor cursor;
		switch (insertType){
		case REPORT:
			/** If the type to insert is REPORT latitude and longitude of the insertion point are passed through a bundle. */
			mBundle = intent.getBundleExtra(EXTRA_ATTRIBUTES);
			if (mBundle == null){
				Log.d(TAG, "Bundle whith latitude and longitude not inserted.");
				finish();
			}	
			showDialog(DIALOG_CHOOSE_REPORT_TYPE_ID);
			break;
		case UPDATE:
			/** If the type to insert is UPDATE the report URI is passed. */
			if (uri == null || Report.getReportId(uri)==null){
				Log.d(TAG, "Report URI not specified or not correct.");
				finish();
			}	
			cursor = managedQuery(uri, ReportQuery.PROJECTION, null, null, null);
			if (!cursor.moveToFirst()){
				Log.d(TAG, "Starting inteng points data not present.");
				finish();
			}
			mBundle = new Bundle();
			mBundle.putString(ICallAttributes.REPORT_ID, Report.getReportId(uri));
			mReportTypeId = GlobalStateAndUtils.getInstance(this).getStringArrayValueIndex(mReportTypesVal,
					cursor.getString(ReportQuery.REPORT_TYPE));
			if (mReportTypeId > 0){
				createReportOrUpdateLayout(UPDATE,mReportTypeId);
			}else{
				//Could never happen.
				Log.d(TAG, "Unknown report type.");
				finish();
			}
			break;
		case REPORT_FEEDBACK:
			/** If the type to insert is UPDATE the report URI is passed. */			
			if (uri == null || Report.getReportId(uri)==null){
				Log.d(TAG, "Report URI not specified or not correct.");
				finish();
			}	
			cursor = managedQuery(uri, ReportQuery.PROJECTION, null, null, null);
			if (!cursor.moveToFirst()){
				Log.d(TAG, "Starting inteng points data not present.");
				finish();
			}
			mBundle = new Bundle();
			mBundle.putString(ICallAttributes.REPORT_ID, Report.getReportId(uri));
			mReportTypeId = GlobalStateAndUtils.getInstance(this).getStringArrayValueIndex(mReportTypesVal,
					cursor.getString(ReportQuery.REPORT_TYPE));
			if (mReportTypeId > 0){
				createFeedbackLayout(REPORT_FEEDBACK,mReportTypeId);
			}else{
				//Could never happen.
				Log.d(TAG, "Unknown report type.");
				finish();
			}
			break;
		case UPDATE_FEEDBACK:
			/** If the type to insert is UPDATE the report URI is passed. */			
			if (uri == null || Update.getUpdateId(uri)==null){
				Log.d(TAG, "Report URI not specified or not correct.");
				finish();
			}	
			cursor = managedQuery(uri, UpdateQuery.PROJECTION, null, null, null);
			if (!cursor.moveToFirst()){
				Log.d(TAG, "Starting inteng points data not present.");
				finish();
			}
			mBundle = new Bundle();
			mBundle.putString(ICallAttributes.REPORT_ID, cursor.getString(UpdateQuery.REPORT_ID));
			mBundle.putString(ICallAttributes.UPDATE_ID, cursor.getString(UpdateQuery.UPDATE_ID));
			mReportTypeId = GlobalStateAndUtils.getInstance(this).getStringArrayValueIndex(mReportTypesVal,
					cursor.getString(UpdateQuery.REPORT_TYPE));
			if (mReportTypeId >= 0){
				createFeedbackLayout(UPDATE_FEEDBACK,mReportTypeId);
			}else{
				//Could never happen.
				Log.d(TAG, "Unknown report type.");
				finish();
			}
			break;
		default:
			//Could never happen.
			Log.d(TAG, "Unknown insertion type.");
			finish();
		}
	}

	@Override
	public void onPause(){
		super.onPause();
		mResultReceiver.clearReceiver();
	}

	@Override
	public void onResume(){
		super.onResume();
		mResultReceiver.setReceiver(this);
		updateRefreshStatus(mResultReceiver.ismSyncing(),"");
	}

	/**
	 * Creates the layout depending on the report type.
	 * @param reportType
	 */
	private void createReportOrUpdateLayout(final int insertionType,final int reportType){
		final String type = mReportTypes[reportType];
		mInsertTypeTextView.setText(type);
		LayoutInflater mInflater = getLayoutInflater();
		LinearLayout insertLinearLayout = (LinearLayout) findViewById(R.id.linearLayoutInsert);
		insertLinearLayout.setOrientation(LinearLayout.VERTICAL);
		insertLinearLayout.removeAllViews();
		insertLinearLayout.addView(mInsertTypeTextView);

		switch (reportType){
		case GlobalStateAndUtils.CAR_CRASH:
		case GlobalStateAndUtils.WORK_IN_PROGRESS:
			mTransitSpinner = addSpinnerView(mInflater,insertLinearLayout,R.array.transit_list,
					R.string.transit_label,R.string.transit_choose);
		case  GlobalStateAndUtils.JAM:
			mTrafficFlowSpinner = addSpinnerView(mInflater,insertLinearLayout,R.array.traffic_flow_list,
					R.string.traffic_flow_label,R.string.traffic_flow_choose);
		case  GlobalStateAndUtils.FIXED_SPEED_CAM:
		case  GlobalStateAndUtils.MOBILE_SPEED_CAM:
			TextView commentLabelTextView = new TextView(this);
			commentLabelTextView.setText(getResources().getString(R.string.comment_label));
			insertLinearLayout.addView(commentLabelTextView);
			mCommentEditText = (EditText) mInflater.inflate(R.layout.edit_text_item, insertLinearLayout,false);
			insertLinearLayout.addView(mCommentEditText);

			if (mTransitSpinner !=null && mTrafficFlowSpinner != null){
				mTransitSpinner.setOnItemSelectedListener(mTrafficFlowListener);
			}

			mInsertButton = new Button(this);
			mInsertButton.setText(getResources().getText(insertionType==REPORT?
					R.string.insert_report_button_label:R.string.insert_update_button_label));
			mInsertButton.setGravity(Gravity.CENTER_HORIZONTAL);
			mInsertButton.setOnClickListener(new View.OnClickListener() {
				public void onClick(View v) {
					try{					
						MReportBean reportOrUpdate = new MReportBean();
						reportOrUpdate.setUserId(GlobalStateAndUtils.getInstance(getApplicationContext()).getUserId());
						reportOrUpdate.setReportType(mReportTypesVal[reportType]);
						if (mTrafficFlowSpinner!=null)
							reportOrUpdate.setTrafficFlowType(getResources().getTextArray(
									R.array.traffic_flowvalues_list)[mTrafficFlowSpinner.getSelectedItemPosition()].toString());
						if (mTransitSpinner!=null)
							reportOrUpdate.setTransitType(getResources().getTextArray(
									R.array.transit_listvalues)[mTransitSpinner.getSelectedItemPosition()].toString());
						reportOrUpdate.setComment(mCommentEditText.getText().toString());

						if (insertionType == REPORT)
							requestInsertReport(mBundle,reportOrUpdate);
						else if (insertionType == UPDATE)
							requestUpdateReport(mBundle,reportOrUpdate);
					} catch (Exception e){
						Toast.makeText(InsertActivity.this, "Wrong parameters.",Toast.LENGTH_LONG).show();
					}
				}
			});
			insertLinearLayout.addView(mInsertButton);
		}
	}
	
	private void createFeedbackLayout(final int insertionType,final int reportType) {
		final String type = mReportTypes[reportType];
		mInsertTypeTextView.setText(type);
		LayoutInflater mInflater = getLayoutInflater();
		LinearLayout insertLinearLayout = (LinearLayout) findViewById(R.id.linearLayoutInsert);
		insertLinearLayout.setOrientation(1);
		insertLinearLayout.removeAllViews();
		insertLinearLayout.addView(mInsertTypeTextView);
		
		TextView voteLabelTextView = new TextView(this);
		voteLabelTextView.setText(getResources().getString(R.string.vote_label));
		insertLinearLayout.addView(voteLabelTextView);
		mRatingBar = (RatingBar) mInflater.inflate(R.layout.rating_bar_item, insertLinearLayout,false);
		insertLinearLayout.addView(mRatingBar);
		mInsertButton = new Button(this);
		mInsertButton.setText(getResources().getText(insertionType== REPORT_FEEDBACK?
				R.string.vote_report_button_label:R.string.vote_update_button_label));
		mInsertButton.setGravity(Gravity.CENTER_HORIZONTAL);
		mInsertButton.setOnClickListener(new View.OnClickListener() {
			public void onClick(View v) {
				try{					
					MFeedBackBean feedback = new MFeedBackBean();
					feedback.setUserId(GlobalStateAndUtils.getInstance(getApplicationContext()).getUserId());
					feedback.setGrade((int) ((mRatingBar.getRating() / mRatingBar.getMax()) * GlobalStateAndUtils.MAX_RATING)); //TODO fix this

					if (insertionType == REPORT_FEEDBACK)
						requestInsertReportFeedback(mBundle,feedback);
					else if (insertionType == UPDATE_FEEDBACK)
						requestInsertUpdateReport(mBundle,feedback);
				} catch (Exception e){
					Toast.makeText(InsertActivity.this, "Wrong parameters.",Toast.LENGTH_LONG).show();
				}
			}
		});
		insertLinearLayout.addView(mInsertButton);
	}

	private Spinner addSpinnerView(LayoutInflater inflater,ViewGroup layout,int choiceArrayList,int labelId,int promptId){
		View view = (View) inflater.inflate(R.layout.spinner_item, layout,false);
		TextView textView = (TextView) view.findViewById(R.id.textViewSpinner);
		textView.setText(getResources().getString(labelId));
		Spinner spinner = (Spinner) view.findViewById(R.id.spinner);
		ArrayAdapter<CharSequence> adapter = ArrayAdapter.createFromResource(
				this, choiceArrayList, android.R.layout.simple_spinner_item);
		adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
		spinner.setAdapter(adapter);
		spinner.setPromptId(R.string.report_type_choose);
		layout.addView(view);
		return spinner;
	}

	public OnItemSelectedListener mTrafficFlowListener = new OnItemSelectedListener(){
		@Override
		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			if (arg2 == COMPROMISED_ID){
				mTrafficFlowSpinner.setSelection(BLOCKED_ID);
				mTrafficFlowSpinner.setEnabled(false);
			}else{
				mTrafficFlowSpinner.setEnabled(true);
			}


		}

		@Override
		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub			
		}
	};

	@Override
	protected Dialog onCreateDialog(int id) {
		Dialog dialog;

		AlertDialog.Builder builder = new AlertDialog.Builder(this);
		switch(id) {
		case DIALOG_CHOOSE_REPORT_TYPE_ID:
			final CharSequence[] items = mReportTypes;
			builder.setTitle(R.string.report_type_choose)
				   .setIcon(R.drawable.myjam_icon)
				   .setItems(items, new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int item) {
					/** Save in a global variable the chosen report type. */
					mReportTypeId = item;				
					/** Dispatches a request to get the reports in a range  {@link INSERT_SEARCH_RANGE} around the position
					 *  where the report wants to be inserted.
					 * */
					int lat = mBundle.getInt(ICallAttributes.LATITUDE);
					int lon = mBundle.getInt(ICallAttributes.LONGITUDE);
					int radius = INSERT_SEARCH_RANGE;
					requestSearch(lat,lon,radius,Search.INSERT_SEARCH);
					createReportOrUpdateLayout(REPORT,mReportTypeId);
				}
			})
			.setOnCancelListener(new DialogInterface.OnCancelListener() {
				
				@Override
				public void onCancel(DialogInterface dialog) {
					finish();				
				}
			});
			dialog = builder.create();
			break;
		case DIALOG_SHOW_REPORTS_ID:
			final String type = mReportTypes[mReportTypeId];
			final String typeValue = mReportTypesVal[mReportTypeId];
			final String dialogText = String.format(getResources().getString(R.string.near_reports_found, 
					type, String.valueOf(INSERT_SEARCH_RANGE)));
			builder.setTitle(R.string.dialog_title)
			.setIcon(R.drawable.myjam_icon)
			.setMessage(dialogText)
			.setCancelable(false)
			.setPositiveButton(getResources().getString(R.string.button_see_label), new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int id) {
					final Intent intent = new Intent(InsertActivity.this, SearchActivity.class);
					intent.setData(SearchReports.buildSearchUri(String.valueOf(Search.INSERT_SEARCH), typeValue)); 
					startActivity(intent);
					InsertActivity.this.finish();
				}
			})
			.setNegativeButton(getResources().getString(R.string.button_continue_label), new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int id) {
					dialog.cancel();
				}
			});
			dialog = builder.create();
			break;
		default:
			dialog = null;
		}
		return dialog;
	}

	private void requestInsertReport(Bundle bundle,MReportBean report){
		final Intent intent = new Intent(InsertActivity.this, MyJamCallService.class);
		intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
		intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.INSERT_REPORT);
		intent.putExtra(MyJamCallService.EXTRA_OBJECT, report);
		intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);
		Log.d(TAG,"Intent sent: "+intent.toString());
		startService(intent);
	}

	private void requestUpdateReport(Bundle bundle,
			MReportBean update) {
		final Intent intent = new Intent(InsertActivity.this, MyJamCallService.class);
		intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
		intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.INSERT_UPDATE);
		intent.putExtra(MyJamCallService.EXTRA_OBJECT, update);
		intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);
		Log.d(TAG,"Intent sent: "+intent.toString());
		startService(intent);	
	}
	
	private void requestInsertReportFeedback(Bundle bundle,
			MFeedBackBean feedback) {
		final Intent intent = new Intent(InsertActivity.this, MyJamCallService.class);
		intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
		intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.INSERT_REPORT_FEEDBACK);
		intent.putExtra(MyJamCallService.EXTRA_OBJECT, feedback);
		intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);
		Log.d(TAG,"Intent sent: "+intent.toString());
		startService(intent);	
	}
	
	private void requestInsertUpdateReport(Bundle bundle,
			MFeedBackBean feedback) {
		final Intent intent = new Intent(InsertActivity.this, MyJamCallService.class);
		intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
		intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.INSERT_UPDATE_FEEDBACK);
		intent.putExtra(MyJamCallService.EXTRA_OBJECT, feedback);
		intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);
		Log.d(TAG,"Intent sent: "+intent.toString());
		startService(intent);
	}

	/**
	 * Requests a search operation to the {@link MyJamCallService}.
	 * @param lat		Latitude of the current position of the user.
	 * @param lon		Longitude of the current position of the user.
	 * @param radius	Radius of the search.
	 * @param searchId	Identifier of the search, used to perform the query and show the right results.
	 */
	private void requestSearch(int lat,int lon,int radius, int searchId){
		final Intent intent = new Intent(InsertActivity.this, MyJamCallService.class);
		intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
		intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.SEARCH_REPORTS);
		Bundle bundle = new Bundle();
		bundle.putInt(ICallAttributes.LATITUDE, lat);
		bundle.putInt(ICallAttributes.LONGITUDE, lon);
		bundle.putInt(ICallAttributes.RADIUS, radius);
		bundle.putInt(ICallAttributes.SEARCH_ID, searchId);
		intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);
		Log.d(TAG,"Intent sent: "+intent.toString());
		startService(intent);
	}


	@Override
	protected void onLocServiceConnected() {		
	}

	@Override
	protected void onLocServiceDisconnected() {

	}

	@Override
	protected void onLocationAvailable() {
	}

	@Override
	protected void onLocationNoMoreAvailable() {
	}


	@Override
	public void onReceiveResult(int resultCode, Bundle resultData) {
		int reqCode = resultData.getInt(MyJamCallService.EXTRA_REQUEST_CODE);
		final boolean mSyncing;
		final String message;
		switch (resultCode){
		//*
		case MyJamCallService.STATUS_RUNNING:
			mSyncing = true;
			message = (reqCode == RequestCode.SEARCH_REPORTS)?getResources().getString(R.string.search_start):
				getResources().getString(R.string.insert_start);
			break;
		case MyJamCallService.STATUS_ERROR:
			mSyncing = false;
			message = "";
			String errMsg = resultData.getString(Intent.EXTRA_TEXT);
			final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
			Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
			Log.d(TAG,errorText);
			break;
		case MyJamCallService.STATUS_FINISHED:
			mSyncing = false;
			message = "";
			if (reqCode == RequestCode.INSERT_REPORT || reqCode == RequestCode.INSERT_UPDATE){
				String type = getResources().getString(R.string.report_string);
				final String successText = String.format(this.getResources().getString(R.string.insert_success, type));
				Toast.makeText(this, successText, Toast.LENGTH_SHORT).show();
				finish();
			}else if (reqCode == RequestCode.INSERT_REPORT_FEEDBACK || 
					reqCode == RequestCode.INSERT_UPDATE_FEEDBACK){
				//TODO Write something on screen
				finish();				
			}else if (reqCode == RequestCode.SEARCH_REPORTS){
				Cursor cursor = managedQuery(SearchReports.buildSearchUri(String.valueOf(Search.INSERT_SEARCH), 
						getResources().getTextArray(R.array.report_typevalues_list)[mReportTypeId].toString())
						, SearchReportsQuery.PROJECTION, null, null,
						SearchReports.DEFAULT_SORT_ORDER);
				int rowsCount = cursor.getCount();
				if (rowsCount>0){
					showDialog(DIALOG_SHOW_REPORTS_ID);
				}
			}
			break;
		default:
			Log.d(TAG, "OnReceiveResult: Unknown result code.");
			mSyncing = false;
			message = "";
			break;					
		}
		updateRefreshStatus(mSyncing,message);
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

}
