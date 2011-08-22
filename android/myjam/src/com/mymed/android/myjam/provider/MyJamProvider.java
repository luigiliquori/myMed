package com.mymed.android.myjam.provider;

import java.util.ArrayList;

import com.mymed.android.myjam.provider.MyJamContract.Feedback;
import com.mymed.android.myjam.provider.MyJamContract.FeedbacksRequest;
import com.mymed.android.myjam.provider.MyJamContract.MyUsers;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Search;
import com.mymed.android.myjam.provider.MyJamContract.SearchReports;
import com.mymed.android.myjam.provider.MyJamContract.SearchResult;
import com.mymed.android.myjam.provider.MyJamContract.Update;
import com.mymed.android.myjam.provider.MyJamContract.UpdatesRequest;

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

public class MyJamProvider extends ContentProvider{
	
    private static final String TAG = "MyJamProvider";

    private static final String DATABASE_NAME = "my_jam.db";
    private static final int DATABASE_VERSION = 1;
    interface Tables{
        final String MY_USERS_TABLE_NAME = "my_users";
		final String SEARCH_TABLE_NAME = "search";
		final String SEARCH_RESULT_TABLE_NAME = "search_results";
		final String REPORT_TABLE_NAME = "reports";
		final String UPDATE_TABLE_NAME = "updates";
        final String UPDATE_REQUEST_TABLE_NAME = "updates_request";
        final String FEEDBACK_TABLE_NAME = "feedbacks";
        final String FEEDBACK_REQUEST_TABLE_NAME = "feedbacks_request";      
        
        final String SEARCH_JOIN_REPORTS = "search_results "
                + "INNER JOIN reports ON search_results.report_id=reports.report_id ";
    }
    
    /** {@code REFERENCES} clauses. */
    private interface References {
    	String REPORT_ID = "REFERENCES " + Tables.REPORT_TABLE_NAME + "(" + Report.REPORT_ID + ")";
        String UPDATE_ID = "REFERENCES " + Tables.UPDATE_TABLE_NAME + "(" + Update.UPDATE_ID + ")";
    }
    
    /** {@code REFERENCES} clauses. */
    private interface Indexes {
    	String SEARCH_RESULT_SEARCH_ID_INDEX = "CREATE INDEX search_result_search_id_index ON "+ Tables.SEARCH_RESULT_TABLE_NAME + "(" + SearchResult.SEARCH_ID + ")";
        String REPORTS_INDEX = "CREATE INDEX reports_index ON "+ Tables.REPORT_TABLE_NAME + "(" + Report.REPORT_ID + ")";
        String REPORTS_USER_ID_INDEX = "CREATE INDEX reports_user_id_index ON "+ Tables.REPORT_TABLE_NAME + "(" + Report.USER_ID + ")";
        String UPDATES_REPORT_ID_INDEX = "CREATE INDEX updates_report_id_index ON "+ Tables.UPDATE_TABLE_NAME + "(" + Update.REPORT_ID + ")";
        String FEEDBACKS_REPORT_ID_INDEX = "CREATE INDEX feedbacks_report_id_index ON "+ Tables.FEEDBACK_TABLE_NAME + "(" + Feedback.REPORT_ID + ")";
        String FEEDBACKS_UPDATE_ID_INDEX = "CREATE INDEX feedbacks_update_id_index ON "+ Tables.FEEDBACK_TABLE_NAME + "(" + Feedback.UPDATE_ID + ")";
    }
    
    private static final int MY_USERS = 18;
    
    private static final int SEARCH = 0;
    private static final int SEARCH_SEARCH_ID = 17;
    
    private static final int SEARCH_RESULT = 1;
    private static final int SEARCH_ID_SEARCH_RESULT = 2;
    
    private static final int SEARCH_REPORTS_SEARCH_ID = 3;
    private static final int SEARCH_REPORTS_SEARCH_ID_REPORT_TYPE = 4;
    
    private static final int REPORT = 5;
    private static final int REPORT_ID = 6;
    
    private static final int UPDATE = 7;
    private static final int REPORT_ID_UPDATE = 8;
    
    private static final int FEEDBACK = 9;   
    private static final int REPORT_ID_FEEDBACK = 10;
    private static final int UPDATE_ID_FEEDBACK = 11;
    
