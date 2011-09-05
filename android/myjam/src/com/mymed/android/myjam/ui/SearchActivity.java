package com.mymed.android.myjam.ui;

import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
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
import android.view.ContextMenu;
import android.view.ContextMenu.ContextMenuInfo;
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
import com.mymed.android.myjam.controller.ICallAttributes;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.SearchResult;
import com.mymed.android.myjam.service.MyJamCallService;
import com.mymed.android.myjam.service.MyJamCallService.RequestCode;

import com.mymed.utils.GlobalVarAndUtils;
import com.mymed.utils.MyResultReceiver;

/**
 * Activity that permits to search reports.
 * @author iacopo
 *
 */
public class SearchActivity extends AbstractLocatedActivity implements MyResultReceiver.Receiver {
	private static final String TAG = "SearchActivity";
	
	/** String used to access the set radius preference */
	private static final String RADIUS_PREFERENCE = "radius_preference";
	/** String used to access the filter preference */
	private static final String FILTER_PREFERENCE = "type_filter_preference";
	private ListView mList;
	
	/** Dialogs */
	static final int DIALOG_NO_LOCATION_ID = 0;
	
	MyResultReceiver mResultReceiver;

	private interface SearchReportsQuery{
		//TODO Add distance to order in a proper way.
		String[] PROJECTION = new String[] {
			Report.QUALIFIER+Report._ID + " AS _id",		//0
			Report.QUALIFIER+Report.REPORT_ID,				//1
			Report.QUALIFIER+Report.REPORT_TYPE,			//2
			Report.QUALIFIER+Report.DATE,					//3
			SearchResult.QUALIFIER+SearchResult.DISTANCE,	//4
		};
		
		int REPORT_ID = 1;
		int REPORT_TYPE = 2;
		int DATE = 3;
		int DISTANCE = 4;
	}
	
	private interface SearchQuery{
		//TODO Add distance to order in a proper way.
		String[] PROJECTION = new String[] {
			Search.DATE									//0
		};	
	}
	
	/** Stores the informations contained in the intent that starts the activity. */
	private int mSearchId;
	
