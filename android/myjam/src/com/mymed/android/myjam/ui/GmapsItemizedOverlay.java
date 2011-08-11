package com.mymed.android.myjam.ui;

import java.util.ArrayList;
import java.util.Iterator;

import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Point;
import android.graphics.drawable.Drawable;

import com.google.android.maps.GeoPoint;
import com.google.android.maps.ItemizedOverlay;
import com.google.android.maps.MapView;
import com.google.android.maps.OverlayItem;
import com.google.android.maps.Projection;

public class GmapsItemizedOverlay extends ItemizedOverlay<OverlayItem> {
	private ArrayList<OverlayItem> mOverlays = new ArrayList<OverlayItem>();
	private ArrayList<GeoPoint[]> mFigures = new ArrayList<GeoPoint[]>();
	
	public GmapsItemizedOverlay(Drawable defaultMarker) {
		super(boundCenterBottom(defaultMarker));	
	}
	
	@Override
	protected OverlayItem createItem(int i) {
		  return mOverlays.get(i);
	}

	@Override
	public int size() {
		return mOverlays.size();
	}
	
	public void addOverlay(OverlayItem overlay) {
	    mOverlays.add(overlay);
	    populate();
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
			int ind=0;
			while (figIt.hasNext()){
				figure = figIt.next();
				oldPt= new Point();
				newPt= new Point();
				//TODO Remove change color, only for debug
				linePaint.setColor(Color.BLACK);
				linePaint.setStrokeWidth(1);
				if (ind==0){
					linePaint.setStrokeWidth(3);
					linePaint.setColor(Color.RED);
				}
				
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
				ind++;
			}
		}
		super.draw(canvas,mapV,shadow);
    }
}
