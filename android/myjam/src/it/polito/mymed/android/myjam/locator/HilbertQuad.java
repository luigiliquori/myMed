package it.polito.mymed.android.myjam.locator;


import java.util.EnumMap;
import java.util.HashMap;
import java.util.Map;

public class HilbertQuad {
	protected enum QuadType{A,D,U,C};
	private long index;
	private short level;
	private double floorLat;
	private double ceilLat;
	private double floorLon;
	private double ceilLon;
	private QuadType typeQuad;
	
	public static class SubQuad {
		public int i;
		public QuadType type;
		
		public SubQuad(int i,QuadType type){
			this.i = i;
			this.type = type;
		}
	}
	
	protected enum Quad{First,Second,Third,Fourth}
	public static Map<QuadType, Map<Quad, SubQuad>> tableEnc =
	    new EnumMap<QuadType, Map<Quad, SubQuad>>(QuadType.class);
	static {
			Map<Quad, SubQuad> map = new EnumMap<Quad, SubQuad>(Quad.class);
			map.put(Quad.First, new SubQuad(0,QuadType.D));
			map.put(Quad.Second, new SubQuad(3,QuadType.C));
			map.put(Quad.Third, new SubQuad(1,QuadType.A));
			map.put(Quad.Fourth, new SubQuad(2,QuadType.A));
	        tableEnc.put(QuadType.A, map);
	        map = new EnumMap<Quad, SubQuad>(Quad.class);
			map.put(Quad.First, new SubQuad(0,QuadType.A));
			map.put(Quad.Second, new SubQuad(1,QuadType.D));
			map.put(Quad.Third, new SubQuad(3,QuadType.U));
			map.put(Quad.Fourth, new SubQuad(2,QuadType.D));
	        tableEnc.put(QuadType.D, map);
	        map = new EnumMap<Quad, SubQuad>(Quad.class);
			map.put(Quad.First, new SubQuad(2,QuadType.U));
			map.put(Quad.Second, new SubQuad(1,QuadType.U));
			map.put(Quad.Third, new SubQuad(3,QuadType.D));
			map.put(Quad.Fourth, new SubQuad(0,QuadType.C));
			tableEnc.put(QuadType.U, map);
			map = new EnumMap<Quad, SubQuad>(Quad.class);
			map.put(Quad.First, new SubQuad(2,QuadType.C));
			map.put(Quad.Second, new SubQuad(3,QuadType.A));
			map.put(Quad.Third, new SubQuad(1,QuadType.C));
			map.put(Quad.Fourth, new SubQuad(0,QuadType.U));
			tableEnc.put(QuadType.C, map);
	}
	public static Map<QuadType, Map<Integer, SubQuad>> tableDec =
	    new EnumMap<QuadType, Map<Integer, SubQuad>>(QuadType.class);
	static {
			Map<Integer, SubQuad> mapDec = new HashMap<Integer, SubQuad>();
			mapDec.put(0, new SubQuad(Quad.First.ordinal(),QuadType.D));
			mapDec.put(3, new SubQuad(Quad.Second.ordinal(),QuadType.C));
			mapDec.put(1, new SubQuad(Quad.Third.ordinal(),QuadType.A));
			mapDec.put(2, new SubQuad(Quad.Fourth.ordinal(),QuadType.A));
	        tableDec.put(QuadType.A, mapDec);
	        mapDec = new HashMap<Integer, SubQuad>();
			mapDec.put(0, new SubQuad(Quad.First.ordinal(),QuadType.A));
			mapDec.put(1, new SubQuad(Quad.Second.ordinal(),QuadType.D));
			mapDec.put(3, new SubQuad(Quad.Third.ordinal(),QuadType.U));
			mapDec.put(2, new SubQuad(Quad.Fourth.ordinal(),QuadType.D));
	        tableDec.put(QuadType.D, mapDec);
	        mapDec = new HashMap<Integer, SubQuad>();
			mapDec.put(2, new SubQuad(Quad.First.ordinal(),QuadType.U));
			mapDec.put(1, new SubQuad(Quad.Second.ordinal(),QuadType.U));
			mapDec.put(3, new SubQuad(Quad.Third.ordinal(),QuadType.D));
			mapDec.put(0, new SubQuad(Quad.Fourth.ordinal(),QuadType.C));
			tableDec.put(QuadType.U, mapDec);
			mapDec = new HashMap<Integer, SubQuad>();
			mapDec.put(2, new SubQuad(Quad.First.ordinal(),QuadType.C));
			mapDec.put(3, new SubQuad(Quad.Second.ordinal(),QuadType.A));
			mapDec.put(1, new SubQuad(Quad.Third.ordinal(),QuadType.C));
			mapDec.put(0, new SubQuad(Quad.Fourth.ordinal(),QuadType.U));
			tableDec.put(QuadType.C, mapDec);
	}

