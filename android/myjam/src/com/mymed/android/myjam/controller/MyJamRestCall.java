package com.mymed.android.myjam.controller;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URI;
import java.net.URISyntaxException;
import java.nio.BufferOverflowException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.Charset;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;

import com.google.gson.Gson;
import com.mymed.android.myjam.type.MReportBean;
import com.mymed.android.myjam.type.MyJamTypes.ReportType;
import com.mymed.android.myjam.type.MyJamTypes.TrafficFlowType;
import com.mymed.android.myjam.type.MyJamTypes.TransitType;



import android.util.Log;
/**
 * 
 * @author iacopo
 *
 */
public class MyJamRestCall{
	// Use the existing servlet running on LocalMyMed
	private String URL = "http://iacoporozzo.dyndns-server.com/backend/MyJamRequestHandler";  
	private Gson gson;
	private static Charset CHARSET = Charset.forName("UTF8");
	public MyJamRestCall(){
		gson = new Gson();
	}
	/**
	 * Insert a new Report
	 * 
	 * @param cf	The name of the keyspace.
	 * @param key	The row key.
	 * @param sCol	The SuperColumn name.
	 * @param name	The Column name.
	 * @param value	The Column value.
	 * @param ttl	The Column time to live before expiration (seconds).
	 * @return		
	 */
	public void insert(){
		HttpClient httpclient = new DefaultHttpClient();
		try {
			int latitude = (int) (44.0*1E6);
			int longitude = (int) (7.01*1E6);
			String q="?code=3&latitude="+String.valueOf(latitude)
			+"&longitude="+String.valueOf(longitude);
			URI uri = new URI(URL+q);
			Log.i("HOST : ", uri.getHost());
			Log.i("PORT : ", "" + uri.getPort());
			HttpPost request = new HttpPost(uri); 
			MReportBean report = new MReportBean();
			report.setComment("CiaoMundo");
			report.setReportType(ReportType.JAM.name());
			report.setTrafficFlowType(TrafficFlowType.BLOCKED.name());
			String jSonReport = gson.toJson(report);
			Log.i("REQUEST : ", ""+request.getURI());
			request.setEntity(new StringEntity(jSonReport,"UTF8"));
			HttpResponse response = httpclient.execute(request);
			String msg;
			HttpEntity entity = response.getEntity();
			msg = convertStreamToString(entity.getContent(),entity.getContentLength());
			Log.i("RESPONSE : ",msg);
		} catch (ClientProtocolException e) {  
			e.printStackTrace();  
		} catch (IOException e) {  
			e.printStackTrace();  
		} catch (URISyntaxException e) {
			e.printStackTrace();
		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			httpclient.getConnectionManager().shutdown();
		}
	}

	/**
	 * 
	 * Cassandra column get operation on a SuperColumnFamily.
	 * 
	 * @param cf	The name of the keyspace.
	 * @param key	The row key.
	 * @param sCol	The SuperColumn name.
	 * @param name	The Column name.
	 * @return Column in json format
	 */
	public String Cget(String cf,String key,String sCol, String name){
		String q = "?code=0&cf=" + cf + "&key=" + key;
		
		if (sCol!=null)
			q = q+"&sCol="+sCol;
		if (name!=null)
			q = q+"&name=" +name;
		HttpClient httpclient = new DefaultHttpClient();
		try {  
			URI uri = new URI(URL + q);
			Log.i("HOST : ", uri.getHost());
			Log.i("PORT : ", "" + uri.getPort());
			Log.i("QUERY :", uri.getQuery());
			HttpGet request = new HttpGet(uri);
			ResponseHandler<String> responseHandler = new BasicResponseHandler();
			String response = httpclient.execute(request, responseHandler);
			return "value = " + response; 
		} catch (ClientProtocolException e) {  
			e.printStackTrace();  
		} catch (IOException e) {  
			e.printStackTrace();  
		} catch (URISyntaxException e) {
			e.printStackTrace();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally {
			httpclient.getConnectionManager().shutdown();
		}
		return "error";
	}
	/**
	 * Cassandra SuperColumn get operation on a SuperColumnFamily.
	 * 
	 * @param cf	The name of the keyspace.
	 * @param key	The row key.
	 * @param sCol	The SuperColumn name.
	 * @return SuperColumn in json format
	 */
	public String SCget(String cf,String key,String sCol){
		return this.Cget(cf, key, sCol, null);
	}
	/**
	 * Cassandra Column get operation on a ColumnFamily.
	 * 
	 * @param cf	The name of the keyspace.
	 * @param key	The row key.
	 * @param name	The Column name.
	 * @return Column in json format
	 */
	public String Cget(String cf,String key,String name){
		return this.Cget(cf, key, null, name);
	}
	
	/**
	 * Given an InputStream reads the bytes as UTF8 chars and return a 
	 * String.
	 * @param is Input stream.
	 * @param length Length of the stream in bytes.
	 * @return The string
	 * @throws InternalBackEndException Format is not correct or the length less then the real wrong.
	 */
	private static String convertStreamToString(InputStream is,long length) throws Exception {
		String streamString;
		if (length>Integer.MAX_VALUE)
			throw new Exception("Wrong Content");
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
				BufferedReader buffRead = new BufferedReader(new InputStreamReader(is,Charset.forName("UTF-8")));
				StringBuilder sb = new StringBuilder();
				String line;
				while ((line = buffRead.readLine()) != null) {
					sb.append(line + "\n");
				}
				return sb.toString();
			}
		} catch (IOException e) {
			throw new Exception("Wrong content");
		} catch (BufferOverflowException e){
			throw new Exception("Wrong length");
		}finally {
			try {
				is.close();             
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
	}
}
