package com.mymed.android.myjam.provider;

import java.util.ArrayList;

import com.mymed.android.myjam.provider.MyJamContract.Feedback;
import com.mymed.android.myjam.provider.MyJamContract.FeedbacksRequest;
import com.mymed.android.myjam.provider.MyJamContract.Login;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.SearchResult;
import com.mymed.android.myjam.provider.MyJamContract.Update;
import com.mymed.android.myjam.provider.MyJamContract.UpdatesRequest;
import com.mymed.android.myjam.provider.MyJamContract.User;

import android.content.ContentProvider;
import android.content.ContentProviderOperation;
import android.content.ContentProviderResult;
import android.content.ContentUris;
import android.content.ContentValues;
import android.content.Context;
import android.content.OperationApplicationException;
import android.content.UriMatcher;
import android.database.Cursor;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.database.sqlite.SQLiteQueryBuilder;
import android.net.Uri;
import android.provider.BaseColumns;
import android.util.Log;

/**
 * Content provider used by myJam application.
 * @author iacopo
 *
 */
public class MyJamProvider extends ContentProvider{
	
    private static final String TAG = "MyJamProvider";

    private static final String DATABASE_NAME = "my_jam.db";
    private static final int DATABASE_VERSION = 1;
    interface Tables{
    	final String LOGIN_TABLE_NAME = "login";
        final String USERS_TABLE_NAME = "users";
		final String SEARCH_TABLE_NAME = "search";
		final String SEARCH_RESULT_TABLE_NAME = "search_results";
		final String REPORTS_TABLE_NAME = "reports";
		final String UPDATES_TABLE_NAME = "updates";
        final String UPDATE_REQUEST_TABLE_NAME = "updates_request";
        final String FEEDBACKS_TABLE_NAME = "feedbacks";
        final String FEEDBACK_REQUEST_TABLE_NAME = "feedbacks_request";      
        
        final String LOGIN_JOIN_USER = "login "
                + "INNER JOIN users ON users.user_id=login.user_id ";
        final String SEARCH_JOIN_REPORTS = "search_results "
                + "INNER JOIN reports ON search_results.report_id=reports.report_id ";
        final String UPDATE_JOIN_REPORT = "updates "
                + "INNER JOIN reports ON updates.report_id=reports.report_id ";
        
    }
    
    /** {@code REFERENCES} clauses. */
    private interface References {
    	String REPORT_ID = "REFERENCES " + Tables.REPORTS_TABLE_NAME + "(" + Report.REPORT_ID + ")";
        String UPDATE_ID = "REFERENCES " + Tables.UPDATES_TABLE_NAME + "(" + Update.UPDATE_ID + ")";
        //TODO String LOGIN_ID = "REFERENCES " + Tables.USERS_TABLE_NAME + "(" + Login.LOGIN_ID + ")";
    }
    
    /** {@code INDEXES} clauses. */
    private interface Indexes {
    	String SEARCH_RESULT_SEARCH_ID_INDEX = "CREATE INDEX search_result_search_id_index ON "+ Tables.SEARCH_RESULT_TABLE_NAME + "(" + SearchResult.SEARCH_ID + ")";
        String REPORTS_INDEX = "CREATE INDEX reports_index ON "+ Tables.REPORTS_TABLE_NAME + "(" + Report.REPORT_ID + ")";
        String REPORTS_USER_ID_INDEX = "CREATE INDEX reports_user_id_index ON "+ Tables.REPORTS_TABLE_NAME + "(" + Report.USER_ID + ")";
        String UPDATES_REPORT_ID_INDEX = "CREATE INDEX updates_report_id_index ON "+ Tables.UPDATES_TABLE_NAME + "(" + Update.REPORT_ID + ")";
        String FEEDBACKS_REPORT_ID_INDEX = "CREATE INDEX feedbacks_report_id_index ON "+ Tables.FEEDBACKS_TABLE_NAME + "(" + Feedback.REPORT_ID + ")";
        String FEEDBACKS_UPDATE_ID_INDEX = "CREATE INDEX feedbacks_update_id_index ON "+ Tables.FEEDBACKS_TABLE_NAME + "(" + Feedback.UPDATE_ID + ")";
    }
    /** Id of the URI's */
    private static final int USER = 1;
    private static final int USER_ID = 2;
    
