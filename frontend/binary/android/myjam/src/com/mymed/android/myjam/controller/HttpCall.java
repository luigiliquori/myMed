package com.mymed.android.myjam.controller;




import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URI;
import java.nio.BufferOverflowException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.Charset;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpDelete;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.methods.HttpPut;
import org.apache.http.client.methods.HttpUriRequest;
import org.apache.http.entity.StringEntity;
import org.json.JSONObject;
import org.json.JSONTokener;

import com.mymed.android.myjam.exception.AbstractMymedException;
import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;

import android.os.Bundle;
import android.util.Log;
/**
 * 
 * @author iacopo
 *
 */
public class HttpCall extends CallContract implements Runnable {
	public static final String TAG = "HttpCall";
	
	private final static String CHARSET_NAME = "UTF8";
	private final static Charset CHARSET = Charset.forName(CHARSET_NAME); 
	// The priority of the call can be high or low.
	public final static int LOW_PRIORITY=0;
	public final static int HIGH_PRIORITY=1;
	private final static String CALL_ERROR="Call error: ";
	private final static String CALL_INTERRUPTED="Call interrupted: ";
	
	private final HttpClient httpClient;
	private final HttpCallHandler handler;
	private String uri;
	private final HttpMethod method;
	private final Bundle attributes;
	private String jSonObj = null;
	private final int id;
	private final int callCode;
	/** State attributes */
	private int numAttempts;
	private volatile int maxNumAttempts;
	private volatile int priority;
	private volatile String activityId;
	
	private HttpUriRequest request = null;
	
	/**
	 * Volatile stop flag used to coordinate state between the threads.
	 */
	protected volatile boolean mStopped = false;
	
	/**
	 * Synchronizes access to mMethod to prevent an unlikely race condition
	 * when stopCall() is called before method has been committed.
	 */
	private Object lock = new Object();
	private Object numAttemptsLock = new Object();
		
	public HttpCall(int id, int callCode, Bundle attributes, HttpCallHandler handler){
		this.httpClient=CallManager.getInstance().getClient();
		this.id = id;
		this.callCode = callCode;
		this.attributes=attributes;
		this.uri = makeUri();
		this.method = CallContract.getHttpMethod(callCode);
		this.handler = handler;
		
		this.numAttempts = 0;
		this.maxNumAttempts =1;
		this.priority = LOW_PRIORITY;
	};	
	
	public HttpCall(int id, int callCode, Bundle attributes, HttpCallHandler handler, String jSonObj){
		this(id, callCode, attributes, handler);
		this.jSonObj = jSonObj;
	};

