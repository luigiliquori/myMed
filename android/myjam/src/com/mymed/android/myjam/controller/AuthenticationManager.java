package com.mymed.android.myjam.controller;

import java.lang.reflect.Type;

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
	private static final String PROFILE_HANDLER_URL = "http://10.0.2.2:8080/LocalMyMed/ProfileRequestHandler";
	private static final String AUTHENTICATION_HANDLER_URL = "http://10.0.2.2:8080/LocalMyMed/AuthenticationRequestHandler";
	private static final String SESSION_HANDLER_URL = "http://10.0.2.2:8080/LocalMyMed/SessionRequestHandler";
	
	private static final String QUERY ="?code=";
	
	/** Request codes*/ 
	protected enum RequestCode {
		// C.R.U.D 
		CREATE 	("0"), 	
		READ 	("1"), 
		UPDATE 	("2"),
		DELETE 	("3");

		public final String code;

		RequestCode(String code){
			this.code = code;
		}
	}
	
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
		String response = httpRequest(PROFILE_HANDLER_URL+q,httpMethod.GET,null);
		Type userType = new TypeToken<MUserBean>(){}.getType();
		try{
			return this.gson.fromJson(response, userType);
		}catch(JsonSyntaxException e){
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
		String response = httpRequest(AUTHENTICATION_HANDLER_URL+q,httpMethod.GET,null);
		Type userType = new TypeToken<MUserBean>(){}.getType();
		try{
			return this.gson.fromJson(response, userType);
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
