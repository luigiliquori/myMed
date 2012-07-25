package com.mymed.android.myjam.service;

import java.lang.ref.WeakReference;

import com.mymed.android.myjam.R;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.net.Uri;
import android.os.Binder;
import android.os.Bundle;
import android.os.Handler;
import android.os.HandlerThread;
import android.os.IBinder;
import android.os.Looper;
import android.os.SystemClock;
import android.provider.Settings;
import android.util.Log;
/**
 * 
 * @author iacopo
 *TODO Check the logic.
 * Only locations with accuracy less then 10 m are considered.
 * If no locations are received for ten seconds, user location is considered not available.
 */
public class LocationService extends Service implements LocationListener{
	private static final String TAG = "MyJamLocationService";

	HandlerThread thread;
	private volatile Looper mServiceLooper;

	private Handler mMessageQueueHandler = new Handler();
	/* Lock used to synchronize the access to location. */
	private Object lock = new Object();

	private static final int TEN_SECONDS = 10000;	//Expiration time for location.

	public static final int MINIMUM_FINE_ACCURACY = 15;	//Minimum accuracy of the new location.

	/**Parameters used for broadcast intent.*/
	public static final String LOCATION_ACTION = "com.mymed.android.myjam.intent.action.LOCATION_CHANGE";
	public static final String EXTRA_ACTION_CODE =
			"com.mymed.android.myjam.extra.ACTION_CODE";
	public static final int LOCATION_AVAILABLE = 0;
	public static final int LOCATION_NO_MORE_AVAILABLE = 1;
	/** Identifier for the notification */
	private static final int LOCATION_ID = 1;
	/**Private parameters.*/
	private boolean mLocAvailable;
	private final IBinder mBinder = new LocalBinder<LocationService>(this);

	Notification mNotification;
	NotificationManager mNotificationManager;

	// This is the object that receives interactions from clients.  See
	// RemoteService for a more complete example.

	LocationManager locationManager;
	private Location currFineLocation = null;

	@Override
	public void onCreate(){
		super.onCreate();
		/** Local attributes initialization. */
		mLocAvailable = false;
		thread = new HandlerThread(TAG);
		thread.start();

		mServiceLooper = thread.getLooper();

		/** Prepares the notification */
		String ns = Context.NOTIFICATION_SERVICE;
		mNotificationManager = (NotificationManager) getSystemService(ns);

		/** Create a new notification */
		int icon = R.drawable.user_location_available;
		CharSequence tickerText = getResources().getText(R.string.notification_loc_available_text);
		long when = System.currentTimeMillis();

		mNotification = new Notification(icon, tickerText, when);
		mNotification.defaults |= Notification.DEFAULT_VIBRATE;

		Context context = getApplicationContext();
		CharSequence contentTitle = getResources().getText(R.string.notification_loc_available_title);
		CharSequence contentText = getResources().getText(R.string.notification_loc_available_text);
		Intent notificationIntent = new Intent(this, this.getClass());
		PendingIntent contentIntent = PendingIntent.getActivity(this, 0, notificationIntent, 0);

		mNotification.setLatestEventInfo(context, contentTitle, contentText, contentIntent);


		locationManager = (LocationManager) this.getSystemService(Context.LOCATION_SERVICE);
				locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, this, mServiceLooper);//locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, TWO_MINUTES, MIN_SPACE_FINE, this, mServiceLooper);
		//		locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 0, 0, this, mServiceLooper);
		if (locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER))
			Log.d(TAG, "Network provider available.");
		else
			Log.i(TAG, "Network provider unavailable.");		
		if (locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)){
			Log.d(TAG, "GPS provider available.");
		}
		else{
			Log.d(TAG, "GPS provider unavailable.");
			//toggleGPS(true); //CR 2012
		}	
	}

	/**
	 * Toggle GPS ON: hack! - CR 2012
	 */
	private void toggleGPS(boolean enable) {
	    String provider = Settings.Secure.getString(getContentResolver(), 
	        Settings.Secure.LOCATION_PROVIDERS_ALLOWED);

	    if(provider.contains("gps") == enable) {
	        return; // the GPS is already in the requested state
	    }

	    final Intent poke = new Intent();
	    poke.setClassName("com.android.settings", 
	        "com.android.settings.widget.SettingsAppWidgetProvider");
	    poke.addCategory(Intent.CATEGORY_ALTERNATIVE);
	    poke.setData(Uri.parse("3"));
	    Context context = getApplicationContext();
	    context.sendBroadcast(poke);
	}
	
	@Override
	public void onDestroy(){
		super.onDestroy();
		locationManager.removeUpdates(this);
		mMessageQueueHandler.removeCallbacks(mLocationExpiredRunnable);
		mServiceLooper.quit();
		mNotificationManager.cancel(LOCATION_ID);//TODO Check
	}

	@Override
	public void onLocationChanged(Location arg0) {
		//TODO Check the scope of this intent.
		if (arg0.getAccuracy()<MINIMUM_FINE_ACCURACY){
			/** Removes the old time-out and sets a new one. */
			mMessageQueueHandler.removeCallbacks(mLocationExpiredRunnable);
			long nextTenSeconds = SystemClock.uptimeMillis() + TEN_SECONDS; //TODO Use a setting.
			mMessageQueueHandler.postAtTime(mLocationExpiredRunnable, nextTenSeconds);
			synchronized(lock){
				if(!mLocAvailable){				
					mLocAvailable = true;
					this.currFineLocation = arg0;
					mNotificationManager.notify(LOCATION_ID, mNotification);
					Intent intent = new Intent(LOCATION_ACTION);
					intent.putExtra(EXTRA_ACTION_CODE, LOCATION_AVAILABLE);
					sendBroadcast(intent); 
				}
				currFineLocation = arg0;
			}
		}
	}

	private Runnable mLocationExpiredRunnable = new Runnable() {
		public void run() {
			onLocationExpired();
		}
	};
	
//	private Runnable mFixedLocationRunnable = new Runnable() {
//		public void run() {
//			Location loc = new Location("FIXED_PROVIDER");
//			loc.setAccuracy((float) 5.0);
//			loc.setLatitude(45.079529); //Prova a Sophia-Antipolis
//			loc.setLongitude(7.675717);
//			onLocationChanged(loc);
//		}
//	};


	public void onLocationExpired() {
		synchronized(lock){
			mLocAvailable = false;
		}
		mNotificationManager.cancel(LOCATION_ID);
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
		//mMessageQueueHandler.postDelayed(mFixedLocationRunnable, 5000); //To be used for the demonstration
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

	/**
	 * Gets the current location.
	 * @return The current location if available, if not returns {@value null}.
	 */
	public Location getCurrentLocation(){
		synchronized(lock){
			if (mLocAvailable)
				return currFineLocation;
			else
				return null;
		}
	}
}
