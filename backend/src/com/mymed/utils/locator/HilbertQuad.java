package com.mymed.utils.locator;

import java.util.EnumMap;
import java.util.HashMap;
import java.util.Map;

import ch.qos.logback.classic.Logger;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;
import com.mymed.utils.MLogger;

/**
 * 
 * Class representing a bucket belonging to the hierarchical grid shaped
 * structure which covers the indexed space.
 * 
 * @author iacopo
 * 
 */
public class HilbertQuad {
  // There are MAX_LEVEL levels in the range (0..maxLevel-1)
  public static final short MAX_LEVEL = 26;
  public static final int NUM_BITS = MAX_LEVEL * 2 - 1;

  protected enum QuadType {
    A,
    D,
    U,
    C
  };

  /**
   * Quadrants ------------------------- | | | | 2 | 3 | | Third | Fourth |
   * ------------------------- | | | | 0 | 1 | | First | Second |
   * -------------------------
   */
  protected static enum Quad {
    First,
    Second,
    Third,
    Fourth
  }

  protected static class SubQuad {
    public short pos;
    public QuadType type;

    protected SubQuad(final short pos, final QuadType type) {
      this.pos = pos;
      this.type = type;
    }
  }

  static final double[] LATITUDE_RANGE = new double[] {Location.MIN_LAT, Location.MAX_LAT};
  static final double[] LONGITUDE_RANGE = new double[] {Location.MIN_LON, Location.MAX_LON};

  private static final Logger LOGGER = MLogger.getLogger();

  private long index;
  private short level;
  private double floorLat;
  private double ceilLat;
  private double floorLon;
  private double ceilLon;
  private QuadType typeQuad;

  protected static final Map<QuadType, Map<Quad, SubQuad>> TABLE_ENC = new EnumMap<QuadType, Map<Quad, SubQuad>>(
      QuadType.class);
  static {
    Map<Quad, SubQuad> map = new EnumMap<Quad, SubQuad>(Quad.class);
    map.put(Quad.First, new SubQuad((short) 0, QuadType.D));
    map.put(Quad.Second, new SubQuad((short) 3, QuadType.C));
    map.put(Quad.Third, new SubQuad((short) 1, QuadType.A));
    map.put(Quad.Fourth, new SubQuad((short) 2, QuadType.A));
    TABLE_ENC.put(QuadType.A, map);
    map = new EnumMap<Quad, SubQuad>(Quad.class);
    map.put(Quad.First, new SubQuad((short) 0, QuadType.A));
    map.put(Quad.Second, new SubQuad((short) 1, QuadType.D));
    map.put(Quad.Third, new SubQuad((short) 3, QuadType.U));
    map.put(Quad.Fourth, new SubQuad((short) 2, QuadType.D));
    TABLE_ENC.put(QuadType.D, map);
    map = new EnumMap<Quad, SubQuad>(Quad.class);
    map.put(Quad.First, new SubQuad((short) 2, QuadType.U));
    map.put(Quad.Second, new SubQuad((short) 1, QuadType.U));
    map.put(Quad.Third, new SubQuad((short) 3, QuadType.D));
    map.put(Quad.Fourth, new SubQuad((short) 0, QuadType.C));
    TABLE_ENC.put(QuadType.U, map);
    map = new EnumMap<Quad, SubQuad>(Quad.class);
    map.put(Quad.First, new SubQuad((short) 2, QuadType.C));
    map.put(Quad.Second, new SubQuad((short) 3, QuadType.A));
    map.put(Quad.Third, new SubQuad((short) 1, QuadType.C));
    map.put(Quad.Fourth, new SubQuad((short) 0, QuadType.U));
    TABLE_ENC.put(QuadType.C, map);
  }

