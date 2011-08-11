package com.mymed.android.myjam.ui;

import java.text.SimpleDateFormat;
import java.util.Calendar;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.location.Location;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.CursorAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;


import com.mymed.android.myjam.R;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.ReportsSearch;
import com.mymed.android.myjam.service.RestCallService;
import com.mymed.android.myjam.service.RestCallService.RequestCode;

import com.mymed.utils.MyResultReceiver;
import com.mymed.utils.MyResultReceiver.Receiver;

/**
 * Activity that permits to search reports.
 * @author iacopo
 *
 */
public class SearchActivity extends AbstractLocatedActivity implements Receiver {
	private static final String TAG = "SearchActivity";
	
	/** String used to access the set radius preference */
	private static final String RADIUS_PREFERENCE = "radius_preference";
	private ListView mList;
	
	MyResultReceiver mResultReceiver;

	private interface SearchQuery{
		//TODO Add distance to order in a proper way.
		String[] PROJECTION = new String[] {		
			ReportsSearch._ID, 			// 0
			ReportsSearch.REPORT_ID,	// 1
			ReportsSearch.REPORT_TYPE, 	// 2
			ReportsSearch.LATITUDE,		// 3
			ReportsSearch.LONGITUDE,	// 4
			ReportsSearch.DISTANCE,		// 5	
			ReportsSearch.DATE			// 6
		};	
	}

