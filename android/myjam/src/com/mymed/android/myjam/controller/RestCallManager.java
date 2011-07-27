package com.mymed.android.myjam.controller;


import java.lang.reflect.Type;
import java.util.LinkedList;
import java.util.List;

import android.content.ContentValues;
import android.content.Context;
import android.os.Handler;


import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.mymed.android.myjam.IMyJamRestActivity;
import com.mymed.android.myjam.controller.RestCall.HttpMethod;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.android.myjam.provider.MyJamContract.LocatedReport;
import com.mymed.android.myjam.type.MShortReportBean;
import com.mymed.android.myjam.type.MyJamTypes.ReportType;

import android.content.ContentResolver;

/**
 * This class performs HTTP methods asynchronously in worker thread, and performs call-backs with the 
 * results to the specified Handler.
 * @author iacopo
 *
 */
public class RestCallManager {	  
    /**Singleton*/
    private static RestCallManager instance;
	private String URL = "http://iacoporozzo.dyndns-server.com/backend/MyJamRequestHandler";
    
	/**Implements a queue of requests, a request at a time is performed.*/
    private Runnable active = null;
    private LinkedList<Runnable> queue = new LinkedList<Runnable>();
    
	private final RestCall myJamRestCall = new RestCall();
	private final Gson gson = new Gson();
	private ContentResolver cr;
	
	public void setContentResolver(ContentResolver cr) {
		this.cr = cr;
	}   
 
    public static RestCallManager getInstance() {
        if (instance == null)
            instance = new RestCallManager();
        return instance;
    }
    
    /**
     * TODO Check this, maybe that passing the context I'm leaking the memory!!!
     * @param latitude
     * @param longitude
     * @param radius
     * @param handler
     * @param context
     */
    public void searchReports(final int latitude,final int longitude,final int radius,final Handler handler,final Context context){
		this.push(new Runnable(){
			@Override
			public void run() {
		    	String q="?code="+MyJamRequestCode.SEARCH_REPORTS.code;
				q=q+"&latitude="+String.valueOf(latitude);
				q=q+"&longitude="+String.valueOf(longitude);
				q=q+"&radius="+String.valueOf(radius);
				notifyStartCall(handler,context);
				try {
					String jSonContent = myJamRestCall.httpRequest(URL+q, HttpMethod.GET, null);
					if (cr==null)
						throw new InternalClientException("Content resolver not set.");
					Type shortReportListType = new TypeToken<List<MShortReportBean>>(){}.getType();
					List<MShortReportBean> listShortRep = gson.fromJson(jSonContent, shortReportListType);
					ContentValues[] cvList = new ContentValues[listShortRep.size()];
					int ind = 0;
					for (MShortReportBean currShortRep:listShortRep){
						ContentValues currVal = new ContentValues();
						currVal.put(LocatedReport.REPORT_TYPE, currShortRep.getReportType());
						currVal.put(LocatedReport.REPORT_ID, currShortRep.getReportId());
						currVal.put(LocatedReport.LATITUDE, currShortRep.getLatitude());
						currVal.put(LocatedReport.LONGITUDE, currShortRep.getLongitude());
						cvList[ind] = currVal;
						ind++;
					}
					cr.delete(LocatedReport.CONTENT_URI, null, null);
					cr.bulkInsert(LocatedReport.CONTENT_URI, cvList);
					sendResult(true,null,handler,context);
				} catch (Exception e) {
					sendResult(false,e.getMessage(),handler,context);
				} finally {
					RestCallManager.instance.callComplete(this);
				}
			}
		});
    }
	
    /**
     * Sends the result back to the activity.
     * thread through its handler.
     * 
     * @param result The boolean holding authentication result
     * @param handler The main UI thread's handler instance.
     * @param context The caller Activity's context.
     */
    private static void sendResult(final Boolean result, final String message,final Handler handler,
        final Context context) {
        if (handler == null || context == null) {
            return;
        }
        handler.post(new Runnable() {
            public void run() {
                ((IMyJamRestActivity) context).onFinishCall(result,message);
            }
        });
    }
    
    /**
     * Notify the start of the call to the activity that performed the
     * request.
     * 
     * @param result The boolean holding authentication result
     * @param handler The main UI thread's handler instance.
     * @param context The caller Activity's context.
     */
    private static void notifyStartCall(final Handler handler,
        final Context context) {
        if (handler == null || context == null) {
            return;
        }
        handler.post(new Runnable() {
            public void run() {
                ((IMyJamRestActivity) context).onStartCall();
            }
        });
    }
 
    private void push(Runnable runnable) {
        queue.add(runnable);
        if (active==null)
            startNext();
    }
 
    private void startNext() {
        if (!queue.isEmpty()) {
            Runnable next = queue.get(0);
            queue.remove(0);
            active=next;
 
            Thread thread = new Thread(next);
            thread.start();
        }
    }
 
    private void callComplete(Runnable runnable) {
        active=null;
        startNext();
    }

	/** Request Code*/
	public enum MyJamRequestCode { 
		SEARCH_REPORTS ("0"),
		GET_REPORT ("1"), 	
		GET_NUMBER_UPDATES ("2"),
		GET_UPDATES ("3"),
		GET_FEEDBACKS("4"),
		GET_ACTIVE_REPORTS("5"),	
		GET_USER_REPORT_UPDATE("6"),	//TODO To implement
		INSERT_REPORT ("7"),
		INSERT_UPDATE ("8"),
		INSERT_FEEDBACK ("9"),
		DELETE_REPORT("10");
		//TODO Eventually add DELETE_REPORT and DELETE_UPDATE
		public final String code;

		MyJamRequestCode(String code){
			this.code = code;
		}
	}
}