  protected static final Map<QuadType, Map<Short, SubQuad>> TABLE_DEC = new EnumMap<QuadType, Map<Short, SubQuad>>(
      QuadType.class);
  static {
    Map<Short, SubQuad> mapDec = new HashMap<Short, SubQuad>();
    mapDec.put((short) 0, new SubQuad((short) Quad.First.ordinal(), QuadType.D));
    mapDec.put((short) 3, new SubQuad((short) Quad.Second.ordinal(), QuadType.C));
    mapDec.put((short) 1, new SubQuad((short) Quad.Third.ordinal(), QuadType.A));
    mapDec.put((short) 2, new SubQuad((short) Quad.Fourth.ordinal(), QuadType.A));
    TABLE_DEC.put(QuadType.A, mapDec);
    mapDec = new HashMap<Short, SubQuad>();
    mapDec.put((short) 0, new SubQuad((short) Quad.First.ordinal(), QuadType.A));
    mapDec.put((short) 1, new SubQuad((short) Quad.Second.ordinal(), QuadType.D));
    mapDec.put((short) 3, new SubQuad((short) Quad.Third.ordinal(), QuadType.U));
    mapDec.put((short) 2, new SubQuad((short) Quad.Fourth.ordinal(), QuadType.D));
    TABLE_DEC.put(QuadType.D, mapDec);
    mapDec = new HashMap<Short, SubQuad>();
    mapDec.put((short) 2, new SubQuad((short) Quad.First.ordinal(), QuadType.U));
    mapDec.put((short) 1, new SubQuad((short) Quad.Second.ordinal(), QuadType.U));
    mapDec.put((short) 3, new SubQuad((short) Quad.Third.ordinal(), QuadType.D));
    mapDec.put((short) 0, new SubQuad((short) Quad.Fourth.ordinal(), QuadType.C));
    TABLE_DEC.put(QuadType.U, mapDec);
    mapDec = new HashMap<Short, SubQuad>();
    mapDec.put((short) 2, new SubQuad((short) Quad.First.ordinal(), QuadType.C));
    mapDec.put((short) 3, new SubQuad((short) Quad.Second.ordinal(), QuadType.A));
    mapDec.put((short) 1, new SubQuad((short) Quad.Third.ordinal(), QuadType.C));
    mapDec.put((short) 0, new SubQuad((short) Quad.Fourth.ordinal(), QuadType.U));
    TABLE_DEC.put(QuadType.C, mapDec);
  }

  public HilbertQuad(final long index, final short level, final double fLat, final double fLon, final double cLat,
      final double cLon, final QuadType typeQuad) {
    this.index = index;
    this.level = level;
    floorLon = fLon;
    ceilLon = cLon;
    floorLat = fLat;
    ceilLat = cLat;
    this.typeQuad = typeQuad;
  }

  public void setIndex(final long index) {
    this.index = index;
  }

  /**
   * Returns the HilbertQuad that contains the location.
   * 
   * @param loc
   *          Location
   * @param level
   *          Level of the HilbertQuad
   * @return
   */
  public static HilbertQuad encode(final Location loc, final int level) throws IllegalArgumentException {
    if (level > MAX_LEVEL) {
      throw new IllegalArgumentException("level exceeds the maximum level.");
    }
    final HilbertQuad quad = new HilbertQuad(0L, (short) 0, LATITUDE_RANGE[0], LONGITUDE_RANGE[0], LATITUDE_RANGE[1],
        LONGITUDE_RANGE[1], QuadType.A);
    getQuad((short) 0, loc.getLatitude(Location.RADIANS), loc.getLongitude(Location.RADIANS), quad, level);
    return quad;
  }

  public static HilbertQuad decode(final long key) throws IllegalArgumentException {
    double lonMid;
    double latMid;
    short tabDecKey;

    if (key < 0 || key > (long) (Math.pow(2, NUM_BITS) - 1)) {
      throw new IllegalArgumentException("key is out of bound");
    }
    long bitMask = (long) Math.pow(2, NUM_BITS - 1);
    final HilbertQuad quad = new HilbertQuad(key, MAX_LEVEL, LATITUDE_RANGE[0], LONGITUDE_RANGE[0], LATITUDE_RANGE[1],
        LONGITUDE_RANGE[1], QuadType.A);
    // The first bit chooses the initial longitude range. Either (-pi,0) or
    // (0,pi)
    lonMid = (quad.getCeilLon() + quad.getFloorLon()) / 2;
    if ((bitMask & key) == bitMask) {
      quad.floorLon = lonMid;
    } else {
      quad.ceilLon = lonMid;
    }
    for (int ind = 0; ind < MAX_LEVEL - 1; ind++) {
      lonMid = (quad.getCeilLon() + quad.getFloorLon()) / 2;
      latMid = (quad.getCeilLat() + quad.getFloorLat()) / 2;
      tabDecKey = 0;
      bitMask >>>= 1;
      if ((bitMask & key) == bitMask) {
        tabDecKey += 2;
      }
      bitMask >>>= 1;
      if ((bitMask & key) == bitMask) {
        tabDecKey++;
      }
      final SubQuad currSubQuad = TABLE_DEC.get(quad.getTypeQuad()).get(tabDecKey);
      quad.setTypeQuad(currSubQuad.type);
      switch (currSubQuad.pos) {
        case 0 : // First quadrant
          quad.ceilLon = lonMid;
          quad.ceilLat = latMid;
          break;
        case 1 : // Second quadrant
          quad.floorLon = lonMid;
          quad.ceilLat = latMid;
          break;
        case 2 : // Third quadrant
          quad.ceilLon = lonMid;
          quad.floorLat = latMid;
          break;
        case 3 : // Fourth quadrant
          quad.floorLon = lonMid;
          quad.floorLat = latMid;
          break;
        default :
          break;
      }
    }
    return quad;
  }

