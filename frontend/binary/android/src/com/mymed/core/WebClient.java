package com.mymed.core;

import java.net.URLDecoder;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;

import android.app.AlertDialog;
import android.graphics.Bitmap;
import android.util.Log;
import android.view.View;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ProgressBar;
import android.widget.TextView;

public class WebClient extends WebViewClient {

	private Mobile activity;

	private static final int CREATE = 0;
	private static final int READ = 1;
	private static final int UPDATE = 2;
	private static final int DELETE = 3;
	
	public WebClient(Mobile activity){
		this.activity = activity;
	}

	@Override
	public void onPageStarted(WebView view, String url, Bitmap favicon) {
		
		((ProgressBar) activity.findViewById(R.id.progressBar1)).setVisibility(View.VISIBLE);
		
		Log.v(Mobile.TAG, "*****************URL=" + url);
		if(url.matches(".*mobile_binary::.*")){
			Log.i(Mobile.TAG, "Receive a mobile API call");
			String params[] = url.split("::");
			if(params.length >= 2) {
				if(params[1].equals("logout")){
					Log.i(Mobile.TAG, "Logout");
					activity.getWebView().loadUrl(Mobile.MYMED_FRONTEND_URL + "?disconnect=1");
				}else if(params[1].equals("choose_picture")){
						Log.i(Mobile.TAG, "choose_picture");
						Preview mPreview = new Preview(activity);
						activity.setContentView(mPreview);
				} else if(params[1].equals("publish")){
					Log.i(Mobile.TAG, "publish");
					if(params.length == 7) {
						// CRAFT THE POST REQUEST
						try {
							HttpClient client = new DefaultHttpClient();  
							String postURL = Mobile.MYMED_BACKEND_URL + "/PublishRequestHandler";
							HttpPost post = new HttpPost(postURL);
							MultipartEntity reqEntity = new MultipartEntity(HttpMultipartMode.BROWSER_COMPATIBLE);  
							reqEntity.addPart("code", new StringBody(CREATE + ""));
							reqEntity.addPart("application", new StringBody(params[2]));
							reqEntity.addPart("user", new StringBody(URLDecoder.decode(params[3])));
							reqEntity.addPart("predicate", new StringBody(URLDecoder.decode(params[4])));
							reqEntity.addPart("data", new StringBody(URLDecoder.decode(params[5])));
							reqEntity.addPart("accessToken", new StringBody(URLDecoder.decode(params[6])));
							post.setEntity(reqEntity);  
							
							HttpResponse response = client.execute(post);  
							HttpEntity resEntity = response.getEntity();  
							//							if (resEntity != null) {    
							Log.i(Mobile.TAG, "Response: " + EntityUtils.toString(resEntity));
							activity.getWebView().loadUrl(Mobile.MYMED_FRONTEND_URL + "?application=" + params[2]);
							//							}
						} catch (Exception e) {
							e.printStackTrace();
							Log.e(Mobile.TAG, e.getMessage());
						}
					} else {
						Log.w(Mobile.TAG, "Argument missing! ");
					}
				} else {
					Log.w(Mobile.TAG, "Unknown Method: " + params[1]);
				}
			} else {
				Log.w(Mobile.TAG, "Argument missing! ");
			}
		}
	}


//	public void onLoadResource (WebView view, String url) {
//		;
//		if (activity.getProgressDialog() == null && !url.matches(".*map.*")) {
//			ProgressDialog dialog = ProgressDialog.show(activity, "", "Chargement en cours...", true);
//			activity.setProgressDialog(dialog);
//		}
//	}
//
	public void onPageFinished(WebView view, String url) {
		ProgressBar bar = (ProgressBar) activity.findViewById(R.id.progressBar1);
		bar.setVisibility(View.GONE);
		if (url.matches(".*/application/"+activity.getString(R.string.app_name)+"/") || url.matches(".*mymed.fr/")){
			Log.v(Mobile.TAG, "main page load");
			((TextView) activity.findViewById(R.id.textView1)).setVisibility(View.GONE);	
		}
		
//		if (activity.getProgressDialog() != null) {
//			if (activity.getProgressDialog().isShowing()) {
//				activity.getProgressDialog().dismiss();
//			}
//		}
	}
	
	
	@Override
	public void onReceivedError(WebView view, int errorCode,
			String description, String failingUrl) {

		AlertDialog alertDialog = new AlertDialog.Builder(activity).create();
		alertDialog.setMessage("non connecté");
		alertDialog.show();
	}
}