	protected static double[] longitudeRange = new double[]{-180,180};
	protected static double[] latitudeRange = new double[]{-80,80};
	public static int numBits=45;
	public static short maxLevel=23;
	
	public HilbertQuad(long index,short level,double fLat,double fLon,double cLat,double cLon,
			QuadType typeQuad){
		this.index = index;
		this.level=level;
		this.floorLon=fLon;
		this.ceilLon=cLon;
		this.floorLat=fLat;
		this.ceilLat=cLat;
		this.typeQuad = typeQuad;
	}
	
	public void setIndex(long index) {
		this.index = index;
	}
	
	static public HilbertQuad encode(GeoLocation loc,int level){
		if (level > maxLevel)
			throw new IllegalArgumentException("level exceeds the maximum level.");
		HilbertQuad quad= new HilbertQuad(0L,(short) 0,latitudeRange[0],longitudeRange[0],
				latitudeRange[1],longitudeRange[1],QuadType.A);
		getQuad((short) 0,loc.getLatitude(),loc.getLongitude(),quad,level);		
		return quad;
	}
	/*
	 * Returns the quad of level @param i that contains the point @param lat, @param lon.
	 */
	//TODO Make it iterative may be better.
	private static void getQuad(short i, double lat, double lon, HilbertQuad quad,
			int level) {
		int quadPos=0;
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
				quad.setIndex(quad.getIndex()+s.i);
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
	public HilbertQuad getQuad(int pos,boolean down){ 
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
				default: throw new IllegalArgumentException("Wrong position (not in the range (0..3))");
			}
			SubQuad s = tableEnc.get(this.getTypeQuad()).get(Quad.values()[pos]);
			hq = new HilbertQuad((this.getIndex()<<2)+s.i,(short) (this.getLevel()+1),floorLat,floorLon,ceilLat,ceilLon,s.type);
		}else{
			hq=null;
		}
		return hq;
	}
		
	public long[] getKeysRange(){
		long[] keysInt=new long[2];
		
		int remBits = (this.getLevel()-1) * 2;
		remBits++; //Because one additional bit is used.
		remBits=numBits-remBits;
		keysInt[0] = this.getIndex() << remBits;
		keysInt[1] = keysInt[0] + (long)(Math.pow(2, remBits)-1);
		return keysInt;
	}
/*
 * Returns a unique identifier of the quad.
 */
	public String getKey(){
		return String.valueOf(this.getLevel())+" "+String.valueOf(this.getIndex());
	}
	
	
	public long getIndex() {
		return index;
	}
	public void setLevel(short level) {
		this.level = level;
	}
	public short getLevel() {
		return level;
	}
	public void setTypeQuad(QuadType typeQuad) {
		this.typeQuad = typeQuad;
	}
	public QuadType getTypeQuad() {
		return typeQuad;
	}
	public void setFloorLat(double floorLat) {
		this.floorLat = floorLat;
	}
	public double getFloorLat() {
		return floorLat;
	}
	public void setCeilLat(double ceilLat) {
		this.ceilLat = ceilLat;
	}
	public double getCeilLat() {
		return ceilLat;
	}
	public void setFloorLon(double floorLon) {
		this.floorLon = floorLon;
	}
	public double getFloorLon() {
		return floorLon;
	}
	public void setCeilLon(double ceilLon) {
		this.ceilLon = ceilLon;
	}
	public double getCeilLon() {
		return ceilLon;
	}	
	public double getHeigth(){
		GeoLocation bottomLeftCorner,topLeftCorner;
		
		bottomLeftCorner = new GeoLocation(this.floorLat,this.floorLon,"blc");
		topLeftCorner = new GeoLocation(this.ceilLat,this.floorLon,"tlc");
		return bottomLeftCorner.distanceGCTo(topLeftCorner);
	}
	public double getTopWidth(){
		GeoLocation topRightCorner,topLeftCorner;
		
		topRightCorner = new GeoLocation(this.ceilLat,this.ceilLon,"trc");
		topLeftCorner = new GeoLocation(this.ceilLat,this.floorLon,"tlc");
		return topRightCorner.distanceGCTo(topLeftCorner);
	}
	public double getBottomWidth(){
		GeoLocation bottomRightCorner,bottomLeftCorner;
		
		bottomRightCorner = new GeoLocation(this.floorLat,this.ceilLon,"brc");
		bottomLeftCorner = new GeoLocation(this.floorLat,this.floorLon,"blc");
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
