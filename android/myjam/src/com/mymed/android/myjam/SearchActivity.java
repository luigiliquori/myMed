package com.mymed.android.myjam;

import android.app.ListActivity;
import android.app.ProgressDialog;
import android.content.ComponentName;
import android.content.ContentUris;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.SimpleCursorAdapter;
import android.widget.Toast;

import com.mymed.android.myjam.R;
import com.mymed.android.myjam.controller.RestCallManager;
import com.mymed.android.myjam.provider.MyJamContract.LocatedReport;
import com.mymed.android.myjam.service.MyJamCallService;
import com.mymed.android.myjam.service.MyJamCallService.LocalBinder;


public class SearchActivity extends ListActivity implements IMyJamRestActivity{
    private static final String TAG = "ReportsList";
    private final Handler mHandler = new Handler();
    RestCallManager mService;
    boolean mBound = false;
        
    private static final String[] PROJECTION = new String[] {
        LocatedReport._ID, // 0
        LocatedReport.REPORT_ID, // 1
        LocatedReport.REPORT_TYPE, // 2
        LocatedReport.LATITUDE,	// 3
        LocatedReport.LONGITUDE, // 4
};
    
    // Menu item ids
    public static final int MENU_ITEM_SEARCH = Menu.FIRST;
    public static final int MENU_VIEW_ON_MAP = Menu.FIRST + 1;
    public static final int MENU_ITEM_INSERT = Menu.FIRST + 2;
    
    private ProgressDialog dialog;
    private SimpleCursorAdapter adapter;
    private Button searchButton;
    private EditText latitudeEditText;
    private EditText longitudeEditText;
    private EditText radiusEditText;
    
    @Override
	public void onCreate(Bundle savedInstanceState) {
	    super.onCreate(savedInstanceState);
	    setContentView(R.layout.main);
	    

	       setDefaultKeyMode(DEFAULT_KEYS_SHORTCUT);
			latitudeEditText = (EditText) findViewById(R.id.editTextLatitdue);
			longitudeEditText = (EditText) findViewById(R.id.editTextLongitude);
			radiusEditText = (EditText) findViewById(R.id.editTextRadius);
			searchButton = (Button) findViewById(R.id.SearchButton);
		    this.searchButton.setOnClickListener(new View.OnClickListener() {
		        public void onClick(View v) {
		        	 if (mBound) {
		                 // Call a method from the LocalService.
		                 // However, if this call were something that might hang, then this request should
		                 // occur in a separate thread to avoid slowing down the activity performance.
		        		 try{
			        		 int lat = (int) (Double.parseDouble(latitudeEditText.getText().toString())*1E6);
			        		 int lon = (int) (Double.parseDouble(longitudeEditText.getText().toString())*1E6);
			              	 int radius = (int) Integer.parseInt(radiusEditText.getText().toString()); 
			              	 mService.searchReports(lat,lon,radius,mHandler,SearchActivity.this);
		        		 } catch (Exception e){
		        			 Toast.makeText(SearchActivity.this, "Wrong parameters.",Toast.LENGTH_LONG).show();
		        		 }
		             }
		        }
		    });


	        // If no data was given in the intent (because we were started
	        // as a MAIN activity), then use our default content provider.
	        Intent intent = getIntent();
	        if (intent.getData() == null) {
	            intent.setData(LocatedReport.CONTENT_URI);
	        }

	        // Inform the list we provide context menus for items
	        getListView().setOnCreateContextMenuListener(this);
	        
	        // Perform a managed query. The Activity will handle closing and requerying the cursor
	        // when needed.
	        Cursor cursor = managedQuery(getIntent().getData(), PROJECTION, null, null,
	                LocatedReport.DEFAULT_SORT_ORDER);

	        // Used to map notes entries from the database to views
	        adapter = new SimpleCursorAdapter(this, R.layout.noteslist_item, cursor,
	                new String[] { LocatedReport.REPORT_TYPE,LocatedReport.LATITUDE,
	        			LocatedReport.LONGITUDE}, 
	                new int[] { R.id.textType,R.id.textLatitude,R.id.textLongitude });
	        setListAdapter(adapter);
	}
    
