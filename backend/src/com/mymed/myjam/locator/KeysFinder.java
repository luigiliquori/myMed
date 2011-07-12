package com.mymed.myjam.locator;


import java.util.ArrayList;
import java.util.Collections;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Set;
import java.util.HashSet;
import java.util.SortedSet;
import java.util.TreeSet;

import com.mymed.myjam.locator.HilbertQuad.SubQuad;


public class KeysFinder {
	private double minLat,minLon,maxLat,maxLon; 	//Degrees
	private Set<HilbertQuad> coveringSet = null;

	private enum Position{internal,intersects,external};
	private static int maxDiameter=100000;
	private static int maxNumRanges=6;
	private static short maxDepth=10;
	private static short areaMaskLength=15;

	/**
	 * Initialize the keys finder creating the covering set.
	 */
	protected KeysFinder(){
		coveringSet=new HashSet<HilbertQuad>();
	}
	/**
	 * Returns the number of key ranges that cover the bounding box of the circular area with diameter range
	 * expressed in meter,the value can be less or equal of maxNumRanges. The ranges are put in the list ranges, 
	 * the total list of covering HilbertQuad is put in the list quads.
	 * @returns Returns the number of key ranges that cover the bounding box.
	 * @param loc Center of the search area.
	 * @param diameter Diameter of the search area
	 * @param ranges List of ranges of keys, must be empty.
	 * @param quads List of HilbertQuad of the covering set.
	 */
	protected List<long[]> getKeysRanges(Location loc,int diameter){		
		//List<HilbertQuad> boundList = getBound(loc,range);
		List<long[]> ranges = new ArrayList<long[]>(maxNumRanges);
		getBound(loc,diameter);
		expandQuads(ranges);
		return ranges;
	}

	/**
	 * Given a position index, returns the related areaId, used as key in the database
	 * @param index The index of the position.
	 * @return
	 */
	protected static long getAreaId(long index) throws IllegalArgumentException{
		long mask;
		int zeroBits;
		if (index<0 || index>(long) (Math.pow(2, HilbertQuad.numBits)-1))
			throw new IllegalArgumentException("key is out of bound");
		/*
		 * The mask composed by areaMaskLength '1', and the remaining bits (numBits-areaMaskLength) '0' 
		 * is created.
		 */
		zeroBits=HilbertQuad.numBits - areaMaskLength;
		mask =((long) Math.pow(2,(double) areaMaskLength)-1);
		mask = mask << zeroBits;
		/*
		 * index AND mask is returned.
		 */
		return ((index & mask)>>>zeroBits);
	}

	protected Set<HilbertQuad> getCoveringSet(){
		return this.coveringSet;
	}

	/*
	 * Returns up to four HilbertQuad, that cover the range specified starting from the point loc. It also sets the attributes minLat, 
	 * minLon, maxLat, maxLon, used by the method expandQuad.
	 */ 	
	private int getBound(Location loc,int diameter){//TODO set to private.
		int level;
		int numQuads=0;

		if (diameter<1 || diameter>maxDiameter)
			throw new IllegalArgumentException("Diameter "+String.valueOf(diameter)+" out of bound");
		//Evaluate the level of coding necessary to obtain a quad that possibly contains the area determined by range. 
		//Set the coordinates of the bounding box.
		Location[] corners = loc.boundingCoordinates(diameter/2);
		minLat=corners[0].getLatitude();
		minLon=corners[0].getLongitude();
		maxLat=corners[1].getLatitude();
		maxLon=corners[1].getLongitude();
		level = getLevel();
		corners = Location.getCorners(corners[0],corners[1]);
		coveringSet.clear();
		for (int i=0;i<4;i++){
			HilbertQuad hq = HilbertQuad.encode(corners[i],level);
			if (coveringSet.add(hq))
				numQuads++;
		}
		return numQuads;
	}

