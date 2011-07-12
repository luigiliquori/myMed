package com.mymed.myjam.locator;

import java.util.Iterator;
import java.util.List;

public class LocatorTester {
	public static void main(String[] arg0){
		Location loc;
		KeysFinder kf;
	    try{
	        loc = new Location(45.15,7.70);	
	    }catch(Exception e){
	    	e.printStackTrace();
	    	loc=null;
	    }
	    kf = new KeysFinder();
	    HilbertQuad hq = HilbertQuad.encode(loc, HilbertQuad.maxLevel);
	    long key = KeysFinder.getAreaId(hq.getIndex());
	    long sColumnName = hq.getIndex();
	    HilbertQuad hq1 = HilbertQuad.decode(hq.getIndex());
		System.out.println("Key: (bin) "+Long.toBinaryString(key)+" (dec) "+Long.toString(key));
	    System.out.println("Cname: (bin) "+Long.toBinaryString(sColumnName)+" (dec) "+Long.toString(sColumnName));
	    System.out.println("MaxLat: "+String.valueOf(hq1.getCeilLat())+" MinLat: "+ String.valueOf(hq1.getFloorLat())+
	    		" MaxLon: "+String.valueOf(hq1.getCeilLon())+" MinLon: "+ String.valueOf(hq1.getFloorLon()));
	    		
	    List<long[]> ranges;
		//Set<HilbertQuad> quadsSet;
	    long startnow = System.currentTimeMillis();
	    ranges = kf.getKeysRanges(loc, 100000);
	    //quadsList = hc.getBound(loc, 100000,quadsList);
	    long endnow = System.currentTimeMillis();
	    System.out.println("Execution time:"+ String.valueOf(endnow-startnow));
	    Iterator<long[]> rangesIt = ranges.iterator();
	    while (rangesIt.hasNext()){
	    	long[] range = rangesIt.next();
	    	long startArea=KeysFinder.getAreaId(range[0]);
	    	long endArea=KeysFinder.getAreaId(range[1]);
	    	System.out.println("MyJamLocator: (area) "+String.valueOf(startArea)+" (loc) "+range[0]);
	        System.out.println("MyJamLocator: (area) "+String.valueOf(endArea)+" (loc) "+range[1]);
	    }
	}
}
