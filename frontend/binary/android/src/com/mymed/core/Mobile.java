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

	public static final String TAG = "*********MyMed";
	public static final String MYMED_FRONTEND_URL = "http://mymed2.sophia.inria.fr";
	public static final String MYMED_BACKEND_URL = "http://mymed2.sophia.inria.fr:8080/backend";

	public Mobile() {
		this.webClient = new WebClient(this);	
	}

	/** Called when the activity is first created. */
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		setContentView(R.layout.main);

		// DEFAULT SETTINGS FOR THE WEBVIEW
		webView = (WebView) findViewById(R.id.web_engine);

		// disable the toolbar
		webView.setWebViewClient(webClient); 

		// setup the zoom
		webView.getSettings().setDefaultZoom(WebSettings.ZoomDensity.MEDIUM);

		// disable scrolling
		webView.setVerticalScrollBarEnabled(false);
		webView.setHorizontalScrollBarEnabled(false);

		// enable javascript
		webView.getSettings().setJavaScriptEnabled(true);

		webView.loadUrl(MYMED_FRONTEND_URL); 
	}

	public WebView getWebView() {
		return webView;
	}

}