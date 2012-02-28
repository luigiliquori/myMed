package com.mymed.android.myjam.service;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Queue;
import java.util.concurrent.ConcurrentLinkedQueue;

import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

import com.google.gson.Gson;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.android.myjam.controller.CallContract;
import com.mymed.android.myjam.controller.CallContract.CallCode;
import com.mymed.android.myjam.controller.CallManager;
import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.controller.HttpCallHandler;
import com.mymed.android.myjam.exception.AbstractMymedException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.android.myjam.provider.MyJamContract;
import com.mymed.android.myjam.provider.MyJamContract.Feedback;
import com.mymed.android.myjam.provider.MyJamContract.Login;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.SearchResult;
import com.mymed.android.myjam.provider.MyJamContract.Update;
import com.mymed.android.myjam.provider.MyJamContract.User;
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MSearchBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MyResultReceiver;

import com.mymed.android.myjam.R;
import android.app.Service;
import android.content.BroadcastReceiver;
import android.content.ContentProviderOperation;
import android.content.ContentResolver;
import android.content.ContentValues;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.OperationApplicationException;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.os.HandlerThread;
import android.os.IBinder;
import android.os.Looper;
import android.os.Message;
import android.os.RemoteException;
import android.util.Log;

/**
 * Service that handles the requests to the front-end.
 * 
 * @author iacopo
 *
 */
public class CallService extends Service {
	private static final String TAG="CallService";
	
	public static final String EXTRA_CALL_ID =
            "com.mymed.android.myjam.extra.EXTRA_CALL_ID";
	
    public static final String EXTRA_STATUS_RECEIVER =
            "com.mymed.android.myjam.extra.STATUS_RECEIVER";
    
    public static final String EXTRA_REQUEST_CODE =
            "com.mymed.android.myjam.extra.REQUEST_CODE";
    
    public static final String EXTRA_PRIORITY_CODE =
            "com.mymed.android.myjam.extra.PRIORITY_CODE";
    
    public static final String EXTRA_NUMBER_ATTEMPTS =
            "com.mymed.android.myjam.extra.RETRIALS_NUMBER";
    
    public static final String EXTRA_ACTIVITY_ID =
            "com.mymed.android.myjam.extra.ACTIVITY_ID";
    
    public static final String EXTRA_UPDATE_ID =
            "com.mymed.android.myjam.extra.UPDATE_ID";
    
    public static final String EXTRA_ATTRIBUTES =
            "com.mymed.android.myjam.extra.ATTRIBUTES";
    
    public static final String EXTRA_OBJECT =
            "com.mymed.android.myjam.extra.OBJECT";
	
    public static final String EXTRA_EXCEPTION =
            "com.mymed.android.myjam.extra.EXCEPTION"; 
    
    /** Type of connections available.  */
    private static final int[] connType={
			ConnectivityManager.TYPE_MOBILE,
			ConnectivityManager.TYPE_WIFI,
			ConnectivityManager.TYPE_WIMAX};

    
    private volatile Looper mServiceLooper;
    private volatile ServiceHandler mServiceHandler;    
    private volatile Map<Integer, HttpCall> mCallsMap;
    /** List of calls that are waiting for network connection. */
    private Queue<HttpCall> mWaitingCalls;
    /** Boolean flag that holds the connection state of the device. 
     * TODO Check. Concurrency issues should arise.
     */
    private volatile boolean mConnected;
    private ContentResolver mResolver;
    private final Gson gson;
    
	public CallService() {
		super();
		gson = new Gson();
	}
    
    /** Receives notifications about network status.*/
	private final BroadcastReceiver networkStatusReceiver = new BroadcastReceiver() {		
		@Override
		public void onReceive(Context context, Intent intent) {	
			boolean newState = isConnected();
			/** The network connection becomes available. */			
			if (!mConnected && newState){
				mConnected = newState;
				mServiceHandler.startWaitingCalls();
			}else{
				mConnected = newState;
			}
		}
	};

    private final class ServiceHandler extends HttpCallHandler {
    	public ServiceHandler(Looper looper) {
    		super (looper);
    	}