	private ProgressDialog mDialog;
	private SearchListAdapter mAdapter;
	private Button mSearchButton;
	private TextView mLastUpdateTextView;
	private SharedPreferences mSettings;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.search_view);
		
		mSettings = PreferenceManager.getDefaultSharedPreferences(this);
		// If no data was given in the intent (because we were started
		// as a MAIN activity), then use our default content provider.
		Intent intent = getIntent();
		Uri uri = intent.getData();
		if (uri == null) {
			uri = SearchReports.buildSearchUri(String.valueOf(Search.NEW_SEARCH));
			intent.setData(uri);
			mSearchId = Search.NEW_SEARCH;
		}else{
			try{
				mSearchId = Integer.parseInt(SearchReports.getSearchId(uri));
			}catch(NumberFormatException nfe){
				Log.e(TAG, "Wrong search id. ");
				finish();
			}
		}
		
		mList = (ListView) findViewById(R.id.SearchList);	
		/** Enable the context menu. */
		registerForContextMenu(mList);
		/** Sets the handler for the action click on the list items. */
		mList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
			@Override
		    public void onItemClick(AdapterView<?> l, View v, int position, long id){
		    	final Cursor cursor = (Cursor)mAdapter.getItem(position);
		        final String reportId = cursor.getString(1);

		        Intent intent = new Intent(SearchActivity.this,ReportDetailsActivity.class);
		        Uri uri = Report.buildReportIdUri(reportId);
		        intent.setData(uri);   	
		        startActivity(intent);
		    }
		});
		mLastUpdateTextView = (TextView) findViewById(R.id.textViewSearchDate);
		setDefaultKeyMode(DEFAULT_KEYS_SHORTCUT);
		mResultReceiver = MyResultReceiver.getInstance();
		mSearchButton = (Button) findViewById(R.id.SearchButton);
		if (mSearchId == Search.NEW_SEARCH){
			mSearchButton.setEnabled(false);
			
			mSearchButton.setOnClickListener(new View.OnClickListener() {
				public void onClick(View v) {
					/*
					* 	The service is started, if it's not, and a search report is performed.
					*/
					try{
						Location currLoc = mService.getCurrentLocation();					
						int lat = (int) (currLoc.getLatitude()*1E6);
						int lon = (int) (currLoc.getLongitude()*1E6);
						int radius = Integer.parseInt(mSettings.getString(RADIUS_PREFERENCE,"10000"));
						requestSearch(lat,lon,radius,Search.NEW_SEARCH);
					} catch (Exception e){
						Toast.makeText(SearchActivity.this, "Wrong parameters.",Toast.LENGTH_LONG).show();
					}
				}
			});
		}else if(mSearchId == Search.INSERT_SEARCH){
			mSearchButton.setEnabled(false);
			mSearchButton.setVisibility(View.GONE);
		}
		
		// Perform a managed query. The Activity will handle closing and requerying the cursor
		// when needed.
		//Query to get the date of the last search.
		Cursor searchCursor = managedQuery(Search.buildSearchUri(String.valueOf(mSearchId)),SearchQuery.PROJECTION, 
				null, null, null);
		if (searchCursor!= null && searchCursor.moveToFirst())
			refreshDate(searchCursor);
	} 
	
	/**
	 * Requests a search operation to the {@link MyJamCallService}.
	 * @param lat		Latitude of the current position of the user.
	 * @param lon		Longitude of the current position of the user.
	 * @param radius	Radius of the search.
	 * @param searchId	Identifier of the search, used to perform the query and show the right results.
	 */
	private void requestSearch(int lat,int lon,int radius, int searchId){
		final Intent intent = new Intent(SearchActivity.this, MyJamCallService.class);
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
	
	/**
	 * Enable and disable the search button, if the activity is on {@link SEARCH mode}
	 * @param enabled If {@value true} enable the button, if {@link false} disable it.
	 */
	private void handleSearchButton(boolean enabled){
		if (mSearchId == Search.NEW_SEARCH)
			mSearchButton.setEnabled(enabled);
	}

	/**
	 * Called when another component comes in front of this activity.
	 */
	@Override 
	protected void onPause() {
		super.onPause();
		mResultReceiver.clearReceiver();
		//TODO Enable or disable search button.
		getContentResolver().unregisterContentObserver(mSearchChangesObserver);
	}

	/**
	 * The activity comes to the foreground.
	 */
	@Override 
	protected void onResume() {
		super.onResume();
		String filter =  mSettings.getString(FILTER_PREFERENCE,"NONE");
		Uri uri = getIntent().getData();
		Cursor cursor = managedQuery(uri, SearchReportsQuery.PROJECTION, 
				filter.equals("NONE")?null:SearchReports.selectByType(1), //If some filter is selected, the selection is applied.
						filter.equals("NONE")?null:new String[]{filter},
								SearchReports.DEFAULT_SORT_ORDER);
		// Used to map notes entries from the database to views
		mAdapter = new SearchListAdapter(this,cursor,true);
		mList.setAdapter(mAdapter);
		mResultReceiver.setReceiver(this);
		getContentResolver().registerContentObserver(
				uri,false, mSearchChangesObserver);
		//TODO Enable or disable search button.
		if (this.mService!=null){
			if (mService.ismLocAvailable())
				handleSearchButton(true);
		}
		updateRefreshStatus(mResultReceiver.ismSyncing());
	}
	
    private ContentObserver mSearchChangesObserver = new ContentObserver(new Handler()) {
        @Override
        public void onChange(boolean selfChange) {
            /** A synchronous query is launched. */
            Cursor cursor = managedQuery(Search.buildSearchUri(String.valueOf(mSearchId)),SearchQuery.PROJECTION, 
        			null, null, null);
        	refreshDate(cursor);
        }
    };
    
    /**
     * Refresh the date of the current search.
     * @param cursor
     */
    private void refreshDate(Cursor cursor){
    	if (cursor!= null && cursor.moveToFirst())
			mLastUpdateTextView.setText(GlobalVarAndUtils.getInstance(this)
					.formatDate(cursor.getLong(0)));
    }
	

	@Override
	protected void onLocServiceConnected() {
		if (!this.mService.ismLocAvailable())
			handleSearchButton(false);
	}

	@Override
	protected void onLocServiceDisconnected() {}
	
	@Override
	/** If the location is available the search button is enabled. */
	protected void onLocationAvailable() {
		handleSearchButton(true);
		Toast.makeText(SearchActivity.this, 
				getResources().getString(R.string.location_available), Toast.LENGTH_LONG).show();
	}

	@Override
	/** If the location is no more available the search button is disabled. */
	protected void onLocationNoMoreAvailable() {
		handleSearchButton(false);
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
	
    @Override
    public void onCreateContextMenu(ContextMenu menu, View view, ContextMenuInfo menuInfo) {
    	super.onCreateContextMenu(menu, view, menuInfo);

        // Inflate context menu from XML
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.search_context_menu, menu);
    }
    
    @Override
    public boolean onContextItemSelected(MenuItem item) {
        AdapterView.AdapterContextMenuInfo info;
        try {
             info = (AdapterView.AdapterContextMenuInfo) item.getMenuInfo();
        } catch (ClassCastException e) {
            Log.e(TAG, "bad menuInfo", e);
            return false;
        }

        switch (item.getItemId()) {
            case R.id.view_on_map: {
                // Shows the currently selected item on map.
		    	final Cursor cursor = (Cursor)mAdapter.getItem(info.position);
		        final String reportId = cursor.getString(SearchReportsQuery.REPORT_ID);

		        Intent intent = new Intent(SearchActivity.this,ShowOnMapActivity.class);
		        Uri uri = Report.buildReportIdUri(reportId);
		        intent.setData(uri);   	
		        startActivity(intent);
                return true;
            }
        }
        return false;
    }
	
	/**
	 * Makes appear and disappear the progress dialog.
	 * @param refreshing 	If true then the dialog appear, 
	 * 						if false the dialog disappear.
	 */
    private void updateRefreshStatus(boolean refreshing) {
        if (refreshing){
        	mDialog = ProgressDialog.show(SearchActivity.this, "", 
					getResources().getString(R.string.searching_reports_msg), true);
        }else{
			if (mDialog != null)
				mDialog.dismiss();
        }
        	
    }
    
    protected Dialog onCreateDialog(int id) {
        Dialog dialog;
        switch(id) {
        case DIALOG_NO_LOCATION_ID:
    		AlertDialog.Builder builder = new AlertDialog.Builder(this);
    		builder.setMessage(getResources().getString(R.string.loc_not_available_dialog_message))
    			.setCancelable(false)       	
    			.setTitle(getResources().getString(R.string.dialog_title))
    			.setPositiveButton(getResources().getString(R.string.positive_button_label), new DialogInterface.OnClickListener() {
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
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
        case R.id.insert_report:
        	if (this.mService.ismLocAvailable()){
        		Intent intent = new Intent(this,InsertActivity.class);
        		intent.putExtra(InsertActivity.EXTRA_INSERT_TYPE, InsertActivity.REPORT);
        		Location currLoc = mService.getCurrentLocation();					
				int lat = (int) (currLoc.getLatitude()*1E6);
				int lon = (int) (currLoc.getLongitude()*1E6);
		        Bundle bundle = new Bundle();
		        bundle.putInt(ICallAttributes.LATITUDE, lat);
		        bundle.putInt(ICallAttributes.LONGITUDE, lon);
		        intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);
        		startActivity(intent);
        	}
        	else{
        		showDialog(DIALOG_NO_LOCATION_ID);
        	}
        	break;
        case R.id.view_on_map:
    		Intent intent = new Intent(this,ShowOnMapActivity.class);
    		Uri uri = getIntent().getData();
    		String filter =  mSettings.getString(FILTER_PREFERENCE,"NONE");
    		if (mSearchId == Search.NEW_SEARCH && !filter.equals("NONE"))
    			uri = SearchReports.buildSearchUri(String.valueOf(mSearchId), filter);
    		intent.setData(uri);
    		startActivity(intent);
        	break;
        case R.id.preferences:        	
        	startActivity(new Intent(this,MyPreferenceActivity.class));
        	break;
        }
        
        return false;
    }
    

	@Override
	public void onReceiveResult(int resultCode, Bundle resultData) {
		boolean mSyncing = false;
		
		switch (resultCode) {
		case MyJamCallService.STATUS_RUNNING:
			mSyncing = true;
			break;
		case MyJamCallService.STATUS_FINISHED: 
			mSyncing = false;
			Toast.makeText(this, getResources().getString(R.string.search_finished),Toast.LENGTH_LONG).show();
			break;
		case MyJamCallService.STATUS_ERROR:
			// Error happened down in SyncService, show as toast.
			mSyncing = false;
			String errMsg = resultData.getString(Intent.EXTRA_TEXT);
			final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
			Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
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

		private final LayoutInflater mInflater;		
		private final GlobalVarAndUtils mUtils;
		
        public SearchListAdapter(Context context, Cursor c, boolean autoRequery) {
			super(context, c, autoRequery);
			mInflater = LayoutInflater.from(context);
			/** Create a GlobalStateAndUtils instance. */
			mUtils = GlobalVarAndUtils.getInstance(context);
		}

		@Override
		public void bindView(View view, Context context, Cursor cursor) {
			TextView typeTextView = (TextView)view.findViewById(R.id.textType);
			TextView distanceTextView = (TextView)view.findViewById(R.id.textDistance);
			TextView dateTextView = (TextView)view.findViewById(R.id.textDate);
			String type = cursor.getString(SearchReportsQuery.REPORT_TYPE);
			int distance = cursor.getInt(SearchReportsQuery.DISTANCE);
			long date = cursor.getLong(SearchReportsQuery.DATE);
			typeTextView.setText(mUtils.formatType(type));
			distanceTextView.setText(mUtils.formatDistance(distance));
			dateTextView.setText(mUtils.formatDate(date));
		}


	    @Override
	    public View newView(Context context, Cursor cursor, ViewGroup parent) {
	      final View view = mInflater.inflate(R.layout.search_report_item, parent, false);
	      return view;

	    }
    }
}