    private static final int LOGIN = 3;
    
    private static final int SEARCH = 4;
    private static final int SEARCH_SEARCH_ID = 5;
    
    private static final int SEARCH_RESULT = 6;
    private static final int SEARCH_ID_SEARCH_RESULT = 7;
    
    private static final int SEARCH_REPORTS_SEARCH_ID = 8;
    private static final int SEARCH_REPORTS_SEARCH_ID_REPORT_TYPE = 9;
    
    private static final int REPORT = 10;
    private static final int REPORT_ID = 11;
    
    private static final int UPDATE = 12;
    private static final int UPDATE_ID = 13;
    private static final int REPORT_ID_UPDATE = 14;
    
    private static final int FEEDBACK = 15;   
    private static final int REPORT_ID_FEEDBACK = 16;
    private static final int UPDATE_ID_FEEDBACK = 17;
    
    private static final int UPDATE_REQUEST = 18;
    private static final int REPORT_ID_UPDATE_REQUEST = 19;
    private static final int FEEDBACK_REQUEST = 20;
    private static final int REPORT_ID_FEEDBACK_REQUEST = 21;
    private static final int UPDATE_ID_FEEDBACK_REQUEST = 22;
    

    private static final UriMatcher sUriMatcher;

    private static class DatabaseHelper extends SQLiteOpenHelper {

		DatabaseHelper(Context context) {
            super(context, DATABASE_NAME, null, DATABASE_VERSION);
        }

