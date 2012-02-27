package com.mymed.android.myjam.ui;

import java.util.ArrayList;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.ContentProviderOperation;
import android.content.ContentResolver;
import android.content.ContentValues;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.OperationApplicationException;
import android.content.SharedPreferences;
import android.database.ContentObserver;
import android.database.Cursor;
import android.location.Location;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.HandlerThread;
import android.os.Looper;
import android.os.RemoteException;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.ContextMenu;
import android.view.ContextMenu.ContextMenuInfo;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.CursorAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;


import com.google.android.maps.GeoPoint;
import com.mymed.android.myjam.R;
import com.mymed.android.myjam.controller.CallContract;
import com.mymed.android.myjam.controller.CallContract.CallCode;
import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.provider.MyJamContract;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.SearchResult;
import com.mymed.android.myjam.service.CallService;

import com.mymed.utils.GeoUtils;
import com.mymed.utils.GlobalStateAndUtils;
import com.mymed.utils.MyResultReceiver;
import com.mymed.utils.MyResultReceiver.IReceiver;

/**
 * Activity that permits to search reports.
 * @author iacopo
 *
 */
public class SearchActivity extends AbstractLocatedActivity implements IReceiver, View.OnClickListener {
	private static final String TAG = "SearchActivity";
	
	/** String used to access the set radius preference */
	private static final String RADIUS_PREFERENCE = "radius_preference";
	/** String used to access the filter preference */
	private static final String FILTER_PREFERENCE = "type_filter_preference";
	/** Search rate preference */
	private static final String SEARCH_RATE_PREFERENCE = "search_rate_preference";

	private static final int DISTANCE_UPDATE_TIME = 30000;
	
	private static final int INSERT_REPORT_REQ = 0x1;
	
	private ListView mList;
	
	/** Dialogs */
	static final int DIALOG_NO_LOCATION_ID = 0;
	
	private Handler mMessageQueueHandler = new Handler();
	
    private HandlerThread thread;
	private volatile Looper mThreadLooper;
	private Handler mRefreshDistanceHandler;
	
	MyResultReceiver mResultReceiver;

	private interface SearchReportsQuery{
		//TODO Add distance to order in a proper way.
		String[] PROJECTION = new String[] {
			Report.QUALIFIER+Report._ID + " AS _id",		//0
			Report.QUALIFIER+Report.REPORT_ID,				//1
			Report.QUALIFIER+Report.REPORT_TYPE,			//2
			Report.QUALIFIER+Report.DATE,					//3
			Report.QUALIFIER+Report.LATITUDE,				//4
			Report.QUALIFIER+Report.LONGITUDE,				//5
			SearchResult.QUALIFIER+SearchResult.DISTANCE,	//6
		};
		
		int REPORT_ID = 1;
		int REPORT_TYPE = 2;
		int DATE = 3;
		int LATITUDE = 4;
		int LONGITUDE = 5;
		int DISTANCE = 6;
	}
	
	private interface SearchQuery{
		//TODO Add distance to order in a proper way.
		String[] PROJECTION = new String[] {
			Search.DATE,									//0
			Search.SEARCHING								//1
		};	
	}
	
	/** Stores the informations contained in the intent that starts the activity. */
	private int mSearchId;
	
	/** Flag used to force a search even if the time elapsed since last search wouldn't be sufficient.*/
	private boolean mForceSearchFlag = false;
	
	private ProgressDialog mDialog;
	private SearchListAdapter mAdapter;
	private Button 	mSearchButton,
					mInsertButton,
					mMapButton;
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
		mSearchButton = (Button) findViewById(R.id.buttonSearch);
		mInsertButton = (Button) findViewById(R.id.buttonInsertReport);
		mMapButton = (Button) findViewById(R.id.buttonViewOnMap);
		mMapButton.setOnClickListener(this);
		if (mSearchId == Search.NEW_SEARCH){
			mSearchButton.setEnabled(false);			
			mSearchButton.setOnClickListener(this);
			mInsertButton.setOnClickListener(this);
			mMapButton.setOnClickListener(this);
		}else if(mSearchId == Search.INSERT_SEARCH){
			mSearchButton.setVisibility(View.GONE);
			mInsertButton.setVisibility(View.GONE);
		}
		
