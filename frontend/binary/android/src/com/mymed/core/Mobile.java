package com.mymed.core;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.ViewSwitcher;

/**
 * 
 * @author lvanni
 */
public class Mobile extends Activity {

	private WebView webView;
	private WebClient webClient;

	private ChromeWebClient chromeWebClient;
	private ProgressDialog progressDialog;
	
	private ViewSwitcher switcher;

	public static final String TAG = "*********MyMed";
	public static final String MYMED_FRONTEND_URL = "http://mymed.fr/application/myRiviera";
	public static final String MYMED_BACKEND_URL = "http://mymed.fr:8080/backend";

	public Mobile() {
		this.webClient = new WebClient(this);
		this.chromeWebClient = new ChromeWebClient(this);
	}

	/** Called when the activity is first created. */
	@TargetApi(7)
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.main);
//		switcher = (ViewSwitcher) findViewById(R.id.viewSwitcher);
//		switcher.showNext();
//		switcher.setBackgroundColor(Color.argb(255, 255, 255, 255));
		// GET WEB VIEW
		webView = (WebView) findViewById(R.id.web_engine);
		
		//webView.setBackgroundColor(Color.argb(55, 55, 255, 120));
		//webView.setBackgroundResource(R.drawable.splash);

		// SET CLIENT
		webView.setWebViewClient(webClient);
		webView.setWebChromeClient(chromeWebClient);

		// disable scrolling
		webView.setVerticalScrollBarEnabled(false);
		webView.setHorizontalScrollBarEnabled(false);

		// set settings
		WebSettings webSettings = webView.getSettings();
		webSettings.setDefaultZoom(WebSettings.ZoomDensity.MEDIUM);
		webSettings.setSavePassword(true);
		webSettings.setSaveFormData(true);
		webSettings.setJavaScriptEnabled(true);
		webSettings.setSupportZoom(false);
		webSettings.setJavaScriptCanOpenWindowsAutomatically(true);
		webSettings.setGeolocationEnabled(true);
		if (isOnline()) {
			// load myMed URL
			webView.loadUrl(MYMED_FRONTEND_URL);
		} else {
			// 
			AlertDialog alertDialog = new AlertDialog.Builder(this).create();
			alertDialog.setMessage("Aucune connexion détectée");
			alertDialog.show();
			//webView.loadUrl("file:///android_asset/www/indexOffline.html");
		}
	}
	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
	    if ((keyCode == KeyEvent.KEYCODE_BACK) && webView.canGoBack()) {
	    	webView.goBack();
	        return true;
	    }
	    return super.onKeyDown(keyCode, event);
	}

	public WebView getWebView() {
		return webView;
	}
	
	public boolean isOnline() {
		ConnectivityManager cm =
				(ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);

		return cm.getActiveNetworkInfo() != null && 
				cm.getActiveNetworkInfo().isConnectedOrConnecting();
	}
	
	public ProgressDialog getProgressDialog() {
		return progressDialog;
	}

	public void setProgressDialog(ProgressDialog progressDialog) {
		this.progressDialog = progressDialog;
	}
	
	public ViewSwitcher getSwitcher() {
		return switcher;
	}

	public void setSwitcher(ViewSwitcher switcher) {
		this.switcher = switcher;
	}


}