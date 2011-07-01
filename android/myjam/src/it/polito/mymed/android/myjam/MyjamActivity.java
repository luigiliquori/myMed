package it.polito.mymed.android.myjam;

import it.polito.mymed.android.myjam.controller.CassandraCall;
import it.polito.mymed.android.myjam.locator.*;


import java.util.Iterator;
import java.util.List;
import java.util.Set;

import com.google.android.maps.GeoPoint;
import com.google.android.maps.MapActivity;
import com.google.android.maps.MapController;
import com.google.android.maps.MapView;
import com.google.android.maps.Overlay;
import com.google.android.maps.OverlayItem;

import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.widget.LinearLayout;

public class MyjamActivity extends MapActivity { 
	LinearLayout linearLayout;
	MapView mapView;
	List<Overlay> mapOverlays;
	Drawable drawable;
	GmapsItemizedOverlay itemizedoverlay;
	KeysFinder kf;
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        mapView = (MapView) findViewById(R.id.mapview);
        mapView.setBuiltInZoomControls(true);
        MapController mapContr = mapView.getController();
        mapOverlays = mapView.getOverlays();
        drawable = this.getResources().getDrawable(R.drawable.phone);
        itemizedoverlay = new GmapsItemizedOverlay(drawable);  
        GeoLocation loc;
        try{
            loc = new GeoLocation(45.15,7.70,"");	
        }catch(Exception e){
        	android.util.Log.e("MyJamLocator", e.toString());
        	loc=null;
        }
        GeoPoint point = GeoPointsFactory.getGeoPoint(loc);
        kf = new KeysFinder();
        /*
         * Insert a fake report.
         */
        HilbertQuad hq = HilbertQuad.encode(loc, HilbertQuad.maxLevel);
        long key = KeysFinder.getAreaId(hq.getIndex());
        long sColumnName = hq.getIndex();
        android.util.Log.d("MyJamActivity", "key = "+Long.toString(key));
        android.util.Log.d("MyJamActivity", "sColumnName = "+Long.toString(sColumnName));
        CassandraCall locationCall = new CassandraCall();
        locationCall.put(String.valueOf(key), new Long(sColumnName), 
        		"proofPointerToReport", "Jam");
        
        
        HilbertQuad hq1 = HilbertQuad.decode(hq.getIndex());
        android.util.Log.d("MyJamActivity", "Index hq (decimal) = "+Long.toString(hq.getIndex()));
        android.util.Log.d("MyJamActivity", "Index hq (binary) = "+Long.toBinaryString(hq.getIndex()));
        android.util.Log.d("MyJamActivity", "Decoded hq lat - lon = "+String.valueOf((hq1.getCeilLat()+hq1.getFloorLat())/2)+" - "+
        		String.valueOf((hq1.getCeilLon()+hq1.getFloorLon())/2));
        android.util.Log.d("MyJamActivity", "Code hq1(binary) = "+Long.toBinaryString(hq1.getKeysRange()[0])+" "+
        		Long.toBinaryString(hq1.getKeysRange()[1]));
        double bw = hq.getBottomWidth();
        double tw = hq.getTopWidth();
        double h = hq.getHeigth();
        android.util.Log.d("MyJamLocator", String.valueOf(bw));
        android.util.Log.d("MyJamLocator", String.valueOf(tw));
        android.util.Log.d("MyJamLocator", String.valueOf(h));
        
        
        GeoLocation[] bBox = loc.boundingCoordinates(50000);
        GeoPoint[] boundingBox = GeoPointsFactory.boxToGeoPoints(bBox[0].getLatitude(),
        		bBox[0].getLongitude(), bBox[1].getLatitude(), bBox[1].getLongitude());
        GeoPoint[] circle = GeoPointsFactory.getCircle(loc,50000);
//        for (int i = 0;i<360;i++){
//            android.util.Log.d("MyJamLocator","Circle distance "+ String.valueOf(i) +" (geoid) :"+String.valueOf(
//            		loc.distanceTo(new GeoLocation((double) (circle[i].getLatitudeE6()*1.0/1E6),
//            				(double)(circle[i].getLongitudeE6()*1.0/1E6),""))));
//            android.util.Log.d("MyJamLocator","Circle distance "+ String.valueOf(i) +" (sphere) :"+String.valueOf(
//            		loc.distanceGCTo(new GeoLocation((double) (circle[i].getLatitudeE6()*1.0/1E6),
//            				(double)(circle[i].getLongitudeE6()*1.0/1E6),""))));
//        }
        
