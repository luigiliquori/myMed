/*
 * Copyright 2012 POLITO 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
package com.mymed.android.myjam.controller;

import java.io.IOException;
import java.io.InputStream;
import java.security.KeyManagementException;
import java.security.KeyStore;
import java.security.KeyStoreException;
import java.security.NoSuchAlgorithmException;
import java.security.UnrecoverableKeyException;
import java.security.cert.CertificateException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

import org.apache.http.HttpVersion;
import org.apache.http.client.HttpClient;
import org.apache.http.conn.params.ConnManagerParams;
import org.apache.http.conn.params.ConnPerRouteBean;
import org.apache.http.conn.scheme.PlainSocketFactory;
import org.apache.http.conn.scheme.Scheme;
import org.apache.http.conn.scheme.SchemeRegistry;
import org.apache.http.conn.ssl.SSLSocketFactory;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.impl.conn.tsccm.ThreadSafeClientConnManager;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;
import org.apache.http.params.HttpProtocolParams;

import com.mymed.android.myjam.R;
import com.mymed.android.myjam.exception.InternalClientException;

import android.content.Context;
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

	// private static final int hpPoolSize = 1;
	private static final int lpPoolSize = 4;

	private HttpClient httpClient;
	// private ExecutorService highPriorityPool;
	private Thread highPriorityThread;
	private ExecutorService lowPriorityPool;

	private static CallManager instance;

	public static CallManager getInstance(Context context) {
		if (instance == null)
			try {
				instance = new CallManager(context);
			} catch (Exception e) {
				Log.d(TAG, e.toString());
			}
		return instance;
	}

	/**
	 * Private constructor.
	 * 
	 * @throws KeyManagementException
	 * @throws NoSuchAlgorithmException
	 * @throws KeyStoreException
	 * @throws UnrecoverableKeyException
	 */
	private CallManager(Context context) throws KeyManagementException,
			NoSuchAlgorithmException, KeyStoreException,
			UnrecoverableKeyException {

		// Create and initialize HTTP parameters
		HttpParams httpParams = new BasicHttpParams();
		/** Sets the version of the HTTP protocol to 1.1 */
		HttpProtocolParams.setVersion(httpParams, HttpVersion.HTTP_1_1);
		/** Sets a timeout until a request is established. [ms] */
		HttpConnectionParams.setConnectionTimeout(httpParams, 10000);
		/** Sets a timeout for waiting for data. [ms] */
		HttpConnectionParams.setSoTimeout(httpParams, 5000);
		/** Set the maximum number of total connections. */
		ConnManagerParams.setMaxTotalConnections(httpParams, lpPoolSize + 1);
		/** Set the maximum number of connections per route. */
		ConnManagerParams.setMaxConnectionsPerRoute(httpParams,
				new ConnPerRouteBean(lpPoolSize + 1));

		SchemeRegistry schemeRegistry = createSchemeRegistry(context);
		ThreadSafeClientConnManager connManager = new ThreadSafeClientConnManager(
				httpParams, schemeRegistry);
		httpClient = new DefaultHttpClient(connManager, httpParams);

		lowPriorityPool = Executors.newFixedThreadPool(lpPoolSize);
		// highPriorityPool = Executors.newFixedThreadPool(hpPoolSize);
		Log.i(TAG, "Executor pool created");

	}

	protected SchemeRegistry createSchemeRegistry(Context context) {
		InputStream certInStream = context.getResources().openRawResource(
				R.raw.mymed_truststore);
		SchemeRegistry schemeRegistry = new SchemeRegistry();
		// Create and initialize scheme registry
		schemeRegistry.register(new Scheme("http", PlainSocketFactory
				.getSocketFactory(), 80));
		SSLSocketFactory sslf = null;

		try {
			KeyStore mymedTrusted = KeyStore.getInstance("BKS");

			mymedTrusted.load(certInStream, "alcotra".toCharArray());

			sslf = new SSLSocketFactory(mymedTrusted);
			
			sslf.setHostnameVerifier(SSLSocketFactory.ALLOW_ALL_HOSTNAME_VERIFIER);
		} catch (KeyStoreException e) {
			Log.e(TAG, "Wrong keystore type.", e);
		} catch (KeyManagementException e) {
			Log.e(TAG, "Error creating SSLSocketFactory.", e);
		} catch (NoSuchAlgorithmException e) {
			Log.e(TAG, "Error creating SSLSocketFactory.", e);
		} catch (UnrecoverableKeyException e) {
			Log.e(TAG, "Error creating SSLSocketFactory.", e);
		} catch (CertificateException e) {
			Log.e(TAG, "Error loading keystore certificate.", e);
		} catch (IOException e) {
			Log.e(TAG, "Error creating scheme registry.", e);
		} finally {

			if (sslf != null) {
				schemeRegistry.register(new Scheme("https", sslf, 8081));
			}
			try {
				certInStream.close();
			} catch (IOException e) {
				Log.e(TAG, "Error closing the certificate stream.", e);
			}

		}
		return schemeRegistry;
	}

	/**
	 * Shut down its connection manager to ensure that all connections kept
	 * alive by the manager get closed and system resources allocated by those
	 * connections are released.
	 */
	public static void shutDown() {
		if (instance != null) {
			// instance.highPriorityPool.shutdown();
			// Log.i(TAG, "High Priority executor pool terminating...");
			if (instance.highPriorityThread != null) {
				Log.i(TAG, "High Priority executor thread terminating...");
				// Wait for the termination of the thread.
				while (instance.highPriorityThread.isAlive()) {
				}
			}
			// while (!instance.highPriorityPool.isTerminated()) {
			// }
			Log.i(TAG, "Finished all threads");
			Log.i(TAG, "Executor pool terminated.");

			instance.lowPriorityPool.shutdown();
			Log.i(TAG, "Low Priority executor pool terminating...");
			while (!instance.lowPriorityPool.isTerminated()) {
			}
			Log.i(TAG, "Finished all threads");
			Log.i(TAG, "Executor pool terminated.");

			instance.httpClient.getConnectionManager().shutdown();
			instance = null;
		}
	}

	protected HttpClient getClient() {
		return instance.httpClient;
	}

	/*
	 * protected Future<?> executeHp(Runnable runnable){ return
	 * instance.highPriorityPool.submit(runnable); }
	 */
	/**
	 * Only one high priority runnable is executed at a time and no queue is
	 * present. if another high priority call is received simply it is
	 * discarded. (This avoid to perform the same request multiple times).
	 * 
	 * @param runnable
	 */
	protected void executeHp(Runnable runnable) throws InternalClientException {
		if (instance.highPriorityThread == null
				|| !instance.highPriorityThread.isAlive()) {
			instance.highPriorityThread = new Thread(runnable, TAG);
			instance.highPriorityThread.start();
		} else {
			throw new InternalClientException(
					"Impossible start call, thread occupied.");
		}
	}

	protected Future<?> executeLp(Runnable runnable) {
		return instance.lowPriorityPool.submit(runnable);
	}

}