  // TODO Check, could be better iterative.
  /**
   * Find the {@link HilbertQuad} of level
   * 
   * @param i
   *          that contains the point
   * @param lat
   *          ,
   * @param lon
   *          .
   * 
   * @param i
   *          Current level of the grid structure. (Because the method is
   *          recursive)
   * @param lat
   *          Latitude of the point (radiant).
   * @param lon
   *          Longitude of the point (radiant).
   * @param quad
   *          Starting {@link HilbertQuad}.
   * @param level
   *          Target level of the grid structure.
   */
  private static void getQuad(final short i, final double lat, final double lon, final HilbertQuad quad, final int level) {
    short quadPos = 0;
    double lonMid, latMid;

    if (i < level) {
      if (i == 0) {
        lonMid = (quad.getCeilLon() + quad.getFloorLon()) / 2;
        if (lon > lonMid) {
          quad.setIndex(1);
          quad.setFloorLon(lonMid);
        } else {
          quad.setCeilLon(lonMid);
        }
      } else {
        quad.setIndex(quad.getIndex() << 2);
        lonMid = (quad.getCeilLon() + quad.getFloorLon()) / 2;
        latMid = (quad.getCeilLat() + quad.getFloorLat()) / 2;
        if (lon > lonMid) {
          quadPos++;
          quad.setFloorLon(lonMid);
        } else {
          quad.setCeilLon(lonMid);
        }
        if (lat > latMid) {
          quadPos += 2;
          quad.setFloorLat(latMid);
        } else {
          quad.setCeilLat(latMid);
        }
        final SubQuad s = TABLE_ENC.get(quad.getTypeQuad()).get(Quad.values()[quadPos]);
        quad.setIndex(quad.getIndex() + s.pos);
        quad.setTypeQuad(s.type);
      }
      quad.setLevel((short) (i + 1));
      getQuad((short) (i + 1), lat, lon, quad, level);
    }
  }

  /**
   * Return the {@link HilbertQuad} that is on position at the upper layer. 0 is
   * the bottom-left sub-quad 1 is the bottom-right sub-quad 2 is the top-left
   * sub-quad 3 is the top-right sub-quad
   * 
   * @param pos
   *          Position of the upper layer {@link HilbertQuad} (refer to the
   *          above legend).
   * @param down
   *          Not used at the moment, must be always set to true} .
   * @return
   */
  public HilbertQuad getQuad(final short pos, final boolean down) {
    // TODO Implement the code for down == false, if necessary.
    double floorLat, floorLon, ceilLat, ceilLon;
    HilbertQuad hq;

    if (getLevel() >= HilbertQuad.MAX_LEVEL) {
      hq = null;
    } else if (down) {
      double lonMid, latMid;
      lonMid = (getCeilLon() + getFloorLon()) / 2;
      latMid = (getCeilLat() + getFloorLat()) / 2;

      switch (pos) {
        case 0 :
          floorLat = getFloorLat();
          ceilLat = latMid;
          floorLon = getFloorLon();
          ceilLon = lonMid;
          break;
        case 1 :
          floorLat = getFloorLat();
          ceilLat = latMid;
          floorLon = lonMid;
          ceilLon = getCeilLon();
          break;
        case 2 :
          floorLat = latMid;
          ceilLat = getCeilLat();
          floorLon = getFloorLon();
          ceilLon = lonMid;
          break;
        case 3 :
          floorLat = latMid;
          ceilLat = getCeilLat();
          floorLon = lonMid;
          ceilLon = getCeilLon();
          break;
        // It would not happen...
        default :
          throw new IllegalArgumentException("Wrong position (not in the range (0..3))");
      }
      final SubQuad s = TABLE_ENC.get(getTypeQuad()).get(Quad.values()[pos]);
      hq = new HilbertQuad((getIndex() << 2) + s.pos, (short) (getLevel() + 1), floorLat, floorLon, ceilLat, ceilLon,
          s.type);
    } else {
      hq = null;
    }

    return hq;
  }

