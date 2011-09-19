package com.mymed.utils;

import java.text.SimpleDateFormat;
import java.util.Calendar;

import android.content.Context;
import android.util.Log;

import com.mymed.android.myjam.R;

/**
 * Lazy-loading Singleton class that stores the global variables for 
 * the whole duration of the application (since it is created).
 * @author iacopo
 *
 */
public class GlobalStateAndUtils {
	private static String TAG = "GlobalState";
	private static GlobalStateAndUtils mInstance;
	
	/** The report types */
	public static final int JAM = 0x0;
	public static final int CAR_CRASH = 0x1;
	public static final int WORK_IN_PROGRESS = 0x2;
	public static final int FIXED_SPEED_CAM = 0x3;
	public static final int MOBILE_SPEED_CAM = 0x4;
 
	/** Code for the user position 
	 * Must not overlap the report types, used to show results in map. */
	public static final int USER_POSITION = 0x10;
	/** Max value for a feedback */
	public static final int MAX_RATING = 10;
	/** Max distance between the user and a report for the insertion of an update or a feeback.*/
	public static final int MAX_INSERTION_DISTANCE = 2000;
	/**
	 * LogIn attributes.
	 */
	private boolean logged;
	private String userId;
	private String login;
	private String password;
	private String userName;
	
	/** Singleton, cannot be instantiated. */
    private GlobalStateAndUtils(Context context){

    	mContext = context.getApplicationContext();
    	//Initialize string variables.
		typeSeparator = mContext.getString(R.string.type_string_separator);
		dateFormat = mContext.getString(R.string.date_format);
		decNumberSeparator = mContext.getString(R.string.frac_number_separator);
    	  
    }  
    
    public synchronized static GlobalStateAndUtils getInstance(Context context)  
    {  
    	if (mInstance == null){
    		mInstance = new GlobalStateAndUtils(context);
    	}
        return mInstance;  
    }  
    
	private Context mContext;
	
	private final String typeSeparator;
	private final String dateFormat;
	private final String decNumberSeparator;
	
	
	////////////////////////////////
	/*   Format data utilities    */
	////////////////////////////////
    /**
     * Format the string arg0 representing milli-degrees in a string representing degrees.
     * (four decimal numbers precision)
     * @param arg0 String representing milli-degrees.
     * @return
     */
    public String formatMilliDegrees(int arg0){
    	String tmp = String.valueOf(arg0);
    	if (tmp == null || tmp.length()<7){
       		Log.d(TAG,"Wrong argument passed to formatMilliDegrees: " + arg0);
    		throw new IllegalArgumentException("Wrong argument passed to formatMilliDegrees: " + arg0);
    	}
    	tmp = tmp.substring(0, tmp.length()-6)+ this.decNumberSeparator
    			+tmp.substring(tmp.length()-6, tmp.length()-2);
    	return tmp;
    }
    
    /**
     * Format the distance as '* Km * m'
     * @param arg0 The distance value.
     * @param arg1 The resolution.
     * @return
     */
    public String formatDistance(int arg0,int arg1){
    	if (arg1 == 0)
    		throw new IllegalArgumentException("The resolution cannot be zero.");
    	
    	int km = arg0/1000;
    	int m = arg0 - km*1000;
    	m = ((int) (m/arg1) * arg1);
    	String tmp = km + " Km "+m+" m";
    	return tmp;
    }
    
    public String formatType(String arg0){
    	if (arg0 == null || arg0.length()<2){
       		Log.d(TAG,"Wrong argument passed to formatType: " + arg0);
       		throw new IllegalArgumentException("Wrong argument passed to formatType: " + arg0);
    	}
    	String tmp = arg0.toLowerCase();
    	tmp = tmp.replace(this.typeSeparator," ");
    	tmp = tmp.substring(0, 1).toUpperCase()+tmp.substring(1, arg0.length());
    	return tmp;
    }
    
    public String formatDate(long arg0){
    	try{
       	 Calendar cal = Calendar.getInstance();
         cal.setTimeInMillis(arg0);

         String format = this.dateFormat;
         SimpleDateFormat sdf = new SimpleDateFormat(format);
         return sdf.format(cal.getTime());
    	}catch(Exception e){
    		Log.d(TAG,"Error in formatDate formatting : " + arg0 + " with :"+dateFormat);
    		throw new IllegalArgumentException("Error in formatDate formatting : " + arg0 + " with :"+dateFormat);
    	}
    }
    
    /**
     * Returns the index of the String {@link arg0} in the StringArray {@link arg1} if present,
     * or {@value -1} if not present.
     * @param arg0	String array.
     * @param arg1	String searched. 
     * @return
     */
    public int getStringArrayValueIndex(String[] arg0,String arg1){
    	for (int i=0;i<arg0.length;i++){
    		if (arg0[i].equals(arg1))
    			return i;
    	}
    	return -1;
    }
    
    /**
     * Returns the id of the icon related to the selected report type, or -1 if the report type
     * code is not valid.
     * @param type The report type
     * @return The id of the icon associated to the type
     */
    public int getIconByReportType(int type){
    	switch (type){
    	case JAM:
    		return R.drawable.brown_marker_j;
    	case CAR_CRASH:
    		return R.drawable.red_marker_c;
    	case WORK_IN_PROGRESS:
    		return R.drawable.yellow_marker_w;
    	case FIXED_SPEED_CAM:
    		return R.drawable.orange_marker_s;
    	case MOBILE_SPEED_CAM:
    		return R.drawable.blue_marker_s;
    	default:
    		return -1;
    	}
    		
    }
    
    /**
     * Returns the id of the icon related to the selected report type, or -1 if the report type
     * code is not valid.
     *TODO Its a little bit tricky, it uses the array from the xml resources. Could be find a better solution.
     * @param type The report type
     * @return The id of the icon associated to the type
     */
    public int getIconByReportType(String type){
    	return getIconByReportType(getStringArrayValueIndex(
    			mContext.getResources().getStringArray(R.array.report_typevalues_list),type));
    }

    /*
     * GETTERS AND SETTERS FOR LOGIN ATTRIBUTES 
     */
    
	public boolean isLogged() {
		return logged;
	}

	public void setLogged(boolean logged) {
		this.logged = logged;
	}

	public String getUserId() {
		return userId;
	}

	public void setUserId(String userId) {
		this.userId = userId;
	}
	
	public String getLogin() {
		return login;
	}

	public void setLogin(String login) {
		this.login = login;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	public String getUserName() {
		return userName;
	}

	public void setUserName(String userName) {
		this.userName = userName;
	}
}
