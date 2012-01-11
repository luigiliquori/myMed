package com.mymed.android.myjam.controller;

import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import org.apache.http.HttpVersion;
import org.apache.http.client.HttpClient;
import org.apache.http.conn.params.ConnManagerParams;
import org.apache.http.conn.params.ConnPerRouteBean;
import org.apache.http.conn.scheme.PlainSocketFactory;
import org.apache.http.conn.scheme.Scheme;
import org.apache.http.conn.scheme.SchemeRegistry;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.impl.conn.tsccm.ThreadSafeClientConnManager;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;
import org.apache.http.params.HttpProtocolParams;

import android.util.Log;

/**
 * 
 * Singleton class that manages the HTTP calls.
 * 
 * @author iacopo
 *
 */
public class CallManager {
	private static final String TAG = "CallManager";
	
	private static final int poolSize = 4;
	
	private HttpClient httpClient;
	private ExecutorService pool;
 
    private static CallManager instance;
 
    public static CallManager getInstance() {
        if (instance == null)
            instance = new CallManager();
        return instance;
    }
    
    /**
     * Private constructor.
     */
    private CallManager(){
        
        // Create and initialize scheme registry 
        SchemeRegistry schemeRegistry = new SchemeRegistry();
        schemeRegistry.register(
                new Scheme("http", PlainSocketFactory.getSocketFactory(), 80));
    	
     // Create and initialize HTTP parameters
    	HttpParams httpParams = new BasicHttpParams();
    	/** Sets the version of the HTTP protocol to 1.1 */
    	HttpProtocolParams.setVersion(httpParams, HttpVersion.HTTP_1_1);		
    	/** Sets a timeout until a request is established. [ms] */
		HttpConnectionParams.setConnectionTimeout(httpParams, 10000);
		/** Sets a timeout for waiting for data. [ms]*/
		HttpConnectionParams.setSoTimeout(httpParams, 5000);
		/** Set the maximum number of total connections. */
		ConnManagerParams.setMaxTotalConnections(httpParams, poolSize);
		/** Set the maximum number of connections per route. */
		ConnManagerParams.setMaxConnectionsPerRoute(httpParams, new ConnPerRouteBean(poolSize));
		
    	ThreadSafeClientConnManager connManager = new ThreadSafeClientConnManager(httpParams, schemeRegistry); 
    	httpClient = new DefaultHttpClient(connManager,httpParams);
    	
    	pool = Executors.newFixedThreadPool(poolSize);
    	Log.i(TAG, "Executor pool created");
    }
    
    /**
     *  Shut down its connection manager to ensure that all connections kept alive by the manager 
     *  get closed and system resources allocated by those connections are released.
     */
    public static void shutDown() {
    	
    	instance.pool.shutdown();
    	Log.i(TAG, "Executor pool terminating...");
    	while (!instance.pool.isTerminated()) {
		}
		System.out.println("Finished all threads");
		Log.i(TAG, "Executor pool terminated.");
		
    	if (instance!=null)
    		instance.httpClient.getConnectionManager().shutdown();
    }
    
    protected HttpClient getClient(){
    	return instance.httpClient;
    }
    
    protected void execute(Runnable runnable){
    	instance.pool.execute(runnable);
    }
    
    
}