    @Override
    protected void onStart() {
        super.onStart();
        // Bind to LocalService
        Intent intent = new Intent(this, MyJamCallService.class);
        bindService(intent, mConnection, Context.BIND_AUTO_CREATE);
    }
    
    @Override
    protected void onStop() {
        super.onStop();
        // Unbind from the service
        if (mBound) {
            unbindService(mConnection);
            mBound = false;
        }
    }
    
    /** Defines callbacks for service binding, passed to bindService() */
    private ServiceConnection mConnection = new ServiceConnection() {

        @Override
        public void onServiceConnected(ComponentName className,
                IBinder service) {
            // We've bound to LocalService, cast the IBinder and get LocalService instance
            @SuppressWarnings("unchecked")
			LocalBinder<RestCallManager> binder = (LocalBinder<RestCallManager>) service;
            mService = binder.getService();
            mBound = true;
        }

        @Override
        public void onServiceDisconnected(ComponentName arg0) {
            mBound = false;
        }
    };
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        super.onCreateOptionsMenu(menu);

        // This is our one standard application action -- inserting a
        // new note into the list.
        menu.add(0, MENU_ITEM_INSERT, 0, R.string.menu_insert)
                .setShortcut('3', 'a')
                .setIcon(android.R.drawable.ic_menu_add);

        // Generate any additional actions that can be performed on the
        // overall list.  In a normal install, there are no additional
        // actions found here, but this allows other applications to extend
        // our menu with their own actions.
        Intent intent = new Intent(null, getIntent().getData());
        intent.addCategory(Intent.CATEGORY_ALTERNATIVE);
        menu.addIntentOptions(Menu.CATEGORY_ALTERNATIVE, 0, 0,
                new ComponentName(this, SearchActivity.class), null, intent, 0, null);

        return true;
    }
    
    @Override
    public boolean onPrepareOptionsMenu(Menu menu) {
        super.onPrepareOptionsMenu(menu);
        final boolean haveItems = getListAdapter().getCount() > 0;

        // If there are any notes in the list (which implies that one of
        // them is selected), then we need to generate the actions that
        // can be performed on the current selection.  This will be a combination
        // of our own specific actions along with any extensions that can be
        // found.
        if (haveItems) {
            // This is the selected item.
            Uri uri = ContentUris.withAppendedId(getIntent().getData(), getSelectedItemId());

            // Build menu...  always starts with the EDIT action...
            Intent[] specifics = new Intent[1];
            specifics[0] = new Intent(Intent.ACTION_EDIT, uri);
            MenuItem[] items = new MenuItem[1];

            // ... is followed by whatever other actions are available...
            Intent intent = new Intent(null, uri);
            intent.addCategory(Intent.CATEGORY_ALTERNATIVE);
            menu.addIntentOptions(Menu.CATEGORY_ALTERNATIVE, 0, 0, null, specifics, intent, 0,
                    items);

            // Give a shortcut to the edit action.
            if (items[0] != null) {
                items[0].setShortcut('1', 'e');
            }
        } else {
            menu.removeGroup(Menu.CATEGORY_ALTERNATIVE);
        }

        return true;
    }
    
    /** Called when a button is clicked (the button in the layout file attaches to
     * this method with the android:onClick attribute) */    


	@Override
	public void onFinishCall(boolean result, String message) {
		dialog.dismiss();
		if (result)
			Toast.makeText(this, "Search finished.",Toast.LENGTH_LONG).show();
		else
			Toast.makeText(this, message,Toast.LENGTH_LONG).show();
//		if (adapter!=null)
//			adapter.notifyDataSetChanged();
	}

	@Override
	public void onStartCall() {
		dialog = ProgressDialog.show(SearchActivity.this, "", 
                "Searching reports...", true);
	}
	
	private static String formatLatLon(String arg){
		if (arg == null)			
			return "";
		
		if (arg.length() == 8)
			return arg.substring(0, 1)+"."+arg.substring(2, 6);
		else if (arg.length() == 8)
			return arg.substring(0, 0)+"."+arg.substring(1, 5);
		else
			return "";
	}

    
    
}
