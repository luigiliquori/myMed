package com.app.myriviera;

import android.annotation.TargetApi;
import android.app.AlertDialog;
import android.util.Log;
import android.webkit.GeolocationPermissions;
import android.webkit.JsResult;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.widget.ProgressBar;

import com.app.myriviera.R;

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
	
	
	@Override
    public void onProgressChanged(WebView view, int newProgress) {

		ProgressBar bar = (ProgressBar) activity.findViewById(R.id.progressBar1);
		//bar.setVisibility(View.VISIBLE);
		bar.setProgress(newProgress);
    }

	@TargetApi(5)
	public void onGeolocationPermissionsShowPrompt(String origin, GeolocationPermissions.Callback callback) {
		callback.invoke(origin, true, false);
	}
}
