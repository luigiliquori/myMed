package com.mymed.android.myjam.ui;

import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.net.Uri;
import android.os.Bundle;
import android.os.IBinder;
import android.provider.Settings;
import android.util.Log;

import com.mymed.android.myjam.service.LocationService;
import com.mymed.android.myjam.service.LocationService.LocalBinder;

/**
 * Abstract activity that allows to the activities that extends it to 
 * access the LocationService.
 * @author iacopo
 *
 */
public abstract class AbstractLocatedActivity extends Activity{
	private static final String TAG = "SearchActivity";
	
	LocationService mService;
	boolean mBound = false;
	
    /** Receives the availability status of the location.*/
	private BroadcastReceiver locationUpdateReceiver = new BroadcastReceiver() {
		@Override
		public void onReceive(Context context, Intent intent) {			
			switch (intent.getIntExtra(LocationService.EXTRA_ACTION_CODE, -1)){
			case LocationService.LOCATION_AVAILABLE:
				onLocationAvailable();
				break;
			case LocationService.LOCATION_NO_MORE_AVAILABLE:
				onLocationNoMoreAvailable();
				break;
			}
		}
	};
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
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
	}
	
	
	/**
	 * Called when the LocationService is connected.
	 */
	protected abstract void onLocServiceConnected();
	/**
	 * Called when the location service is disconnected.
	 */
	protected abstract void onLocServiceDisconnected();
	/**
	 * Called when the location is available.
	 */
	protected abstract void onLocationAvailable();
	/**
	 * Called if the location is no more available.
	 */
	protected abstract void onLocationNoMoreAvailable();
	
	/** Defines callbacks for service binding, passed to bindService() */
	private ServiceConnection mConnection = new ServiceConnection(){
		
		@Override
		public void onServiceConnected(ComponentName className,
				IBinder service) {
			// We've bound to LocalService, cast the IBinder and get LocalService instance
			@SuppressWarnings("unchecked")
			LocalBinder<LocationService> binder = (LocalBinder<LocationService>) service;
			mService = binder.getService();
			onLocServiceConnected();
			mBound = true;
			Log.d(TAG,"LocationService connected.");
		}

		@Override
		public void onServiceDisconnected(ComponentName arg0) {
			mBound = false;
			onLocServiceDisconnected();
			Log.d(TAG,"LocationService disconnected.");
		}
	};
}
