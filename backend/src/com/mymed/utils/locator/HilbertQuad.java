package com.mymed.utils.locator;


import java.util.EnumMap;
import java.util.HashMap;
import java.util.Map;

/**
 * 
 * @author iacopo
 *
 */
public class HilbertQuad {
	protected enum QuadType{A,D,U,C};
	private long index;
	private short level;
	private double floorLat;
	private double ceilLat;
	private double floorLon;
	private double ceilLon;
	private QuadType typeQuad;
	
	protected static class SubQuad {
		public short pos;
		public QuadType type;
		
		protected SubQuad(short pos,QuadType type){
			this.pos = pos;
			this.type = type;
		}
	}
	/*		Quadrants
	 * 		-------------------------
	 * 		|			|			|
 	 *		|	  2		|	  3		| 	 		
	 *		|	Third	|	Fourth	|
	 * 		-------------------------
	 * 		|			|			|
 	 *		|	  0		|	  1		| 	 		
	 *		|	First	|	Second	|
	 * 		-------------------------
	 */
	protected static enum Quad{First,Second,Third,Fourth}
	public static Map<QuadType, Map<Quad, SubQuad>> tableEnc =
	    new EnumMap<QuadType, Map<Quad, SubQuad>>(QuadType.class);
	static {
			Map<Quad, SubQuad> map = new EnumMap<Quad, SubQuad>(Quad.class);
			map.put(Quad.First, new SubQuad((short) 0,QuadType.D));
			map.put(Quad.Second, new SubQuad((short) 3,QuadType.C));
			map.put(Quad.Third, new SubQuad((short) 1,QuadType.A));
			map.put(Quad.Fourth, new SubQuad((short) 2,QuadType.A));
	        tableEnc.put(QuadType.A, map);
	        map = new EnumMap<Quad, SubQuad>(Quad.class);
			map.put(Quad.First, new SubQuad((short) 0,QuadType.A));
			map.put(Quad.Second, new SubQuad((short) 1,QuadType.D));
			map.put(Quad.Third, new SubQuad((short) 3,QuadType.U));
			map.put(Quad.Fourth, new SubQuad((short) 2,QuadType.D));
	        tableEnc.put(QuadType.D, map);
	        map = new EnumMap<Quad, SubQuad>(Quad.class);
			map.put(Quad.First, new SubQuad((short) 2,QuadType.U));
			map.put(Quad.Second, new SubQuad((short) 1,QuadType.U));
			map.put(Quad.Third, new SubQuad((short) 3,QuadType.D));
			map.put(Quad.Fourth, new SubQuad((short) 0,QuadType.C));
			tableEnc.put(QuadType.U, map);
			map = new EnumMap<Quad, SubQuad>(Quad.class);
			map.put(Quad.First, new SubQuad((short) 2,QuadType.C));
			map.put(Quad.Second, new SubQuad((short) 3,QuadType.A));
			map.put(Quad.Third, new SubQuad((short) 1,QuadType.C));
			map.put(Quad.Fourth, new SubQuad((short) 0,QuadType.U));
			tableEnc.put(QuadType.C, map);
	}
	protected static Map<QuadType, Map<Short, SubQuad>> tableDec =
	    new EnumMap<QuadType, Map<Short, SubQuad>>(QuadType.class);
	static {
			Map<Short, SubQuad> mapDec = new HashMap<Short, SubQuad>();
			mapDec.put((short) 0, new SubQuad((short) Quad.First.ordinal(),QuadType.D));
			mapDec.put((short) 3, new SubQuad((short) Quad.Second.ordinal(),QuadType.C));
			mapDec.put((short) 1, new SubQuad((short) Quad.Third.ordinal(),QuadType.A));
			mapDec.put((short) 2, new SubQuad((short) Quad.Fourth.ordinal(),QuadType.A));
	        tableDec.put(QuadType.A, mapDec);
	        mapDec = new HashMap<Short, SubQuad>();
			mapDec.put((short) 0, new SubQuad((short) Quad.First.ordinal(),QuadType.A));
			mapDec.put((short) 1, new SubQuad((short) Quad.Second.ordinal(),QuadType.D));
			mapDec.put((short) 3, new SubQuad((short) Quad.Third.ordinal(),QuadType.U));
			mapDec.put((short) 2, new SubQuad((short) Quad.Fourth.ordinal(),QuadType.D));
	        tableDec.put(QuadType.D, mapDec);
	        mapDec = new HashMap<Short, SubQuad>();
			mapDec.put((short) 2, new SubQuad((short) Quad.First.ordinal(),QuadType.U));
			mapDec.put((short) 1, new SubQuad((short) Quad.Second.ordinal(),QuadType.U));
			mapDec.put((short) 3, new SubQuad((short) Quad.Third.ordinal(),QuadType.D));
			mapDec.put((short) 0, new SubQuad((short) Quad.Fourth.ordinal(),QuadType.C));
			tableDec.put(QuadType.U, mapDec);
			mapDec = new HashMap<Short, SubQuad>();
			mapDec.put((short) 2, new SubQuad((short) Quad.First.ordinal(),QuadType.C));
			mapDec.put((short) 3, new SubQuad((short) Quad.Second.ordinal(),QuadType.A));
			mapDec.put((short) 1, new SubQuad((short) Quad.Third.ordinal(),QuadType.C));
			mapDec.put((short) 0, new SubQuad((short) Quad.Fourth.ordinal(),QuadType.U));
			tableDec.put(QuadType.C, mapDec);
	}