        List<long[]> ranges;
		Set<HilbertQuad> quadsSet;
        long startnow = android.os.SystemClock.uptimeMillis();
        ranges = kf.getKeysRanges(loc, 100000);
        //quadsList = hc.getBound(loc, 100000,quadsList);
        long endnow = android.os.SystemClock.uptimeMillis();
        android.util.Log.i("MyJamLocator", "Excution time: "+(endnow-startnow)+" ms");
        //List<HilbertQuad> solution = new LinkedList<HilbertQuad>();
//        List<HilbertQuad> fineList = hc.expandQuad(boundList,new LinkedList<HilbertQuad>(),
//        		boundList.get(0).getLevel(),-1,true,boundList.get(0).getLevel()+3,false);
        //List<HilbertQuad> fineList = hc.expandQuads(boundList);
        quadsSet = kf.getCoveringSet();
        Iterator<long[]> rangesIt = ranges.iterator();
        while (rangesIt.hasNext()){
        	long[] range = rangesIt.next();
        	long startArea=KeysFinder.getAreaId(range[0]);
        	long endArea=KeysFinder.getAreaId(range[1]);
            android.util.Log.d("MyJamLocator", String.valueOf(startArea));
            android.util.Log.d("MyJamLocator", String.valueOf(endArea));
        }
        android.util.Log.d("MyJamLocator", String.valueOf(KeysFinder.getNumKeys(quadsSet)));
        android.util.Log.d("MyJamLocator", String.valueOf(quadsSet.size()));
        Iterator<long[]> it = ranges.iterator();
        while (it.hasNext()){
        	long[] value = it.next();
            android.util.Log.d("MyJamLocator", String.valueOf(value[0])+" - "+String.valueOf(value[1]));
        }
        int deltaLat = Math.abs(boundingBox[1].getLatitudeE6() - boundingBox[2].getLatitudeE6());
        int deltaLon = Math.abs(boundingBox[0].getLongitudeE6() - boundingBox[1].getLongitudeE6());
        if (deltaLon>180*1E6)
        	deltaLon = (int)(360*1E6) - deltaLon;
        mapContr.zoomToSpan(deltaLat,deltaLon);
        mapContr.animateTo(point);
        OverlayItem overlayitem = new OverlayItem(point, "", "");
        itemizedoverlay.addOverlay(overlayitem);
        itemizedoverlay.addFigure(boundingBox);
        itemizedoverlay.addFigure(circle);
        HilbertQuad boundHq;
        Iterator<HilbertQuad> itf = quadsSet.iterator();
        while (itf.hasNext()){
        	boundHq = itf.next();
        	itemizedoverlay.addFigure(GeoPointsFactory.boxToGeoPoints(boundHq.getFloorLat(),
        			boundHq.getFloorLon(),boundHq.getCeilLat(),boundHq.getCeilLon()));
        	/*android.util.Log.d("MyJamLocator", "Code hq1(binary) = "+
       			Long.toString(boundHq.getKeysRange()[0])+" "+
            		Long.toString(boundHq.getKeysRange()[1]));
            //*/
        }
        mapOverlays.add(itemizedoverlay);
        
    }
    
    @Override
    protected boolean isRouteDisplayed() {
        return false;
    }
}