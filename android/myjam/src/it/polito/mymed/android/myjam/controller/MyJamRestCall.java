package it.polito.mymed.android.myjam.controller;

import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;

import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;

import com.google.gson.Gson;
import com.mymed.android.myjam.type.AbstractReport.PermanenceType;
import com.mymed.android.myjam.type.AbstractReport.ReportType;
import com.mymed.android.myjam.type.AbstractReport.TrafficFlowType;
import com.mymed.android.myjam.type.AbstractReport.TransitType;
import com.mymed.android.myjam.type.NewReport;
import com.mymed.android.myjam.type.Position;

import android.util.Log;
/**
 * 
 * @author iacopo
 *
 */
public class MyJamRestCall {
	// Use the existing servlet running on LocalMyMed
	String URL = "http://iacoporozzo.dyndns-server.com/backend/MyJamRequestHandler";  
	Gson gson;
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
	public String insert(){
		HttpClient httpclient = new DefaultHttpClient();
		try {
			String q="?code=3";
			URI uri = new URI(URL+q);
			Log.i("HOST : ", uri.getHost());
			Log.i("PORT : ", "" + uri.getPort());
			HttpPost request = new HttpPost(uri); 
			NewReport report = new NewReport();
			report.setComment("CiaoMundo");
			report.setReportType(ReportType.CAR_CRASH);
			Position pos = new Position();
			pos.setLatitude((int) (44.0*1E6));
			pos.setLongitude((int) (7.01*1E6));
			pos.validate();
			report.setPos(pos);
			report.setPermanence(PermanenceType.SHORT);
			report.setTrafficFlowType(TrafficFlowType.BLOCKED);
			report.setTransitType(TransitType.COMPROMIZED);
			String jSonReport = gson.toJson(report);
			Log.i("REQUEST : ", "" + request.getURI());
			request.setEntity(new StringEntity(jSonReport,"UTF8"));
			httpclient.execute(request);
			return request.getRequestLine().toString() + "\n\n---> Sent!"; 
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
		return "error";
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
}
