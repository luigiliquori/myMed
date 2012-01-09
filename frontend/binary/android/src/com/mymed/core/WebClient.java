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

import android.app.ProgressDialog;
import android.graphics.Bitmap;
import android.util.Log;
import android.webkit.WebView;
import android.webkit.WebViewClient;

public class WebClient extends WebViewClient {

	private Mobile activity;
	private ProgressDialog progressDialog;

	private static final int CREATE = 0;
	private static final int READ = 1;
	private static final int UPDATE = 2;
	private static final int DELETE = 3;
	
	public WebClient(Mobile activity){
		this.activity = activity;
	}

	@Override
	public void onPageStarted(WebView view, String url, Bitmap favicon) {
		Log.v(Mobile.TAG, "URL=" + url);
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


	public void onLoadResource (WebView view, String url) {
		if (progressDialog == null && !url.matches(".*map.*")) {
			progressDialog = new ProgressDialog(activity);
			progressDialog.setMessage("Chargement en cours...");
			progressDialog.show();
		}
	}

	public void onPageFinished(WebView view, String url) {
		if (progressDialog != null) {
			if (progressDialog.isShowing()) {
				progressDialog.dismiss();
				progressDialog = null;
			}
		}
	}
}
