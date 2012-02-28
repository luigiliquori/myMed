package com.mymed.android.myjam.ui;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.ContentObserver;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.mymed.android.myjam.R;
import com.mymed.android.myjam.controller.CallContract;
import com.mymed.android.myjam.controller.CallContract.CallCode;
import com.mymed.android.myjam.controller.HttpCall;
import com.mymed.android.myjam.provider.MyJamContract.Login;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.User;
import com.mymed.android.myjam.service.CallService;
import com.mymed.utils.GlobalStateAndUtils;
import com.mymed.utils.HexEncoder;
import com.mymed.utils.MyResultReceiver;
import com.mymed.utils.MyResultReceiver.IReceiver;

/**
 * Activity that handles login and logout.
 * @author iacopo
 *
 */
public class LoginActivity extends Activity implements IReceiver {
	private static final String TAG = "LoginActivity";
	
	private MyResultReceiver mResultReceiver;
	private GlobalStateAndUtils mGlobalState;
	
	/* WIDGETS */
	private Button mLogButton;
	private Button mHomeButton;	
	private EditText mLoginEditText;
	private EditText mPwdEditText;
	private TextView mLoggedTextView;
	private TextView mLoginTextView;
	private TextView mPwdTextView;
	private ProgressDialog mDialog;
	private TextView mSignInLink;
	
	/** Dialogs */
	static final int DIALOG_NULL_LOGIN_ID = 0x0;
	static final int DIALOG_NULL_PWD_ID = 0x1;
	
	private interface LoginQuery{
		//TODO Add distance to order in a proper way.
		String[] PROJECTION = new String[] {
			Login.QUALIFIER+Login.LOGIN_ID,					//0
			Login.QUALIFIER+Login.PASSWORD,					//1
			Login.QUALIFIER+Login.USER_ID,					//2
			Login.QUALIFIER+Login.LOGGED,					//3
			Login.QUALIFIER+Login.DATE,						//4
			Login.QUALIFIER+Login.ACCESS_TOKEN,				//5
			User.QUALIFIER+User.USER_NAME					//6
		};
		