        @Override
        public void onCreate(SQLiteDatabase db) {
        	// Enable foreign key constraints
            db.execSQL("PRAGMA foreign_keys=ON;");

            db.execSQL("CREATE TABLE " + Tables.LOGIN_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + Login.LOGIN_ID + " TEXT NOT NULL,"
                    + Login.USER_ID + " TEXT NOT NULL,"
                    + Login.PASSWORD + " TEXT NOT NULL,"
                    + Login.DATE + " INTEGER,"
                    + Login.LOGGED + " INTEGER,"
                    + Login.ACCESS_TOKEN + " TEXT,"
                    + "UNIQUE (" + User.USER_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL("CREATE TABLE " + Tables.USERS_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + User.USER_ID + " TEXT NOT NULL ,"
                    + Login.LOGIN_ID + " TEXT NOT NULL,"
                    + User.USER_NAME + " TEXT,"
                    + User.E_MAIL + " TEXT,"
                    + User.FIRST_NAME + " TEXT,"
                    + User.LAST_NAME + " TEXT,"
                    + User.GENDER + " TEXT,"
                    + User.REPUTATION + "TEXT,"
                    + "UNIQUE (" + User.USER_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL("CREATE TABLE " + Tables.SEARCH_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + Search.SEARCH_ID + " INTEGER NOT NULL,"
                    + Search.DATE + " INTEGER,"
                    + Search.LATITUDE + " INTEGER,"
                    + Search.LONGITUDE + " INTEGER,"
                    + Search.RADIUS + " INTEGER,"
                    + Search.SEARCHING + " INTEGER,"
                    + "UNIQUE (" + Search.SEARCH_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL("CREATE TABLE " + Tables.SEARCH_RESULT_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + SearchResult.REPORT_ID + " TEXT NOT NULL,"
                    + SearchResult.SEARCH_ID + " TEXT NOT NULL,"
                    + SearchResult.DISTANCE + " INTEGER,"
                    + "UNIQUE (" + SearchResult.REPORT_ID + "," + SearchResult.SEARCH_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL(Indexes.SEARCH_RESULT_SEARCH_ID_INDEX);
            
            db.execSQL("CREATE TABLE " + Tables.REPORTS_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + Report.REPORT_ID + " TEXT NOT NULL," 
                    + Report.USER_ID + " TEXT,"
                    + Report.USER_NAME + " TEXT,"
                    + Report.LATITUDE + " INTEGER NOT NULL,"
                    + Report.LONGITUDE + " INTEGER NOT NULL,"
                    + Report.REPORT_TYPE + " TEXT NOT NULL,"
                    + Report.TRAFFIC_FLOW + " TEXT,"
                    + Report.COMMENT + " TEXT,"
                    + Report.DATE + " INTEGER,"
                    + Report.FLAG_COMPLETE + " INTEGER DEFAULT 0,"
                    + "UNIQUE (" + Report.REPORT_ID + ") ON CONFLICT IGNORE)");
            
            db.execSQL(Indexes.REPORTS_INDEX);
            db.execSQL(Indexes.REPORTS_USER_ID_INDEX);
            
            db.execSQL("CREATE TABLE " + Tables.UPDATES_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
            		+ Update.UPDATE_ID + " TEXT NOT NULL,"
            		+ Update.REPORT_ID + " TEXT " + References.REPORT_ID + " ON DELETE CASCADE,"
                    + Update.USER_ID + " TEXT NOT NULL,"
                    + Update.USER_NAME + " TEXT NOT NULL,"
                    + Update.TRAFFIC_FLOW + " TEXT,"
                    + Update.COMMENT + " TEXT,"
                    + Update.DATE + " INTEGER NOT NULL,"
                    + "UNIQUE (" + Update.UPDATE_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL(Indexes.UPDATES_REPORT_ID_INDEX);
            
            db.execSQL("CREATE TABLE " + Tables.UPDATE_REQUEST_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
            		+ UpdatesRequest.REPORT_ID + " TEXT " + References.REPORT_ID + " ON DELETE CASCADE,"
                    + UpdatesRequest.LAST_UPDATE + " INTEGER NOT NULL,"
                    + UpdatesRequest.UPDATING + " INTEGER NOT NULL,"
                    + "UNIQUE (" + UpdatesRequest.REPORT_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL("CREATE TABLE " + Tables.FEEDBACKS_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
            		+ Feedback.REPORT_ID + " TEXT " + References.REPORT_ID + " ON DELETE CASCADE,"
            		+ Feedback.UPDATE_ID + " TEXT " + References.UPDATE_ID + " ON DELETE CASCADE,"
                    + Feedback.VALUE + " INTEGER,"
                    + Feedback.USER_ID + " TEXT NOT NULL,"
                    + "UNIQUE (" + Feedback.USER_ID + "," + Feedback.REPORT_ID + ") ON CONFLICT REPLACE,"
            		+ "UNIQUE (" + Feedback.USER_ID + "," + Feedback.UPDATE_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL(Indexes.FEEDBACKS_REPORT_ID_INDEX);
            db.execSQL(Indexes.FEEDBACKS_UPDATE_ID_INDEX);
            
            db.execSQL("CREATE TABLE " + Tables.FEEDBACK_REQUEST_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
            		+ FeedbacksRequest.REPORT_ID + " TEXT " + References.REPORT_ID + " ON DELETE CASCADE,"
            		+ FeedbacksRequest.UPDATE_ID + " TEXT " + References.UPDATE_ID + " ON DELETE CASCADE,"
                    + FeedbacksRequest.LAST_UPDATE + " INTEGER NOT NULL,"
                    + FeedbacksRequest.UPDATING + " INTEGER NOT NULL,"
                    + "UNIQUE (" + FeedbacksRequest.REPORT_ID + ") ON CONFLICT REPLACE,"
                    + "UNIQUE (" + FeedbacksRequest.UPDATE_ID + ") ON CONFLICT REPLACE)");            
        }

        @Override
        public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
            Log.w(TAG, "Upgrading database from version " + oldVersion + " to "
                    + newVersion + ", which will destroy all old data");
            db.execSQL("DROP TABLE IF EXISTS notes");
            onCreate(db);
        }
        
        
        @Override
        public void onOpen(SQLiteDatabase db)
        {
          super.onOpen(db);
          if (!db.isReadOnly())
          {
            // Enable foreign key constraints
            db.execSQL("PRAGMA foreign_keys=ON;");
          }
        }			
	}
    
    private DatabaseHelper mOpenHelper;

	@Override
	public boolean onCreate() {
        mOpenHelper = new DatabaseHelper(getContext());
        return true;
	}
	
	
	@Override
	public int delete(Uri uri, String selection, String[] selectionArgs) {
        String tableName;
        Uri contentUri;
        
		int match = sUriMatcher.match(uri);
        switch (match){
        case LOGIN:
        	tableName = Tables.LOGIN_TABLE_NAME;
        	contentUri = Login.CONTENT_URI;
        	break;
        case SEARCH_RESULT:
        	tableName = Tables.SEARCH_RESULT_TABLE_NAME;
        	contentUri = SearchResult.CONTENT_URI;
        	break;
        case REPORT:
        	tableName = Tables.REPORTS_TABLE_NAME;
        	contentUri = Report.CONTENT_URI;
        	break;
        default:
            throw new IllegalArgumentException("Unknown URI " + uri);
        }
        
        // Get the database and run the query
        SQLiteDatabase db = mOpenHelper.getReadableDatabase();
        int num = db.delete(tableName, selection, selectionArgs);

        // Tell the cursor what uri to watch, so it knows when its source data changes
        getContext().getContentResolver().notifyChange(contentUri, null);

		return num;
	}

	@Override
	public String getType(Uri uri) {
		// TODO Auto-generated method stub
		return null;
	}
	
	@Override
    public Uri insert(Uri uri, ContentValues initialValues) {
        String tableName;
        String nullColumnHack;
        Uri contentUri;
    	// Validate the requested uri
        switch (sUriMatcher.match(uri)) {
        case LOGIN:
        	tableName = Tables.LOGIN_TABLE_NAME;
        	nullColumnHack = Login.LOGIN_ID;
        	contentUri = Login.CONTENT_URI;
        	break;
        case USER:
        	tableName = Tables.USERS_TABLE_NAME;
        	nullColumnHack = User.USER_ID;
        	contentUri = User.CONTENT_URI;
        	break;
        case SEARCH:
        	tableName = Tables.SEARCH_TABLE_NAME;
        	nullColumnHack = Search.SEARCH_ID;
        	contentUri = Search.CONTENT_URI;
        	break;
        case SEARCH_RESULT:
        	tableName = Tables.SEARCH_RESULT_TABLE_NAME;
        	nullColumnHack = SearchResult.REPORT_ID;
        	contentUri = SearchResult.CONTENT_URI;
        	break;
        case REPORT:
        	tableName = Tables.REPORTS_TABLE_NAME;
        	nullColumnHack = Report.REPORT_ID;
        	contentUri = Report.CONTENT_URI;
        	break;
        case UPDATE:
        	tableName = Tables.UPDATES_TABLE_NAME;
        	nullColumnHack = Update.UPDATE_ID;
        	contentUri = Update.CONTENT_URI;
        	break;
        case UPDATE_REQUEST:
        	tableName = Tables.UPDATE_REQUEST_TABLE_NAME;
        	nullColumnHack = UpdatesRequest._ID;
        	contentUri = UpdatesRequest.CONTENT_URI;
        	break;
        case FEEDBACK:
        	tableName = Tables.FEEDBACKS_TABLE_NAME;
        	nullColumnHack = Feedback._ID;
        	contentUri = Feedback.CONTENT_URI;
        	break;
        case FEEDBACK_REQUEST:
        	tableName = Tables.FEEDBACK_REQUEST_TABLE_NAME;
        	nullColumnHack = FeedbacksRequest.REPORT_ID;
        	contentUri = FeedbacksRequest.CONTENT_URI;
        	break;
        default:
            throw new IllegalArgumentException("Unknown URI " + uri);
        }

        ContentValues values;
        if (initialValues != null) {
            values = new ContentValues(initialValues);
        } else {
        	throw new IllegalArgumentException("Values not specified.");     
        }

        SQLiteDatabase db = mOpenHelper.getWritableDatabase();
        long rowId = db.insert(tableName, nullColumnHack, values);
        if (rowId > 0) {
            Uri currUri = ContentUris.withAppendedId(contentUri, rowId);
            getContext().getContentResolver().notifyChange(currUri, null);
            return currUri;
        }

        throw new SQLException("Failed to insert row into " + uri);
    }

	@Override
	public Cursor query(Uri uri, String[] projection, String selection,
			String[] selectionArgs, String sortOrder) {
		final String id;
		String groupBy = null;
		
		SQLiteQueryBuilder qb = new SQLiteQueryBuilder();
		int match = sUriMatcher.match(uri);
        switch (match){
        case LOGIN:
        	qb.setTables(Tables.LOGIN_JOIN_USER);
        	break;
        case USER_ID:
        	id = User.getUserId(uri);
        	qb.setTables(Tables.USERS_TABLE_NAME);
        	qb.appendWhere("user_id =\"" + id+"\"");
        	break;
        case SEARCH_SEARCH_ID:
        	id = SearchReports.getSearchId(uri);
        	qb.setTables(Tables.SEARCH_TABLE_NAME);
        	qb.appendWhere("search_id =\"" + id+"\"");
        	break;
        case SEARCH_REPORTS_SEARCH_ID:
        	id = SearchReports.getSearchId(uri);
        	qb.setTables(Tables.SEARCH_JOIN_REPORTS);
        	qb.appendWhere("search_results.search_id =\"" + id+"\"");
        	break;
        case SEARCH_REPORTS_SEARCH_ID_REPORT_TYPE:
        	qb.setTables(Tables.SEARCH_JOIN_REPORTS);
        	qb.appendWhere("search_results.search_id =\"" + SearchReports.getSearchId(uri) + "\" AND reports.report_type =\""+ SearchReports.getReportType(uri)+"\"");
        	break;
        case REPORT_ID:
        	id = Report.getReportId(uri);
        	qb.setTables(Tables.REPORTS_TABLE_NAME);
        	qb.appendWhere(Report.REPORT_ID + "=\"" + id+"\"");
        	break;
        case UPDATE_ID:
        	id = Update.getUpdateId(uri);
        	qb.setTables(Tables.UPDATE_JOIN_REPORT);
        	qb.appendWhere(Update.QUALIFIER+Update.UPDATE_ID + "=\"" + id+"\"");
        	break;
        case REPORT_ID_UPDATE:
        	id = Update.getReportId(uri);
        	qb.setTables(Tables.UPDATES_TABLE_NAME);
        	qb.appendWhere(Update.REPORT_ID + "=\"" + id+"\"");
        	break;
        case REPORT_ID_UPDATE_REQUEST:
        	id = Report.getReportId(uri);
        	qb.setTables(Tables.UPDATE_REQUEST_TABLE_NAME);
        	qb.appendWhere(UpdatesRequest.REPORT_ID + "=\"" + id+"\"");
        	break;	
        case REPORT_ID_FEEDBACK:
        	/*
        	 * In this case a count of the feedbacks is done, the results 
        	 * are grouped by value (0 or 1).
        	 */
        	id = Feedback.getId(uri);
        	groupBy = Feedback.VALUE;
        	projection = new String[]{Feedback.VALUE,Feedback.COUNT};//TODO Check
        	qb.setTables(Tables.FEEDBACKS_TABLE_NAME);
        	qb.appendWhere(Feedback.REPORT_ID + "=\"" + id+"\"");
        	break;
        case UPDATE_ID_FEEDBACK:
        	/*
        	 * In this case a count of the feedbacks is done, the results 
        	 * are grouped by value (0 or 1).
        	 */
        	id = Feedback.getId(uri);
        	groupBy = Feedback.VALUE;
        	projection = new String[]{Feedback.VALUE,Feedback.COUNT};//TODO Check
        	qb.setTables(Tables.FEEDBACKS_TABLE_NAME);
        	qb.appendWhere(Feedback.UPDATE_ID + "=\"" + id+"\"");
        	break;
        case REPORT_ID_FEEDBACK_REQUEST:
        	id = FeedbacksRequest.getId(uri);
        	qb.setTables(Tables.FEEDBACK_REQUEST_TABLE_NAME);
        	qb.appendWhere(FeedbacksRequest.REPORT_ID + "=\"" + id+"\"");
        	break;
        case UPDATE_ID_FEEDBACK_REQUEST:
        	id = FeedbacksRequest.getId(uri);
        	qb.setTables(Tables.FEEDBACK_REQUEST_TABLE_NAME);
        	qb.appendWhere(FeedbacksRequest.UPDATE_ID + "=\"" + id+"\"");
        	break;	
        default:
            throw new IllegalArgumentException("Unknown URI " + uri);
        }
        
        // Get the database and run the query
        SQLiteDatabase db = mOpenHelper.getReadableDatabase();
        Cursor c = qb.query(db, projection, selection, selectionArgs, groupBy, null, sortOrder);

        // Tell the cursor what uri to watch, so it knows when its source data changes
        c.setNotificationUri(getContext().getContentResolver(), uri);
        return c;
	}
	
	@Override
	public int update(Uri uri, ContentValues values, String selection,
			String[] selectionArgs) {
        Log.d(TAG, "update(uri=" + uri + ", values=" + values.toString() + ")");
        String tableName;
        Uri contentUri;
        
		int match = sUriMatcher.match(uri);
        switch (match){
        case LOGIN:
        	tableName = Tables.LOGIN_TABLE_NAME;
        	contentUri = Login.CONTENT_URI;
        	break;
        case SEARCH:
        	tableName = Tables.SEARCH_TABLE_NAME;
        	contentUri = Search.CONTENT_URI;
        	break;
        case SEARCH_RESULT:
        	tableName = Tables.SEARCH_RESULT_TABLE_NAME;
        	contentUri = SearchResult.CONTENT_URI;
        	break;
        case REPORT:
        	tableName = Tables.REPORTS_TABLE_NAME;
        	contentUri = Report.CONTENT_URI;
        	break;
        case UPDATE_REQUEST:
        	tableName = Tables.UPDATE_REQUEST_TABLE_NAME;
        	contentUri = Report.CONTENT_URI;
        	break;
        case FEEDBACK_REQUEST:
        	tableName = Tables.FEEDBACK_REQUEST_TABLE_NAME;
        	contentUri = Report.CONTENT_URI;
        	break;
        default:
            throw new IllegalArgumentException("Unknown URI " + uri);
        }
        
        // Get the database and run the query
        SQLiteDatabase db = mOpenHelper.getReadableDatabase();
        int num = db.update(tableName, values, selection, selectionArgs);

        // Notify changes to the content observers.
        getContext().getContentResolver().notifyChange(contentUri, null);

		return num;

	}
	
	
    /**
     * Apply the given set of {@link ContentProviderOperation}, executing inside
     * a {@link SQLiteDatabase} transaction. All changes will be rolled back if
     * any single one fails.
     */
    @Override
    public ContentProviderResult[] applyBatch(ArrayList<ContentProviderOperation> operations)
            throws OperationApplicationException {
        final SQLiteDatabase db = mOpenHelper.getWritableDatabase();
        db.beginTransaction();
        try {
            final int numOperations = operations.size();
            final ContentProviderResult[] results = new ContentProviderResult[numOperations];
            for (int i = 0; i < numOperations; i++) {
                results[i] = operations.get(i).apply(this, results, i);
            }
            db.setTransactionSuccessful();
            return results;
        } finally {
            db.endTransaction();
        }
    }

    static {
        sUriMatcher = new UriMatcher(UriMatcher.NO_MATCH);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "login", LOGIN);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "users", USER);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "users/*", USER_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search", SEARCH);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search/*", SEARCH_SEARCH_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_result", SEARCH_RESULT);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_result/*", SEARCH_ID_SEARCH_RESULT);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_reports/*", SEARCH_REPORTS_SEARCH_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_reports/*/*", SEARCH_REPORTS_SEARCH_ID_REPORT_TYPE);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "reports", REPORT);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "reports/*", REPORT_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "updates", UPDATE);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "updates/r_id/*", REPORT_ID_UPDATE);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "updates/*", UPDATE_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "updates_request", UPDATE_REQUEST);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "updates_request/*", REPORT_ID_UPDATE_REQUEST);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedbacks", FEEDBACK);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedbacks/r_id/*", REPORT_ID_FEEDBACK);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedbacks/u_id/*", UPDATE_ID_FEEDBACK);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedbacks_request", FEEDBACK_REQUEST);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedbacks_request/r_id/*", REPORT_ID_FEEDBACK_REQUEST);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedbacks_request/u_id/*", UPDATE_ID_FEEDBACK_REQUEST);
    }

}
