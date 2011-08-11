package com.mymed.android.myjam.service;

import java.lang.ref.WeakReference;

import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Binder;
import android.os.Bundle;
import android.os.HandlerThread;
import android.os.IBinder;
import android.os.Looper;
import android.util.Log;
/**
 * 
 * @author iacopo
 *TODO Check the logic.
 */
public class LocationService extends Service implements LocationListener{
	private static final String TAG = "MyJamLocationListener";
	
    HandlerThread thread;
	private volatile Looper mServiceLooper;
	
	/** The intent used to bind the service with one activity, contains a parameter that specify the precision required. */
    public static final String EXTRA_PRECISION =
            "com.mymed.android.myjam.extra.PRECISION";
    public static final int STANDARD = 0;
    public static final int FINE = 1;
    
    public static final int MINIMUM_FINE_ACCURACY = 50;	//Minimum accuracy of the new location. (Only in FINE mode).
    
	/**Parameters used for broadcast intent.*/
	public static final String LOCATION_ACTION = "com.mymed.android.myjam.intent.action.LOCATION_CHANGE";
    public static final String EXTRA_ACTION_CODE =
            "com.mymed.android.myjam.extra.ACTION_CODE";
    public static final int LOCATION_AVAILABLE = 0;
    public static final int LOCATION_NO_MORE_AVAILABLE = 1;
    /**Private parameters.*/
    private boolean mLocAvailable;
	private int mMode;
	private final IBinder mBinder = new LocalBinder<LocationService>(this);
	
    // This is the object that receives interactions from clients.  See
    // RemoteService for a more complete example.
    
	LocationManager locationManager;
	private Location currLocation = null;
	private static final int TWO_MINUTES = 1000 * 60 * 2;
	private static final long MIN_TIME_FINE = 1000 * 10; /** Minimum time in milliseconds between two updates. */
	private static final float MIN_SPACE_FINE = 2.5f; /** Minimum distance change in meter to trigger an update. */

	@Override
	public void onCreate(){
		super.onCreate();
		/** Local attributes initialization. */
		mLocAvailable = false;
		mMode = STANDARD;
		thread = new HandlerThread(TAG);
		thread.start();

		mServiceLooper = thread.getLooper();

		locationManager = (LocationManager) this.getSystemService(Context.LOCATION_SERVICE);
		locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, MIN_TIME_FINE, MIN_SPACE_FINE, this, mServiceLooper);
		locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, TWO_MINUTES, MIN_SPACE_FINE, this, mServiceLooper);
		if (locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER))
			Log.d(TAG, "Network provider available.");
		else
			Log.i(TAG, "Network provider unavailable.");		
		if (locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)){
			Log.d(TAG, "GPS provider available.");
		}
		else{
			Log.d(TAG, "GPS provider unavailable.");	
		}	
	}

	@Override
	public void onDestroy(){
		super.onDestroy();
		locationManager.removeUpdates(this);
		mServiceLooper.quit();
	}

	@Override
	public void onLocationChanged(Location arg0) {
		//TODO Check the scope of this intent.
		if (mMode == STANDARD){
			if(!mLocAvailable){
				mLocAvailable = true;
				Intent intent = new Intent(LOCATION_ACTION);
				intent.putExtra(EXTRA_ACTION_CODE, LOCATION_AVAILABLE);
				sendBroadcast(intent);
			}
			if (isBetterLocation(arg0))
				this.currLocation = arg0;
		}else if (mMode == FINE && arg0.getProvider() == LocationManager.GPS_PROVIDER){
			this.currLocation = arg0;
		}

	}

	public void onLocationExpired() {
		mLocAvailable = false;
		Intent intent = new Intent(LOCATION_ACTION);
		intent.putExtra(EXTRA_ACTION_CODE, LOCATION_NO_MORE_AVAILABLE);
		sendBroadcast(intent);
		Log.i(TAG, "Current location expired  disabled.");
	}

	@Override
	public void onProviderDisabled(String provider) {
		Log.i(TAG, provider + " disabled.");
	}

	@Override
	public void onProviderEnabled(String provider) {
		Log.i(TAG, provider + " enabled.");
	}
	@Override
	public void onStatusChanged(String provider, int status, Bundle extras) {}

	@Override
	public IBinder onBind(Intent intent) {
		intent.getIntExtra(EXTRA_PRECISION, 0);
		if (intent.getIntExtra(EXTRA_PRECISION, 0) == FINE){
			mLocAvailable = false;
			locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, this, mServiceLooper);
			Intent broadcastIntent = new Intent(LOCATION_ACTION);
			intent.putExtra(EXTRA_ACTION_CODE, LOCATION_NO_MORE_AVAILABLE);
			sendBroadcast(broadcastIntent);
		}else{
			locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, TWO_MINUTES, MIN_SPACE_FINE, this, mServiceLooper);
		}
		return mBinder;
	}

	public class LocalBinder<S> extends Binder {
		private  WeakReference<S> mService;

		public LocalBinder(S service){
			mService = new WeakReference<S>(service);
		}

		public S getService() {
			return mService.get();
		}
	}

	public Location getCurrentLocation(){
		return currLocation;
	}

	public boolean ismLocAvailable() {
		return mLocAvailable;
	}

	public void setmLocAvailable(boolean mLocAvailable) {
		this.mLocAvailable = mLocAvailable;
	}

	/** Determines whether one Location reading is better than the current Location fix
	 * @param location  The new Location that you want to evaluate
	 * @param currentBestLocation  The current Location fix, to which you want to compare the new one
	 */
	protected boolean isBetterLocation(Location location) {
		if (currLocation == null) {
			// A new location is always better than no location
			return true;
		}

		// Check whether the new location fix is newer or older
		long timeDelta = location.getTime() - currLocation.getTime();
		boolean isSignificantlyNewer = timeDelta > TWO_MINUTES;
		boolean isSignificantlyOlder = timeDelta < -TWO_MINUTES;
		boolean isNewer = timeDelta > 0;

		// If it's been more than two minutes since the current location, use the new location
		// because the user has likely moved
		if (isSignificantlyNewer) {
			return true;
			// If the new location is more than two minutes older, it must be worse
		} else if (isSignificantlyOlder) {
			return false;
		}

		// Check whether the new location fix is more or less accurate
		int accuracyDelta = (int) (location.getAccuracy() - currLocation.getAccuracy());
		boolean isLessAccurate = accuracyDelta > 0;
		boolean isMoreAccurate = accuracyDelta < 0;
		boolean isSignificantlyLessAccurate = accuracyDelta > 200;

		// Check if the old and new location are from the same provider
		boolean isFromSameProvider = isSameProvider(location.getProvider(),
				currLocation.getProvider());

		// Determine location quality using a combination of timeliness and accuracy
		if (isMoreAccurate) {
			return true;
		} else if (isNewer && !isLessAccurate) {
			return true;
		} else if (isNewer && !isSignificantlyLessAccurate && isFromSameProvider) {
			return true;
		}
		return false;
	}

	/** Checks whether two providers are the same */
	private boolean isSameProvider(String provider1, String provider2) {
		if (provider1 == null) {
			return provider2 == null;
		}
		return provider1.equals(provider2);
	}
}