		// Perform a managed query. The Activity will handle closing and requerying the cursor
		// when needed.
		//Query to get the date of the last search.
		ContentResolver cr = getContentResolver();
		Cursor searchCursor = cr.query(Search.buildSearchUri(String.valueOf(mSearchId)),SearchQuery.PROJECTION, 
				null, null, null);
		if (searchCursor.moveToFirst())
			refreshDate(searchCursor);
		else{
			//Insert an entry corresponding to the search type, if not already present.
			ContentValues contVal = new ContentValues();
			contVal.put(Search.SEARCH_ID, mSearchId);
			contVal.put(Search.SEARCHING, false);
			cr.insert(Search.CONTENT_URI, contVal);
		}			
		searchCursor.close();		
	}
	
    private Runnable mSearchRunnable = new Runnable() {
        public void run() {
        	//If there is a search ongoing this search is skipped, but the next is normally scheduled.
    		Cursor searchCursor = getContentResolver().query(Search.buildSearchUri(String.valueOf(mSearchId)),SearchQuery.PROJECTION, 
    				null, null, null);
    		//Checks whether synch flag is set or not.
    		if (searchCursor.moveToFirst() && searchCursor.getInt(1)==0)
    			requestSearch(Search.NEW_SEARCH);
        	long nextSearchTime = Integer.parseInt(mSettings.getString(SEARCH_RATE_PREFERENCE,"600000"));
            mMessageQueueHandler.postDelayed(mSearchRunnable, nextSearchTime);
            searchCursor.close();
        }
    };
    
    /**
     *  TODO Check
     *  This runnable runs on a thread different from the UI one. It access concurrently the cursor used by the list adapter,
     *  problems may arise.
     *   
     */
    private Runnable mRefreshDistanceRunnable = new Runnable() {
        public void run() {
        	try {
        		ContentResolver resolver = getContentResolver();
        		Cursor cursor = mAdapter.getCursor();
        		Location currLoc;
        		if (cursor.moveToFirst() && mService!=null && (currLoc = mService.getCurrentLocation())!=null){
        			ArrayList<ContentProviderOperation> batch = new ArrayList<ContentProviderOperation> (cursor.getCount());
        			while (!cursor.isClosed() && !cursor.isAfterLast()){
        				String reportId = cursor.getString(SearchReportsQuery.REPORT_ID);
        				double dist = GeoUtils.getGCDistance(GeoUtils.toGeoPoint(currLoc),
        						new GeoPoint(cursor.getInt(SearchReportsQuery.LATITUDE),cursor.getInt(SearchReportsQuery.LONGITUDE)));
        				ContentValues currVal = new ContentValues();
        				currVal.put(SearchResult.DISTANCE, (int) dist);
        				batch.add(ContentProviderOperation.newUpdate(SearchResult.CONTENT_URI).withValues(currVal)
        						.withSelection(SearchResult.REPORT_SELECTION, new String[]{reportId}).build());
        				cursor.moveToNext();
        			}
						resolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
						resolver.notifyChange(SearchReports.buildSearchUri(String.valueOf(mSearchId)), null);
        		}
        		long nextUpdateDistanceTime = DISTANCE_UPDATE_TIME;//TODO Fix this.
        		mRefreshDistanceHandler.postDelayed(mRefreshDistanceRunnable, nextUpdateDistanceTime);
        	} catch (RemoteException e) {
        		Log.e(TAG, e.getMessage()!=null?"RefreshDistanceRunnable: "+e.getMessage():"RefreshDistanceRunnable: No messages.");
        	} catch (OperationApplicationException e) {
        		Log.e(TAG, e.getMessage()!=null?"RefreshDistanceRunnable: "+e.getMessage():"RefreshDistanceRunnable: No messages.");
        	} catch (Exception e){
        		Log.e(TAG, e.getMessage()!=null?"RefreshDistanceRunnable: "+e.getMessage():"RefreshDistanceRunnable: No messages.");
        	}
        }
    };

	
	@Override
	public void onClick(View v) {
		if (v.equals(mSearchButton)){
				mForceSearchFlag = true;
				postSearch();
		}else if (v.equals(mInsertButton)){
			Location currLoc;
        	if ((currLoc = this.mService.getCurrentLocation()) != null){
        		Intent intent = new Intent(this,InsertActivity.class);
        		intent.putExtra(InsertActivity.EXTRA_INSERT_TYPE, InsertActivity.REPORT);					
				int lat = (int) (currLoc.getLatitude()*1E6);
				int lon = (int) (currLoc.getLongitude()*1E6);
		        Bundle bundle = new Bundle();
		        bundle.putInt(CallContract.LATITUDE, lat);
		        bundle.putInt(CallContract.LONGITUDE, lon);
		        intent.putExtra(CallService.EXTRA_ATTRIBUTES, bundle);
        		startActivityForResult(intent, INSERT_REPORT_REQ);
        	}
        	else{
        		showDialog(DIALOG_NO_LOCATION_ID);
        	}
		}else if (v.equals(mMapButton)){
    		Intent intent = new Intent(this,ViewOnMapActivity.class);
    		Uri uri = getIntent().getData();
    		String filter =  mSettings.getString(FILTER_PREFERENCE,"NONE");
    		if (mSearchId == Search.NEW_SEARCH && !filter.equals("NONE"))
    			uri = SearchReports.buildSearchUri(String.valueOf(mSearchId), filter);
    		intent.setData(uri);
    		startActivity(intent);
		}
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data){
		super.onActivityResult(requestCode, resultCode, data);
		switch (requestCode){
			case INSERT_REPORT_REQ:
				if (resultCode == Activity.RESULT_OK)
					// If a new report has been successfully inserted by the user a new search is triggered.
					mForceSearchFlag = true;
				break;
		}
	}
	
	/**
	 * Requests a search operation to the {@link CallService}.
	 * @param lat		Latitude of the current position of the user.
	 * @param lon		Longitude of the current position of the user.
	 * @param radius	Radius of the search.
	 * @param searchId	Identifier of the search, used to perform the query and show the right results.
	 */
	private void requestSearch(int searchId){
		final Intent intent = new Intent(SearchActivity.this, CallService.class);
		Location currLoc;
		
		if (mService != null && (currLoc = mService.getCurrentLocation())!=null){					
			int lat = (int) (currLoc.getLatitude()*1E6);
			int lon = (int) (currLoc.getLongitude()*1E6);
			int radius = Integer.parseInt(mSettings.getString(RADIUS_PREFERENCE,"10000"));
			intent.putExtra(CallService.EXTRA_ACTIVITY_ID, this.getClass().getName());
			intent.putExtra(CallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
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
		mMessageQueueHandler.removeCallbacks(mSearchRunnable);
		mRefreshDistanceHandler.removeCallbacks(mRefreshDistanceRunnable);
		mThreadLooper.quit();
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
		mResultReceiver.setReceiver(this.getClass().getName(),this);
		getContentResolver().registerContentObserver(
				uri,false, mSearchChangesObserver);
		//TODO Enable or disable search button.
		if (mService!=null && mService.getCurrentLocation()!=null){
				handleSearchButton(true);
				postSearch();
		}
		//** Starts a new thread to where the distance refresh is executed. */ 
		thread = new HandlerThread(TAG);
		thread.start();
		mThreadLooper = thread.getLooper();
		mRefreshDistanceHandler= new Handler(mThreadLooper);
		mRefreshDistanceHandler.postDelayed(mRefreshDistanceRunnable, DISTANCE_UPDATE_TIME);
	}
	
	/** Checks the time of last search, if a search has been done at a time t greater then (actual_time - refresh_period) the search is executed immediately. */
	private void postSearch(){
		Cursor searchCursor = getContentResolver().query(Search.buildSearchUri(String.valueOf(mSearchId)),SearchQuery.PROJECTION, 
				null, null, null);
		// If mForceSearch flag is not set and the last search record is available.
		if (!mForceSearchFlag && searchCursor.moveToFirst()){
			long lastSearch = searchCursor.getLong(0);			
			long nextSearchTime = Math.max(0, lastSearch + Integer.parseInt(mSettings.getString(SEARCH_RATE_PREFERENCE,"600000")) - System.currentTimeMillis());
            mMessageQueueHandler.postDelayed(mSearchRunnable, nextSearchTime);
		}else{
			mMessageQueueHandler.removeCallbacks(mSearchRunnable);
			mMessageQueueHandler.post(mSearchRunnable);
			//Maybe that the search is done immediately because mForceSearch flag was true.
			mForceSearchFlag = false;
		}
		searchCursor.close();
	}
	
    private ContentObserver mSearchChangesObserver = new ContentObserver(new Handler()) {
        @Override
        public void onChange(boolean selfChange) {
            /** A synchronous query is launched. */
            Cursor cursor = getContentResolver().query(Search.buildSearchUri(String.valueOf(mSearchId)),SearchQuery.PROJECTION, 
        			null, null, null);
        	refreshDate(cursor);
        	cursor.close();
        }
    };
    
    /**
     * Refresh the date of the current search.
     * @param cursor
     */
    private void refreshDate(Cursor cursor){
    	if (cursor!= null && cursor.moveToFirst())
			mLastUpdateTextView.setText(GlobalStateAndUtils.getInstance(this)
					.formatDate(cursor.getLong(0)));
    }
	

	@Override
	protected void onLocServiceConnected() {
		if (mService.getCurrentLocation() == null)
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
		postSearch();
	}

	@Override
	/** If the location is no more available the search button is disabled. */
	protected void onLocationNoMoreAvailable() {
		mMessageQueueHandler.removeCallbacks(mSearchRunnable);
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
        inflater.inflate(R.menu.preference_menu, menu);
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

		        Intent intent = new Intent(SearchActivity.this,ViewOnMapActivity.class);
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
        	mDialog = ProgressDialog
			.show(SearchActivity.this, "", 
					getResources().getString(R.string.searching_reports_msg), true);
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
    
    protected Dialog onCreateDialog(int id) {
        Dialog dialog;
        switch(id) {
        case DIALOG_NO_LOCATION_ID:
    		AlertDialog.Builder builder = new AlertDialog.Builder(this);
    		builder.setMessage(getResources().getString(R.string.loc_not_available_dialog_message))
    			.setCancelable(false)       	
    			.setTitle(R.string.dialog_title)
    			.setIcon(R.drawable.myjam_icon)
    			.setPositiveButton(R.string.positive_button_label, new DialogInterface.OnClickListener() {
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
        case R.id.preferences:        	
        	startActivity(new Intent(this,MyPreferenceActivity.class));
        	break;
        }
        
        return false;
    }
    

	@Override
	public void onReceiveResult(int resultCode, Bundle resultData) {
//		boolean mSyncing = false;
//		
//		switch (resultCode) {
//		case HttpCallHandler.MSG_CALL_START:
//			mSyncing = true;
//			break;
//		case HttpCallHandler.MSG_CALL_SUCCESS: 
//			mSyncing = false;
//			Toast.makeText(this, getResources().getString(R.string.search_finished),Toast.LENGTH_LONG).show();
//			break;
//		case HttpCallHandler.MSG_CALL_ERROR:
//			// Error happened down in SyncService, show as toast.
//			mSyncing = false;
//			//
//			
//			String errMsg = resultData.getString(Intent.EXTRA_TEXT);
//			final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
//			Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
//			break;
//		}
//
//	updateRefreshStatus(mSyncing);
	}

	
	/**
	 * Adapter that prepares the data for the List.
	 * @author iacopo
	 *
	 */
    private static class SearchListAdapter extends CursorAdapter {

		private final LayoutInflater mInflater;		
		private final GlobalStateAndUtils mUtils;
		
        public SearchListAdapter(Context context, Cursor c, boolean autoRequery) {
			super(context, c, autoRequery);
			mInflater = LayoutInflater.from(context);
			/** Create a GlobalStateAndUtils instance. */
			mUtils = GlobalStateAndUtils.getInstance(context);
		}

		@Override
		public void bindView(View view, Context context, Cursor cursor) {
			TextView typeTextView = (TextView)view.findViewById(R.id.textType);
			TextView distanceTextView = (TextView)view.findViewById(R.id.textDistance);
			TextView dateTextView = (TextView)view.findViewById(R.id.textDate);
			ImageView iconImageView = (ImageView)view.findViewById(R.id.imageViewSearchIcon);
			String type = cursor.getString(SearchReportsQuery.REPORT_TYPE);
			int distance = cursor.getInt(SearchReportsQuery.DISTANCE);
			long date = cursor.getLong(SearchReportsQuery.DATE);
			typeTextView.setText(mUtils.formatType(type));
			distanceTextView.setText(mUtils.formatDistance(distance,50));
			dateTextView.setText(mUtils.formatDate(date));
			iconImageView.setImageResource(GlobalStateAndUtils.getInstance(context).getIconByReportType(type));
		}


	    @Override
	    public View newView(Context context, Cursor cursor, ViewGroup parent) {
	      final View view = mInflater.inflate(R.layout.search_report_item, parent, false);
	      return view;

	    }
    }


	@Override
	public void onUpdateProgressStatus(boolean state, int callCode, int callId) {
		updateRefreshStatus(state);
	}

	@Override
	public void onCallError(int callCode, int callId, String errorMessage,
			int numAttempt, int maxAttempts) {
		final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errorMessage));
		Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
	}

	@Override
	public void onCallInterrupted(int callCode, int callId) {}

	@Override
	public void onCallSuccess(int callCode, int callId) {
		Toast.makeText(this, getResources().getString(R.string.search_finished),Toast.LENGTH_LONG).show();
	}

	@Override
	public void onServiceDestroyed() {
		// TODO Auto-generated method stub
		
	}
}
