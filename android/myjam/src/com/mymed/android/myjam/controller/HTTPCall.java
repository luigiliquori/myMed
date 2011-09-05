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
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
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
public class HTTPCall{
	private final static String CHARSET_NAME = "UTF8";
	private final static Charset CHARSET = Charset.forName(CHARSET_NAME); 
	
	private static final String AND = "&";
	private static final String EQUAL = "=";
	
	/** Http Method*/
	protected enum httpMethod {
		GET,
		POST,
		PUT,
		DELETE;}
	
	public HTTPCall(){};
	
	/**
	 * Executes the HTTP method.
	 * @param uriString
	 * @param method
	 * @param JSonObj
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	protected String httpRequest(String uriString,httpMethod method,String JSonObj)  
			throws InternalBackEndException, IOBackEndException, InternalClientException{
		HttpClient httpclient = new DefaultHttpClient();
		HttpUriRequest request; 
		try{
			URI uri = new URI(uriString);
			switch (method){
			case GET:
				request = new HttpGet(uri);
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
			Log.i("REQUEST : ", ""+request.getURI());
			HttpResponse response = httpclient.execute(request);
			HttpEntity entity = response.getEntity();
			Log.i("STATUS",String.valueOf(response.getStatusLine().getStatusCode()));
			String responseContent = convertStreamToString(entity.getContent(),entity.getContentLength());
			Log.i("RESPONSE : ",responseContent);	
			int statusCode = response.getStatusLine().getStatusCode();
			switch(statusCode){
			case HttpStatus.SC_INTERNAL_SERVER_ERROR:
			case HttpStatus.SC_NOT_FOUND:
			case HttpStatus.SC_CONFLICT: //TODO Check if this error code is really used.
				JSONObject object = (JSONObject) new JSONTokener(responseContent).nextValue();
				String message = null;
				if (object.has("error")){
					JSONObject errObj = object.getJSONObject("error");
					message = errObj.getString("message");
				}				
				if (statusCode==HttpStatus.SC_INTERNAL_SERVER_ERROR)
					throw new InternalBackEndException(message);
				else
					throw new IOBackEndException(message);				
			case HttpStatus.SC_OK:
				return responseContent;
			default:
				throw new InternalClientException("Unknown status code.");
			}			
		} catch (URISyntaxException e) {
			throw new InternalClientException("Malformed URI.");
		} catch (JSONException e) {
			throw new InternalClientException("Wrong message content format.");
		} catch (UnsupportedEncodingException e) {
			throw new InternalClientException("Unsupported charset.");
		} catch (IllegalStateException e) {
			throw new InternalClientException("Communication error.");
		} catch (IOException e) {
			throw new InternalClientException("Communication error.");
		} catch (IOBackEndException e) {
			throw new InternalClientException(e.getMessage());
		} catch (InternalBackEndException e) {
			throw new InternalClientException(e.getMessage());
		} catch (InternalClientException e) {
			throw new InternalClientException(e.getMessage());
		} catch (Exception e) {
			throw new InternalClientException("Unknown error.");
		}finally {
			httpclient.getConnectionManager().shutdown();
		}
	}
	
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
	protected String appendAttribute(String url,String name,String value){
		return url+=AND+name+EQUAL+value;
	}
}
