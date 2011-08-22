package com.mymed.android.myjam.provider;

import com.mymed.android.myjam.provider.MyJamProvider.Tables;

import android.net.Uri;
import android.provider.BaseColumns;

/**
 * Convenience definitions for MyJamProvider
 * @author iacopo
 *
 */
public final class MyJamContract {
	
	// This class cannot be instantiated
    private MyJamContract() {}    
    
    interface MyUsersColumns {
        /** Id of the user. (Required)*/
    	String USER_ID = "user_id";
        /** Name of the user*/
    	String USER_NAME = "user_name";
    }
    
    interface SearchColumns {
        /** Search type. (Required)*/
    	String SEARCH_ID = "search_id";
        /** Search date. */
        String DATE = "date";
    }
    
    interface SearchResultColumns {
    	/** Unique id identifying the report. (Required)*/
    	String REPORT_ID = "report_id";
        /** Search type. (Required)*/
    	String SEARCH_ID = "search_id";
    	/** Distance between the report pointed by {@link REPORT_ID} and the user position. */
        String DISTANCE = "distance";  
    }
        
    interface ReportColumns {
    	/** Unique id identifying the report. (Required)*/
    	String REPORT_ID = "report_id";
    	/** Unique id identifying the user. (Required)*/
    	String USER_ID = "user_id";
    	/** Name of the user. */
    	String USER_NAME = "user_name";
        /** Latitude of the report. (Required)*/
        String LATITUDE = "latitude";
        /** Longitude of the report. (Required)*/
        String LONGITUDE = "longitude";
    	/** Report type. */
    	String REPORT_TYPE = "report_type";
    	/** Traffic flow. */
    	String TRAFFIC_FLOW = "traffic_flow";
    	/** Transit type. */
    	String TRANSIT_TYPE = "transit_type";
    	/** Comment. */
    	String COMMENT = "comment";
        /** Insertion date. */
        String DATE = "date";   
    	/** Flag that says whether the report has been completely retrieved or partially inserted during the search. */
    	String FLAG_COMPLETE = "flag_complete";
    }
    
    interface UpdateColumns {
    	/** Unique id identifying the update. (Required)*/
    	String UPDATE_ID = "update_id";
    	/** Unique id identifying the report. */
    	String REPORT_ID = "report_id";
    	/** Unique id identifying the user. */
    	String USER_ID = "user_id";
    	/** Name of the user. */
    	String USER_NAME = "user_name";
    	/** Traffic flow. */
    	String TRAFFIC_FLOW = "traffic_flow";
    	/** Transit type. */
    	String TRANSIT_TYPE = "transit_type";
    	/** Comment. */
    	String COMMENT = "comment";
    	/** Insertion date. */
        String DATE = "date";
    }
    
    interface FeedbackColumns {
    	/** Unique id identifying the feedback. */
    	String FEEDBACK_ID = "feedback_id";
    	/** Unique id identifying the report. */
    	String REPORT_ID = "report_id";
    	/** Unique id identifying the report. */
    	String UPDATE_ID = "update_id";
    	/** Unique id identifying the user. */
    	String USER_ID = "user_id";
    	/** Grade. */
        String GRADE = "grade";     
    }
    
    interface RequestColumns {
    	/** Unique id identifying the report, which demand is referred to. */
    	String REPORT_ID = "report_id";
    	/** Unique id identifying the update, which demand is referred to. */
    	String UPDATE_ID = "update_id";
    	/** Last time  */
        String LAST_UPDATE = "last_update"; 
        /** Flag set when a requested update has not been completed.*/
        String UPDATING = "updating"; 
    }
    
    public static final String CONTENT_AUTHORITY = "com.mymed.android.myjam";

    private static final Uri BASE_CONTENT_URI = Uri.parse("content://" + CONTENT_AUTHORITY);
    
