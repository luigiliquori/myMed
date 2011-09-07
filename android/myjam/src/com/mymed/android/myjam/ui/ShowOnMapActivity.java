package com.mymed.android.myjam.ui;

import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.content.UriMatcher;
import android.database.Cursor;
import android.graphics.drawable.Drawable;
import android.location.Location;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.os.SystemClock;
import android.util.Log;

import com.google.android.maps.GeoPoint;
import com.google.android.maps.MapActivity;
import com.google.android.maps.MapController;
import com.google.android.maps.MapView;
import com.mymed.android.myjam.R;
import com.mymed.android.myjam.provider.MyJamContract;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.service.LocationService;
import com.mymed.android.myjam.service.LocationService.LocalBinder;
import com.mymed.utils.GeoUtils;
import com.mymed.utils.GlobalStateAndUtils;

/**
 * Shows the reports position on map. 
 * @author iacopo
 *
 */
public class ShowOnMapActivity extends MapActivity {
	private static final String TAG = "MapActivity";
    
	/** Position of the fields inside the cursor. */
	private static final int REPORT_ID = 0;
	private static final int REPORT_TYPE = 1;
	private static final int LATITUDE = 2;
	private static final int LONGITUDE = 3;
	
	/** modeId */
	private static final int REPORT = 0;
    private static final int SEARCH = 1;
    
    private static final int ONE_SECOND = 1000;
    
	LocationService mService;
	MapView mMapView;
	MapController mMapController;
	MyJamItemizedOverlay mItemizedOverlay;
	private Handler mMessageQueueHandler = new Handler();

	boolean mBound = false;
	
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
	    super.onCreate(savedInstanceState);
	    setContentView(R.layout.map_view);
	    mMapView = (MapView) findViewById(R.id.mapview);
        mMapView.setStreetView(true);//setting this to true causes blue lines around roads
        mMapView.invalidate();
        mMapView.setBuiltInZoomControls(true);
        mMapController = mMapView.getController();
	    
	    Drawable drawable = this.getResources().getDrawable(R.drawable.user_location_available);
        mItemizedOverlay = new MyJamItemizedOverlay(drawable,this);
        