	protected static double[] latitudeRange = new double[]{Math.toDegrees(Location.MIN_LAT),
		Math.toDegrees(Location.MAX_LAT)};
	protected static double[] longitudeRange = new double[]{Math.toDegrees(Location.MIN_LON),
		Math.toDegrees(Location.MAX_LON)};
	public static final short maxLevel=26; // There are maxLevel levels in the range (0..maxLevel-1)
	public static final int numBits=maxLevel*2-1;
	
	protected HilbertQuad(long index,short level,double fLat,double fLon,double cLat,double cLon,
			QuadType typeQuad){
		this.index = index;
		this.level=level;
		this.floorLon=fLon;
		this.ceilLon=cLon;
		this.floorLat=fLat;
		this.ceilLat=cLat;
		this.typeQuad = typeQuad;
	}
	
	protected void setIndex(long index) {
		this.index = index;
	}
	/**
	 * Returns the HilbertQuad that contains the location.
	 * @param loc Location
	 * @param level	Level of the HilbertQuad
	 * @return
	 */
	static protected HilbertQuad encode(Location loc,int level) throws IllegalArgumentException{
		if (level > maxLevel)
			throw new IllegalArgumentException("level exceeds the maximum level.");
		HilbertQuad quad= new HilbertQuad(0L,(short) 0,latitudeRange[0],longitudeRange[0],
				latitudeRange[1],longitudeRange[1],QuadType.A);
		getQuad((short) 0,loc.getLatitude(),loc.getLongitude(),quad,level);		
		return quad;
	}
	