	private ProgressDialog mDialog;
	private SearchListAdapter mAdapter;
	private Button searchButton;
	private SharedPreferences mSettings;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.main);
		
		/** Sets the handle for the action click on the list items. */
		mList = (ListView) findViewById(R.id.SearchList);
		mList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
			@Override
		    public void onItemClick(AdapterView<?> l, View v, int position, long id){
		    	final Cursor cursor = (Cursor)mAdapter.getItem(position);
		        final String reportId = cursor.getString(cursor.getColumnIndex(
		                ReportsSearch.REPORT_ID));

		        Intent intent = new Intent(SearchActivity.this,ReportDetailsActivity.class);
		        Uri uri = Uri.withAppendedPath(Report.CONTENT_URI, reportId);
		        intent.setData(uri);   	
		        startActivity(intent);
		    }
		});
		setDefaultKeyMode(DEFAULT_KEYS_SHORTCUT);
		mSettings = PreferenceManager.getDefaultSharedPreferences(this);
		mResultReceiver = new MyResultReceiver(new Handler());
		searchButton = (Button) findViewById(R.id.SearchButton);
		searchButton.setEnabled(false);
		
		this.searchButton.setOnClickListener(new View.OnClickListener() {
			public void onClick(View v) {
				/*
				* 	The service is started, if it's not, and a search report is performed.
				*/
				try{
					Location currLoc = mService.getCurrentLocation();					
					int lat = (int) (currLoc.getLatitude()*1E6);
					int lon = (int) (currLoc.getLongitude()*1E6);
					int radius = Integer.parseInt(mSettings.getString(RADIUS_PREFERENCE,"10000"));
			        final Intent intent = new Intent(SearchActivity.this, RestCallService.class);
			        intent.putExtra(RestCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
			        intent.putExtra(RestCallService.EXTRA_REQUEST_CODE, RequestCode.SEARCH_REPORTS);
			        Bundle bundle = new Bundle();
			        bundle.putString(com.mymed.android.myjam.controller.MyJamCallManager.MyJamCallAttributes.LATITUDE, String.valueOf(lat));
			        bundle.putString(com.mymed.android.myjam.controller.MyJamCallManager.MyJamCallAttributes.LONGITUDE, String.valueOf(lon));
			        bundle.putString(com.mymed.android.myjam.controller.MyJamCallManager.MyJamCallAttributes.RADIUS, String.valueOf(radius));
			        intent.putExtra(RestCallService.EXTRA_ATTRIBUTES, bundle);
			        Log.d(TAG,"Intent sent: "+intent.toString());
			        startService(intent);
				} catch (Exception e){
					Toast.makeText(SearchActivity.this, "Wrong parameters.",Toast.LENGTH_LONG).show();
				}
			}
		});


		// If no data was given in the intent (because we were started
		// as a MAIN activity), then use our default content provider.
		Intent intent = getIntent();
		if (intent.getData() == null) {
			intent.setData(ReportsSearch.CONTENT_URI);
		}

		// Perform a managed query. The Activity will handle closing and requerying the cursor
		// when needed.
		Cursor cursor = managedQuery(getIntent().getData(), SearchQuery.PROJECTION, null, null,
				ReportsSearch.DEFAULT_SORT_ORDER);

		// Used to map notes entries from the database to views
		mAdapter = new SearchListAdapter(this,cursor,true);
		mList.setAdapter(mAdapter);
	}    

	/**
	 * Called when another component comes in front of this activity.
	 */
	@Override 
	protected void onPause() {
		super.onPause();
		mResultReceiver.clearReceiver();
		//TODO Enable or disable search button. 
	}

	/**
	 * The activity comes to the foreground.
	 */
	@Override 
	protected void onResume() {
		super.onResume();
		mResultReceiver.setReceiver(this);
		//TODO Enable or disable search button.
	}
	

	@Override
	protected void onLocServiceConnected() {
		if (!this.mService.ismLocAvailable())
			this.searchButton.setEnabled(false);
	}

	@Override
	protected void onLocServiceDisconnected() {}
	
	@Override
	/** If the location is available the search button is enabled. */
	protected void onLocationAvailable() {
		searchButton.setEnabled(true);
		Toast.makeText(SearchActivity.this, 
				getResources().getString(R.string.location_available), Toast.LENGTH_LONG).show();
	}

	@Override
	/** If the location is no more available the search button is disabled. */
	protected void onLocationNoMoreAvailable() {
		searchButton.setEnabled(false);
		Toast.makeText(SearchActivity.this, 
				getResources().getString(R.string.location_unavailable), Toast.LENGTH_LONG).show();
	}

	@Override
	/**
	 * Inflate the menu,from the XML description.
	 */
	public boolean onCreateOptionsMenu(Menu menu) {
		super.onCreateOptionsMenu(menu);

        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.search_menu, menu);		
		return true;
	}

	
	/**
	 * Makes appear and disappear the progress dialog.
	 * @param refreshing 	If true then the dialog appear, 
	 * 						if false the dialog disappear.
	 */
    private void updateRefreshStatus(boolean refreshing) {
        if (refreshing){
        	mDialog = ProgressDialog.show(SearchActivity.this, "", 
					getResources().getString(R.string.searching_reports), true);
        }else{
			if (mDialog != null)
				mDialog.dismiss();
        }
        	
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
        case R.id.insert_report:
        	break;
        case R.id.view_on_map:
        	break;
        case R.id.preferences:        	
        	startActivity(new Intent(this,MyPreferenceActitvity.class));
        	break;
        }
        
        return false;
    }
    

	@Override
	public void onReceiveResult(int resultCode, Bundle resultData) {
		boolean mSyncing = false;
		
		switch (resultCode) {
		case RestCallService.STATUS_RUNNING:
			mSyncing = true;
			break;
		case RestCallService.STATUS_FINISHED: 
			mSyncing = false;
			Toast.makeText(this, getResources().getString(R.string.search_finished),Toast.LENGTH_LONG).show();
			break;
		case RestCallService.STATUS_ERROR:
			// Error happened down in SyncService, show as toast.
			mSyncing = false;
			String errMsg = resultData.getString(Intent.EXTRA_TEXT);
			final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
			Toast.makeText(this, errorText, Toast.LENGTH_LONG).show();
			break;
		}

	updateRefreshStatus(mSyncing);
	}

	
	/**
	 * Adapter that prepares the data for the List.
	 * @author iacopo
	 *
	 */
    private static class SearchListAdapter extends CursorAdapter {

		private LayoutInflater mInflater;
		
		private final String typeSeparator;
		private final String dateFormat;
		
		
        public SearchListAdapter(Context context, Cursor c, boolean autoRequery) {
			super(context, c, autoRequery);
			mInflater = LayoutInflater.from(context);
			typeSeparator = context.getString(R.string.type_string_separator);
			dateFormat = context.getString(R.string.date_format);
		}

		@Override
		public void bindView(View view, Context context, Cursor cursor) {
			TextView typeTextView = (TextView)view.findViewById(R.id.textType);
			TextView distanceTextView = (TextView)view.findViewById(R.id.textDistance);
			TextView dateTextView = (TextView)view.findViewById(R.id.textDate);
			String type = cursor.getString(cursor.getColumnIndex(ReportsSearch.REPORT_TYPE));
			int distance = cursor.getInt(cursor.getColumnIndex(ReportsSearch.DISTANCE));
			long date = cursor.getLong(cursor.getColumnIndex(ReportsSearch.DATE));
			typeTextView.setText(formatType(type));
			distanceTextView.setText(formatDistance(distance));
			dateTextView.setText(formatDate(date));
		}


	    @Override

	    public View newView(Context context, Cursor cursor, ViewGroup parent) {
	      final View view = mInflater.inflate(R.layout.search_report_item, parent, false);
	      return view;

	    }
	    /**
	     * Format the string arg0 representing milli-degrees in a string representing degrees.
	     * (four decimal numbers precision)
	     * @param arg0 String representing milli-degrees.
	     * @return
	     */
//	    private String formatMilliDegrees(int arg0){
//	    	String tmp = String.valueOf(arg0);
//	    	if (tmp == null || tmp.length()<7)
//	    		throw new IllegalArgumentException();
//	    	tmp = tmp.substring(0, tmp.length()-6)+ this.decNumberSeparator
//	    			+tmp.substring(tmp.length()-6, tmp.length()-2);
//	    	return tmp;
//	    }
	    
	    private String formatDistance(int arg0){
	    	int km = arg0/1000;
	    	int m = arg0 - km*1000;
	    	String tmp = km + " Km "+m+" m ";
	    	return tmp;
	    }
	    
	    private String formatType(String arg0){
	    	if (arg0 == null || arg0.length()<2)
	    		throw new IllegalArgumentException();
	    	String tmp = arg0.toLowerCase();
	    	tmp = tmp.replace(this.typeSeparator," ");
	    	tmp = tmp.substring(0, 1).toUpperCase()+tmp.substring(1, arg0.length());
	    	return tmp;
	    }
	    
	    private String formatDate(long arg0){
	    	 Calendar cal = Calendar.getInstance();
	         cal.setTimeInMillis(arg0);

	         String format = this.dateFormat;
	         SimpleDateFormat sdf = new SimpleDateFormat(format);
	         return sdf.format(cal.getTime());
	    }
    }

}
