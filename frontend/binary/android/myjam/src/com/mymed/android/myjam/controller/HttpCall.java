package com.mymed.android.myjam.controller;




import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.net.URI;
import java.net.URISyntaxException;
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
import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;

import android.os.Handler;
import android.os.Message;
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
	
	private static final String AND = "&";
	private static final String EQUAL = "=";
	
	private final HttpClient httpClient;
	private final Handler handler;
	private String uri;
	private final HttpMethod method;
	private String JSonObj = null;
	
	private HttpUriRequest request;
	
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
	public enum HttpMethod {
		GET,
		POST,
		PUT,
		DELETE;}
	
	public HttpCall(Handler handler,HttpMethod method, String uriString){
		this.httpClient=CallManager.getInstance().getClient();
		this.uri = uriString;
		this.method = method;
		this.handler = handler;
	};	
	
	public HttpCall(Handler handler,HttpMethod method, String uriString,String JSonObj){
		this(handler, method, uriString);
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
		uri+=AND+name+EQUAL+value;
	}

	/**
	 * Run the http method.
	 */
	@Override
	public void run() { 
		try{
			handler.sendMessage(Message.obtain(handler,0));
			URI uri = new URI(this.uri);
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
			
			Log.i(TAG, " REQUEST : "+request.getURI());
			HttpResponse response = httpClient.execute(request);
			
			HttpEntity entity = response.getEntity();
			Log.i(TAG," STATUS "+String.valueOf(response.getStatusLine().getStatusCode()));
			String responseContent = convertStreamToString(entity.getContent(),entity.getContentLength());
			entity.consumeContent();
			Log.i(TAG," RESPONSE : "+responseContent);	
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
				handler.sendMessage(Message.obtain(handler,1, responseContent));
				break;
			default:
				throw new InternalClientException("Unknown status code.");
			}			
		} catch (URISyntaxException e) {
			handler.sendMessage(Message.obtain(handler,3,new InternalClientException("Malformed URI.")));
			this.request.abort();
		} catch (JSONException e) {
			handler.sendMessage(Message.obtain(handler,3,new InternalClientException("Wrong message content format.")));
			this.request.abort();
		} catch (UnsupportedEncodingException e) {
			handler.sendMessage(Message.obtain(handler,3,new InternalClientException("Unsupported charset.")));
			this.request.abort();
		} catch (IllegalStateException e) {
			handler.sendMessage(Message.obtain(handler,3,new InternalClientException("Communication error.")));
			this.request.abort();
		} catch (IOException e) {
			handler.sendMessage(Message.obtain(handler,3,new InternalClientException("Communication error.")));
			this.request.abort();
		} catch (IOBackEndException e) {
			handler.sendMessage(Message.obtain(handler,3,new InternalClientException(e.getMessage())));
			this.request.abort();
		} catch (InternalBackEndException e) {
			handler.sendMessage(Message.obtain(handler,3,new InternalClientException(e.getMessage())));
			this.request.abort();
		} catch (InternalClientException e) {
			handler.sendMessage(Message.obtain(handler,3,new InternalClientException(e.getMessage())));
			this.request.abort();
		} catch (Exception e) {
			handler.sendMessage(Message.obtain(handler,3,new InternalClientException("Unknown error.")));
			this.request.abort();
		} 
	}
	
	/**
	 * Executes the call exploiting the thread pool of the call manager.
	 */
	void execute(){
		CallManager.getInstance().run(this);
	}
}