	static protected HilbertQuad decode(long key) throws IllegalArgumentException{
		double lonMid;
		double latMid;
		short tabDecKey;
		
		if (key<0 || key>(long) (Math.pow(2, numBits)-1))
			throw new IllegalArgumentException("key is out of bound");
		long bitMask = (long) Math.pow(2, numBits-1);
		HilbertQuad quad= new HilbertQuad(key,maxLevel,latitudeRange[0],longitudeRange[0],
				latitudeRange[1],longitudeRange[1],QuadType.A);
		//The first bit chooses the initial longitude range. Either (-180,0) or (0,180) 
		lonMid = (quad.getCeilLon()+quad.getFloorLon())/2;
		if ((bitMask & key)==bitMask)
			quad.floorLon = lonMid;
		else
			quad.ceilLon = lonMid;
		for(int ind=0;ind<maxLevel-1;ind++){
			lonMid = (quad.getCeilLon()+quad.getFloorLon())/2;
			latMid = (quad.getCeilLat()+quad.getFloorLat())/2;
			tabDecKey=0;
			bitMask >>>= 1;
			if ((bitMask & key)==bitMask)
				tabDecKey+=2;
			bitMask >>>= 1;
			if ((bitMask & key)==bitMask)
				tabDecKey++;
			SubQuad currSubQuad =  tableDec.get(quad.getTypeQuad()).get(tabDecKey);
			quad.setTypeQuad(currSubQuad.type);
			switch (currSubQuad.pos){
			case (0): //First quadrant
				quad.ceilLon=lonMid;
				quad.ceilLat=latMid;
				break; 
			case (1): //Second quadrant
				quad.floorLon=lonMid;
				quad.ceilLat=latMid;
				break;
			case (2): //Second quadrant
				quad.ceilLon=lonMid;
				quad.floorLat=latMid;
				break; 
			case (3): //Second quadrant
				quad.floorLon=lonMid;
				quad.floorLat=latMid;
				break; 
			}
		}
		return quad;
	}
	/*
	 * Returns the quad of level @param i that contains the point @param lat, @param lon.
	 */
	//TODO Could be better iterative.
	private static void getQuad(short i, double lat, double lon, HilbertQuad quad,
			int level) {
		short quadPos=0;
		double lonMid,latMid;
		
		if (i<level){
			if (i==0){
				lonMid = (quad.getCeilLon()+quad.getFloorLon())/2;
				if (lon>lonMid){
					quad.setIndex(1);
					quad.setFloorLon(lonMid);
				}else
					quad.setCeilLon(lonMid);				
			}else{
				quad.setIndex(quad.getIndex() << 2);
				lonMid = (quad.getCeilLon()+quad.getFloorLon())/2;
				latMid = (quad.getCeilLat()+quad.getFloorLat())/2;
				if (lon>lonMid){
					quadPos++;
					quad.setFloorLon(lonMid);
				}else
					quad.setCeilLon(lonMid);
				if (lat>latMid)
				{
					quadPos+=2;
					quad.setFloorLat(latMid);
				}else
					quad.setCeilLat(latMid);
				SubQuad s = tableEnc.get(quad.getTypeQuad()).get(Quad.values()[quadPos]);
				quad.setIndex(quad.getIndex()+s.pos);
				quad.setTypeQuad(s.type);	
			}
			quad.setLevel((short) (i+1));
			getQuad((short) (i+1),lat,lon,quad,level);
		}
	}
	
	/*
	 * Get the sub-quad of @param quad at the position @param pos. 
	 * 	0 is the bottom-left sub-quad
	 * 	1 is the bottom-right sub-quad
	 * 	2 is the top-left sub-quad
	 * 	3 is the top-right sub-quad
	 */
	protected HilbertQuad getQuad(short pos,boolean down){ 
		//TODO Implement the code for down == false, if necessary.
		double floorLat,floorLon,ceilLat,ceilLon;
		HilbertQuad hq;
		if (this.getLevel()>=HilbertQuad.maxLevel){
			hq=null;
		}else if (down){
			double lonMid,latMid;
			lonMid = (this.getCeilLon()+this.getFloorLon())/2;
			latMid = (this.getCeilLat()+this.getFloorLat())/2;
			
			switch (pos) {
				case 0:	floorLat = this.getFloorLat();
						ceilLat = latMid;
						floorLon = this.getFloorLon();
						ceilLon = lonMid;
						break;
				case 1:	floorLat = this.getFloorLat();
						ceilLat = latMid;
						floorLon = lonMid;
						ceilLon = this.getCeilLon();
						break;
				case 2:	floorLat = latMid;
						ceilLat = this.getCeilLat();
						floorLon = this.getFloorLon();
						ceilLon = lonMid;
				break;
				case 3:	floorLat = latMid;
						ceilLat = this.getCeilLat();
						floorLon = lonMid;
						ceilLon = this.getCeilLon();
				break;
				//I would not happen...
				default: throw new IllegalArgumentException("Wrong position (not in the range (0..3))");
			}
			SubQuad s = tableEnc.get(this.getTypeQuad()).get(Quad.values()[pos]);
			hq = new HilbertQuad((this.getIndex()<<2)+s.pos,(short) (this.getLevel()+1),floorLat,floorLon,ceilLat,ceilLon,s.type);
		}else{
			hq=null;
		}
		return hq;
	}
	/**
	 * Returns the keys range associated to this HilbertQuad.	
	 * @return
	 */
	protected long[] getKeysRange(){
		long[] keysInt=new long[2];
		
		int remBits = (this.getLevel()-1) * 2;
		remBits++; //Because one additional bit is used.
		remBits=numBits-remBits;
		keysInt[0] = this.getIndex() << remBits;
		keysInt[1] = keysInt[0] + (long)(Math.pow(2, remBits)-1);
		return keysInt;
	}
	
