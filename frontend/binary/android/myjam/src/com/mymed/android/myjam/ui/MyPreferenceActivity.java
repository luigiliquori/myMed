package com.mymed.android.myjam.ui;

import com.mymed.android.myjam.R;
import com.mymed.android.myjam.service.LocationService;
import com.mymed.android.myjam.service.LocationService.LocalBinder;

import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.os.Bundle;
import android.os.IBinder;
import android.preference.PreferenceActivity;
import android.util.Log;

/**
 * 
 * @author iacopo
 *
 */
public class MyPreferenceActivity extends PreferenceActivity{
	private static final String TAG = "PreferenceActivity";
	// Preference activity binds to LocationService, to not loose user position 
	//(to hold GPS connection).
	@SuppressWarnings("unused")
	private LocationService mService;
	private boolean mBound;
	
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        
        // Load the preferences from an XML resource
        addPreferencesFromResource(R.xml.preferences);
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
    
	/** Defines callbacks for service binding, passed to bindService() */
	private ServiceConnection mConnection = new ServiceConnection(){
		
		@Override
		public void onServiceConnected(ComponentName className,
				IBinder service) {
			//Bound to LocalService, cast the IBinder and get LocalService instance
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

}