	/*
	 * Expands the HilbertQuads in boundList that cover the search area until no HilbertQuad is further expandable, 
	 * the max depth is reached or the max.  
	 */
	private int expandQuads(List<long[]> ranges){
		int level=-1;
		boolean expanded=true;


		/*
		 * INITIALIZATION
		 * The list endRanges, that contains the HilbertQuad located at the end of a range of keys is populated.
		 * The list expandable that contains the HilbertQuad that can be further expanded is populated
		 */
		List<Long> rangesExtremes = new ArrayList<Long>(maxNumRanges*2);
		OrderedHqList expandable = new OrderedHqList();
		HilbertQuad oldHq = null;
		/*
		 * coveringSet is inserted in a TreeSet, ordered by keys, so that it can be iterated to initialize ranges 
		 * Extremes.
		 */
		SortedSet<HilbertQuad> tmpCovSet = new TreeSet<HilbertQuad>(new HqComparator());
		tmpCovSet.addAll(coveringSet);
		Iterator<HilbertQuad> covSetIterator = tmpCovSet.iterator();
		rangesExtremes.add(tmpCovSet.first().getKeysRange()[0]);
		while (covSetIterator.hasNext()){
			HilbertQuad hq = covSetIterator.next();
			level = hq.getLevel();
			long index = hq.getIndex();
			if (oldHq!=null && index!=oldHq.getIndex()+1){
				rangesExtremes.add(hq.getKeysRange()[0]);
				rangesExtremes.add(oldHq.getKeysRange()[1]);
			}	
			oldHq = hq;
			//Metric of the current HilbertQuad is evaluated.
			short metric = getMetric(hq);
			//short metric = getMetric1(hq);
			expandable.addOrdered(new HqWrapper(metric,hq));
		}
		rangesExtremes.add(oldHq.getKeysRange()[1]);		
		/*
		 * EXPANSION
		 * The algorithm are expanded in the order of the expandable list, if this doesn't exceed the max. number of ranges.
		 */
		short depth = 0;
		List<HilbertQuad> nextExpandable = null;
		while(expanded && level<HilbertQuad.maxLevel && depth<maxDepth){
			expanded=false;
			//After the first iteration in which nextExpandable is null, expandable list is populated.
			if (nextExpandable==null){
				nextExpandable = new LinkedList<HilbertQuad>();
			}else{
				Iterator<HilbertQuad> expIt = nextExpandable.iterator();
				while (expIt.hasNext()){
					HilbertQuad currHq = expIt.next();
					short metric = getMetric(currHq);
					//short metric = getMetric1(currHq);
					expandable.addOrdered(new HqWrapper(metric,currHq));
				}
				nextExpandable.clear();
			}
			Iterator<HqWrapper> expIterator = expandable.iterator();
			while (expIterator.hasNext()){
				HqWrapper indexQuad = expIterator.next();
				HilbertQuad hq = indexQuad.getQuad();
				int addedSubQuads = expandQuad(hq,rangesExtremes,nextExpandable);
				if (addedSubQuads!=-1)
					expanded = true;
			}
			level++;
			depth++;
			expandable.clear();
		}	
		return getNumRanges(ranges,rangesExtremes);		
	}	
	/*
	 * When returns -1 means that the quad cannot be expanded, because it would exceed the maxNumRanges. 
	 */
	private int expandQuad(HilbertQuad hq,List<Long> extremes,List<HilbertQuad> nextExpandable){
		boolean start=false,
		end=false;
		int counter=0;
		HilbertQuad subHq;
		//Discovers by looking endRanges list, if the current HilbertQuad is the first or the last of a certain range 
		//of keys.
		long[] hqKeys = hq.getKeysRange();
		if (extremes.contains(hqKeys[0]))
			start = true;
		if (extremes.contains(hqKeys[1]))
			end = true;
		//Removes the current HilbertQuad from the list. End try to expand it. If this is not possible is reinserted in 
		//the same position.		
		List<HilbertQuad> insertedList = new LinkedList<HilbertQuad>();
		List<HilbertQuad> tmpExpList = new LinkedList<HilbertQuad>();
		List<Long>	tmpExtremes = new LinkedList<Long>();
		boolean prevIns = !start;
		short addedRanges = 0;
		short numRanges =(short) (extremes.size() / 2);
		for (short i=0;i<4;i++){
			//The sub-quads are visited ordered by key range.
			SubQuad s =HilbertQuad.tableDec.get(hq.getTypeQuad()).get(i);
			subHq = hq.getQuad(s.pos,true);
			Position subQuadPosition = checkPosition(subHq);
			long[] subRange = subHq.getKeysRange();
			//If the sub-quad is not external to the bounding box is inserted in the list.
			if (subQuadPosition != Position.external){
				insertedList.add(subHq);
				if (!prevIns)
					tmpExtremes.add(subRange[0]);
				prevIns = true;
				counter++;
				if (i==3 && end){
					if (numRanges+addedRanges-1>=maxNumRanges)
						return -1;
					tmpExtremes.add(subRange[1]);	
				}
				if (subQuadPosition == Position.intersects)
					tmpExpList.add(subHq);
			}else{
				if (i==3 && !end){
					tmpExtremes.add(subRange[1]+1);
				}
				if (prevIns){
					if ((numRanges+addedRanges>=maxNumRanges) && !(end)){
						return -1;
					}else if((numRanges+addedRanges-1>=maxNumRanges) && end){
						return -1;
					}
					prevIns = false;
					tmpExtremes.add(subRange[0]-1);
					addedRanges++;
				}
			}
		}
		//The sorted set extremes and the HilbertQuad's set are updated.
		extremes.remove(hqKeys[0]);
		extremes.remove(hqKeys[1]);
		extremes.addAll(tmpExtremes);
		//Updates the list of HilbertQuad expandable on the next iteration.
		nextExpandable.addAll(tmpExpList);
		//Updates the set of HilbertQuad covering the bounding box
		coveringSet.remove(hq);
		coveringSet.addAll(insertedList);
		return counter;
	}

