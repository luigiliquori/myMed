package com.mymed.android.myjam.ui;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;

import com.mymed.android.myjam.R;
import com.mymed.android.myjam.controller.CallContract;
import com.mymed.android.myjam.controller.CallContract.CallCode;
import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.service.CallService;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.utils.GlobalStateAndUtils;
import com.mymed.utils.MyResultReceiver;
import com.mymed.utils.MyResultReceiver.IReceiver;
/**
 * Activity that permits to insert reports or updates.
 * @author iacopo
 *
 */
public class InsertActivity extends AbstractLocatedActivity implements IReceiver{
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

	/** The insertion type. */
	protected static final int REPORT = 0x0;
	protected static final int UPDATE = 0x1;
	protected static final int REPORT_FEEDBACK = 0x2;
	protected static final int UPDATE_FEEDBACK = 0x3;


	private static final int INSERT_SEARCH_RANGE = 500;

	/** Dialogs. */
	static final int DIALOG_CHOOSE_REPORT_TYPE_ID = 0;
	static final int DIALOG_SHOW_REPORTS_ID = 1;

	public static final String EXTRA_INSERT_TYPE =
			"com.mymed.android.myjam.extra.INSERT_TYPE";

	public static final String EXTRA_ATTRIBUTES =
			"com.mymed.android.myjam.extra.ATTRIBUTES";

	private String[] mReportTypes;
	private String[] mReportTypesVal;

	MyResultReceiver mResultReceiver;
	private int mReportTypeId;