    	@Override
    	public void handleMessage(Message msg) {
    		final MyResultReceiver receiver; 
    		final Intent intent;
    		final String jsonObj;
    		final HttpCall call;
    		final Bundle bundle = new Bundle();
//    		if ((callBean = mCallsMap.get(msg.arg1))!=null)
//    			receiver = callBean.getReceiver();
//    		else
//    			receiver = null;
    		call = mCallsMap.get(msg.arg1);
    		receiver = MyResultReceiver.getInstance();
    		switch(msg.what){
    		/** It is a new request arrived at the service. */
    		case NEW_CALL:
    			intent = (Intent) msg.obj;
//TODO    			MCallBean newCall = new MCallBean();
//    			newCall.setCallId(msg.arg1);
//    			newCall.setCallCode(intent.getIntExtra(EXTRA_REQUEST_CODE, 0));
//    			newCall.setAttributes(intent.getBundleExtra(EXTRA_ATTRIBUTES));
//    			newCall.setObject(intent.getSerializableExtra(EXTRA_OBJECT));
//    			newCall.setReceiver((ResultReceiver) intent.getParcelableExtra(EXTRA_STATUS_RECEIVER));
//    			newCall.setPriority(intent.getIntExtra(EXTRA_PRIORITY_CODE, 1));
//    			newCall.setMaxNumAttempts(intent.getIntExtra(EXTRA_NUMBER_ATTEMPTS, 1));
//    			newCall.setActivityId(intent.getStringExtra(EXTRA_ACTIVITY_ID));
    			//TODO Check
    			Object obj = intent.getSerializableExtra(EXTRA_OBJECT);
    			jsonObj=obj!=null?gson.toJson(obj):null;
  //  			mCallsMap.put(msg.arg1, newCall);
  //      		startCall(msg.arg1,);
    			startCall(msg.arg1, intent.getIntExtra(EXTRA_REQUEST_CODE, 0), 
    					intent.getBundleExtra(EXTRA_ATTRIBUTES), 
    					intent.getStringExtra(EXTRA_ACTIVITY_ID), 
    					intent.getIntExtra(EXTRA_NUMBER_ATTEMPTS, 1), 
    					intent.getIntExtra(EXTRA_PRIORITY_CODE, 1), 
    					jsonObj);
    			break;
    		/** The call started. */
    		case INTERRUPT_CALL:
    			if (call!=null) call.abort();
    			break;
    		/** The call started. */
    		case MSG_CALL_START:
    			Log.d(TAG,"Call "+msg.arg1+" started.");
    			prepareCallBundle(call,null,bundle);
    			if (receiver != null) receiver.send(MSG_CALL_START, bundle);
    			break;
    		/** The call didn't start 
    		 * 	The reason is that the thread on which HP calls are executed is already occupied,
    		 * 	and the request has been discarded.
    		 * */
    		case MSG_CALL_NOT_STARTED:
    			Log.d(TAG,"Call "+msg.arg1+" not started.");
    			prepareCallBundle(call,null,bundle);
    			if (receiver != null) 
    				receiver.send(MSG_CALL_NOT_STARTED, bundle);
    			else 
    				Log.d(TAG,"Notification call not started failed.");
    			Log.d(TAG,"Call "+msg.arg1+" not started.");
    			mCallsMap.remove(msg.arg1);
    			break;
    		/** The call ended successfully. */
    		case MSG_CALL_SUCCESS:
    			Log.d(TAG,"Call "+msg.arg1+" succesfull. Result: "+(String) msg.obj);
    			prepareCallBundle(call,(String) msg.obj,bundle);
    			handleSuccessCall(msg);
    			if (receiver != null) receiver.send(MSG_CALL_SUCCESS, bundle);
    			break;
    		/** The call has been interrupted. */
    		case MSG_CALL_INTERRUPTED:
    			Log.d(TAG,"Call "+msg.arg1+" interrupted.");
    			prepareCallBundle(call,(String) msg.obj,bundle);
    			if (receiver != null) receiver.send(MSG_CALL_INTERRUPTED, bundle);
    			mCallsMap.remove(msg.arg1);
    			break;
    		/** The call ended due to an error. */
    		case MSG_CALL_ERROR:
    			Log.d(TAG,"Call "+msg.arg1+" error: "+(String) msg.obj);
    			/*	Handling of corner cases.
    			 * 
    			 *  - If the session token is no more present on the back-end, but is locally stored on the
    			 *  content provider, the application thinks it holds a valid token even if this is not actually
    			 *  true.  
    			 */ 
    			// The application tries to log out, but the token is no more valid.
    			if (call.getCallCode() == CallCode.LOG_OUT && msg.arg2 == 404){
    				prepareCallBundle(call,(String) msg.obj,bundle);
        			handleSuccessCall(msg);
        			if (receiver != null) receiver.send(MSG_CALL_SUCCESS, bundle);
    			}else if(call.getCallCode() == CallCode.CHECK_ACCESS_TOKEN && msg.arg2 == 404){
    				prepareCallBundle(call,(String) msg.obj,bundle);
        			handleSuccessCall(msg);
        			if (receiver != null) receiver.send(MSG_CALL_SUCCESS, bundle);
    			}else{
    				prepareCallBundle(call,(String) msg.obj,bundle);
        			if (receiver != null) receiver.send(MSG_CALL_ERROR, bundle);
        			if (call.getNumAttempts()>=call.getMaxNumAttempts())
        				mCallsMap.remove(msg.arg1); 
        			else{
        				/** After first attempt all calls are considered low priority. */
        				call.setPriority(HttpCall.LOW_PRIORITY);
        				executeCall(call);
        			}
    			}
    			break;
    		case MSG_CALL_WAITING:
    			Log.d(TAG,"Call "+msg.arg1+" waiting for network access.");
    			prepareCallBundle(call,null,bundle);
    			if (receiver != null) receiver.send(MSG_CALL_WAITING, bundle);
    			break;
    		case MSG_START_WAITING_CALLS:
				HttpCall currCall;
				while ((currCall = mWaitingCalls.poll())!=null && mConnected){
					currCall.execute();
				}
    			break;
    		}
			if (mCallsMap.size() <= 0){
				Log.d(TAG,"Call service stopping itself.");
				stopSelf();
			}
    	}
    }
    
