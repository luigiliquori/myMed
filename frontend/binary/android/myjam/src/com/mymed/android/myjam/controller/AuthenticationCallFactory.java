package com.mymed.android.myjam.controller;

import com.mymed.android.myjam.exception.IOBackEndException;
import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;


/**
 * Class that provides methods to authenticate.
 * @author iacopo
 *
 */
public class AuthenticationCallFactory {
	
//	private AuthenticationCallFactory(int id, int callCode,HttpCallHandler handler, HttpMethod method,
//			String uriString) {
//		super(id, callCode, handler, method, uriString);
//	}
//
//	private static final String PROFILE_HANDLER_URL = BACKEND_URL+"ProfileRequestHandler";
//	private static final String AUTHENTICATION_HANDLER_URL = BACKEND_URL+"AuthenticationRequestHandler";
//	private static final String SESSION_HANDLER_URL = BACKEND_URL+"SessionRequestHandler";
////*/	
//	
//	
////	public AuthenticationManager(){
////		gson = new Gson();
////	};
////
////	
////	public MUserBean createUser(MUserBean user){
////		return user;		
////	}
////	
//	/**
//	 * Gets the profile of the user having ID {@link userId}
//	 * @param userId 	ID of the profile.
//	 * @return
//	 * @throws InternalBackEndException	Error server side.
//	 * @throws IOBackEndException		User corresponding to {@link userId} doesn't exist.
//	 * @throws InternalClientException	Error client side.
//	 */
//	public static HttpCall getUser(int id, HttpCallHandler handler, String userId){
//		HttpCall call = new AuthenticationCallFactory(id, CallCode.GET_USER, handler,HttpMethod.GET,PROFILE_HANDLER_URL);
//		call.appendAttribute(CODE, RequestCode.READ.code);
//		call.appendAttribute(ID, userId);
//		return call;
//		
//	}
////	public MUserBean getUser(String userId) 
////			throws InternalBackEndException, IOBackEndException, InternalClientException{
////		
////		String q=QUERY+RequestCode.READ.code;
////		q=appendAttribute(q,ID,userId);
////		JSONObject response;
////		try{
////			response = (JSONObject) new JSONTokener(httpRequest(PROFILE_HANDLER_URL+q,HttpMethod.POST,null)).nextValue();
////			JSONObject data = response.getJSONObject("data");
////			String profile = data.getString("profile");
////			Type userType = new TypeToken<MUserBean>(){}.getType();
////			return this.gson.fromJson(profile, userType);
////		}catch(JsonSyntaxException e){
////			throw new InternalBackEndException("Wrong response format.");
////		} catch (JSONException e) {
////			throw new InternalBackEndException("Wrong response format.");
////		}		
////	}
////	
//	/**
//	 * Performs an authentication request. 
//	 * 
//	 * @param authentication	{@link MauthenticationBean} containing authentication info.
//	 * @param profile	{@link MUserBean} Containing user profile.
//	 * @return	User profile if authentication succeeded.
//	 * @throws InternalBackEndException	Error server side.
//	 * @throws IOBackEndException		Authentication fails.
//	 * @throws InternalClientException	Error client side.
//	 */
//	public static HttpCall authenticate(int id, HttpCallHandler handler, String login, String password){
//		HttpCall call = new AuthenticationCallFactory(id, CallCode.LOG_IN, handler,HttpMethod.POST,AUTHENTICATION_HANDLER_URL);
//		call.appendAttribute(CODE, RequestCode.READ.code);
//		call.appendAttribute(LOGIN, login);
//		call.appendAttribute(PASSWORD, password);
//		return call;
//		
//	}
////	public MUserBean authenticate(String login,String password) 
////			throws InternalBackEndException, IOBackEndException, InternalClientException{
////		
////		String q=QUERY+RequestCode.READ.code;
////		q=appendAttribute(q,LOGIN,login);
////		q=appendAttribute(q,PASSWORD,password);
////
////		JSONObject response;
////		try {
////			response = (JSONObject) new JSONTokener(httpRequest(AUTHENTICATION_HANDLER_URL+q,HttpMethod.POST,null)).nextValue();
////			JSONObject data = response.getJSONObject("data");
////			String profile = data.getString("user");
////			Type userType = new TypeToken<MUserBean>(){}.getType();
////			return this.gson.fromJson(profile, userType);
////		} catch (JSONException e) {
////			throw new InternalBackEndException("Wrong response format.");
////		}catch(JsonSyntaxException e){
////			throw new InternalBackEndException("Wrong response format.");
////		}		
////	}
////	
////	/**
////	 * Logs In to myMed, creates a session.
////	 * @param userId	ID of the user profile on myMed
////	 * @param ip		ip address of the user.
////	 * @throws InternalBackEndException	Error server side.
////	 * @throws IOBackEndException		Could never happen.
////	 * @throws InternalClientException	Error client side.
////	 */
////	public static HttpCall logIn(int id, HttpCallHandler handler, String userId, String ip){
////		HttpCall call = new AuthenticationCallFactory(handler,HttpMethod.GET,SESSION_HANDLER_URL,id);
////		call.appendAttribute(CODE, RequestCode.CREATE.code);
////		call.appendAttribute(USER_ID, userId);
////		call.appendAttribute(IP, ip);
////		return call;
////		
////	}
////	public void logIn(String userId,String ip) 
////			throws InternalBackEndException, IOBackEndException, InternalClientException{
////		
////		String q=QUERY+RequestCode.CREATE.code;
////		q=appendAttribute(q,USER_ID,userId);
////		q=appendAttribute(q,IP,ip);
////		httpRequest(SESSION_HANDLER_URL+q,HttpMethod.POST,null);
////	}
////	
//	/**
//	 * Logs Out from myMed, deletes the session.
//	 * @param userId	ID of the user profile on myMed
//	 * @param ip		ip address of the user.
//	 * @throws InternalBackEndException	Error server side.
//	 * @throws IOBackEndException		Could never happen.
//	 * @throws InternalClientException	Error client side.
//	 */
//	public static HttpCall logOut(int id, HttpCallHandler handler, String accessToken){
//		HttpCall call = new AuthenticationCallFactory(id, CallCode.LOG_OUT, handler,HttpMethod.GET,SESSION_HANDLER_URL);
//		call.appendAttribute(CODE, RequestCode.DELETE.code);
//		call.appendAttribute(ACCESS_TOKEN, accessToken);
//		call.appendAttribute(SOCIAL_NETWORK, "");
//		return call;
//		
//	}
////	public void logOut(String userId) 
////			throws InternalBackEndException, IOBackEndException, InternalClientException{
////		
////		String q=QUERY+RequestCode.DELETE.code;
////		q=appendAttribute(q,USER_ID,userId);
////		httpRequest(SESSION_HANDLER_URL+q,HttpMethod.DELETE,null);
////	}
//	
////    static {
////       
////    }

}
