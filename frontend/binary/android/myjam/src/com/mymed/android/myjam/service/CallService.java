package com.mymed.android.myjam.service;

import android.app.Service;
import android.content.Intent;
import android.os.Handler;
import android.os.HandlerThread;
import android.os.IBinder;
import android.os.Looper;
import android.os.Message;

public class CallService extends Service {
	private static final String TAG="CallService";
    private volatile Looper mServiceLooper;
    private volatile ServiceHandler mServiceHandler;
    
    public interface RequestCode{
    	int LOG_IN = 0x0;
    	int PARTIAL_LOG_OUT = 0x1;
    	int COMPLETE_LOG_OUT = 0x2;
    	//TODO Creates a new myMed profile int REGISTER_USER = 0x2;
    	/*
    	 *Don't change the order, because there the following five values are used to
    	 *find a string inside the array type_obj in array.xml. 
    	 */
    	int SEARCH_REPORTS = 0x3;
    	int GET_REPORT = 0x4;
    	int GET_UPDATES = 0x5;
    	int GET_REPORT_FEEDBACKS = 0x6;
    	int GET_UPDATE_FEEDBACKS = 0x7;
    	
    	int INSERT_REPORT = 0x8;
    	int INSERT_UPDATE = 0x9;
    	int INSERT_REPORT_FEEDBACK = 0x10;
    	int INSERT_UPDATE_FEEDBACK = 0x11;
    }


    private final class ServiceHandler extends Handler {
        public ServiceHandler(Looper looper) {
            super (looper);
        }

        @Override
        public void handleMessage(Message msg) {
            onHandleIntent((Intent) msg.obj);
            //stopSelf(msg.arg1); // Must be stopped when the work ends.
        }
    }

    @Override
    public void onCreate() {

        super .onCreate();
        HandlerThread thread = new HandlerThread("IntentService["
                + TAG + "]");
        thread.start();

        mServiceLooper = thread.getLooper();
        mServiceHandler = new ServiceHandler(mServiceLooper);
    }

    @Override
    public void onStart(Intent intent, int startId) {
        Message msg = mServiceHandler.obtainMessage();
        msg.arg1 = startId;
        msg.obj = intent;
        mServiceHandler.sendMessage(msg);
    }

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {
        onStart(intent, startId);
        return START_NOT_STICKY;
    }

    @Override
    public void onDestroy() {
        mServiceLooper.quit();
    }

    /**
     * Started service, don't bind. 
     */
    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }
    
    protected void onHandleIntent(Intent intent){
    	
    }

}