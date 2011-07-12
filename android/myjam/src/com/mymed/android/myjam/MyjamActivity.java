package com.mymed.android.myjam;

import android.app.Activity;
import android.os.Bundle;

import com.mymed.android.myjam.R;
import com.mymed.android.myjam.controller.MyJamRestCall;

public class MyjamActivity extends Activity { 

	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        
        MyJamRestCall locationCall = new MyJamRestCall();
        locationCall.insert();
    }
}