	protected long getIndex() {
		return index;
	}
	protected void setLevel(short level) {
		this.level = level;
	}
	protected short getLevel() {
		return level;
	}
	protected void setTypeQuad(QuadType typeQuad) {
		this.typeQuad = typeQuad;
	}
	protected QuadType getTypeQuad() {
		return typeQuad;
	}
	protected void setFloorLat(double floorLat) {
		this.floorLat = floorLat;
	}
	protected double getFloorLat() {
		return floorLat;
	}
	protected void setCeilLat(double ceilLat) {
		this.ceilLat = ceilLat;
	}
	protected double getCeilLat() {
		return ceilLat;
	}
	protected void setFloorLon(double floorLon) {
		this.floorLon = floorLon;
	}
	protected double getFloorLon() {
		return floorLon;
	}
	protected void setCeilLon(double ceilLon) {
		this.ceilLon = ceilLon;
	}
	protected double getCeilLon() {
		return ceilLon;
	}	
	protected double getHeigth(){
		Location bottomLeftCorner,topLeftCorner;
		
		try {
			bottomLeftCorner = new Location(this.floorLat,this.floorLon);
			topLeftCorner = new Location(this.ceilLat,this.floorLon);
		} catch (GeoLocationOutOfBoundException e) {
			// Never happens.
			e.printStackTrace();
			return 0d;
		}
		return bottomLeftCorner.distanceGCTo(topLeftCorner);
	}
	protected double getTopWidth(){
		Location topRightCorner,topLeftCorner;
		
		try{
			topRightCorner = new Location(this.ceilLat,this.ceilLon);
			topLeftCorner = new Location(this.ceilLat,this.floorLon);
		} catch (GeoLocationOutOfBoundException e) {
			// Never happens.
			e.printStackTrace();
			return 0d;
		}
		return topRightCorner.distanceGCTo(topLeftCorner);
	}
	protected double getBottomWidth(){
		Location bottomRightCorner,bottomLeftCorner;
		
		try{
			bottomRightCorner = new Location(this.floorLat,this.ceilLon);
			bottomLeftCorner = new Location(this.floorLat,this.floorLon);			
		} catch (GeoLocationOutOfBoundException e) {
			// Never happens.
			e.printStackTrace();
			//android.util.Log.e("HilbertQuad", e.getMessage());
			return 0d;
		}
		return bottomRightCorner.distanceGCTo(bottomLeftCorner);		
	}	
	
	@Override
	public boolean equals(Object obj){
		HilbertQuad hq = null;
		if (obj == null || obj.getClass() != this.getClass())
			return false;
		else {
			hq = (HilbertQuad) obj;
			if (this.index == hq.getIndex() && this.level == hq.getLevel())
				return true;
			else
				return false;	
		}
	}
	
	@Override
	public int hashCode(){
	     // Start with a non-zero constant.
	     int result = 17;
	     
	     result = 31 * result + this.level;
	     result = 31 * result + (int) (this.index ^ (this.index >>> 32));
	     return result;
	}
	
}
