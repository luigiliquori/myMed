package com.mymed.android.myjam.service;
import java.lang.ref.WeakReference;

import com.mymed.android.myjam.controller.RestCallManager;
import android.app.Service;
import android.content.Intent;
import android.os.Binder;
import android.os.IBinder;

public class MyJamCallService extends Service{
	private final RestCallManager callManager = RestCallManager.getInstance();
	private final IBinder mBinder = new LocalBinder<RestCallManager>(callManager);

    
	
	/**
     * Class used for the client Binder.  Because we know this service always
     * runs in the same process as its clients, we don't need to deal with IPC.
     * Is static because of the bug documented on: 
     * http://code.google.com/p/android/issues/detail?id=6426
     */
	public class LocalBinder<S> extends Binder {
	    private  WeakReference<S> mService;
	    	    
	    public LocalBinder(S service){
	        mService = new WeakReference<S>(service);
	    }
	    
	    public S getService() {
	        return mService.get();
	    }
	}
	
	@Override
	public void onCreate(){
		callManager.setContentResolver(getContentResolver());
	}

    @Override
    public IBinder onBind(Intent intent) {
        return mBinder;
    }
}
