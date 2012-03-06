package com.mymed.android.myjam.controller;

public abstract class CallContract {

	/** Call Code */
	public interface CallCode{
		int LOG_IN = 0x0;
		int CHECK_ACCESS_TOKEN = 0x1;
		int LOG_OUT = 0x2;

		int SEARCH_REPORTS = 0x3;
		int GET_REPORT = 0x4;
		int GET_UPDATES = 0x5;
		int GET_REPORT_FEEDBACKS = 0x6;
		int GET_UPDATE_FEEDBACKS = 0x7;

		int INSERT_REPORT = 0x8;
		int INSERT_UPDATE = 0x9;
		int INSERT_REPORT_FEEDBACK = 0x10;
		int INSERT_UPDATE_FEEDBACK = 0x11;

		int DELETE_REPORT = 0x12;

		int GET_USER = 0x13;    		
	}

	/** Http Methods */
	protected enum HttpMethod {
		GET,
		POST,
		PUT,
		DELETE;
	}

	public static final String FRONTEND_URL = "http://mymed38.polito.it/mymed/";
	//protected static final String BACKEND_URL = "http://10.0.2.2:8080/mymed_backend/"; //Testing purposes.	
	protected static final String BACKEND_URL = "http://mymed38.polito.it:8080/backend/"; //Italian backbone.
	protected static final String PROFILE_HANDLER_URL = BACKEND_URL+"ProfileRequestHandler";
	protected static final String AUTHENTICATION_HANDLER_URL = BACKEND_URL+"AuthenticationRequestHandler";
	protected static final String SESSION_HANDLER_URL = BACKEND_URL+"SessionRequestHandler";
	private static final String MY_JAM_REPORT_HANDLER_URL = BACKEND_URL+"MyJamReportRequestHandler";
	private static final String MY_JAM_UPDATE_HANDLER_URL = BACKEND_URL+"MyJamUpdateRequestHandler";
	private static final String MY_JAM_FEEDBACK_HANDLER_URL = BACKEND_URL+"MyJamFeedbackRequestHandler";

	public static final String ID = "id";
	public static final String AUTHENTICATION = "authentication";
	public static final String PROFILE = "profile";
	public static final String USER_ID = "userID";
	public static final String IP = "ip";
	public static final String LOGIN = "login";
	public static final String PASSWORD = "password";
	public static final String ACCESS_TOKEN = "accessToken";
	public static final String SOCIAL_NETWORK = "socialNetwork";
	
	protected static final String QUERY_STRING = "?";
	protected static final String AND = "&";
	protected static final String EQUAL = "=";
	protected static final String CODE = "code";

	/**
	 *  myJam Attributes
	 */
	//General.
	public static final String REPORT_ID = "id";
	public static final String UPDATE_ID = "updateID";	
	public static final String START_TIME = "startTime";
	//Search report.
	public static final String LATITUDE = "latitude";
	public static final String LONGITUDE = "longitude";
	public static final String RADIUS = "radius";	
	public static final String SEARCH_ID = "searchID";

	/** Request codes */ 
	public enum RequestCode {
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

