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

import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;

import android.util.Log;
/**
 * 
 * @author iacopo
 *
 */
public class HttpCall implements Runnable{
	public static final String TAG = "StoppableHttpCall";
	
	private final static String CHARSET_NAME = "UTF8";
	private final static Charset CHARSET = Charset.forName(CHARSET_NAME); 
	
	//protected static final String BACKEND_URL = "http://10.0.2.2:8080/mymed_backend/"; //Testing purposes.
	protected static final String BACKEND_URL = "http://130.192.9.113:8080/mymed_backend/"; //Italian backbone.
	private static final String QUERY_STRING = "?";
	private static final String AND = "&";
	private static final String EQUAL = "=";
	protected static final String CODE = "code";
	
	private final HttpClient httpClient;
	private final HttpCallHandler handler;
	private String uri;
	private boolean firstParam = true;
	private final HttpMethod method;
	private String JSonObj = null;
	private final int id;
	
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
	
	/** Http Method*/
	protected enum HttpMethod {
		GET,
		POST,
		PUT,
		DELETE;}
	
	public HttpCall(HttpCallHandler handler,HttpMethod method, String uriString,Integer id){
		this.httpClient=CallManager.getInstance().getClient();
		this.uri = uriString;
		this.method = method;
		this.handler = handler;
		this.id = id;
	};	
	
	public HttpCall(HttpCallHandler handler,HttpMethod method, String uriString,Integer id, String JSonObj){
		this(handler, method, uriString, id);
		this.JSonObj = JSonObj;
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
	 * Append an attribute to the given URL.
	 * @param url 	URL
	 * @param name	Name of the attribute.
	 * @param value	Value of the attribute.
	 * @return
	 */
	public void appendAttribute(String name,String value){
		if (firstParam){
			uri+=QUERY_STRING+name+EQUAL+value;
			firstParam = false;
		}else{
			uri+=AND+name+EQUAL+value;
		}
	}

	/**
	 * Run the http method.
	 */
	@Override
	public void run() { 
		long startCall = System.currentTimeMillis();
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
					if (JSonObj != null)
						((HttpPost) request).setEntity(new StringEntity(JSonObj,CHARSET_NAME));
					break;
				case PUT:
					request = new HttpPut(uri);
					if (JSonObj != null)
						((HttpPut) request).setEntity(new StringEntity(JSonObj,CHARSET_NAME));
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
			entity.consumeContent();
			//Log.i(TAG," RESPONSE : "+responseContent);	
			int statusCode = response.getStatusLine().getStatusCode();
			switch(statusCode){
			case HttpStatus.SC_INTERNAL_SERVER_ERROR:
			case HttpStatus.SC_NOT_FOUND:
			case HttpStatus.SC_CONFLICT: //TODO Check if this error code is really used.
				JSONObject object = (JSONObject) new JSONTokener(responseContent).nextValue();
				String message = null;
				if (object.has("description")){
					//JSONObject errObj = object.getJSONObject("error");
					message = object.getString("description");
				}				
				if (statusCode==HttpStatus.SC_INTERNAL_SERVER_ERROR)
					throw new InternalBackEndException(message);
				else
					throw new IOBackEndException(message);				
			case HttpStatus.SC_OK:
				if (handler!=null)
					handler.callSuccess(id,responseContent);
				long endCall = System.currentTimeMillis();
				Log.d(TAG, "End call "+ id + " :" + String.valueOf(endCall) + " Duration: "+ String.valueOf(endCall - startCall));
				break;
			default:
				throw new InternalClientException("Unknown status code.");
			}			
//		} catch (URISyntaxException e) {
//			handler.callError(new InternalClientException("Malformed URI."));
//			this.request.abort();
//		} catch (JSONException e) {
//			handler.callError(new InternalClientException("Wrong message content format."));
//			this.request.abort();
//		} catch (UnsupportedEncodingException e) {
//			handler.callError(new InternalClientException("Unsupported charset."));
//			this.request.abort();
//		} catch (IllegalStateException e) {
//			handler.callError(new InternalClientException("Communication error."));
//			this.request.abort();
//		} catch (IOException e) {
//			handler.callError(new InternalClientException("Communication error."));
//			this.request.abort();
//		} catch (IOBackEndException e) {
//			handler.callError(new InternalClientException(e.getMessage()));
//			this.request.abort();
//		} catch (InternalBackEndException e) {
//			handler.callError(new InternalClientException(e.getMessage()));
//			this.request.abort();
//		} catch (InternalClientException e) {
//			handler.callError(new InternalClientException(e.getMessage()));
//			this.request.abort();
		} catch (Exception e) {
			String message;
			if (mStopped){
				message = "Call interrupted "+id+" : ";
				handler.callInterrupted(id);
			}else{
				message = "Call error: ";
				if (handler!=null)
					handler.callError(id,new InternalClientException(message+e.toString()));
			}
			Log.d(TAG, message+e.toString());
			this.request.abort();
		} finally {
			//long endCall = System.currentTimeMillis();
			//Log.d(TAG, "End call "+ id + " :" + String.valueOf(endCall) + " Duration: "+ String.valueOf(endCall - startCall));
		}
	}
	
	/**
	 * Executes the call exploiting the thread pool of the call manager.
	 */
	public void execute(){
		CallManager.getInstance().execute(this);
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
}