	private RadioGroup mTrafficFlowRadioGroup;
	private Button 	mInsertButton;
	private EditText mCommentEditText;
	private Bundle mBundle;
	private ProgressDialog mDialog;
	private TextView mInsertTypeTextView,
					 mTrafficFlowLabelTextView;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.insert_view);
		mResultReceiver = MyResultReceiver.getInstance(this.getClass().getName(),this);
		mInsertTypeTextView = (TextView) findViewById(R.id.textViewInsertType);
		mReportTypes = getResources().getStringArray(R.array.report_type_list);
		mReportTypesVal = getResources().getStringArray(R.array.report_typevalues_list);
		mInsertButton = (Button) findViewById(R.id.buttonInsert);
		mTrafficFlowRadioGroup = (RadioGroup) findViewById(R.id.radioGroupTrafficFlow);
		mTrafficFlowRadioGroup.setVisibility(View.GONE);
		mTrafficFlowLabelTextView = (TextView) findViewById(R.id.textViewTrafficFlowLabel);
		mTrafficFlowLabelTextView.setVisibility(View.GONE);
		mCommentEditText = (EditText) findViewById(R.id.editTextComment);
		
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
			mBundle.putString(CallContract.REPORT_ID, Report.getReportId(uri));
			mReportTypeId = GlobalStateAndUtils.getInstance(this).getStringArrayValueIndex(mReportTypesVal,
					cursor.getString(ReportQuery.REPORT_TYPE));
			if (mReportTypeId >= 0){
				createReportOrUpdateLayout(UPDATE);
			}else{
				//Could never happen.
				Log.d(TAG, "Unknown report type.");
				finish();
			}
			break;
		default:
			//Could never happen.
			Log.d(TAG, "Unknown insertion type.");
			setResult(Activity.RESULT_CANCELED);
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
		MyResultReceiver resultReceiver = MyResultReceiver.getInstance(this.getClass().getName(),this);
		resultReceiver.checkOngoingCalls();
	}

	/**
	 * Creates the layout depending on the report type.
	 * @param reportType
	 */
	private void createReportOrUpdateLayout(final int insertionType){
		final String type = mReportTypes[mReportTypeId];
		mInsertTypeTextView.setText(type);

		switch (mReportTypeId){
		case GlobalStateAndUtils.CAR_CRASH:
		case GlobalStateAndUtils.WORK_IN_PROGRESS:
		case  GlobalStateAndUtils.JAM:
			
			int i = 0;
			LayoutInflater inflater = getLayoutInflater();
			boolean isFirst = true;
			for (CharSequence trafficFlowVal:getResources().getTextArray(R.array.traffic_flow_list)){
				if (!(insertionType == REPORT && isFirst)){ //If it is a report the first value corresponding to ``NORMAL'' is skipped.
					RadioButton radioButton = (RadioButton) inflater.inflate(R.layout.radio_button_item,null);
					radioButton.setText(trafficFlowVal);
					radioButton.setClickable(true);
					radioButton.setId(i++);
					mTrafficFlowRadioGroup.addView(radioButton);
				}
				isFirst = false;
			}
			mTrafficFlowRadioGroup.check(0);
			mTrafficFlowRadioGroup.setVisibility(View.VISIBLE);
			mTrafficFlowLabelTextView.setVisibility(View.VISIBLE);
		case  GlobalStateAndUtils.FIXED_SPEED_CAM:
		case  GlobalStateAndUtils.MOBILE_SPEED_CAM:

			mInsertButton.setText(getResources().getText(insertionType==REPORT?
					R.string.insert_report_button_label:R.string.insert_update_button_label));
			mInsertButton.setOnClickListener(new View.OnClickListener() {
				public void onClick(View v) {
					try{					
						MReportBean reportOrUpdate = new MReportBean();
						reportOrUpdate.setUserId(GlobalStateAndUtils.getInstance(getApplicationContext()).getUserId());
						reportOrUpdate.setReportType(mReportTypesVal[mReportTypeId]);
						if (mReportTypeId!=GlobalStateAndUtils.FIXED_SPEED_CAM && mReportTypeId!=GlobalStateAndUtils.MOBILE_SPEED_CAM)
							reportOrUpdate.setTrafficFlowType(getResources().getTextArray(
									R.array.traffic_flowvalues_list)[mTrafficFlowRadioGroup.getCheckedRadioButtonId()].toString());
						reportOrUpdate.setComment(mCommentEditText.getText().toString());

						if (insertionType == REPORT)
							requestInsertReport(mBundle,reportOrUpdate);
						else if (insertionType == UPDATE)
							requestUpdateReport(mBundle,reportOrUpdate);
						mInsertButton.setEnabled(false);
					} catch (Exception e){
						Toast.makeText(InsertActivity.this, "Wrong parameters.",Toast.LENGTH_LONG).show();
					}
				}
			});
		}
	}

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
					int lat = mBundle.getInt(CallContract.LATITUDE);
					int lon = mBundle.getInt(CallContract.LONGITUDE);
					int radius = INSERT_SEARCH_RANGE;
					requestSearch(lat,lon,radius,Search.INSERT_SEARCH);
					createReportOrUpdateLayout(REPORT);
					mInsertButton.setEnabled(false);
				}
			})
			.setOnCancelListener(new DialogInterface.OnCancelListener() {
				
				@Override
				public void onCancel(DialogInterface dialog) {
					setResult(Activity.RESULT_CANCELED);
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
					InsertActivity.this.setResult(Activity.RESULT_CANCELED);
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
		final Intent intent = new Intent(InsertActivity.this, CallService.class);
		bundle.putString(CallContract.ACCESS_TOKEN, GlobalStateAndUtils.getInstance(this).getAccessToken());
		intent.putExtra(CallService.EXTRA_ACTIVITY_ID, this.getClass().getName());
		intent.putExtra(CallService.EXTRA_REQUEST_CODE, CallCode.INSERT_REPORT);
		intent.putExtra(CallService.EXTRA_PRIORITY_CODE, HttpCall.HIGH_PRIORITY);
		intent.putExtra(CallService.EXTRA_NUMBER_ATTEMPTS, 3);
		intent.putExtra(CallService.EXTRA_OBJECT, report);
		intent.putExtra(CallService.EXTRA_ATTRIBUTES, bundle);
		Log.d(TAG,"Intent sent: "+intent.toString());
		startService(intent);
	}

	private void requestUpdateReport(Bundle bundle,
			MReportBean update) {
		final Intent intent = new Intent(InsertActivity.this, CallService.class);
		bundle.putString(CallContract.ACCESS_TOKEN, GlobalStateAndUtils.getInstance(this).getAccessToken());
		intent.putExtra(CallService.EXTRA_ACTIVITY_ID, this.getClass().getName());
		intent.putExtra(CallService.EXTRA_REQUEST_CODE, CallCode.INSERT_UPDATE);
		intent.putExtra(CallService.EXTRA_PRIORITY_CODE, HttpCall.HIGH_PRIORITY);
		intent.putExtra(CallService.EXTRA_NUMBER_ATTEMPTS, 3);
		intent.putExtra(CallService.EXTRA_OBJECT, update);
		intent.putExtra(CallService.EXTRA_ATTRIBUTES, bundle);
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
		final Intent intent = new Intent(InsertActivity.this, CallService.class);
		intent.putExtra(CallService.EXTRA_ACTIVITY_ID, this.getClass().getName());
		intent.putExtra(CallService.EXTRA_REQUEST_CODE, CallCode.SEARCH_REPORTS);
		intent.putExtra(CallService.EXTRA_PRIORITY_CODE, HttpCall.HIGH_PRIORITY);
		intent.putExtra(CallService.EXTRA_NUMBER_ATTEMPTS, 1);
		Bundle bundle = new Bundle();
		bundle.putString(CallContract.ACCESS_TOKEN, GlobalStateAndUtils.getInstance(this).getAccessToken());
		bundle.putInt(CallContract.LATITUDE, lat);
		bundle.putInt(CallContract.LONGITUDE, lon);
		bundle.putInt(CallContract.RADIUS, radius);
		bundle.putInt(CallContract.SEARCH_ID, searchId);
		intent.putExtra(CallService.EXTRA_ATTRIBUTES, bundle);
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

	/**
	 * Makes appear and disappear the progress dialog.
	 * @param refreshing 	If true then the dialog appear, 
	 * 						if false the dialog disappear.
	 */
	private void updateRefreshStatus(boolean refreshing,String message) {
		if (refreshing){
			mDialog = ProgressDialog.show(this, "", 
					message, true);
		 	mDialog.setOnKeyListener(new DialogInterface.OnKeyListener() {
				@Override
				public boolean onKey(DialogInterface dialog, int keyCode, KeyEvent event) {
					MyResultReceiver resultRec = MyResultReceiver.get();
					if (keyCode == KeyEvent.KEYCODE_SEARCH && event.getRepeatCount() == 0) {
						return true; // Pretend we processed it
					}else if (keyCode == KeyEvent.KEYCODE_BACK && event.getRepeatCount() == 0){
						//The calls of LoginActivity are not stoppable. The dialog is dismiss only if for some
						//odd reason the call is ended but the activity has not informed.
						if ((resultRec = MyResultReceiver.get()) == null){
							mDialog.dismiss();
							return true;
						}								
						if (resultRec.getOngoingHPCall()==null 
								&& mDialog!=null){
							mDialog.dismiss();							
							return true;
						}
					}
					return false; // Any other keys are still processed as normal
				}
		 	});
		}else{
			mInsertButton.setEnabled(true);
			if (mDialog != null)
				mDialog.dismiss();
		}

	}

	@Override
	public void onUpdateProgressStatus(boolean state, int callCode, int callId) {
		int messageCode;
		switch(callCode){
		case(CallCode.SEARCH_REPORTS):
			messageCode = R.string.search_start;
			break;
		case (CallCode.INSERT_REPORT):
			messageCode = R.string.insert_report_start;
			break;
		case (CallCode.INSERT_UPDATE):
			messageCode = R.string.insert_update_start;
			break;
		default:
			return;
		}
		String message = getResources().getString(messageCode);
		updateRefreshStatus(state,message);
	}

	@Override
	public void onCallError(int callCode, int callId, String errorMessage,
			int numAttempt, int maxAttempts) {
		final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errorMessage));
		Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
		Log.d(TAG,errorText);
		finish();
	}

	@Override
	public void onCallInterrupted(int callCode, int callId) {
		// TODO Auto-generated method stub
	}

	@Override
	public void onCallSuccess(int callCode, int callId) {
		switch (callCode){
		case CallCode.INSERT_REPORT:
		case CallCode.INSERT_UPDATE:
			String type = getResources().getString(callCode == CallCode.INSERT_REPORT?R.string.report_string:R.string.update_string);
			final String successText = String.format(this.getResources().getString(R.string.insert_success, type));
			Toast.makeText(this, successText, Toast.LENGTH_SHORT).show();
			setResult(Activity.RESULT_OK);
			finish();			
			break;
		case CallCode.SEARCH_REPORTS:
			Cursor cursor = managedQuery(SearchReports.buildSearchUri(String.valueOf(Search.INSERT_SEARCH), 
					getResources().getTextArray(R.array.report_typevalues_list)[mReportTypeId].toString())
					, SearchReportsQuery.PROJECTION, null, null,
					SearchReports.DEFAULT_SORT_ORDER);
			int rowsCount = cursor.getCount();
			if (rowsCount>0){
				showDialog(DIALOG_SHOW_REPORTS_ID);
			}			
			return;
		}
	}
}
