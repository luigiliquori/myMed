package com.mymed.utils;

import java.util.ArrayList;
import java.util.Iterator;

import android.app.AlertDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Point;
import android.graphics.drawable.Drawable;
import android.net.Uri;

import com.google.android.maps.GeoPoint;
import com.google.android.maps.ItemizedOverlay;
import com.google.android.maps.MapView;
import com.google.android.maps.OverlayItem;
import com.google.android.maps.Projection;
import com.mymed.android.myjam.R;
import com.mymed.android.myjam.provider.MyJamContract.Report;
import com.mymed.android.myjam.ui.ReportDetailsActivity;

/**
 * 
 * @author iacopo
 *
 */
public class MyJamItemizedOverlay extends ItemizedOverlay<OverlayItem> {
	private ArrayList<OverlayItem> mOverlays = new ArrayList<OverlayItem>();
	private ArrayList<GeoPoint[]> mFigures = new ArrayList<GeoPoint[]>();
	private Drawable mJamMarker,
					 mCarCrashMarker,
					 mWorkInProgressMarker,
					 mFixedSpeedCamMarker,
					 mMobileSpeedCamMarker,
					 mCurrentPositionMarker;
	private OverlayItem currUserPosition = null;
	
	private Context mContext; //TODO Check if is leaking memory
		
	public MyJamItemizedOverlay(Drawable defaultMarker,Context context) {
		super(boundCenterBottom(defaultMarker));	
		GlobalStateAndUtils instance = GlobalStateAndUtils.getInstance(context);
	    mJamMarker = context.getResources().getDrawable(instance.getIconByReportType(GlobalStateAndUtils.JAM));
		mCarCrashMarker = context.getResources().getDrawable(instance.getIconByReportType(GlobalStateAndUtils.CAR_CRASH));
		mWorkInProgressMarker = context.getResources().getDrawable(instance.getIconByReportType(GlobalStateAndUtils.WORK_IN_PROGRESS));
		mFixedSpeedCamMarker = context.getResources().getDrawable(instance.getIconByReportType(GlobalStateAndUtils.FIXED_SPEED_CAM));
		mMobileSpeedCamMarker = context.getResources().getDrawable(instance.getIconByReportType(GlobalStateAndUtils.MOBILE_SPEED_CAM));
		mCurrentPositionMarker = context.getResources().getDrawable(R.drawable.ic_maps_indicator_current_position);
		
		mContext = context;
	}
	
	@Override
	protected OverlayItem createItem(int i) {
		  return mOverlays.get(i);
	}

	@Override
	public int size() {
		return mOverlays.size();
	}
	
	/**
	 * Adds an overlay with a marker depending on the type. 
	 * @param position
	 * @param typeId
	 */
	public void addOverlay(GeoPoint position,int typeId,String id) {
		OverlayItem overlayItem;
		switch (typeId){
			case GlobalStateAndUtils.JAM:
				overlayItem = new OverlayItem(position,"",id);
				boundCenterBottom(mJamMarker);
				overlayItem.setMarker(mJamMarker);
				break;
			case GlobalStateAndUtils.CAR_CRASH:
				overlayItem = new OverlayItem(position,"",id);
				boundCenterBottom(mCarCrashMarker);
				overlayItem.setMarker(mCarCrashMarker);
				break;
			case GlobalStateAndUtils.WORK_IN_PROGRESS:
				overlayItem = new OverlayItem(position,"",id);
				boundCenterBottom(mWorkInProgressMarker);
				overlayItem.setMarker(mWorkInProgressMarker);
				break;
			case GlobalStateAndUtils.FIXED_SPEED_CAM:
				overlayItem = new OverlayItem(position,"",id);
				boundCenterBottom(mFixedSpeedCamMarker);
				overlayItem.setMarker(mFixedSpeedCamMarker);
				break;
			case GlobalStateAndUtils.MOBILE_SPEED_CAM:
				overlayItem = new OverlayItem(position,"",id);
				boundCenterBottom(mMobileSpeedCamMarker);
				overlayItem.setMarker(mMobileSpeedCamMarker);
				break;
			case GlobalStateAndUtils.USER_POSITION:
				overlayItem = new OverlayItem(position,mContext.getResources().getString(R.string.notification_loc_available_title),
						mContext.getResources().getString(R.string.notification_loc_available_title));
				boundCenterBottom(mCurrentPositionMarker);
				overlayItem.setMarker(mCurrentPositionMarker);
				if (currUserPosition != null)
					mOverlays.remove(currUserPosition);
				currUserPosition = overlayItem;
				break;
			default:
				return;
		}
	    mOverlays.add(overlayItem);
	    populate();
	}
	
    @Override
    protected boolean onTap(int index)
    {
        OverlayItem item = mOverlays.get(index);

        if (currUserPosition != null && currUserPosition.equals(item)){
        	//Do stuff here when you tap, i.e. :
            new AlertDialog.Builder(mContext)
    		.setTitle(R.string.dialog_title)
    		.setIcon(R.drawable.myjam_icon)
            .setMessage(item.getSnippet())
            .show();
        }else{
        	showDetails(item.getSnippet());
        }
        //return true to indicate we've taken care of it
        return true;
    }
    
    /**
     * Starts details activity.
     * @param reportId The id of the selected report.
     */
	private void showDetails(String reportId){
        Intent intent = new Intent(mContext,ReportDetailsActivity.class);
        Uri uri = Report.buildReportIdUri(reportId);
        intent.setData(uri);   	
        mContext.startActivity(intent);
	}
	
	public void addFigure(GeoPoint[] fig) {
		mFigures.add(fig);
	}
	
	@Override
    public void draw(Canvas canvas, MapView mapV,boolean shadow){
		if (shadow){
			GeoPoint[] figure;
			Point oldPt,newPt;
			
			Projection projection = mapV.getProjection();
			Paint linePaint = new Paint(Paint.ANTI_ALIAS_FLAG);
			Iterator<GeoPoint[]> figIt = mFigures.iterator();
			while (figIt.hasNext()){
				figure = figIt.next();
				oldPt= new Point();
				newPt= new Point();
				//TODO Remove change color, only for debug
				linePaint.setStrokeWidth(3);
				linePaint.setColor(Color.RED);

				for (int index = 0;index<figure.length;index++){
					if (index==0){
						projection.toPixels(figure[index], oldPt);
						projection.toPixels(figure[(index+1)%figure.length], newPt);
						canvas.drawLine(oldPt.x, oldPt.y, newPt.x, newPt.y, linePaint);
						oldPt=newPt;
						newPt = new Point();
					}else{
						projection.toPixels(figure[(index+1)%figure.length], newPt);
						canvas.drawLine(oldPt.x, oldPt.y, newPt.x, newPt.y, linePaint);
						oldPt = newPt;
						newPt = new Point();
					}
				}
			}
		}
		super.draw(canvas,mapV,shadow);
    }
}