	    Intent intent = getIntent();
		/** If no URI has been specified in the intent the activity is closed.*/
		Uri uri = null;
		if ((uri = intent.getData())==null)
			finish();
		switch (sURIMatcher.match(uri)){
		case REPORT:
			insertReportOverlay(uri);
			break;
		case SEARCH:
			insertSearchOverlay(uri);
			break;
		default:
			Log.e(TAG,"Unknon data URI.");
			finish();
			break;
		}
	}
	
    private Runnable mRefreshUserPositionRunnable = new Runnable() {
        public void run() {
        	if (mBound && mService.ismLocAvailable()){
        		Location loc = mService.getCurrentLocation();
        		GeoPoint userPos = GeoUtils.toGeoPoint(loc);
        		mItemizedOverlay.addOverlay(userPos, GlobalStateAndUtils.USER_POSITION, null);
        		mMapView.getOverlays().clear();
        		mMapView.getOverlays().add(mItemizedOverlay);
        		mMapView.invalidate();
        	}
            // The runnable starts again after 5 minutes
            long nextSecond = SystemClock.uptimeMillis() + ONE_SECOND; //TODO Use a setting. 
            mMessageQueueHandler.postAtTime(mRefreshUserPositionRunnable, nextSecond);
        }
    };
	
	private void insertSearchOverlay(Uri uri){
		Cursor reportsCursor = managedQuery(uri,SearchReportsQuery.PROJECTION,null,null,SearchReports.DEFAULT_SORT_ORDER);
		String searchId = SearchReports.getSearchId(uri);
		Cursor searchCursor = managedQuery(Search.buildSearchUri(searchId),SearchQuery.PROJECTION,null,null,null);
		if (searchCursor.moveToFirst() && reportsCursor.moveToFirst()){
			int centerLat = searchCursor.getInt(SearchQuery.LATITUDE);
			int centerLon = searchCursor.getInt(SearchQuery.LONGITUDE);
			int radius = searchCursor.getInt(SearchQuery.RADIUS);
			GeoPoint searchCenter = new GeoPoint(centerLat,
					centerLon);
			mItemizedOverlay.addFigure(
					GeoUtils.getCircle(searchCenter, 
							radius));
			addOverlays(reportsCursor);
			GeoPoint[] boundingBoxPoints = GeoUtils.getBoundingBox(searchCenter, radius);
			int deltaLat = Math.abs(boundingBoxPoints[1].getLatitudeE6() - boundingBoxPoints[2].getLatitudeE6());
	        int deltaLon = Math.abs(boundingBoxPoints[0].getLongitudeE6() - boundingBoxPoints[1].getLongitudeE6());
	        if (deltaLon>180*1E6)
	        	deltaLon = (int)(360*1E6) - deltaLon;
			mMapView.getOverlays().add(mItemizedOverlay);
			mMapController.zoomToSpan(deltaLat, deltaLon);
			mMapController.animateTo(searchCenter);
		}else
			finish();
	}
	
	private void insertReportOverlay(Uri uri){
		Cursor reportsCursor = managedQuery(uri,ReportQuery.PROJECTION,null,null,null);
		if (reportsCursor.moveToFirst()){
			GeoPoint reportPos = addOverlays(reportsCursor);
			mMapView.getOverlays().add(mItemizedOverlay);
			mMapController.setZoom(20);
			mMapController.animateTo(reportPos);
		}else
			finish();
	}

	@Override
	protected boolean isRouteDisplayed() {
		// TODO Auto-generated method stub
		return false;
	}
	
    /** Receives the availability status of the location.*/
	private BroadcastReceiver locationUpdateReceiver = new BroadcastReceiver() {
		@Override
		public void onReceive(Context context, Intent intent) {			
			switch (intent.getIntExtra(LocationService.EXTRA_ACTION_CODE, -1)){
			case LocationService.LOCATION_AVAILABLE:
				//onLocationAvailable();
				break;
			case LocationService.LOCATION_NO_MORE_AVAILABLE:
				//onLocationNoMoreAvailable();
				break;
			}
		}
	};
	
	/**
	 * Adds the overlays to {@link mItemizedOverlay} and return the last position.
	 * @param cursor Cursor pointing to the reports (or report).
	 */
	private GeoPoint addOverlays(Cursor cursor){
		GeoPoint position=null;
		GlobalStateAndUtils utilsInstance = GlobalStateAndUtils.getInstance(getApplicationContext());
		String[] reportTypeVal = getResources().getStringArray(R.array.report_typevalues_list);
		
		if (cursor!=null && cursor.moveToFirst()){
			int typeId;
			while (!cursor.isAfterLast()){
				position = new GeoPoint(cursor.getInt(LATITUDE),cursor.getInt(LONGITUDE));
				typeId = utilsInstance.getStringArrayValueIndex(
						reportTypeVal, 
						cursor.getString(REPORT_TYPE));
				mItemizedOverlay.addOverlay(position, typeId, cursor.getString(REPORT_ID));
				cursor.moveToNext();
			}
		}
		return position;
	}
	
    @Override
    protected void onStart() {
        super.onStart();
        // Bind to LocationService
		Intent intent = new Intent(this, LocationService.class);
		bindService(intent, mConnection, Context.BIND_AUTO_CREATE);
    }
    
	@Override
	protected void onStop() {
		super.onStop();
		if (mBound) {
			unbindService(mConnection);
			mBound = false;
		}
	}
	
	/**
	 * Called when another component comes in front of this activity.
	 */
	@Override 
	protected void onPause() {
		super.onPause();
		unregisterReceiver(locationUpdateReceiver);
		mMessageQueueHandler.removeCallbacks(mRefreshUserPositionRunnable);
	}

	/**
	 * The activity comes to the foreground.
	 */
	@Override 
	protected void onResume() {
		super.onResume();
		IntentFilter filter = new IntentFilter();
  	  	filter.addAction(LocationService.LOCATION_ACTION);
		registerReceiver(locationUpdateReceiver,filter);
		mMessageQueueHandler.post(mRefreshUserPositionRunnable);
	}
		
	/** Defines callbacks for service binding, passed to bindService() */
	private ServiceConnection mConnection = new ServiceConnection(){
		
		@Override
		public void onServiceConnected(ComponentName className,
				IBinder service) {
			//TODO We've bound to LocalService, cast the IBinder and get LocalService instance
			@SuppressWarnings("unchecked")
			LocalBinder<LocationService> binder = (LocalBinder<LocationService>) service;
			mService = binder.getService();
			//onLocServiceConnected();
			mBound = true;
			Log.d(TAG,"LocationService connected.");
		}

		@Override
		public void onServiceDisconnected(ComponentName arg0) {
			mBound = false;
			//TODO onLocServiceDisconnected();
			Log.d(TAG,"LocationService disconnected.");
		}
	};
	
	/**
     * Parameters used to perform the report query.
     */
    private interface ReportQuery {

        String[] PROJECTION = {
        		Report.REPORT_ID,
                Report.REPORT_TYPE,
                Report.LATITUDE,
                Report.LONGITUDE,
        };
    }
    
	private interface SearchReportsQuery{
		//TODO Add distance to order in a proper way.
		String[] PROJECTION = new String[] {
			Report.QUALIFIER+Report.REPORT_ID,				//0
			Report.QUALIFIER+Report.REPORT_TYPE,			//1
			Report.QUALIFIER+Report.LATITUDE,				//2
			Report.QUALIFIER+Report.LONGITUDE,				//3

		};	
	}
	
	private interface SearchQuery{
		//TODO Add distance to order in a proper way.
		String[] PROJECTION = new String[] {
			Search.LATITUDE,
			Search.LONGITUDE,
			Search.RADIUS
		};
		
		int LATITUDE = 0;
		int LONGITUDE = 1;
		int RADIUS = 2;
	}
	
	private static final UriMatcher sURIMatcher = new UriMatcher(UriMatcher.NO_MATCH);
		static
	    {
			sURIMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_reports/*", SEARCH);
			sURIMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_reports/*/*", SEARCH);
	    	sURIMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "reports/*", REPORT);
	    }
	

}