	/*
	 * Returns the level on depth to use to get the boundary.
	 */
	private short getLevel(){
		short level = 1;
		double	deltaLat = (HilbertQuad.latitudeRange[1]-HilbertQuad.latitudeRange[0]), 
		deltaLon = (HilbertQuad.longitudeRange[1]-HilbertQuad.longitudeRange[0])/2,
		deltaLatBound = this.maxLat - this.minLat,
		deltaLonBound;

		if (this.minLon<=this.maxLon)
			deltaLonBound = this.maxLon - this.minLon;	
		else
			/*
			 * If the maxLon is less then the minLoss, the bounding box is across the 180 degrees meridian
			 * for sure the minLon is in the longitude interval 0,180, and maxLon is inside -180,0 ,because
			 * we have never bounding box with deltaLon > 180. Then is sufficient add 360
			 */
			deltaLonBound = this.maxLon - this.minLon + 360.0;
		for (level=1;level<HilbertQuad.maxLevel;level++){
			//If the HilbertQuads at this level are no more sufficient to cover the bounding box.
			if (deltaLon < deltaLonBound || deltaLat < deltaLatBound) 
				return (short) (level-1);
			deltaLon = deltaLon/2;
			deltaLat = deltaLat/2;
		}
		return level;
	}

	/*
	 * Returns a metric between 0 and 1000 indicating how much of the area of the HilbertQuad hq lies 
	 * on the bounding box region.  
	 * This function starts from the assumption that the HilbertQuad hq intersects the bounding box.
	 */
	private short getMetric(HilbertQuad hq){
		double deltaLon,deltaLat;

		if (hq!=null){
			if((hq.getFloorLon()<=this.minLon && hq.getCeilLon()>this.minLon) || 
					(hq.getFloorLon()<this.minLon && hq.getCeilLon()>=this.minLon))
				deltaLon = hq.getCeilLon()-this.minLon;
			else if((hq.getFloorLon()<=this.maxLon && hq.getCeilLon()>this.maxLon) || 
					(hq.getFloorLon()<this.maxLon && hq.getCeilLon()>=this.maxLon))
				deltaLon = this.maxLon-hq.getFloorLon();
			else
				deltaLon = hq.getCeilLon()-hq.getFloorLon();
			if((hq.getFloorLat()<=this.minLat && hq.getCeilLat()>this.minLat) || 
					(hq.getFloorLat()<this.minLat && hq.getCeilLat()>=this.minLat))
				deltaLat = hq.getCeilLat()-this.minLat;
			else if((hq.getFloorLat()<=this.maxLat && hq.getCeilLat()>this.maxLat) || 
					(hq.getFloorLat()<this.maxLat && hq.getCeilLat()>=this.maxLat))
				deltaLat = this.maxLat-hq.getFloorLat();
			else
				deltaLat = hq.getCeilLat()-hq.getFloorLat();

			double maxArea = (hq.getCeilLon()-hq.getFloorLon()) * (hq.getCeilLat() - hq.getFloorLat());
			short metric =  (short) Math.round(((deltaLon * deltaLat)/maxArea)*1000);
			return metric;	

		}else{
			throw new IllegalArgumentException("null argument.");
		}
	}