	/**
	 * Return the URL associated to {@link callCode}.
	 * 
	 * @param callCode Code of the call belonging to {@link CallCode}.
	 * @return
	 */
	protected static final String getUrlPath(int callCode){
		switch(callCode){
		case CallCode.LOG_IN:
			return AUTHENTICATION_HANDLER_URL;
		case CallCode.CHECK_ACCESS_TOKEN:
			return SESSION_HANDLER_URL;
		case CallCode.LOG_OUT:
			return SESSION_HANDLER_URL;
		case CallCode.SEARCH_REPORTS:
			return MY_JAM_REPORT_HANDLER_URL;
		case CallCode.GET_REPORT:
			return MY_JAM_REPORT_HANDLER_URL;
		case CallCode.GET_UPDATES:
			return MY_JAM_UPDATE_HANDLER_URL;
		case CallCode.GET_REPORT_FEEDBACKS:
			return MY_JAM_FEEDBACK_HANDLER_URL;
		case CallCode.GET_UPDATE_FEEDBACKS:
			return MY_JAM_FEEDBACK_HANDLER_URL;
		case CallCode.INSERT_REPORT:
			return MY_JAM_REPORT_HANDLER_URL;
		case CallCode.INSERT_UPDATE:
			return MY_JAM_UPDATE_HANDLER_URL;
		case CallCode.INSERT_REPORT_FEEDBACK:
			return MY_JAM_FEEDBACK_HANDLER_URL;
		case CallCode.INSERT_UPDATE_FEEDBACK:
			return MY_JAM_FEEDBACK_HANDLER_URL;
		case CallCode.DELETE_REPORT:
			return MY_JAM_REPORT_HANDLER_URL;
		case CallCode.GET_USER:
			return PROFILE_HANDLER_URL;
		default:
			return null;
		}
	}

	protected static final HttpMethod getHttpMethod(int callCode){
		switch(callCode){
		case CallCode.LOG_IN:
			return HttpMethod.POST;
		case CallCode.CHECK_ACCESS_TOKEN:
			return HttpMethod.GET;			
		case CallCode.LOG_OUT:
			return HttpMethod.GET;
		case CallCode.SEARCH_REPORTS:
			return HttpMethod.GET;
		case CallCode.GET_REPORT:
			return HttpMethod.GET;
		case CallCode.GET_UPDATES:
			return HttpMethod.GET;
		case CallCode.GET_REPORT_FEEDBACKS:
			return HttpMethod.GET;
		case CallCode.GET_UPDATE_FEEDBACKS:
			return HttpMethod.GET;
		case CallCode.INSERT_REPORT:
			return HttpMethod.POST;
		case CallCode.INSERT_UPDATE:
			return HttpMethod.POST;
		case CallCode.INSERT_REPORT_FEEDBACK:
			return HttpMethod.POST;
		case CallCode.INSERT_UPDATE_FEEDBACK:
			return HttpMethod.POST;
		case CallCode.DELETE_REPORT:
			return HttpMethod.DELETE;
		case CallCode.GET_USER:
			return HttpMethod.GET;
		default:
			return null;
		}
	}
	
	protected static final String getRequestCode(int callCode){
		switch(callCode){
		case CallCode.LOG_IN:
			return RequestCode.READ.code;
		case CallCode.CHECK_ACCESS_TOKEN:
			return RequestCode.READ.code;
		case CallCode.LOG_OUT:
			return RequestCode.DELETE.code;
		case CallCode.SEARCH_REPORTS:
			return RequestCode.READ.code;
		case CallCode.GET_REPORT:
			return RequestCode.READ.code;
		case CallCode.GET_UPDATES:
			return RequestCode.READ.code;
		case CallCode.GET_REPORT_FEEDBACKS:
			return RequestCode.READ.code;
		case CallCode.GET_UPDATE_FEEDBACKS:
			return RequestCode.READ.code;
		case CallCode.INSERT_REPORT:
			return RequestCode.CREATE.code;
		case CallCode.INSERT_UPDATE:
			return RequestCode.CREATE.code;
		case CallCode.INSERT_REPORT_FEEDBACK:
			return RequestCode.CREATE.code;
		case CallCode.INSERT_UPDATE_FEEDBACK:
			return RequestCode.CREATE.code;
		case CallCode.DELETE_REPORT:
			return RequestCode.DELETE.code;
		case CallCode.GET_USER:
			return RequestCode.READ.code;
		default:
			return null;
		}
	}
	
	/**
	 * Append an attribute to the given URL.
	 * @param url 	URL
	 * @param name	Name of the attribute.
	 * @param value	Value of the attribute.
	 * @return
	 */
	protected static String makeUri(int callCode){
		String uri = getUrlPath(callCode)+QUERY_STRING+CODE+EQUAL+getRequestCode(callCode);
		return uri;
	}
}
