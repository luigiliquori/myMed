package com.mymed.utils;

import java.lang.ref.WeakReference;

import com.mymed.android.myjam.service.MyJamCallService;


import android.os.Bundle;
import android.os.Handler;
import android.os.ResultReceiver;
import android.util.Log;

/**
 * Singleton class that retains the syncing status.
 * @author iacopo
 *
 */
public class MyResultReceiver extends ResultReceiver {
	private static MyResultReceiver instance;
    private static final String TAG = "MyResultReceiver";
    private WeakReference<Receiver> mReceiver;
    private boolean mSyncing = false;

    public static MyResultReceiver getInstance(){
    	if (instance == null)
    		instance = new MyResultReceiver();
    	return instance;
    }
    
    private MyResultReceiver() {
        super(new Handler());
    }
    
    public void clearReceiver() {
        mReceiver = null;
    }

    public void setReceiver(Receiver receiver) {
        mReceiver = new WeakReference<Receiver>(receiver);
    }

    public interface Receiver {
        public void onReceiveResult(int resultCode, Bundle resultData);
    }
    
	public boolean ismSyncing() {
		return mSyncing;
	}

	public void setmSyncing(boolean mSyncing) {
		this.mSyncing = mSyncing;
	}

    @Override
    protected void onReceiveResult(int resultCode, Bundle resultData) {
		switch (resultCode) {
		case MyJamCallService.STATUS_RUNNING:
			setmSyncing(true);
			break;
		case MyJamCallService.STATUS_FINISHED: 
		case MyJamCallService.STATUS_ERROR:	
			setmSyncing(false);
			break;
		}
        if (mReceiver != null) {
            mReceiver.get().onReceiveResult(resultCode, resultData);
        } else {
            Log.w(TAG, "Dropping result on floor for code " + resultCode + ": "
                    + resultData.toString());
        }
    }
}