    private static final String PATH_MY_USERS = "my_users";
    private static final String PATH_SEARCH = "search";
    private static final String PATH_SEARCH_RESULT = "search_result";
    private static final String PATH_SEARCH_REPORTS = "search_reports";
    private static final String PATH_REPORTS = "report";
    private static final String PATH_UPDATES = "update";
    private static final String PATH_UPDATE_REQUEST = "update_request";
    private static final String PATH_FEEDBACK = "feedback";
    private static final String PATH_FEEDBACK_REQUEST = "feedback_request";    
    private static final String PATH_REPORT_ID = "r_id";
    private static final String PATH_UPDATE_ID = "u_id";
    

    public static final class MyUsers implements BaseColumns,MyUsersColumns {
        // This class cannot be instantiated
        private MyUsers() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_MY_USERS).build();

        /**
         * The prefix to use when a qualified name is required.
         */
        public static final String QUALIFIER = Tables.MY_USERS_TABLE_NAME + ".";
        
        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "user_name";        
    }
    
    public static final class Search implements BaseColumns,SearchColumns {
        // This class cannot be instantiated
        private Search() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_SEARCH).build();

        /**
         * The prefix to use when a qualified name is required.
         */
        public static final String QUALIFIER = Tables.SEARCH_TABLE_NAME + ".";
        
        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "distance";
        
        public static Uri buildSearchUri(String searchId) {
            return CONTENT_URI.buildUpon().appendPath(searchId).build();
        }
        
        /**
         * The default {@link SEARCH_ID}.
         */
        public static final int NEW_SEARCH = 0x0;
        public static final int OLD_SEARCH = 0x1;
        public static final int INSERT_SEARCH = 0x2;
        
        
    }
    
    public static final class SearchResult implements BaseColumns,SearchResultColumns {
        // This class cannot be instantiated
        private SearchResult() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_SEARCH_RESULT).build();

        /**
         * The prefix to use when a qualified name is required.
         */
        public static final String QUALIFIER = Tables.SEARCH_RESULT_TABLE_NAME + ".";
        
        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "distance";
        
        /**
         * Selection used to perform query, to know if the data can be refreshed.
         */
        public static final String SEARCH_ID_SELECTION = SEARCH_ID +"= ?";
    }
    
    public static final class SearchReports implements BaseColumns {
        // This class cannot be instantiated
        private SearchReports() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_SEARCH_REPORTS).build();
        
        public static Uri buildSearchUri(String searchId) {
            return CONTENT_URI.buildUpon().appendPath(searchId).build();
        }
        
        public static Uri buildSearchUri(String searchId,String reportType) {
            return CONTENT_URI.buildUpon().appendPath(searchId).appendPath(reportType).build();
        }
        
        public static String getSearchId(Uri uri) {
            return uri.getPathSegments().get(1);
        }
        
        public static String getReportType(Uri uri) {
            return uri.getPathSegments().get(2);
        }
        
        public static boolean isReportType(Uri uri) {
        	return (uri.getPathSegments().size()>2);
        }
        
        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = Tables.SEARCH_RESULT_TABLE_NAME + ".distance";
        
    }
    
    public static final class Report implements BaseColumns,ReportColumns {
        // This class cannot be instantiated
        private Report() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_REPORTS).build();
        
        /**
         * Build the uri to access specific updates.
         * @param reportId 
         * @return The uri.
         */
        public static Uri buildReportIdUri(String reportId) {
            return CONTENT_URI.buildUpon().appendPath(reportId).build();
        }
        
        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "date DESC";
        
        /**
         * The prefix to use when a qualified name is required.
         */
        public static final String QUALIFIER = Tables.REPORT_TABLE_NAME + ".";
               
        /**
         * Selection used to update a report.
         */
        public static final String REPORT_SELECTION = REPORT_ID+"=? ";
        
        /**
         * Selection used to update a report.
         */
        public static final String STALE_ENTRIES_SELECTION = REPORT_ID +" NOT IN (SELECT "+ SearchResult.REPORT_ID + 
        		" FROM "+ Tables.SEARCH_RESULT_TABLE_NAME +") AND "+ USER_ID+ " NOT IN (SELECT "+ MyUsers.USER_ID + 
                		" FROM "+ Tables.MY_USERS_TABLE_NAME +")";
        
        /**
         * Returns the reportId given the URI.
         * @param uri
         * @return
         */
        public static String getReportId(Uri uri) {
            return uri.getPathSegments().get(1);
        }
    }
    
    public static final class Update implements BaseColumns,UpdateColumns {
        // This class cannot be instantiated
        private Update() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_UPDATES).build();
        
        /**
         * Build the uri to access specific updates.
         * @param reportId 
         * @return The uri.
         */
        public static Uri buildReportIdUri(String reportId) {
            return CONTENT_URI.buildUpon().appendPath(reportId).build();
        }
        
        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "date DESC";
        
        /**
         * The prefix to use when a qualified name is required.
         */
        public static final String QUALIFIER = Tables.UPDATE_TABLE_NAME + ".";
    }
    
    public static final class UpdatesRequest implements BaseColumns,RequestColumns {
        // This class cannot be instantiated
        private UpdatesRequest() {}
        
        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_UPDATE_REQUEST).build();
        
        /**
         * Build the uri to access specific updates.
         * @param reportId 
         * @return The uri.
         */
        public static Uri buildReportIdUri(String reportId) {
            return CONTENT_URI.buildUpon().appendPath(reportId).build();
        }
        
        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "last_update DESC";
        
        /**
         * The prefix to use when a qualified name is required.
         */
        public static final String QUALIFIER = Tables.UPDATE_REQUEST_TABLE_NAME + ".";
        
        /**
         * Selection used to perform query, to know if the data can be refreshed.
         */
        public static final String REFRESH_SELECTION = UPDATING+"=1 OR ("+UPDATING+"=0 AND "+LAST_UPDATE+">? )";
    }
    
    public static final class Feedback implements BaseColumns,FeedbackColumns {
        // This class cannot be instantiated
        private Feedback() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_FEEDBACK).build();
        
        /**
         * Build the uri to access specific updates.
         * @param reportId 
         * @return The uri.
         */
        public static Uri buildReportIdUri(String reportId) {
            return CONTENT_URI.buildUpon().appendPath(PATH_REPORT_ID).appendPath(reportId).build();
        }
        
        /**
         * Build the uri to access specific updates.
         * @param reportId 
         * @return The uri.
         */
        public static Uri buildUpdateIdUri(String updateId) {
            return CONTENT_URI.buildUpon().appendPath(PATH_UPDATE_ID).appendPath(updateId).build();
        }
        
        /**
         * The prefix to use when a qualified name is required.
         */
        public static final String QUALIFIER = Tables.FEEDBACK_TABLE_NAME + ".";
        
        public static String getId(Uri uri) {
            return uri.getPathSegments().get(2);
        }       
    }
    
    public static final class FeedbacksRequest implements BaseColumns,RequestColumns {
        // This class cannot be instantiated
        private FeedbacksRequest() {}
        
        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_FEEDBACK_REQUEST).build();
        
        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "last_update DESC";
        
        /**
         * The prefix to use when a qualified name is required.
         */
        public static final String QUALIFIER = Tables.FEEDBACK_REQUEST_TABLE_NAME + ".";
        
        /**
         * Build the uri to access specific updates.
         * @param reportId 
         * @return The uri.
         */
        public static Uri buildReportIdUri(String reportId) {
            return CONTENT_URI.buildUpon().appendPath(PATH_REPORT_ID).appendPath(reportId).build();
        }
        
        /**
         * Build the uri to access specific updates.
         * @param reportId 
         * @return The uri.
         */
        public static Uri buildUpdateIdUri(String updateId) {
            return CONTENT_URI.buildUpon().appendPath(PATH_UPDATE_ID).appendPath(updateId).build();
        }

        
        /**
         * Selection used to perform query, to know if the data can be refreshed.
         */
        public static final String REFRESH_SELECTION = UPDATING+"=1 OR ("+UPDATING+"=0 AND "+LAST_UPDATE+">? )";
        
        public static String getId(Uri uri) {
            return uri.getPathSegments().get(2);
        }       
    }
	
    
}