	/**
	 * Given an InputStream reads the bytes as UTF8 chars and return a 
	 * String.
	 * @param is Input stream.
	 * @param length Length of the stream in bytes.
	 * @return The string
	 * @throws InternalBackEndException Format is not correct or the length less then the real wrong.
	 */
	private static String convertStreamToString(InputStream is,long length) throws InternalClientException {
		String streamString;
		if (length>Integer.MAX_VALUE)
			throw new InternalClientException("Wrong Content");
		int byteLength = (int) length;
		try {			
			if (byteLength>0){
				ByteBuffer byteBuff = ByteBuffer.allocate(byteLength);
				int currByte;
				while ((currByte=is.read()) != -1) {
					byteBuff.put((byte) currByte);
				}
				byteBuff.compact();
				final CharBuffer charBuf = CHARSET.newDecoder().decode(byteBuff);
				streamString = charBuf.toString();
				return streamString;	
			}else{
				BufferedReader buffRead = new BufferedReader(new InputStreamReader(is,Charset.forName(CHARSET_NAME)));
				StringBuilder sb = new StringBuilder();
				String line;
				while ((line = buffRead.readLine()) != null) {
					sb.append(line + "\n");
				}
				return sb.toString();
			}
		} catch (IOException e) {
			throw new InternalClientException("Wrong content");
		} catch (BufferOverflowException e){
			throw new InternalClientException("Wrong length");
		}finally {
			try {
				is.close();             
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
	}

	/**
	 * Run the http method.
	 */
	public void run() { 
		long startCall = System.currentTimeMillis();
		String message=null;
		try{
			if (handler!=null)
				handler.callStart(id);
			Log.d(TAG,"Start call "+ id + " :" +String.valueOf(startCall));
			URI uri = new URI(this.uri);
			
			
			if (mStopped)
				return;
			
			synchronized(lock){
				switch (method){
				case GET:
					this.request = new HttpGet(uri);
					break;
				case POST:
					request = new HttpPost(uri);
					if (jSonObj != null)
						((HttpPost) request).setEntity(new StringEntity(jSonObj,CHARSET_NAME));
					break;
				case PUT:
					request = new HttpPut(uri);
					if (jSonObj != null)
						((HttpPut) request).setEntity(new StringEntity(jSonObj,CHARSET_NAME));
					break;
				case DELETE:
					request = new HttpDelete(uri);
					break;
				default:
					request = new HttpGet(uri);
				} 
			}
			//Log.i(TAG, " REQUEST : "+request.getURI());
			HttpResponse response = httpClient.execute(request);
			
			synchronized(lock) {
				request = null;
			}
			
			HttpEntity entity = response.getEntity();
			//Log.i(TAG," STATUS "+String.valueOf(response.getStatusLine().getStatusCode()));
			String responseContent = convertStreamToString(entity.getContent(),entity.getContentLength());
			if (responseContent==null)
				throw new InternalClientException("Response empty."); 
			entity.consumeContent();
			//Log.i(TAG," RESPONSE : "+responseContent);	
			int statusCode = response.getStatusLine().getStatusCode();
			switch(statusCode){
			case HttpStatus.SC_INTERNAL_SERVER_ERROR:
			case HttpStatus.SC_NOT_FOUND:
			case HttpStatus.SC_CONFLICT: //TODO Check if this error code is really used.
			case HttpStatus.SC_FORBIDDEN:
				JSONObject object = (JSONObject) new JSONTokener(responseContent).nextValue();
				if (object.has("description")){
					//JSONObject errObj = object.getJSONObject("error");
					message = object.getString("description");
				}				
				if (statusCode==HttpStatus.SC_INTERNAL_SERVER_ERROR)
					throw new InternalBackEndException(message);
				else
					throw new IOBackEndException(message,404);				
			case HttpStatus.SC_OK:
				if (handler!=null)
					handler.callSuccess(id,responseContent);
				break;
			default:
				throw new InternalClientException("Unknown status code.");
			}			
		} catch (AbstractMymedException e) {
			if (mStopped){
				message = CALL_INTERRUPTED+id;
				handler.callInterrupted(id);
			}else{
				handler.callError(id,e.getStatus(),message);
			}
			Log.d(TAG, message);
		} catch (Exception e) {
			message = CALL_ERROR+e.toString();
			handler.callError(id,400,message);
		} finally {
			long endCall = System.currentTimeMillis();
			if (message!=null) Log.d(TAG,"Result: "+message);
			Log.d(TAG, "End call "+ id + " :" + String.valueOf(endCall) + " Duration: "+ String.valueOf(endCall - startCall));
		}
	}
	
	/**
	 * Executes the call exploiting the thread pools of the call manager.
	 * 
	 * @param priority The priority of the call: {@value 1} for high priority,
	 * 	{@value 0} for low priority.
	 */
	public void execute(){
		
		switch(priority){
		case LOW_PRIORITY:	
			CallManager.getInstance().executeLp(this);
			synchronized(numAttemptsLock){
				this.numAttempts++;
			}
			break;
		// If no other HP calls are ongoing the call is executed.
		case HIGH_PRIORITY:
			try{
				CallManager.getInstance().executeHp(this);
				synchronized(numAttemptsLock){
					this.numAttempts++;
				}
			}catch(InternalClientException e){
				handler.callNotStart(id);
			} 
			break;
		default:
		}
	}
	
	/**
	 * TODO Check
	 * @return The URI to be used to perform the call.
	 */
	private String makeUri(){
		String uri = makeUri(callCode);
		for(String param:attributes.keySet()){
			uri+=AND+param+EQUAL+String.valueOf(attributes.get(param));
		}
		return uri;
	}
	
	/**
	 * Aborts the http call.
	 */
	public void abort(){
		/**
		 *	Already stopped possibly from another thread.
		 */
		if (mStopped == true)
			return;
		
		Log.d(TAG, "Stopping call...");
		
		mStopped = true;
		//TODO Check if it is really necessary lock. 
		synchronized(lock){
			if (request != null)
				request.abort();
		}
		Log.d(TAG, "Call stopped...");
	}

	/** Getters and setters */
	
	/**
	 * Get the call Id.
	 * 
	 * @return The id of the current call.
	 */
	public int getCallId() {
		return id;
	}
	
	/**
	 * Get the call code.
	 * 
	 * @return The id of the current call.
	 */
	public int getCallCode() {
		return callCode;
	}
	
	/**
	 * Get the attributes.
	 * 
	 * @return The {@link Bundle} containing the attributes of the call.
	 */
	public Bundle getAttributes() {
		return attributes;
	}
	
	/**
	 * Get the maximum number of attempts.
	 * 
	 * @return The maximum number of attempts.
	 */
	public int getMaxNumAttempts() {
		return maxNumAttempts;
	}
	
	/**
	 * Set the maximum number of attempts.
	 *  
	 * @param maxNumAttempts Maximum number of attempts.
	 */
	public void setMaxNumAttempts(int maxNumAttempts) {
		this.maxNumAttempts = maxNumAttempts;
	}

	/**
	 * Get the priority of the call. 
	 * 
	 * @return The priority of the call.
	 */
	public int getPriority() {
		return priority;
	}

	/**
	 * Set the priority of the call.
	 * 
	 * @param priority
	 */
	public void setPriority(int priority) {
		this.priority = priority;
	}
	
	/**
	 * Increase the current number of attempts.
	 * 
	 * @return Number of attempts.
	 */
	public int increaseNumAttempts() {
		int num;
		synchronized(numAttemptsLock){
			num = ++this.numAttempts;
		}
		return num;
	}
	
	/**
	 * Return the current number of attempts.
	 * 
	 * @return Number of attempts.
	 */
	public int getNumAttempts() {
		int num;
		synchronized(numAttemptsLock){
			num = this.numAttempts;
		}
		return num;
	}

	/**
	 * Return the id of the activity which requested the call.
	 * 
	 * @return String containing the id of the requesting activity.
	 */
	public String getActivityId() {
		return activityId;
	}

	/**
	 * Set the id of the activity which requested the call.
	 * 
	 * @param activityId Id of the activity which requested the call
	 */
	public void setActivityId(String activityId) {
		this.activityId = activityId;
	}
}