	/*
	 * Returns the total number of keys, belonging to the list of HilbertQuad hilbQuadlist.
	 */
	public static long getNumKeys(Set<HilbertQuad> covSet){
		long num=0;

		if (covSet!=null){
			Iterator<HilbertQuad> it = covSet.iterator();
			while (it.hasNext()){
				long[] range=it.next().getKeysRange();
				num += range[1]-range[0]+1;
			}
			return num;
		}else{
			throw new IllegalArgumentException("null argument.");
		}
	}

	/*
	 * Returns the number of ranges of keys inside the ordered list of HilbertQuad hilbQuadList, and put
	 * them inside the list ranges.
	 */
	private static short getNumRanges(List<long[]> ranges,List<Long> extremes){
		short 	numRanges=0;
		long[]	currentRange=null;

		try{
			Collections.sort(extremes);
			Iterator<Long> itExt = extremes.iterator();
			while (itExt.hasNext()){
				currentRange = new long[2];
				currentRange[0] = itExt.next();
				currentRange[1] = itExt.next();
				ranges.add(currentRange);
				numRanges++;
			}
			return numRanges;
		}catch(Exception e){
			throw new IllegalArgumentException("null argument.");
		}
	}

	/*
	 * Return the position of the HilbertQuad @param hq with respect to the bounding box.
	 * Position may be: external, internal or intersects. Position is referred to the HilbertQuad with respect
	 * to the bounding box. 
	 * If the HilbertQuad contains the bounding box the results will be intersects, if the bounding box contains 
	 * the HilbertQuad the results will be internal.
	 */
	private Position checkPosition(HilbertQuad hq){
		double 	offset 	 = 0.0,
		offsetHqLon = 0.0;

		if (minLon>maxLon){// across 180 degrees meridian (the difference between minLon and maxLon must be < 180)
			offset = 360.0;
			if (hq.getCeilLon()<0)
				offsetHqLon = 360.0;
		}
		if ((hq.getFloorLat()>=minLat && hq.getCeilLat()<=maxLat) && 
				(hq.getFloorLon()+offsetHqLon>=minLon && hq.getCeilLon()+offsetHqLon<=maxLon+offset))
			return Position.internal;
		if ((hq.getCeilLat()<minLat || hq.getFloorLat()>maxLat) || 
				(hq.getFloorLon()+offsetHqLon>maxLon+offset || hq.getCeilLon()+offsetHqLon<minLon))
			return Position.external;

		return Position.intersects;
	}	
}
