package com.mymed.core;

import android.app.Activity;
import android.os.Bundle;
import android.webkit.WebSettings;
import android.webkit.WebView;

/**
 * 
 * @author lvanni
 */
public class Mobile extends Activity {

	private WebView webView;
	private WebClient webClient;
	private ChromeWebClient chromeWebClient;

	public static final String TAG = "*********MyMed";
	public static final String MYMED_FRONTEND_URL = "http://mymed2.sophia.inria.fr";
	public static final String MYMED_BACKEND_URL = "http://mymed2.sophia.inria.fr:8080/backend";

	public Mobile() {
		this.webClient = new WebClient(this);
		this.chromeWebClient = new ChromeWebClient(this);
	}

	/** Called when the activity is first created. */
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		setContentView(R.layout.main);
		
		// GET WEB VIEW
		webView = (WebView) findViewById(R.id.web_engine);
		setContentView(webView);

		// SET CLIENT
		webView.setWebViewClient(webClient);
		webView.setWebChromeClient(chromeWebClient);

		// setup the zoom
		webView.getSettings().setDefaultZoom(WebSettings.ZoomDensity.MEDIUM);

		// disable scrolling
		webView.setVerticalScrollBarEnabled(false);
		webView.setHorizontalScrollBarEnabled(false);

		// set settings
		WebSettings webSettings = webView.getSettings();
		webSettings.setSavePassword(true);
		webSettings.setSaveFormData(true);
		webSettings.setJavaScriptEnabled(true);
		webSettings.setSupportZoom(false);
		webSettings.setJavaScriptCanOpenWindowsAutomatically(true);
		webSettings.setGeolocationEnabled(true);
		setContentView(R.layout.main);
		
		// load myMed URL
		webView.loadUrl(MYMED_FRONTEND_URL);
		
		// launch the splash screen
//		setContentView(R.layout.splash);
//		Thread splashThread = new Thread() {
//			@Override
//			public void run() {
//				try {
//					int waited = 0;
//					while (waited < 800) {
//						sleep(400);
//						waited += 100;
//					}
//				} catch (InterruptedException e) {
//				} finally {
					// set Clients

//				}
//			}
//		};
//
//		splashThread.start();
	}

	public WebView getWebView() {
		return webView;
	}

}