		int LOGIN = 0;
		int PASSWORD = 1;
		int USER_ID = 2;
		int LOGGED = 3;
		int ACCESS_TOKEN = 5;
		//TODO Not used at the moment. int DATE = 4;
		int USER_NAME = 6;
	}

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.login_view);
		mLogButton = (Button) findViewById(R.id.buttonLogIn);
		mHomeButton = (Button) findViewById(R.id.buttonHome);
		mLoginEditText = (EditText) findViewById(R.id.editTextLogin);
		mPwdEditText = (EditText) findViewById(R.id.editTextPwd);
		mLoginTextView = (TextView) findViewById(R.id.textViewLogin);
		mPwdTextView = (TextView) findViewById(R.id.textViewPwd);
		mLoggedTextView = (TextView) findViewById(R.id.textViewLogged);
		mSignInLink = (TextView) findViewById(R.id.textViewSignIn);
		mSignInLink.setOnClickListener(new View.OnClickListener() {
			public void onClick(View v) {
				Uri uri = Uri.parse( "http://130.192.9.113/mobile/#login&ui-state=dialog" );
				startActivity( new Intent( Intent.ACTION_VIEW, uri ) );
			}
		});
		mHomeButton.setOnClickListener(new View.OnClickListener() {
			public void onClick(View v) {
				startSearchActivity();
			}
		});
		
		mResultReceiver = MyResultReceiver.getInstance();
		mGlobalState = GlobalStateAndUtils.getInstance(getApplicationContext());
	}
	
	@Override
	protected void onStart() {
		super.onStart();
	}
	
	@Override 
	protected void onResume() {
		super.onResume();
		
		mResultReceiver.setReceiver(this.getClass().getName(),this);
		refreshLayout();
		getContentResolver().registerContentObserver(
				Login.CONTENT_URI,false, mSessionChangesObserver);
	}
	
	/**
	 * 	Set the layout according to the login status.
	 */
	private void refreshLayout(){
		updateLoginStatus();
		if (mGlobalState.isLogged()){
				createLogOutLayout();
		}else{
			if (mGlobalState.getUserId()!=null && mGlobalState.getPassword()!=null){
				requestLogin(mGlobalState.getUserId(),
						mGlobalState.getPassword());
			}else{
			createLogInLayout();
			}
		}
	}
	
	/**
	 * Updates the status of the login on {@link GlobalStateAndUtils}.
	 */
	private void updateLoginStatus(){
		GlobalStateAndUtils instance = GlobalStateAndUtils.getInstance(getApplicationContext());
		
		Cursor searchCursor = getContentResolver().query(Login.CONTENT_URI,LoginQuery.PROJECTION, 
				null, null, null);
		if (searchCursor.moveToFirst()){
			instance.setUserId(searchCursor.getString(LoginQuery.USER_ID));
			instance.setLogin(searchCursor.getString(LoginQuery.LOGIN));
			instance.setPassword(searchCursor.getString(LoginQuery.PASSWORD));
			instance.setLogged(searchCursor.getInt(LoginQuery.LOGGED)==1?true:false);
			instance.setUserName(searchCursor.getString(LoginQuery.USER_NAME));
			instance.setAccessToken(searchCursor.getString(LoginQuery.ACCESS_TOKEN));
			checkAccessToken(instance.getAccessToken());
		}else{
			instance.setUserId(null);
			instance.setLogin(null);
			instance.setPassword(null);
			instance.setLogged(false);
			instance.setPassword(null);
			instance.setAccessToken(null);
		}
		searchCursor.close();
	}
	
	/**
	 * 	Executed when a change on Login table is notified.
	 */
    private ContentObserver mSessionChangesObserver = new ContentObserver(new Handler()) {
        @Override
        public void onChange(boolean selfChange) {
            refreshLayout();
        }
    };
	
	@Override 
	protected void onPause() {
		super.onPause();
		mResultReceiver.clearReceiver();
		getContentResolver().unregisterContentObserver(mSessionChangesObserver);
	}
	
	@Override
	protected void onStop() {
		super.onStop();
	}
	
	@Override
	public void onDestroy() {
		super.onDestroy();
		requestLogout(mGlobalState.getAccessToken());
		GlobalStateAndUtils.releaseResources();
		MyResultReceiver.shutdown();
	}

	private void createLogInLayout(){
		mLoginEditText.setVisibility(View.VISIBLE);
		mPwdEditText.setVisibility(View.VISIBLE);
		mLoginTextView.setVisibility(View.VISIBLE);
		mPwdTextView.setVisibility(View.VISIBLE);
		mHomeButton.setVisibility(View.GONE);
		mLoggedTextView.setVisibility(View.GONE);
		mLogButton.setText(getResources().getString(R.string.login_button_label));
		mLogButton.setOnClickListener(new View.OnClickListener() {
			public void onClick(View v) {
				if (mLoginEditText.getText().length() == 0){
					showDialog(DIALOG_NULL_LOGIN_ID);
					return;
				}
				if (mPwdEditText.getText().length() == 0){
					showDialog(DIALOG_NULL_PWD_ID);
					return;
				}					
				String login = mLoginEditText.getText().toString();
				String pwd = mPwdEditText.getText().toString();				
				requestLogin(login,getSHA512pwd(pwd));
				mLoginEditText.setText("");
				mPwdEditText.setText("");
			}
		});
	}
	
	private void createLogOutLayout(){
		mLoginEditText.setVisibility(View.GONE);
		mPwdEditText.setVisibility(View.GONE);
		mLoginTextView.setVisibility(View.GONE);
		mPwdTextView.setVisibility(View.GONE);
		mHomeButton.setVisibility(View.VISIBLE);
		mLoggedTextView.setVisibility(View.VISIBLE);
		mLoggedTextView.setText(String.format(getResources().getString(R.string.logged_text_view_label),mGlobalState.getUserName()));
		mLogButton.setText(getResources().getString(R.string.logout_button_label));
		mLogButton.setOnClickListener(new View.OnClickListener() {
			public void onClick(View v) {
				if (mGlobalState.isLogged())
					requestLogout(mGlobalState.getAccessToken());
			}
		});
		mHomeButton.setOnClickListener(new View.OnClickListener() {
			public void onClick(View v) {
				startSearchActivity();
			}
		});		
	}
	
	private String getSHA512pwd(String pwd){
		String SHA512pwd = null;
		
		try {
			MessageDigest md = MessageDigest.getInstance("SHA-512");
			byte[] digest = md.digest(pwd.getBytes());
			SHA512pwd = String.copyValueOf(HexEncoder.encodeHex(digest));
		} catch (NoSuchAlgorithmException e) {
			Log.e(TAG, "Unknown digest algorithm.");
		}
		return SHA512pwd;
	}
	
	private void requestLogin(String login,String pwd){
		final Intent intent = new Intent(LoginActivity.this, CallService.class);
		intent.putExtra(CallService.EXTRA_ACTIVITY_ID, this.getClass().getName());
		intent.putExtra(CallService.EXTRA_REQUEST_CODE, CallCode.LOG_IN);
		intent.putExtra(CallService.EXTRA_PRIORITY_CODE, HttpCall.HIGH_PRIORITY);
		intent.putExtra(CallService.EXTRA_NUMBER_ATTEMPTS, 1);
        Bundle bundle = new Bundle();
        bundle.putString(CallContract.LOGIN, login);
        bundle.putString(CallContract.PASSWORD, pwd);
        intent.putExtra(CallService.EXTRA_ATTRIBUTES, bundle);
        Log.d(TAG,"Intent sent: "+intent.toString());
        startService(intent);
//		startSearchActivity();
	}
	
	private void requestLogout(String accessToken){
		final Intent intent = new Intent(LoginActivity.this, CallService.class);
		intent.putExtra(CallService.EXTRA_ACTIVITY_ID, this.getClass().getName());
		intent.putExtra(CallService.EXTRA_REQUEST_CODE, CallCode.LOG_OUT);
		intent.putExtra(CallService.EXTRA_PRIORITY_CODE, HttpCall.HIGH_PRIORITY);
		intent.putExtra(CallService.EXTRA_NUMBER_ATTEMPTS, 1);
        Bundle bundle = new Bundle();
        bundle.putString(CallContract.ACCESS_TOKEN, accessToken);
        bundle.putString(CallContract.SOCIAL_NETWORK, "");
        intent.putExtra(CallService.EXTRA_ATTRIBUTES, bundle);
        Log.d(TAG,"Intent sent: "+intent.toString());
        startService(intent);
	}
	
	private void checkAccessToken(String accessToken){
		final Intent intent = new Intent(LoginActivity.this, CallService.class);
		intent.putExtra(CallService.EXTRA_ACTIVITY_ID, this.getClass().getName());
		intent.putExtra(CallService.EXTRA_REQUEST_CODE, CallCode.CHECK_ACCESS_TOKEN);
		intent.putExtra(CallService.EXTRA_PRIORITY_CODE, HttpCall.LOW_PRIORITY);
		intent.putExtra(CallService.EXTRA_NUMBER_ATTEMPTS, 1);
        Bundle bundle = new Bundle();
        bundle.putString(CallContract.ACCESS_TOKEN, accessToken);
        bundle.putString(CallContract.SOCIAL_NETWORK, "");
        intent.putExtra(CallService.EXTRA_ATTRIBUTES, bundle);
        Log.d(TAG,"Intent sent: "+intent.toString());
        startService(intent);
	}
	