  /**
   * Returns the keys range associated to this member.
   * 
   * @return Keys range.
   */
  public long[] getKeysRange() {
    final long[] keysInt = new long[2];

    int remBits = (getLevel() - 1) * 2;
    remBits++; // Because one additional bit is used.
    remBits = NUM_BITS - remBits;
    keysInt[0] = getIndex() << remBits;
    keysInt[1] = keysInt[0] + (long) (Math.pow(2, remBits) - 1);
    return keysInt;
  }

  public long getIndex() {
    return index;
  }

  public void setLevel(final short level) {
    this.level = level;
  }

  public short getLevel() {
    return level;
  }

  public void setTypeQuad(final QuadType typeQuad) {
    this.typeQuad = typeQuad;
  }

  public QuadType getTypeQuad() {
    return typeQuad;
  }

  public void setFloorLat(final double floorLat) {
    this.floorLat = floorLat;
  }

  public double getFloorLat() {
    return floorLat;
  }

  public void setCeilLat(final double ceilLat) {
    this.ceilLat = ceilLat;
  }

  public double getCeilLat() {
    return ceilLat;
  }

  public void setFloorLon(final double floorLon) {
    this.floorLon = floorLon;
  }

  public double getFloorLon() {
    return floorLon;
  }

  public void setCeilLon(final double ceilLon) {
    this.ceilLon = ceilLon;
  }

  public double getCeilLon() {
    return ceilLon;
  }

  public double getHeigth() {
    Location bottomLeftCorner, topLeftCorner;

    try {
      bottomLeftCorner = new Location(floorLat, floorLon);
      topLeftCorner = new Location(ceilLat, floorLon);
    } catch (final GeoLocationOutOfBoundException e) {
      // Never happens.
      LOGGER.debug(e.toString());
      return 0d;
    }
    return bottomLeftCorner.distanceGCTo(topLeftCorner);
  }

  public double getTopWidth() {
    Location topRightCorner, topLeftCorner;

    try {
      topRightCorner = new Location(ceilLat, ceilLon);
      topLeftCorner = new Location(ceilLat, floorLon);
    } catch (final GeoLocationOutOfBoundException e) {
      // Never happens.
      LOGGER.debug(e.toString());
      return 0d;
    }
    return topRightCorner.distanceGCTo(topLeftCorner);
  }

  public double getBottomWidth() {
    Location bottomRightCorner, bottomLeftCorner;

    try {
      bottomRightCorner = new Location(floorLat, ceilLon);
      bottomLeftCorner = new Location(floorLat, floorLon);
    } catch (final GeoLocationOutOfBoundException e) {
      // Never happens.
      LOGGER.debug(e.toString());
      return 0d;
    }
    return bottomRightCorner.distanceGCTo(bottomLeftCorner);
  }

  @Override
  public boolean equals(final Object obj) {
    boolean equal = false;

    if (this == obj) {
      equal = true;
    } else if (obj instanceof HilbertQuad) {
      final HilbertQuad comparable = (HilbertQuad) obj;

      equal = true;

      equal &= index == comparable.getIndex();
      equal &= level == comparable.getLevel();
    }

    return equal;
  }

  /**
   * Compute a unique hash code identifying the bucket with index {@link index}
   * and level {@link level}
   */
  @Override
  public int hashCode() {
    int result = 17;
    result = 31 * result + level;
    result = 31 * result + (int) (index ^ index >>> 32);
    return result;
  }
}