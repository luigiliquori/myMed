package com.app.myben;

import android.graphics.Bitmap;
import android.util.Log;
import android.view.View;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ProgressBar;
import android.widget.TextView;

public class WebClient extends WebViewClient {

	private Mobile activity;

	public WebClient(Mobile activity) {
		this.activity = activity;
	}

	@Override
	public void onPageStarted(WebView view, String url, Bitmap favicon) {

		// check connection
		if (!activity.isOnline()) {
			activity.getWebView().loadUrl(
					"file:///android_asset/www/noConnection.html");
		} else {
			((ProgressBar) activity.findViewById(R.id.progressBar1))
					.setVisibility(View.VISIBLE);
			Log.v(Mobile.TAG, "URL=" + url);
		}

	}

	@Override
	public void onPageFinished(WebView view, String url) {
		ProgressBar bar = (ProgressBar) activity
				.findViewById(R.id.progressBar1);
		bar.setVisibility(View.GONE);
		Log.w(Mobile.TAG, url);

		((TextView) activity.findViewById(R.id.textView1))
				.setVisibility(View.GONE);
		activity.findViewById(R.id.imageView1).setVisibility(View.GONE);
		activity.findViewById(R.id.imageView2).setVisibility(View.GONE);
	}

}