    private void prepareCallBundle(HttpCall call, String message,Bundle bundle){
    	if (call!=null){
    		bundle.putInt(MyResultReceiver.CALL_ID, call.getCallId());
    		bundle.putInt(MyResultReceiver.CALL_CODE, call.getCallCode());
    		bundle.putString(MyResultReceiver.ACTIVITY_ID, call.getActivityId());
    		bundle.putInt(MyResultReceiver.PRIORITY, call.getPriority());
    		bundle.putInt(MyResultReceiver.MAX_NUM_ATTEMPTS, call.getMaxNumAttempts());
    		bundle.putInt(MyResultReceiver.NUM_ATTEMPTS, call.getNumAttempts());
    	} 
    	if (message!=null)
    		bundle.putString(MyResultReceiver.MESSAGE, message);
    }

    @Override
    public void onCreate() {
        super.onCreate();
        HandlerThread thread = new HandlerThread(TAG);
        thread.start();
        /** Checks whether a network connection is available or not. */
        mResolver = getContentResolver();
        mConnected=isConnected();
		final IntentFilter filter = new IntentFilter();
		filter.addAction(ConnectivityManager.CONNECTIVITY_ACTION);
		registerReceiver(networkStatusReceiver,filter);
		/** List which stores the calls waiting to be executed, when the network connection is not available. */
		mWaitingCalls = new ConcurrentLinkedQueue<HttpCall>();
        mServiceLooper = thread.getLooper();
        mServiceHandler = new ServiceHandler(mServiceLooper);
        /** Creates the maps to store MCallBean objects.*/
        mCallsMap = new HashMap<Integer,HttpCall>();
    }
    
    /**
     * Checks whether the connection is available or not.
     * @return
     */
    public boolean isConnected() {
        NetworkInfo[] networkInfo = (NetworkInfo[]) ((ConnectivityManager) this.getSystemService(Context.CONNECTIVITY_SERVICE)).
        		getAllNetworkInfo();

        for (int i:connType){
        	if (i<networkInfo.length && networkInfo[i] != null && 
        			networkInfo[i].isConnected() && networkInfo[i].isAvailable()) {
            	return true;
            }
        }
        return false;
    }

    @Override
    public void onStart(Intent intent, int startId) {
        Message msg = mServiceHandler.obtainMessage();
        if (intent.getIntExtra(EXTRA_REQUEST_CODE, HttpCallHandler.INTERRUPT_CALL)==HttpCallHandler.INTERRUPT_CALL){
        	msg.what = HttpCallHandler.INTERRUPT_CALL;
        	msg.arg1 = intent.getIntExtra(EXTRA_CALL_ID, -1);
        }else{
        	msg.what = HttpCallHandler.NEW_CALL;
            msg.arg1 = startId;
            msg.obj = intent;
        }
        /** If it is an interrupt request it doesn't carry the (@value EXTRA_REQUEST_CODE). */
        mServiceHandler.sendMessage(msg);
    }

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {
    	Log.d(TAG, "Received start id " + startId + ": " + intent);
        onStart(intent, startId);
        return START_STICKY;
    }

    @Override
    public void onDestroy() {
    	CallManager.shutDown();
    	unregisterReceiver(networkStatusReceiver);
        mServiceLooper.quit();
        Log.d(TAG, "onDestroy...");
    }

