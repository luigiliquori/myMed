package com.mymed.android.myjam.provider;

import com.mymed.android.myjam.provider.MyJamContract.Feedback;
import com.mymed.android.myjam.provider.MyJamContract.LocatedReport;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.provider.MyJamContract.Update;

import android.content.ContentProvider;
import android.content.ContentUris;
import android.content.ContentValues;
import android.content.Context;
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
        final String LOCATED_REPORTS_TABLE_NAME = "located_reports";
        final String REPORTS_TABLE_NAME = "reports";
        final String UPDATES_TABLE_NAME = "updates";
        final String FEEDBACKS_TABLE_NAME = "feedbacks";
    }
    
    /** {@code REFERENCES} clauses. */
    private interface References {
        String REPORT_ID = "REFERENCES " + Tables.REPORTS_TABLE_NAME + "(" + Report.REPORT_ID + ")";
    }
    
    private static final int LOCATED_REPORTS = 1;
    private static final int REPORTS = 2;
    private static final int UPDATES = 3;
    private static final int FEEDBACKS = 4;

    private static final int LOCATED_REPORT_ID = 5;    
    private static final int REPORT_ID = 6;
    private static final int UPDATE_ID = 7;
    private static final int FEEDBACK_ID = 8;
    
    

    private static final UriMatcher sUriMatcher;

    private static class DatabaseHelper extends SQLiteOpenHelper {

		DatabaseHelper(Context context) {
            super(context, DATABASE_NAME, null, DATABASE_VERSION);
        }

        @Override
        public void onCreate(SQLiteDatabase db) {
            db.execSQL("CREATE TABLE " + Tables.LOCATED_REPORTS_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + LocatedReport.REPORT_TYPE + " TEXT NOT NULL,"
                    + LocatedReport.REPORT_ID + " TEXT " + References.REPORT_ID + ","
                    + LocatedReport.LATITUDE + " INTEGER NOT NULL,"
                    + LocatedReport.LONGITUDE + " INTEGER NOT NULL,"
                    + LocatedReport.DISTANCE + " INTEGER,"
                    + LocatedReport.DATE + " INTEGER,"
                    + "UNIQUE (" + LocatedReport.REPORT_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL("CREATE TABLE " + Tables.REPORTS_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                    + Report.REPORT_ID + " TEXT NOT NULL,"
                    + Report.USER_ID + " TEXT NOT NULL,"
                    + Report.USER_NAME + " TEXT NOT NULL,"
                    + Report.REPORT_TYPE + " TEXT NOT NULL,"
                    + Report.TRAFFIC_FLOW + " TEXT,"
                    + Report.TRANSIT_TYPE + " TEXT,"
                    + Report.COMMENT + " TEXT,"
                    + Report.DATE + " INTEGER,"
                    + "UNIQUE (" + Report.REPORT_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL("CREATE TABLE " + Tables.UPDATES_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
            		+ Update.UPDATE_ID + " TEXT NOT NULL,"
            		+ Update.REPORT_ID + " TEXT " + References.REPORT_ID + ","
                    + Update.USER_ID + " TEXT NOT NULL,"
                    + Update.USER_NAME + " TEXT NOT NULL,"
                    + Update.TRAFFIC_FLOW + " TEXT,"
                    + Update.TRANSIT_TYPE + " TEXT,"
                    + Update.COMMENT + " TEXT,"
                    + Update.DATE + " INTEGER,"
                    + "UNIQUE (" + Update.UPDATE_ID + ") ON CONFLICT REPLACE)");
            
            db.execSQL("CREATE TABLE " + Tables.FEEDBACKS_TABLE_NAME + " ("
            		+ BaseColumns._ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
            		+ Feedback.FEEDBACK_ID + " TEXT NOT NULL,"
            		+ Feedback.REPORT_ID + " TEXT " + References.REPORT_ID + ","
                    + Feedback.GRADE + " INTEGER NOT NULL,"
                    + Feedback.USER_ID + " TEXT NOT NULL,"
                    + Feedback.DATE + " INTEGER"
                    + ");");            
        }

        @Override
        public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
            Log.w(TAG, "Upgrading database from version " + oldVersion + " to "
                    + newVersion + ", which will destroy all old data");
            db.execSQL("DROP TABLE IF EXISTS notes");
            onCreate(db);
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
        case LOCATED_REPORTS:
        	tableName = Tables.LOCATED_REPORTS_TABLE_NAME;
        	contentUri = LocatedReport.CONTENT_URI;
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
        case LOCATED_REPORTS:
        	tableName = Tables.LOCATED_REPORTS_TABLE_NAME;
        	nullColumnHack = LocatedReport.REPORT_ID;
        	contentUri = LocatedReport.CONTENT_URI;
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
	public int bulkInsert(Uri uri, ContentValues[] values) {
        String tableName;
        String nullColumnHack;
        Uri contentUri;
        int cont = 0;
    	// Validate the requested uri
        switch (sUriMatcher.match(uri)) {
        case LOCATED_REPORTS:
        	tableName = Tables.LOCATED_REPORTS_TABLE_NAME;
        	nullColumnHack = LocatedReport.REPORT_ID;
        	contentUri = LocatedReport.CONTENT_URI;
        	break;
        default:
            throw new IllegalArgumentException("Unknown URI " + uri);
        }
        
        if (values == null)
        	throw new IllegalArgumentException("Null content.");
        for (ContentValues currVal:values){
            if (currVal != null) {      	     
                SQLiteDatabase db = mOpenHelper.getWritableDatabase();
                long rowId = db.insert(tableName, nullColumnHack, currVal);
                if (rowId > 0) {
                    cont++;
                    
                }else
                	throw new SQLException("Failed to insert row into " + uri);
            }
        }
        getContext().getContentResolver().notifyChange(contentUri, null);
        return cont;
	}

	@Override
	public Cursor query(Uri uri, String[] projection, String selection,
			String[] selectionArgs, String sortOrder) {
		
		SQLiteQueryBuilder qb = new SQLiteQueryBuilder();
		int match = sUriMatcher.match(uri);
        switch (match){
        case LOCATED_REPORTS:
        	qb.setTables(Tables.LOCATED_REPORTS_TABLE_NAME);
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

	@Override
	public int update(Uri uri, ContentValues values, String selection,
			String[] selectionArgs) {
		// TODO Auto-generated method stub
		return 0;
	}
	
    static {
        sUriMatcher = new UriMatcher(UriMatcher.NO_MATCH);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, Tables.LOCATED_REPORTS_TABLE_NAME, LOCATED_REPORTS);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, Tables.LOCATED_REPORTS_TABLE_NAME+"/#", LOCATED_REPORT_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, Tables.REPORTS_TABLE_NAME, REPORTS);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, Tables.REPORTS_TABLE_NAME+"/#", REPORT_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, Tables.UPDATES_TABLE_NAME, UPDATES);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, Tables.UPDATES_TABLE_NAME+"/#", UPDATE_ID);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, Tables.FEEDBACKS_TABLE_NAME, FEEDBACKS);
        sUriMatcher.addURI(MyJamContract.CONTENT_AUTHORITY, Tables.FEEDBACKS_TABLE_NAME+"/#", FEEDBACK_ID);
    }

}
