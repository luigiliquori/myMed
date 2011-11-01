package com.mymed.android.myjam.ui;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.mymed.android.myjam.R;
import com.mymed.android.myjam.controller.ICallAttributes;
import com.mymed.android.myjam.provider.MyJamContract.Login;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.User;
import com.mymed.android.myjam.service.MyJamCallService;
import com.mymed.android.myjam.service.MyJamCallService.RequestCode;
import com.mymed.utils.GlobalStateAndUtils;
import com.mymed.utils.HexEncoder;
import com.mymed.utils.MyResultReceiver;

/**
 * Activity that handles login and logout.
 * @author iacopo
 *
 */
public class LoginActivity extends Activity implements MyResultReceiver.Receiver {
	private static final String TAG = "LoginActivity";
	
	private MyResultReceiver mResultReceiver;
	private GlobalStateAndUtils mGlobalState;
	
	private static final int LOG_IN = 0x0;
	private static final int LOG_OUT = 0x1;
	
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
			User.QUALIFIER+User.USER_NAME					//5
		};
		
		int LOGIN = 0;
		int PASSWORD = 1;
		int USER_ID = 2;
		int LOGGED = 3;
		//TODO Not used at the moment. int DATE = 4;
		int USER_NAME = 5;
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
		
		mResultReceiver.setReceiver(this);
		
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
		}else{
			instance.setUserId(null);
			instance.setLogin(null);
			instance.setPassword(null);
			instance.setLogged(false);
			instance.setPassword(null);
		}
		searchCursor.close();
	}
	
	@Override 
	protected void onPause() {
		super.onPause();
		mResultReceiver.clearReceiver();
	}
	
	@Override
	protected void onStop() {
		super.onStop();
	}
	
	@Override
	public void onDestroy() {
		super.onDestroy();
		requestLogout(mGlobalState.getUserId());
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
				//TODO add empty test.
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
					requestLogout(mGlobalState.getUserId());
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
		final Intent intent = new Intent(LoginActivity.this, MyJamCallService.class);
        intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
        intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.LOG_IN);
        Bundle bundle = new Bundle();
        bundle.putString(ICallAttributes.LOGIN, login);
        bundle.putString(ICallAttributes.PASSWORD, pwd);
        bundle.putString(ICallAttributes.IP, "0.0.0.0"); //TODO Fake IP
        intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);
        Log.d(TAG,"Intent sent: "+intent.toString());
        startService(intent);
	}
	
	private void requestLogout(String userId){
		final Intent intent = new Intent(LoginActivity.this, MyJamCallService.class);
        intent.putExtra(MyJamCallService.EXTRA_STATUS_RECEIVER, mResultReceiver);
        intent.putExtra(MyJamCallService.EXTRA_REQUEST_CODE, RequestCode.COMPLETE_LOG_OUT);
        Bundle bundle = new Bundle();
        bundle.putString(ICallAttributes.USER_ID, userId);
        intent.putExtra(MyJamCallService.EXTRA_ATTRIBUTES, bundle);
        Log.d(TAG,"Intent sent: "+intent.toString());
        startService(intent);
	}
	
	private void startSearchActivity(){
		final Intent intent = new Intent(LoginActivity.this, SearchActivity.class);
        intent.setData(SearchReports.buildSearchUri(String.valueOf(Search.NEW_SEARCH)));
        Log.d(TAG,"Intent sent: "+intent.toString());
        startActivity(intent);
	}
		
	@Override
	public void onReceiveResult(int resultCode, Bundle resultData) {
		boolean mSyncing = false;
		int code = LOG_IN;
		
		int reqCode = resultData.getInt(MyJamCallService.EXTRA_REQUEST_CODE);
		switch (resultCode) {
		case MyJamCallService.STATUS_RUNNING:
			mSyncing = true;
			if (reqCode == RequestCode.LOG_IN)
				code=LOG_IN;
			else if (reqCode == RequestCode.COMPLETE_LOG_OUT || reqCode == RequestCode.PARTIAL_LOG_OUT)
				code=LOG_OUT;
			break;
		case MyJamCallService.STATUS_FINISHED: 
			mSyncing = false;
			updateLoginStatus();
			if (reqCode == RequestCode.LOG_IN){
				Toast.makeText(this, getResources().getString(R.string.logged_in_msg), Toast.LENGTH_SHORT).show();
				startSearchActivity();
			}else if (reqCode == RequestCode.COMPLETE_LOG_OUT || reqCode == RequestCode.PARTIAL_LOG_OUT){
				Toast.makeText(this, getResources().getString(R.string.logged_out_msg), Toast.LENGTH_SHORT).show();
				createLogInLayout();
			}
			break;
		case MyJamCallService.STATUS_ERROR:
			// Error happened down in SyncService, show as toast.
			mSyncing = false;
			String errMsg = resultData.getString(Intent.EXTRA_TEXT);
			final String errorText = String.format(this.getResources().getString(R.string.toast_call_error, errMsg));
			Toast.makeText(this, errorText, Toast.LENGTH_SHORT).show();
			break;
		}		
		updateRefreshStatus(mSyncing,code);
	}
	
	/**
	 * Makes appear and disappear the progress dialog.
	 * @param refreshing 	If true then the dialog appear, 
	 * 						if false the dialog disappear.
	 */
    private void updateRefreshStatus(boolean refreshing,int code) {
        if (refreshing){
        	mDialog = ProgressDialog.show(LoginActivity.this, "", 
					getResources().getString(code==LOG_IN?R.string.logging_in_msg:R.string.logging_out_msg), true);
        }else{
			if (mDialog != null)
				mDialog.dismiss();
        }
        	
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


}
