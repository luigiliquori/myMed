package com.mymed.utils;

import java.lang.ref.WeakReference;


import android.os.Bundle;
import android.os.Handler;
import android.os.ResultReceiver;
import android.util.Log;


public class MyResultReceiver extends ResultReceiver {
    private static final String TAG = "MyResultReceiver";
    private WeakReference<Receiver> mReceiver;

    public MyResultReceiver(Handler handler) {
        super(handler);
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

    @Override
    protected void onReceiveResult(int resultCode, Bundle resultData) {
        if (mReceiver != null) {
            mReceiver.get().onReceiveResult(resultCode, resultData);
        } else {
            Log.w(TAG, "Dropping result on floor for code " + resultCode + ": "
                    + resultData.toString());
        }
    }
}