    private static final int UPDATE_REQUEST = 12;
    private static final int REPORT_ID_UPDATE_REQUEST = 13;
    private static final int FEEDBACK_REQUEST = 14;
    private static final int REPORT_ID_FEEDBACK_REQUEST = 15;
    private static final int UPDATE_ID_FEEDBACK_REQUEST = 16;
    

    private static final UriMatcher sUriMatcher;

    private static class DatabaseHelper extends SQLiteOpenHelper {

		DatabaseHelper(Context context) {
            super(context, DATABASE_NAME, null, DATABASE_VERSION);
        }

        @Override
        public void onCreate(SQLiteDatabase db) {
        	// Enable foreign key constraints
            db.execSQL("PRAGMA foreign_keys=ON;");
            
            db.execSQL("CREATE TABLE " + Tables.MY_USERS_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + MyUsers.USER_ID + " TEXT NOT NULL,"
                    + MyUsers.USER_NAME + " TEXT,"
                    + "UNIQUE (" + MyUsers.USER_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL("CREATE TABLE " + Tables.SEARCH_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + Search.SEARCH_ID + " INTEGER NOT NULL,"
                    + Search.DATE + " INTEGER,"
                    + "UNIQUE (" + Search.SEARCH_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL("CREATE TABLE " + Tables.SEARCH_RESULT_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + SearchResult.REPORT_ID + " TEXT NOT NULL,"
                    + SearchResult.SEARCH_ID + " TEXT NOT NULL,"
                    + SearchResult.DISTANCE + " INTEGER,"
                    + "UNIQUE (" + SearchResult.REPORT_ID + "," + SearchResult.SEARCH_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL(Indexes.SEARCH_RESULT_SEARCH_ID_INDEX);
            
            db.execSQL("CREATE TABLE " + Tables.REPORT_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + Report.REPORT_ID + " TEXT NOT NULL," 
                    + Report.USER_ID + " TEXT,"
                    + Report.USER_NAME + " TEXT,"
                    + Report.LATITUDE + " INTEGER NOT NULL,"
                    + Report.LONGITUDE + " INTEGER NOT NULL,"
                    + Report.REPORT_TYPE + " TEXT NOT NULL,"
                    + Report.TRAFFIC_FLOW + " TEXT,"
                    + Report.TRANSIT_TYPE + " TEXT,"
                    + Report.COMMENT + " TEXT,"
                    + Report.DATE + " INTEGER,"
                    + Report.FLAG_COMPLETE + " INTEGER DEFAULT 0,"
                    + "UNIQUE (" + Report.REPORT_ID + ") ON CONFLICT IGNORE)");
            
            db.execSQL(Indexes.REPORTS_INDEX);
            db.execSQL(Indexes.REPORTS_USER_ID_INDEX);
            
            db.execSQL("CREATE TABLE " + Tables.UPDATE_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
            		+ Update.UPDATE_ID + " TEXT NOT NULL,"
            		+ Update.REPORT_ID + " TEXT " + References.REPORT_ID + " ON DELETE CASCADE,"
                    + Update.USER_ID + " TEXT NOT NULL,"
                    + Update.USER_NAME + " TEXT NOT NULL,"
                    + Update.TRAFFIC_FLOW + " TEXT,"
                    + Update.TRANSIT_TYPE + " TEXT,"
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
            
            db.execSQL("CREATE TABLE " + Tables.FEEDBACK_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
            		+ Feedback.REPORT_ID + " TEXT " + References.REPORT_ID + " ON DELETE CASCADE,"
            		+ Feedback.UPDATE_ID + " TEXT " + References.UPDATE_ID + " ON DELETE CASCADE,"
                    + Feedback.GRADE + " INTEGER,"
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
        case SEARCH_RESULT:
        	tableName = Tables.SEARCH_RESULT_TABLE_NAME;
        	contentUri = SearchResult.CONTENT_URI;
        	break;
        case REPORT:
        	tableName = Tables.REPORT_TABLE_NAME;
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
        case MY_USERS:
        	tableName = Tables.MY_USERS_TABLE_NAME;
        	nullColumnHack = MyUsers.USER_ID;
        	contentUri = MyUsers.CONTENT_URI;
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
        	tableName = Tables.REPORT_TABLE_NAME;
        	nullColumnHack = Report.REPORT_ID;
        	contentUri = Report.CONTENT_URI;
        	break;
        case UPDATE:
        	tableName = Tables.UPDATE_TABLE_NAME;
        	nullColumnHack = Update.UPDATE_ID;
        	contentUri = Update.CONTENT_URI;
        	break;
        case UPDATE_REQUEST:
        	tableName = Tables.UPDATE_REQUEST_TABLE_NAME;
        	nullColumnHack = UpdatesRequest._ID;
        	contentUri = UpdatesRequest.CONTENT_URI;
        	break;
        case FEEDBACK:
        	tableName = Tables.FEEDBACK_TABLE_NAME;
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
		
		SQLiteQueryBuilder qb = new SQLiteQueryBuilder();
		int match = sUriMatcher.match(uri);
        switch (match){
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
        	qb.setTables(Tables.REPORT_TABLE_NAME);
        	qb.appendWhere(Report.REPORT_ID + "=\"" + id+"\"");
        	break;
        case REPORT_ID_UPDATE:
        	id = Report.getReportId(uri);
        	qb.setTables(Tables.UPDATE_TABLE_NAME);
        	qb.appendWhere(Update.REPORT_ID + "=\"" + id+"\"");
        	break;
        case REPORT_ID_UPDATE_REQUEST:
        	id = Report.getReportId(uri);
        	qb.setTables(Tables.UPDATE_REQUEST_TABLE_NAME);
        	qb.appendWhere(UpdatesRequest.REPORT_ID + "=\"" + id+"\"");
        	break;	
        case REPORT_ID_FEEDBACK:
        	id = Feedback.getId(uri);
        	qb.setTables(Tables.FEEDBACK_TABLE_NAME);
        	qb.appendWhere(Feedback.REPORT_ID + "=\"" + id+"\"");
        	break;
        case UPDATE_ID_FEEDBACK:
        	id = Feedback.getId(uri);
        	qb.setTables(Tables.FEEDBACK_TABLE_NAME);
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
        Cursor c = qb.query(db, projection, selection, selectionArgs, null, null, sortOrder);

        // Tell the cursor what uri to watch, so it knows when its source data changes
        c.setNotificationUri(getContext().getContentResolver(), uri);
        return c;
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

	@Override
	public int update(Uri uri, ContentValues values, String selection,
			String[] selectionArgs) {
        Log.d(TAG, "update(uri=" + uri + ", values=" + values.toString() + ")");
        String tableName;
        Uri contentUri;
        
		int match = sUriMatcher.match(uri);
        switch (match){
        case SEARCH_RESULT:
        	tableName = Tables.SEARCH_RESULT_TABLE_NAME;
        	contentUri = SearchResult.CONTENT_URI;
        	break;
        case REPORT:
        	tableName = Tables.REPORT_TABLE_NAME;
        	contentUri = Report.CONTENT_URI;
        	break;
        default:
            throw new IllegalArgumentException("Unknown URI " + uri);
        }
        
        // Get the database and run the query
        SQLiteDatabase db = mOpenHelper.getReadableDatabase();
        int num = db.update(tableName, values, selection, selectionArgs);

        // Tell the cursor what uri to watch, so it knows when its source data changes
        getContext().getContentResolver().notifyChange(contentUri, null);

		return num;

	}
	
    static {
        sUriMatcher = new UriMatcher(UriMatcher.NO_MATCH);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "my_users", MY_USERS);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search", SEARCH);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search/*", SEARCH_SEARCH_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_result", SEARCH_RESULT);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_result/*", SEARCH_ID_SEARCH_RESULT);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_reports/*", SEARCH_REPORTS_SEARCH_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "search_reports/*/*", SEARCH_REPORTS_SEARCH_ID_REPORT_TYPE);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "report", REPORT);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "report/*", REPORT_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "update", UPDATE);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "update/*", REPORT_ID_UPDATE);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "update_request", UPDATE_REQUEST);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "update_request/*", REPORT_ID_UPDATE_REQUEST);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedback", FEEDBACK);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedback/r_id/*", REPORT_ID_FEEDBACK);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedback/u_id/*", UPDATE_ID_FEEDBACK);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedback_request", FEEDBACK_REQUEST);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedback_request/r_id/*", REPORT_ID_FEEDBACK_REQUEST);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, "feedback_request/u_id/*", UPDATE_ID_FEEDBACK_REQUEST);
    }

}
