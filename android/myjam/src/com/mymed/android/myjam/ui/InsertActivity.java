package com.mymed.android.myjam.ui;

import android.app.AlertDialog;
import android.app.Dialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.location.Location;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.mymed.android.myjam.R;
import com.mymed.android.myjam.controller.IMyJamCallAttributes;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.service.MyJamCallService;
import com.mymed.android.myjam.service.MyJamCallService.RequestCode;
import com.mymed.android.myjam.type.MReportBean;

import com.mymed.utils.MyResultReceiver;
/**
 * Activity that permits to insert reports or updates.
 * @author iacopo
 *
 */
public class InsertActivity extends AbstractLocatedActivity implements MyResultReceiver.Receiver{
	private static final String TAG = "InsertActivity";
	
	/** The mode of the view. */
	private static final int REPORT = 0x0;
	private static final int UPDATE = 0x1;
	/** The report types */
	private static final int JAM = 0x0;
	private static final int CAR_CRASH = 0x1;
	private static final int WORK_IN_PROGRESS = 0x2;
	private static final int FIXED_SPEED_CAM = 0x3;
	private static final int MOBILE_SPEED_CAM = 0x4;
	
	private static final int INSERT_SEARCH_RANGE = 500;
	
	/** Dialog. */
	static final int DIALOG_CHOOSE_REPORT_TYPE_ID = 0;
	
	public static final String EXTRA_REQUEST_CODE =
            "com.mymed.android.myjam.extra.INSERT_TYPE";
	
	public static final String EXTRA_ATTRIBUTES =
            "com.mymed.android.myjam.extra.ATTRIBUTES";

	
	
	MyResultReceiver mResultReceiver;
	private int mInsertionType;
	private int mReportType;
	
	private Spinner mTrafficFlowSpinner,
					mTransitSpinner;
	private Button 	mInsertButton;
	private EditText mCommentEditText;
	private Bundle mBundle;
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.insert_view);
		mResultReceiver = MyResultReceiver.getInstance();
		
		Intent intent = getIntent();
		Uri uri = intent.getData();
		if (uri == null) {
			mInsertionType = REPORT;
			mBundle = intent.getBundleExtra(EXTRA_ATTRIBUTES);
			showDialog(DIALOG_CHOOSE_REPORT_TYPE_ID);
		}else{
			//TODO Handle insert UPDATE case.
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
	}
	
	/**
	 * Creates the layout depending on the report type.
	 * @param reportType
	 */
	private void createLayout(int reportType){
		LayoutInflater mInflater = getLayoutInflater();
		LinearLayout insertLinearLayout = (LinearLayout) findViewById(R.id.linearLayoutInsert);
		insertLinearLayout.setOrientation(1);
		insertLinearLayout.removeAllViews();
		switch (reportType){
		case CAR_CRASH:
		case WORK_IN_PROGRESS:
			mTransitSpinner = addSpinnerView(mInflater,insertLinearLayout,R.array.transit_list,
		    		R.string.transit_label,R.string.transit_choose);
		case  JAM:
			mTrafficFlowSpinner = addSpinnerView(mInflater,insertLinearLayout,R.array.traffic_flow_list,
		    		R.string.traffic_flow_label,R.string.traffic_flow_choose);
		case  FIXED_SPEED_CAM:
		case  MOBILE_SPEED_CAM:
			TextView commentLabelTextView = new TextView(this);
			commentLabelTextView.setText(getResources().getString(R.string.comment_label));
			insertLinearLayout.addView(commentLabelTextView);
			mCommentEditText = (EditText) mInflater.inflate(R.layout.edit_text_item, insertLinearLayout,false);
			insertLinearLayout.addView(mCommentEditText);
			mInsertButton = new Button(this);
			mInsertButton.setText(getResources().getText(R.string.insert_button_label));
			mInsertButton.setGravity(Gravity.CENTER_HORIZONTAL);
			mInsertButton.setOnClickListener(new View.OnClickListener() {
				public void onClick(View v) {
					/*
					* 	The service is started, if it's not, and a search report is performed.
					*/
					try{					
						MReportBean report = new MReportBean();
						report.setReportType(getResources().getTextArray(R.array.report_typevalues_list)[mReportType].toString());
						if (mTrafficFlowSpinner!=null)
							report.setTrafficFlowType(getResources().getTextArray(
									R.array.traffic_flowvalues_list)[mTrafficFlowSpinner.getSelectedItemPosition()].toString());
						if (mTransitSpinner!=null)
							report.setTransitType(getResources().getTextArray(
								R.array.transit_listvalues)[mTransitSpinner.getSelectedItemPosition()].toString());
						report.setComment(mCommentEditText.getText().toString());
						requestInsertReport(mBundle,report);
					} catch (Exception e){
						Toast.makeText(InsertActivity.this, "Wrong parameters.",Toast.LENGTH_LONG).show();
					}
				}
			});
			insertLinearLayout.addView(mInsertButton);
		}
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
	
	@Override
    protected Dialog onCreateDialog(int id) {
        Dialog dialog;
        switch(id) {
        case DIALOG_CHOOSE_REPORT_TYPE_ID:
        	final CharSequence[] items = getResources().getTextArray(R.array.report_type_list);
        	AlertDialog.Builder builder = new AlertDialog.Builder(this);
        	builder.setTitle(getResources().getString(R.string.report_type_choose));
        	builder.setItems(items, new DialogInterface.OnClickListener() {
        	    public void onClick(DialogInterface dialog, int item) {
        	        mReportType = item;
        	        Location currLoc = mService.getCurrentLocation();					
					int lat = (int) (currLoc.getLatitude()*1E6);
					int lon = (int) (currLoc.getLongitude()*1E6);
					int radius = INSERT_SEARCH_RANGE;
					requestSearch(lat,lon,radius,Search.INSERT_SEARCH);
        		    createLayout(mReportType);
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
        bundle.putInt(IMyJamCallAttributes.LATITUDE, lat);
        bundle.putInt(IMyJamCallAttributes.LONGITUDE, lon);
        bundle.putInt(IMyJamCallAttributes.RADIUS, radius);
        bundle.putInt(IMyJamCallAttributes.SEARCH_ID, searchId);
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
		switch (resultCode){
			case MyJamCallService.STATUS_RUNNING:
				break;
			case MyJamCallService.STATUS_ERROR:
				String errMsg = resultData.getString(Intent.EXTRA_TEXT);
				final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
				Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
				Log.d(TAG,errorText);
				break;
			case MyJamCallService.STATUS_FINISHED:
				if (reqCode == RequestCode.INSERT_REPORT){
					String type = getResources().getString(R.string.report_string);
					final String successText = String.format(this.getResources().getString(R.string.insert_success, type));
					Toast.makeText(this, successText, Toast.LENGTH_SHORT).show();
					finish();
				}else if (reqCode == RequestCode.SEARCH_REPORTS){
					//TODO
				}
				break;
		}

	}

}