//	/**
//	 * Request the interruption of the current HP call.
//	 * 
//	 * @return {@value true} if there is an HP call ongoing, {@value false} otherwise. 
//	 */
//	private boolean requestInterruptCall(){
//		final Intent intent = new Intent(LoginActivity.this, CallService.class);
//		final int[] ongCallDetails;
//		if ((ongCallDetails=MyResultReceiver.getInstance().getOngoingHPCall())!=null){
//			intent.putExtra(CallService.EXTRA_ACTIVITY_ID, this.getClass().getName());
//			intent.putExtra(CallService.EXTRA_CALL_ID, ongCallDetails[0]);
//	        startService(intent);
//	        return true;
//		}else{
//			return false;
//		}
//
//	}
	
	private void startSearchActivity(){
		final Intent intent = new Intent(LoginActivity.this, SearchActivity.class);
        intent.setData(SearchReports.buildSearchUri(String.valueOf(Search.NEW_SEARCH)));
        Log.d(TAG,"Intent sent: "+intent.toString());
        startActivity(intent);
	}
		
	
    protected Dialog onCreateDialog(int id) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
		builder.setMessage(getResources().getString(R.string.loc_not_available_dialog_message))
			.setTitle(R.string.dialog_title)
			.setIcon(R.drawable.myjam_icon)
			.setCancelable(false)       	
			.setPositiveButton(R.string.positive_button_label, new DialogInterface.OnClickListener() {
				public void onClick(DialogInterface dialog, int id) {
					dialog.cancel();
				}
			});
        switch(id) {
        case DIALOG_NULL_LOGIN_ID:
        	builder.setMessage(R.string.empty_login_msg);
        	break;
        case DIALOG_NULL_PWD_ID:
        	builder.setMessage(R.string.empty_pwd_msg);
        	break;
        default:
            return null;
        }
        return builder.create();
    }

	@Override
	public void onUpdateProgressStatus(boolean state, int callCode, int callId) {
		updateRefreshStatus(state,callCode);
	}

	@Override
	public void onCallError(int callCode, int callId, String errorMessage,
			int numAttempt, int maxAttempts) {
		//String errMsg = resultData.getString(Intent.EXTRA_TEXT);
		final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errorMessage));
		Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
	}

	@Override
	public void onCallInterrupted(int callCode, int callId) {
		// TODO Auto-generated method stub
		
	}
	
	@Override
	public void onReceiveResult(int resultCode, Bundle resultData) {
//		boolean mSyncing = false;
//		int code = LOG_IN;
//		
//		final MCallBean call = (MCallBean) resultData.getSerializable(CallService.CALL_BEAN);
//		switch (resultCode) {
//		case HttpCallHandler.MSG_CALL_START:
//			mSyncing = true;
//			if (call.getCallCode() == RequestCode.LOG_IN)
//				code=LOG_IN;
//			else if (call.getCallCode() == RequestCode.LOG_OUT)
//				code=LOG_OUT;
//			break;
//		case HttpCallHandler.MSG_CALL_SUCCESS: 
//			mSyncing = false;
//			updateLoginStatus();
//			if (call.getCallCode() == RequestCode.LOG_IN){
//				Toast.makeText(this, getResources().getString(R.string.logged_in_msg), Toast.LENGTH_SHORT).show();
//				startSearchActivity();
//			}else if (call.getCallCode() == RequestCode.LOG_OUT){
//				Toast.makeText(this, getResources().getString(R.string.logged_out_msg), Toast.LENGTH_SHORT).show();
//				createLogInLayout();
//			}
//			break;
//		case HttpCallHandler.MSG_CALL_ERROR:
//			// Error happened down in SyncService, show as toast.
//			mSyncing = false;
//			String errMsg = resultData.getString(Intent.EXTRA_TEXT);
//			final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
//			Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
//			break;
//		}		
//		
	}
	
	/**
	 * Makes appear and disappear the progress dialog.
	 * @param refreshing 	If true then the dialog appear, 
	 * 						if false the dialog disappear.
	 */
    private void updateRefreshStatus(boolean refreshing,int code) {
        if (refreshing){
        	mDialog = ProgressDialog.show(LoginActivity.this, "", 
					getResources().getString(code==CallCode.LOG_IN?R.string.logging_in_msg:R.string.logging_out_msg), true);
         	mDialog.setOnKeyListener(new DialogInterface.OnKeyListener() {
				@Override
				public boolean onKey(DialogInterface dialog, int keyCode, KeyEvent event) {
					if (keyCode == KeyEvent.KEYCODE_SEARCH && event.getRepeatCount() == 0) {
						return true; // Pretend we processed it
					}else if (keyCode == KeyEvent.KEYCODE_BACK && event.getRepeatCount() == 0){
						//The calls of LoginActivity are not stoppable.
						if (MyResultReceiver.getInstance().getOngoingHPCall()==null && mDialog!=null)
							mDialog.dismiss();
						return true;
					}
					return false; // Any other keys are still processed as normal
				}
         	});
        }else{
			if (mDialog != null)
				mDialog.dismiss();
        }
        	
    }

	@Override
	public void onCallSuccess(int callCode, int callId) {
		updateLoginStatus();
		if (callCode == CallCode.LOG_IN){
			Toast.makeText(this, getResources().getString(R.string.logged_in_msg), Toast.LENGTH_SHORT).show();
			startSearchActivity();
		}else if (callCode == CallCode.LOG_OUT){
			Toast.makeText(this, getResources().getString(R.string.logged_out_msg), Toast.LENGTH_SHORT).show();
			createLogInLayout();
		}
	}

	@Override
	public void onServiceDestroyed() {
		// TODO Auto-generated method stub
		
	}



}
