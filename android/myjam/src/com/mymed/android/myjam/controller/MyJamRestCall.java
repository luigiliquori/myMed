package com.mymed.android.myjam.controller;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.lang.reflect.Type;
import java.net.URI;
import java.net.URISyntaxException;
import java.nio.BufferOverflowException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.Charset;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
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

import com.google.gson.Gson;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.android.myjam.type.MFeedBackBean;
import com.mymed.android.myjam.type.MReportBean;
import com.mymed.android.myjam.type.MShortReportBean;


import android.util.Log;
/**
 * 
 * @author iacopo
 *
 */
public class MyJamRestCall{
	// Uses the servlet running on LocalMyMed
	private String URL = "http://iacoporozzo.dyndns-server.com/backend/MyJamRequestHandler";  
	private Gson gson;
	private static final String charsetName = ("UTF8");
	private static Charset CHARSET = Charset.forName(charsetName);
	
	public MyJamRestCall(){
		gson = new Gson();
	}
	
	/** Request Code*/
	protected enum MyJamRequestCode { 
		SEARCH_REPORTS ("0"),
		GET_REPORT ("1"), 	
		GET_AVAILABLE_UPDATES ("2"),
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
	
	/** Http Method*/
	protected enum httpMethod {
		GET,
		POST,
		PUT,
		DELETE;
	}
	/**
	 * Searches the reports in the specified area.
	 * @param latitude Longitude of the center of the area [micro-degree].
	 * @param longitude Latitude of the center of the area [micro-degree].
	 * @param radius Diameter of the area [meter].
	 * @return
	 * @throws InternalClientException 
	 * @throws IOBackEndException 
	 * @throws InternalBackEndException 
	 */
	public List<MShortReportBean> searchReports(int latitude,int longitude, int radius) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.SEARCH_REPORTS.code;
		q=q+"&latitude="+String.valueOf(latitude);
		q=q+"&longitude="+String.valueOf(longitude);
		q=q+"&radius="+String.valueOf(radius);
		String response = httpRequest(URL+q,httpMethod.GET,null);
		Type shortReportListType = new TypeToken<List<MShortReportBean>>(){}.getType();
		try{
			return this.gson.fromJson(response, shortReportListType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	/**
	 * Gets the report corresponding to id.
	 * @param id Identifier of the Report.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public MReportBean getReport(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.GET_REPORT.code;
		q=q+"&id="+id;
		String response = httpRequest(URL+q,httpMethod.GET,null);
		try{
			return this.gson.fromJson(response, MReportBean.class);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	/**
	 * Returns a list with the available Updates of the report corresponding to id.
	 * @param id Identifier of the Report.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public List<String> getAvailableUpdates(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.GET_AVAILABLE_UPDATES.code;
		q=q+"&id="+id;
		String response = httpRequest(URL+q,httpMethod.GET,null);
		Type reportIdListType = new TypeToken<List<String>>(){}.getType();
		try{
			return this.gson.fromJson(response, reportIdListType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	
	/**
	 * Gets the updates corresponding to the ids. 
	 * @param ids List of updates id.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public List<MReportBean> getUpdates(List<String> ids) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.GET_UPDATES.code;
		for (String id:ids){
			q=q+"&id="+id;
		}
		String response = httpRequest(URL+q,httpMethod.GET,null);
		Type reportListType = new TypeToken<List<MReportBean>>(){}.getType();
		try{
			return this.gson.fromJson(response, reportListType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		}
		
	}
	
	/**
	 * Gets the feedbacks corresponding to report id. 
	 * @param id Id of the report.
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public List<MFeedBackBean> getFeedBacks(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.GET_FEEDBACKS.code;
		q=q+"&id="+id;
		String response = httpRequest(URL+q,httpMethod.GET,null);
		Type feedListType = new TypeToken<List<MFeedBackBean>>(){}.getType();
		try{
			return this.gson.fromJson(response, feedListType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		}
		
	}
	
	/**
	 * Gets the ids of the active reports inserted by a user.
	 * @param userId
	 * @return A list of ids.
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public List<String> getActiveReports(String userId) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.GET_ACTIVE_REPORTS.code;
		q=q+"&userId="+userId;
		String response = httpRequest(URL+q,httpMethod.GET,null);
		Type stringListType = new TypeToken<List<String>>(){}.getType();
		try{
			return this.gson.fromJson(response, stringListType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		}
	}
	
	/**
	 * Inserts a new Report
	 * @param cf	The name of the keyspace.
	 * @param key	The row key.
	 * @param sCol	The SuperColumn name.
	 * @param name	The Column name.
	 * @param value	The Column value.
	 * @param ttl	The Column time to live before expiration (seconds).
	 * @return		
	 * @throws InternalClientException 
	 * @throws IOBackEndException 
	 * @throws InternalBackEndException 
	 */
	public String insertReport(int latitude,int longitude,MReportBean report) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.INSERT_REPORT.code;
		q=q+"&latitude="+String.valueOf(latitude);
		q=q+"&longitude="+String.valueOf(longitude);
		String jSonReport = gson.toJson(report);
		String response =httpRequest(URL+q,httpMethod.POST,jSonReport);
		System.out.println(response);
		try{
			return this.gson.fromJson(response, String.class);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		}		
	}
	/**
	 * Inserts a new update
	 * @param id
	 * @param update
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public void insertUpdate(String id,MReportBean update) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.INSERT_UPDATE.code;
		q=q+"&id="+String.valueOf(id);
		String jSonReport = gson.toJson(update);
		System.out.println(httpRequest(URL+q,httpMethod.POST,jSonReport));
	}
	/**
	 * Inserts a new feedBack
	 * @param id
	 * @param update
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 * @throws InternalClientException
	 */
	public void insertFeedBack(String id,MFeedBackBean update) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.INSERT_FEEDBACK.code;
		q=q+"&id="+String.valueOf(id);
		String jSonFeedBack = gson.toJson(update);
		System.out.println(httpRequest(URL+q,httpMethod.POST,jSonFeedBack));
	}
	
	public void deleteReport(String id) throws InternalBackEndException, IOBackEndException, InternalClientException{
		String q="?code="+MyJamRequestCode.DELETE_REPORT.code;
		q=q+"&id="+String.valueOf(id);
		System.out.println(httpRequest(URL+q,httpMethod.DELETE,null));
	}
	
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
	private String httpRequest(String uriString,httpMethod method,String JSonObj)  throws InternalBackEndException, IOBackEndException, InternalClientException{
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
				((HttpPost) request).setEntity(new StringEntity(JSonObj,charsetName));
				break;
			case PUT:
				request = new HttpPut(uri);
				((HttpPut) request).setEntity(new StringEntity(JSonObj,charsetName));
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
			case 500:
			case 404:
				JSONObject object = (JSONObject) new JSONTokener(responseContent).nextValue();
				String message = null;
				if (object.has("error")){
					JSONObject errObj = object.getJSONObject("error");
					message = errObj.getString("message");
				}				
				if (statusCode==500)
					throw new InternalBackEndException(message);
				else
					throw new IOBackEndException(message);
			case 200:
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
				BufferedReader buffRead = new BufferedReader(new InputStreamReader(is,Charset.forName(charsetName)));
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
}
