package fr.mymed.mymemory;

import java.net.URLDecoder;
import java.util.Currency;

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
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.media.AudioManager;
import android.net.Uri;
import android.sax.StartElementListener;
import android.telephony.TelephonyManager;
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
				/*
				 * Logout
				 */
				if(params[1].equals("logout")){
					Log.i(Mobile.TAG, "Logout");
					boolean res = activity.moveTaskToBack(true);
					Log.i(Mobile.TAG, "Moved task : " + res);
				}
				/*
				 * phonecalls
				 */
				else if (params[1].equals("call")){

					
					int numberOfCalls = params.length -1;

					
					Intent callIntent = new Intent(Intent.ACTION_CALL);
					callIntent.setData(Uri.parse("tel:"+params[2]));
					
					// Turn the speaker On
					AudioManager audioManager = (AudioManager)activity.getSystemService(Context.AUDIO_SERVICE);
					audioManager.setSpeakerphoneOn(true);
					
					CallReceiver callReceiver = new CallReceiver();
					// Make the call
					activity.startActivity(callIntent);
					
					
					
				}
				else {
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
	@Override
	public void onPageFinished(WebView view, String url) {
		ProgressBar bar = (ProgressBar) activity.findViewById(R.id.progressBar1);
		bar.setVisibility(View.GONE);
//		if (url.matches(".*/application/"+activity.getString(R.string.app_name)+"/") || url.matches(".*mymed.fr/")){
			Log.v(Mobile.TAG, "main page load");
			((TextView) activity.findViewById(R.id.textView1)).setVisibility(View.GONE);
			activity.findViewById(R.id.imageView1).setVisibility(View.GONE);
			activity.findViewById(R.id.imageView2).setVisibility(View.GONE);
//		}
		
//		if (activity.getProgressDialog() != null) {
//			if (activity.getProgressDialog().isShowing()) {
//				activity.getProgressDialog().dismiss();
//			}
//		}
	}
	
	
//	@Override
//	public void onReceivedError(WebView view, int errorCode,
//			String description, String failingUrl) {
//
//		AlertDialog alertDialog = new AlertDialog.Builder(activity).create();
//		alertDialog.setMessage("non connecté");
//		alertDialog.show();
//	}
}