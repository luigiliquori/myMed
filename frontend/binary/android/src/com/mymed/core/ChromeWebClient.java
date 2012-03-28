package com.mymed.core;

import android.app.AlertDialog;
import android.util.Log;
import android.webkit.GeolocationPermissions;
import android.webkit.JsResult;
import android.webkit.WebChromeClient;
import android.webkit.WebView;

public class ChromeWebClient extends WebChromeClient {

	private Mobile activity;

	public ChromeWebClient(Mobile activity){
		this.activity = activity;
	}

	@Override
	public boolean onJsAlert(WebView view, String url, String message, JsResult result) {
		Log.d(Mobile.TAG, message);
		// This shows the dialog box.  This can be commented out for dev
		AlertDialog.Builder alertBldr = new AlertDialog.Builder(activity);
		alertBldr.setMessage(message);
		alertBldr.setTitle("Alert");
		alertBldr.show();
		result.confirm();
		return true;
	}

	public void onGeolocationPermissionsShowPrompt(String origin, GeolocationPermissions.Callback callback) {
		callback.invoke(origin, true, false);
	}
}
