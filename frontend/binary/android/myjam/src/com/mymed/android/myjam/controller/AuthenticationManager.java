package com.mymed.android.myjam.controller;

import java.lang.reflect.Type;

import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

import com.google.gson.Gson;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.model.data.user.MUserBean;

/**
 * Class that provides methods to authenticate.
 * @author iacopo
 *
 */
public class AuthenticationManager extends HTTPCall implements ICallAttributes {
	/*
	 *		Attributes. 
	 */
/**	private static final String PROFILE_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/ProfileRequestHandler";
	private static final String AUTHENTICATION_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/AuthenticationRequestHandler";
	private static final String SESSION_HANDLER_URL = "http://130.192.9.113:8080/mymed_backend/SessionRequestHandler";
//*/
 	//For local testing with the emulator.

	private static final String PROFILE_HANDLER_URL = "http://10.0.2.2:8080/mymed_backend/ProfileRequestHandler";
	private static final String AUTHENTICATION_HANDLER_URL = "http://10.0.2.2:8080/mymed_backend/AuthenticationRequestHandler";
	private static final String SESSION_HANDLER_URL = "http://10.0.2.2:8080/mymed_backend/SessionRequestHandler";
//*/	
	private static final String QUERY ="?code=";
	
	private Gson gson;
	
	public AuthenticationManager(){
		gson = new Gson();
	};

	
	public MUserBean createUser(MUserBean user){
		return user;		
	}
	
	/**
	 * Gets the profile of the user having ID {@link userId}
	 * @param userId 	ID of the profile.
	 * @return
	 * @throws InternalBackEndException	Error server side.
	 * @throws IOBackEndException		User corresponding to {@link userId} doesn't exist.
	 * @throws InternalClientException	Error client side.
	 */
	public MUserBean getUser(String userId) 
			throws InternalBackEndException, IOBackEndException, InternalClientException{
		
		String q=QUERY+RequestCode.READ.code;
		q=appendAttribute(q,ID,userId);
		JSONObject response;
		try{
			response = (JSONObject) new JSONTokener(httpRequest(PROFILE_HANDLER_URL+q,HttpMethod.POST,null)).nextValue();
			JSONObject data = response.getJSONObject("data");
			String profile = data.getString("profile");
			Type userType = new TypeToken<MUserBean>(){}.getType();
			return this.gson.fromJson(profile, userType);
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}		
	}
	
	/**
	 * Performs an authentication request. 
	 * @param authentication	{@link MauthenticationBean} containing authentication info.
	 * @param profile	{@link MUserBean} Containing user profile.
	 * @return	User profile if authentication succeeded.
	 * @throws InternalBackEndException	Error server side.
	 * @throws IOBackEndException		Authentication fails.
	 * @throws InternalClientException	Error client side.
	 */
	public MUserBean authenticate(String login,String password) 
			throws InternalBackEndException, IOBackEndException, InternalClientException{
		
		String q=QUERY+RequestCode.READ.code;
		q=appendAttribute(q,LOGIN,login);
		q=appendAttribute(q,PASSWORD,password);

		JSONObject response;
		try {
			response = (JSONObject) new JSONTokener(httpRequest(AUTHENTICATION_HANDLER_URL+q,httpMethod.POST,null)).nextValue();
			JSONObject data = response.getJSONObject("data");
			String profile = data.getString("user");
			Type userType = new TypeToken<MUserBean>(){}.getType();
			return this.gson.fromJson(profile, userType);
		} catch (JSONException e) {
			throw new InternalBackEndException("Wrong response format.");
		}catch(JsonSyntaxException e){
			throw new InternalBackEndException("Wrong response format.");
		}		
	}
	
	/**
	 * Logs In to myMed, creates a session.
	 * @param userId	ID of the user profile on myMed
	 * @param ip		ip address of the user.
	 * @throws InternalBackEndException	Error server side.
	 * @throws IOBackEndException		Could never happen.
	 * @throws InternalClientException	Error client side.
	 */
	public void logIn(String userId,String ip) 
			throws InternalBackEndException, IOBackEndException, InternalClientException{
		
		String q=QUERY+RequestCode.CREATE.code;
		q=appendAttribute(q,USER_ID,userId);
		q=appendAttribute(q,IP,ip);
		httpRequest(SESSION_HANDLER_URL+q,httpMethod.POST,null);
	}
	
	/**
	 * Logs Out from myMed, deletes the session.
	 * @param userId	ID of the user profile on myMed
	 * @param ip		ip address of the user.
	 * @throws InternalBackEndException	Error server side.
	 * @throws IOBackEndException		Could never happen.
	 * @throws InternalClientException	Error client side.
	 */
	public void logOut(String userId) 
			throws InternalBackEndException, IOBackEndException, InternalClientException{
		
		String q=QUERY+RequestCode.DELETE.code;
		q=appendAttribute(q,USER_ID,userId);
		httpRequest(SESSION_HANDLER_URL+q,httpMethod.DELETE,null);
	}

}