    /**
     * Started service, don't bind. 
     */
    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }
    
    /**
     * 
     * @param reqId
     * @param attributes
     * @param applicationId
     * @param maxNumAttempts
     * @param priority
     * @param jsonObj
     */
    private void startCall(final int reqId, final int callCode, final Bundle attributes, 
    		final String activityId, final int maxNumAttempts, final int priority, final String jsonObj){
//TODO    	final MCallBean callBean = mCallsMap.get(reqId);
    	final HttpCall call;
//    	boolean report = false;
//    	switch(callCode){
//    	/**
//    	 * 
//    	 */
//    	case CallCode.LOG_IN:
//    		call = AuthenticationCallFactory.authenticate(reqId, mServiceHandler, 
//    				attributes.getString(ICallAttributes.LOGIN), 
//    				attributes.getString(ICallAttributes.PASSWORD));
//    		break;
//    	case CallCode.LOG_OUT:
//    		call = AuthenticationCallFactory.logOut(reqId, mServiceHandler, 
//    				attributes.getString(ICallAttributes.ACCESS_TOKEN));
//    		break;
//    	//TODO
//    	case CallCode.SEARCH_REPORTS:
//    		call = MyJamCallFactory.searchReports(reqId, mServiceHandler,
//    				attributes.getString(ICallAttributes.ACCESS_TOKEN),
//    				attributes.getInt(ICallAttributes.LATITUDE),
//    				attributes.getInt(ICallAttributes.LONGITUDE),
//    				attributes.getInt(ICallAttributes.RADIUS));
//    		break;
//    	case CallCode.GET_REPORT:
//    		call = MyJamCallFactory.getReport(reqId, mServiceHandler,
//    				attributes.getString(ICallAttributes.ACCESS_TOKEN),
//    				attributes.getString(ICallAttributes.REPORT_ID));
//    		break;
//    	case CallCode.INSERT_REPORT:
//    		call = MyJamCallFactory.insertReport(reqId, mServiceHandler,
//    				attributes.getString(ICallAttributes.ACCESS_TOKEN),
//    				attributes.getInt(ICallAttributes.LATITUDE),
//    				attributes.getInt(ICallAttributes.LONGITUDE),
//    				jsonObj);
//    		break;
//    	case CallCode.GET_UPDATES:
//    		call = MyJamCallFactory.getUpdates(reqId, mServiceHandler,
//    				attributes.getString(ICallAttributes.ACCESS_TOKEN),
//    				attributes.getString(ICallAttributes.REPORT_ID),
//    				attributes.getLong(ICallAttributes.START_TIME));
//    		break;
//    	case CallCode.INSERT_UPDATE:
//    		call = MyJamCallFactory.insertUpdate(reqId, mServiceHandler,
//    				attributes.getString(ICallAttributes.ACCESS_TOKEN),
//    				attributes.getString(ICallAttributes.REPORT_ID),
//    				jsonObj);
//    		break;
//    	case CallCode.GET_REPORT_FEEDBACKS:
//    		report = true;
//    	case CallCode.GET_UPDATE_FEEDBACKS:
//    		call = MyJamCallFactory.getFeedbacks(reqId, report, mServiceHandler,
//    				attributes.getString(ICallAttributes.ACCESS_TOKEN),
//    				attributes.getString(ICallAttributes.REPORT_ID));
//    		break;
//    	case CallCode.INSERT_REPORT_FEEDBACK:
//    	case CallCode.INSERT_UPDATE_FEEDBACK:
//    		call = MyJamCallFactory.insertFeedBack(reqId, mServiceHandler,
//    				attributes.getString(ICallAttributes.ACCESS_TOKEN),
//    				attributes.getString(ICallAttributes.REPORT_ID),
//    				attributes.getString(ICallAttributes.UPDATE_ID),
//    				jsonObj);
//    		break;
//    	default:
//    		call = null;
//    	}
//    	if (call!=null){
//    		call.setActivityId(activityId);
//    		call.setMaxNumAttempts(maxNumAttempts);
//    		call.setPriority(priority);
//    	}
    	call = jsonObj==null?new HttpCall(reqId, callCode, attributes, mServiceHandler):
    		new HttpCall(reqId, callCode, attributes, mServiceHandler, jsonObj);
    	call.setActivityId(activityId);
		call.setMaxNumAttempts(maxNumAttempts);
		call.setPriority(priority);
		mCallsMap.put(reqId, call);
		
    	executeCall(call);
    }
    
    /**
     * Try to execute the {@link HttpCall} passed as a parameter if it is not null.
     * 	- If the network is available the call is executed.
     * 	- If the network is not available and the priority of the call is {@link HIGH_PRIORITY} the call is not executed
     *	  and it is thrown an exception (the number of attempts is increased as well).
     *	- If the network is not available and the priority of the call is {@link LOW_PRIORITY} the call is put in the wait
     *	  queue to be executed when the network is again available.
     * 
     * @param callBean
     */
    private void executeCall(HttpCall call){
    	if (call!=null){
    		if (mConnected)
    			call.execute();
    		else{
    			if (call.getPriority() == HttpCall.HIGH_PRIORITY){
    				call.increaseNumAttempts();
    				mServiceHandler.callError(call.getCallId(), 
    						400,getResources().getString(R.string.network_unavailable));
    			}else if (call.getPriority() == HttpCall.LOW_PRIORITY){
    				mWaitingCalls.add(call);
    				mServiceHandler.callWaiting(call.getCallId());
    			}
    		}
    	}
    }
    
    /**
     * Extract the data from the json object {@link jsonObj}.
     * 
     * @param jsonObj json object.
     * @param label	label of the data.
     * @return
     * @throws InternalBackEndException 
     */
    private String extractData(String jsonObj,String label) throws InternalBackEndException{
		JSONObject response;
		try{
			response = (JSONObject) new JSONTokener(jsonObj).nextValue();
			JSONObject data = response.getJSONObject("data");
			String jsonData = data.getString(label);
			return jsonData;
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException(getResources().getString(R.string.wrong_format));
		} catch (JSONException e) {
			throw new InternalBackEndException(getResources().getString(R.string.wrong_format));
		}
    }
    
    private void handleSuccessCall(Message msg){
    	final HttpCall call = mCallsMap.remove(msg.arg1);
    	final String result = (String) msg.obj;
    	try{
    		switch(call.getCallCode()){
    		case CallCode.LOG_IN:
    			handleLogIn(result,call.getAttributes());
    			break;
    		case CallCode.CHECK_ACCESS_TOKEN:
    			handleCheckAccessToken(msg.arg2);
    			break;
    		case CallCode.LOG_OUT:
    			handleLogOut();
    			break;
    		case CallCode.SEARCH_REPORTS:
    			handleSearchReports(result,call.getAttributes());
    			break;
    		case CallCode.GET_REPORT:
    			handleGetReport(result,call.getAttributes());
    			break;
    		case CallCode.INSERT_REPORT:
    			handleInsertReport(result,call.getAttributes());
    			break;
    		case CallCode.GET_UPDATES:
    			handleGetUpdates(result,call.getAttributes());
    			break;
    		case CallCode.INSERT_UPDATE:
    			handleInsertUpdate(result,call.getAttributes());
    			break;
    		case CallCode.GET_REPORT_FEEDBACKS:
    		case CallCode.GET_UPDATE_FEEDBACKS:
    			handleGetFeedbacks(result,call.getAttributes(),call.getCallCode());
    			break;
    		case CallCode.INSERT_REPORT_FEEDBACK:
    		case CallCode.INSERT_UPDATE_FEEDBACK:
    			handleInsertFeedback(result, call.getAttributes(), call.getCallCode());
    			break;
    		default:
    		}
    	}catch(AbstractMymedException e){
    		mServiceHandler.callError(msg.arg1, e.getStatus(), e.getMessage());
    	} catch (Exception e) {
    		mServiceHandler.callError(msg.arg1, 400, e.toString());
		}
    }

	/**
     * Executes the operations necessary to store the results upon a successful {@link LOG_IN}
     * 
     * @param callBean
     * @throws InternalBackEndException
     * @throws RemoteException
     * @throws OperationApplicationException
     */
    private void handleLogIn(String message, Bundle attributes) 
    		throws InternalBackEndException, RemoteException, OperationApplicationException{
    	final ArrayList<ContentProviderOperation> batch = new ArrayList<ContentProviderOperation> (2);    	
    	final String jsonData = extractData(message,"user");
    	final String accessToken = extractData(message,"accessToken");
    	
		Type userType = new TypeToken<MUserBean>(){}.getType();
		MUserBean user = this.gson.fromJson(jsonData, userType);
		ContentValues currVal = new ContentValues();
		currVal.put(User.USER_ID, user.getId());
		currVal.put(User.LOGIN_ID, user.getLogin());
		currVal.put(User.USER_NAME, user.getName());
		currVal.put(User.FIRST_NAME, user.getFirstName());
		currVal.put(User.LAST_NAME, user.getLastName());
		currVal.put(User.GENDER, user.getGender());
		//TODO add eventually other values.
		batch.add(ContentProviderOperation.newInsert(User.CONTENT_URI).withValues(currVal).build());
		currVal = new ContentValues();
		currVal.put(Login.LOGIN_ID, user.getLogin());
		currVal.put(Login.USER_ID, user.getId());
		currVal.put(Login.PASSWORD, attributes.getString(CallContract.PASSWORD));
		currVal.put(Login.DATE, System.currentTimeMillis());
		currVal.put(Login.LOGGED, true);
		currVal.put(Login.ACCESS_TOKEN, accessToken);
		batch.add(ContentProviderOperation.newInsert(Login.CONTENT_URI).withValues(currVal).build());
		mResolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
    }
    
    /**
     * If the result code is not present (result code 404), the Login Data on the content provider is eliminated. 
     * 
     * @param resultCode
     * @throws RemoteException
     * @throws OperationApplicationException
     */
    private void handleCheckAccessToken(int resultCode) 
    		throws RemoteException, OperationApplicationException {
		if (resultCode == 200){
			// Do nothing.
		}else if (resultCode == 404)
			handleLogOut();
	}
    
    /**
     * Executes the operations necessary to store the results upon a successful {@link LOG_OUT}
     * 
     * @param callBean
     * @throws RemoteException
     * @throws OperationApplicationException
     */
    private void handleLogOut() 
    		throws RemoteException, OperationApplicationException{
    	final ArrayList<ContentProviderOperation> batch = new ArrayList<ContentProviderOperation> (2);
    	
    	batch.add(ContentProviderOperation.newDelete(Login.CONTENT_URI).build());
    	mResolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
    	mResolver.notifyChange(Login.CONTENT_URI, null);
    }
    
    /**
     * Executes the operations necessary to store the results upon a successful {@link SEARCH_REPORTS}
     * call. 
     * 
     * @param callBean
     * @throws RemoteException
     * @throws OperationApplicationException
     * @throws InternalBackEndException
     * @throws InternalClientException
     */
    private void handleSearchReports(String message, Bundle attributes) 
    		throws RemoteException, OperationApplicationException, InternalBackEndException, InternalClientException{
    	String jsonData = extractData(message,"search_reports");
		Type searchBeanListType = new TypeToken<List<MSearchBean>>(){}.getType();

		List<MSearchBean> listSearchRep = this.gson.fromJson(jsonData, searchBeanListType);
		/** The number of operations to be performed on the content provider is 2 times the number of found reports plus 3:
		 * 
		 *	There are two types of search the first is executed from {@link SearchActivity} the second is performed before 
		 *	an insertion, to check that no other reports of the same time are present.
		 * 
		 */
		ArrayList<ContentProviderOperation> batch = new ArrayList<ContentProviderOperation> ((2*listSearchRep.size())+3);
		int searchId = attributes.getInt(CallContract.SEARCH_ID);
		/** 
		 * If it's a {@link NEW_SEARCH} the following operations are done:
		 * -The search results with flag OLD_SEARCH are deleted.
		 * -The search results with flag NEW_SEARCH are marked as old (OLD_SEARCH).
		 * -The new search results are inserted, with flag NEW_SEARCH.
		 * -The reports that are no more pointed by entries in SearchResults table and that doesn't belong to
		 * the current user are deleted automatically.   
		 **/
		if (searchId == Search.NEW_SEARCH){
			batch.add(ContentProviderOperation.newDelete(SearchResult.CONTENT_URI).withSelection(SearchResult.SEARCH_ID_SELECTION,
					new String[]{String.valueOf(Search.OLD_SEARCH)}).build());
			batch.add(ContentProviderOperation.newUpdate(SearchResult.CONTENT_URI).withSelection(SearchResult.SEARCH_ID_SELECTION,
					new String[]{String.valueOf(Search.NEW_SEARCH)}).withValue(SearchResult.SEARCH_ID, Search.OLD_SEARCH).build());
			/** 
			 * If it's a {@link INSERT_SEARCH} the following operations are done:
			 * -The search results with flag INSERT_SEARCH are deleted.
			 * -The new search results are inserted, with flag INSERT_SEARCH.
			 * -The reports that are no more pointed by entries in SearchResults table and that doesn't belong to
			 * the current user are deleted.   
			 **/
		}else if(searchId == Search.INSERT_SEARCH){
			batch.add(ContentProviderOperation.newDelete(SearchResult.CONTENT_URI).withSelection(SearchResult.SEARCH_ID_SELECTION,
					new String[]{String.valueOf(Search.INSERT_SEARCH)}).build());
		}else
			throw new InternalClientException("The search id is not valid.");
		ContentValues currVal = new ContentValues();
		currVal.put(Search.SEARCH_ID, searchId);
		currVal.put(Search.DATE, System.currentTimeMillis());
		currVal.put(Search.LATITUDE, attributes.getInt(CallContract.LATITUDE));
		currVal.put(Search.LONGITUDE, attributes.getInt(CallContract.LONGITUDE));
		currVal.put(Search.RADIUS, attributes.getInt(CallContract.RADIUS));
		currVal.put(Search.SEARCHING, false);
		batch.add(ContentProviderOperation.newInsert(Search.CONTENT_URI).withValues(currVal).build());
		for (MSearchBean currShortRep:listSearchRep){
			currVal = new ContentValues();
			currVal.put(SearchResult.REPORT_ID, currShortRep.getId());
			currVal.put(SearchResult.DISTANCE, currShortRep.getDistance());
			currVal.put(SearchResult.SEARCH_ID, searchId);
			batch.add(ContentProviderOperation.newInsert(SearchResult.CONTENT_URI).withValues(currVal).build());
			currVal = new ContentValues();
			currVal.put(Report.REPORT_ID,	currShortRep.getId());
			currVal.put(Report.REPORT_TYPE,	currShortRep.getValue());
			currVal.put(Report.LATITUDE, currShortRep.getLatitude());
			currVal.put(Report.LONGITUDE, currShortRep.getLongitude());
			currVal.put(Report.DATE, currShortRep.getDate());
			batch.add(ContentProviderOperation.newInsert(Report.CONTENT_URI).withValues(currVal).build());
		}
		batch.add(ContentProviderOperation.newDelete(Report.CONTENT_URI).
				withSelection(Report.STALE_ENTRIES_SELECTION, null).build());
		mResolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
		mResolver.notifyChange(SearchReports.buildSearchUri(String.valueOf(searchId)), null);
    }
    
    /**
     * Executes the operations necessary to store the results upon a successful {@link GET_REPORT}
     * call.
     *  
     * @param callBean
     * @throws InternalBackEndException
     */
    private void handleGetReport(String message, Bundle attributes) throws InternalBackEndException{
    	String jsonData = extractData(message,"report");
    	Type reportType = new TypeToken<MReportBean>(){}.getType();
    	MReportBean report = this.gson.fromJson(jsonData, reportType);
    	String reportId = attributes.getString(CallContract.REPORT_ID);
    	ContentValues reportVal = new ContentValues();
    	reportVal.put(Report.TRAFFIC_FLOW, report.getTrafficFlowType());
    	reportVal.put(Report.COMMENT, report.getComment());
    	reportVal.put(Report.USER_ID, report.getUserId());
    	reportVal.put(Report.USER_NAME, report.getUserName());
    	reportVal.put(Report.FLAG_COMPLETE, true);
    	/**
    	 * I use update because the report has been already inserted during the search, 
    	 * here I add only the details that were not present.
    	 */
    	mResolver.update(Report.CONTENT_URI, reportVal, Report.REPORT_SELECTION, new String[]{reportId});
    }
    
    /**
     * Executes the operations necessary to store the results upon a successful {@link GET_UPDATES}
     * 
     * @param callBean
     * @throws InternalBackEndException
     * @throws RemoteException
     * @throws OperationApplicationException
     */
    private void handleGetUpdates(String message, Bundle attributes) 
    		throws InternalBackEndException, RemoteException, OperationApplicationException{
		final String reportId = attributes.getString(CallContract.REPORT_ID);
		final ArrayList<ContentProviderOperation> batch;
		
		String jsonData = extractData(message,"updates");
		Type reportListType = new TypeToken<List<MReportBean>>(){}.getType();
		List<MReportBean> listUpdates = this.gson.fromJson(jsonData, reportListType);		
		batch = new ArrayList<ContentProviderOperation> (listUpdates.size());
		ContentValues currVal = new ContentValues();
		for (MReportBean currUpdate:listUpdates){
			currVal = new ContentValues();
			currVal.put(Update.UPDATE_ID, currUpdate.getId());
			currVal.put(Update.REPORT_ID, reportId);
			currVal.put(Update.TRAFFIC_FLOW, currUpdate.getTrafficFlowType());
			currVal.put(Update.COMMENT, currUpdate.getComment());
			currVal.put(Update.DATE, currUpdate.getTimestamp());
			currVal.put(Update.USER_ID, currUpdate.getUserId());
			currVal.put(Update.USER_NAME, currUpdate.getUserName());
			batch.add(ContentProviderOperation.newInsert(Update.CONTENT_URI).withValues(currVal).build());
		}
		mResolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
		if (listUpdates.size()>0)
			mResolver.notifyChange(Update.CONTENT_URI, null);
    }
    
    /**
     * Executes the operations necessary to store the results upon a successful {@link INSERT_REPORT}.
     * 
     * @param callBean
     * @throws InternalBackEndException 
     */
    private void handleInsertReport(String message, Bundle attributes) 
    		throws InternalBackEndException{
    	String jsonData = extractData(message,"report");
    	Type reportType = new TypeToken<MReportBean>(){}.getType();
    	MReportBean reportRes = this.gson.fromJson(jsonData, reportType);
    	ContentValues currVal = new ContentValues();
		currVal.put(Report.REPORT_ID, reportRes.getId());
		currVal.put(Report.USER_ID, reportRes.getUserId());
		currVal.put(Report.USER_NAME, reportRes.getUserName());
		currVal.put(Report.LATITUDE, attributes.getInt(CallContract.LATITUDE));
		currVal.put(Report.LONGITUDE, attributes.getInt(CallContract.LONGITUDE));
		currVal.put(Report.DATE, reportRes.getTimestamp());
		currVal.put(Report.REPORT_TYPE, reportRes.getReportType());
		currVal.put(Report.TRAFFIC_FLOW, reportRes.getTrafficFlowType());
		currVal.put(Report.COMMENT, reportRes.getComment());
		currVal.put(Report.FLAG_COMPLETE, true);
		mResolver.insert(Report.CONTENT_URI, currVal);
    }
    
    /**
     * Executes the operations necessary to store the results upon a successful {@link INSERT_UPDATE}.
     * 
     * @param callBean
     * @throws InternalBackEndException 
     */
    private void handleInsertUpdate(String message, Bundle attributes) throws InternalBackEndException{
    	String jsonData = extractData(message,"update");
    	Type reportType = new TypeToken<MReportBean>(){}.getType();
    	MReportBean updateRes = this.gson.fromJson(jsonData, reportType);
		ContentValues currVal = new ContentValues();
		currVal.put(Update.UPDATE_ID, updateRes.getId());
		currVal.put(Update.REPORT_ID, attributes.getInt(CallContract.REPORT_ID));
		currVal.put(Update.USER_ID, updateRes.getUserId());
		currVal.put(Update.USER_NAME, updateRes.getUserName());
		currVal.put(Update.DATE, updateRes.getTimestamp());
		currVal.put(Update.TRAFFIC_FLOW, updateRes.getTrafficFlowType());
		currVal.put(Update.COMMENT, updateRes.getComment());
		mResolver.insert(Update.CONTENT_URI, currVal);
    }
    
    /**
     * Executes the operations necessary to store the results upon a successful {@link GET_FEEDBACKS}.
     * 
     * @param callBean
     * @throws OperationApplicationException 
     * @throws RemoteException 
     * @throws InternalBackEndException 
     */
    private void handleGetFeedbacks(String message, Bundle attributes, int callCode) 
    		throws RemoteException, OperationApplicationException, InternalBackEndException{
    	final String reportOrUpdateId = attributes.getString(CallContract.REPORT_ID);
    	final ArrayList<ContentProviderOperation> batch;
    	
    	String jsonData = extractData(message,"feedbacks");
		Type feedListType = new TypeToken<List<MFeedBackBean>>(){}.getType();
		List<MFeedBackBean> listFeedBacks = this.gson.fromJson(jsonData, feedListType);
    	batch = new ArrayList<ContentProviderOperation> (listFeedBacks.size());
    	for (MFeedBackBean currFeedBack:listFeedBacks){
    		ContentValues currVal = new ContentValues();
    		currVal.put(Feedback.USER_ID, currFeedBack.getUserId());
    		currVal.put(callCode==CallCode.GET_REPORT_FEEDBACKS?Feedback.REPORT_ID:Feedback.UPDATE_ID, reportOrUpdateId);
    		currVal.put(Feedback.VALUE, currFeedBack.getValue());
    		batch.add(ContentProviderOperation.newInsert(Feedback.CONTENT_URI).withValues(currVal).build());
    	}
    	mResolver.applyBatch(MyJamContract.CONTENT_AUTHORITY, batch);
    	mResolver.notifyChange(callCode==CallCode.GET_REPORT_FEEDBACKS?
    			Feedback.buildReportIdUri(null):
    				Feedback.buildUpdateIdUri(null), null);
    }
    
    private void handleInsertFeedback(String message, Bundle attributes, int callCode)
    		throws RemoteException, OperationApplicationException, InternalBackEndException{
    	String jsonData = extractData(message,"feedback");
		Type feedType = new TypeToken<MFeedBackBean>(){}.getType();
		MFeedBackBean feedback = this.gson.fromJson(jsonData, feedType);
    	
		String reportId = attributes.getString(CallContract.REPORT_ID);
		String updateId = attributes.getString(CallContract.UPDATE_ID);
		//TODO Use user_name and user_id in my_reports (to add after log-in is done).
		ContentValues currVal = new ContentValues();
		currVal.put(Feedback.USER_ID, feedback.getUserId()); //TODO fix this
		currVal.put(callCode==CallCode.INSERT_REPORT_FEEDBACK?Feedback.REPORT_ID:Feedback.UPDATE_ID, 
				callCode==CallCode.INSERT_REPORT_FEEDBACK?reportId:updateId);
		currVal.put(Feedback.VALUE, feedback.getValue());
		mResolver.insert(Feedback.CONTENT_URI, currVal);
		mResolver.notifyChange(callCode==CallCode.INSERT_REPORT_FEEDBACK?
				Feedback.buildReportIdUri(null):
					Feedback.buildUpdateIdUri(null), null);

    }
}