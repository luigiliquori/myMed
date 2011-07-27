package com.mymed.android.myjam.provider;

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
    
    interface LocatedReportColumns {
    	/** Unique id identifying the report. (Required)*/
    	String REPORT_ID = "report_id";
        /** Unique id identifying the location. (Required)*/
    	String REPORT_TYPE = "report_type";
        /** Latitude of the report. (Required)*/
        String LATITUDE = "latitude";
        /** Longitude of the report. (Required)*/
        String LONGITUDE = "longitude";
        /** Distance between the report and the user position. */
        String DISTANCE = "distance";
        /** Insertion date. */
        String DATE = "date";
    }
    
    interface ReportColumns {
    	/** Unique id identifying the report. */
    	String REPORT_ID = "report_id";
    	/** Unique id identifying the user. */
    	String USER_ID = "user_id";
    	/** Name of the user. */
    	String USER_NAME = "user_name";
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
    }
    
    interface UpdateColumns {
    	/** Unique id identifying the report. */
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
    	/** Unique id identifying the user. */
    	String USER_ID = "user_id";
    	/** Grade. */
        String GRADE = "grade";
    	/** Insertion date. */
        String DATE = "date";     
    }
    
    public static final String CONTENT_AUTHORITY = "com.mymed.android.myjam";

    private static final Uri BASE_CONTENT_URI = Uri.parse("content://" + CONTENT_AUTHORITY);

    private static final String PATH_LOCATED_REPORTS = "located_reports";
    private static final String PATH_REPORTS = "reports";
    private static final String PATH_UPDATES = "updates";
    private static final String PATH_FEEDBACKS = "feedbacks";
    
    public static final class LocatedReport implements BaseColumns,LocatedReportColumns {
        // This class cannot be instantiated
        private LocatedReport() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_LOCATED_REPORTS).build();

        /**
         * The MIME type of {@link #CONTENT_URI} providing a directory of notes.
         */
        public static final String CONTENT_TYPE = "vnd.android.cursor.dir/vnd.myjam.locatedreport";

        /**
         * The MIME type of a {@link #CONTENT_URI} sub-directory of a single note.
         */
        public static final String CONTENT_ITEM_TYPE = "vnd.android.cursor.item/vnd.myjam.locatedreport";

        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "distance DESC";
    }
    
    public static final class Report implements BaseColumns,ReportColumns {
        // This class cannot be instantiated
        private Report() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_REPORTS).build();

        /**
         * The MIME type of {@link #CONTENT_URI} providing a directory of notes.
         */
        public static final String CONTENT_TYPE = "vnd.android.cursor.dir/vnd.myjam.report";

        /**
         * The MIME type of a {@link #CONTENT_URI} sub-directory of a single note.
         */
        public static final String CONTENT_ITEM_TYPE = "vnd.android.cursor.item/vnd.myjam.report";

        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "date DESC";
    }
    
    public static final class Update implements BaseColumns,UpdateColumns {
        // This class cannot be instantiated
        private Update() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_UPDATES).build();

        /**
         * The MIME type of {@link #CONTENT_URI} providing a directory of notes.
         */
        public static final String CONTENT_TYPE = "vnd.android.cursor.dir/vnd.myjam.update";

        /**
         * The MIME type of a {@link #CONTENT_URI} sub-directory of a single note.
         */
        public static final String CONTENT_ITEM_TYPE = "vnd.android.cursor.item/vnd.myjam.update";

        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "date DESC";
    }
    
    public static final class Feedback implements BaseColumns,FeedbackColumns {
        // This class cannot be instantiated
        private Feedback() {}

        /**
         * The content:// style URL for this table
         */
        public static final Uri CONTENT_URI = BASE_CONTENT_URI.buildUpon().appendPath(PATH_FEEDBACKS).build();

        /**
         * The MIME type of {@link #CONTENT_URI} providing a directory of notes.
         */
        public static final String CONTENT_TYPE = "vnd.android.cursor.dir/vnd.myjam.feedback";

        /**
         * The MIME type of a {@link #CONTENT_URI} sub-directory of a single note.
         */
        public static final String CONTENT_ITEM_TYPE = "vnd.android.cursor.item/vnd.myjam.feedback";

        /**
         * The default sort order for this table
         */
        public static final String DEFAULT_SORT_ORDER = "date DESC";
    }
	
    
}
