package com.mymed.utils.locator;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashSet;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Set;
import java.util.SortedSet;
import java.util.TreeSet;

import com.mymed.utils.locator.HilbertQuad.SubQuad;

/**
 * Class implementing the algorithm which translates the search area in a fixed
 * number of keys ranges. Those keys are the locationId.
 * 
 * @author iacopo
 * 
 */
public class KeysFinder {
  private static final int MAX_RADIUS = 50000;
  private static final int MAX_RANGES = 6;
  private static final int AREA_MASK_LEN = 15;
  private static final short MAX_DEPTH = 6; // NOPMD

  private double minLat, minLon, maxLat, maxLon; // Radians
  private Set<HilbertQuad> coveringSet = null;

  private enum Position {
    internal,
    intersects,
    external
  };

  /**
   * Initialize the keys finder creating an empty covering set.
   */
  protected KeysFinder() {
    coveringSet = new HashSet<HilbertQuad>();
  }

  /**
   * Returns the number of key ranges that cover the bounding box of the
   * circular area with radius range expressed in meter,the value can be less or
   * equal of MAX_RANGES. The ranges are put in the list ranges, the total list
   * of covering HilbertQuad is put in the list quads.
   * 
   * @returns Returns the number of key ranges that cover the bounding box.
   * @param loc
   *          Center of the search area.
   * @param radius
   *          Radius of the search area
   * @param ranges
   *          List of ranges of keys, must be empty.
   * @param quads
   *          List of HilbertQuad of the covering set.
   */
  protected List<long[]> getKeysRanges(final Location loc, final int radius) {
    final List<long[]> keysRanges = new ArrayList<long[]>(MAX_RANGES);

    getBound(loc, radius);
    expandQuads(keysRanges);

    return keysRanges;
  }

  /**
   * Given a position index, returns the related areaId, used as key in the
   * database
   * 
   * @param index
   *          The index of the position.
   * @return
   */
  protected static int getAreaId(final long index) throws IllegalArgumentException {
    long mask;
    int zeroBits;

    if (index < 0 || index > (long) (Math.pow(2, HilbertQuad.numBits) - 1)) {
      throw new IllegalArgumentException("key is out of bound");
    }

    /*
     * The mask composed by AREA_MASK_LEN '1', and the remaining bits
     * (numBits-AREA_MASK_LEN) '0' is created.
     */
    zeroBits = HilbertQuad.numBits - AREA_MASK_LEN;
    mask = (long) Math.pow(2, AREA_MASK_LEN) - 1;
    mask = mask << zeroBits;
    /*
     * index AND mask is returned.
     */
    return (int) ((index & mask) >>> zeroBits);
  }

  protected Set<HilbertQuad> getCoveringSet() {
    return coveringSet;
  }

  /**
   * Returns up to four {@link HilbertQuad} that cover the range specified
   * starting from the point. It also sets the attributes minLat, minLon,
   * maxLat, maxLon, used by the method expandQuad.
   * 
   * @param loc
   *          Center of the search area.
   * @param radius
   *          Radius of the search area.
   * @return
   */
  private int getBound(final Location loc, final int radius) {
    int level;
    int numQuads = 0;

    if (radius < 10 || radius > MAX_RADIUS) {
      throw new IllegalArgumentException("Radius " + String.valueOf(radius) + " out of bound");
    }
    // Evaluate the level of coding necessary to obtain an HilbertQuad that
    // possibly contains the area determined by range.
    // Set the coordinates of the bounding box.
    Location[] corners = loc.boundingCoordinates(radius);
    minLat = corners[0].getLatitude(Location.RADIANS);
    minLon = corners[0].getLongitude(Location.RADIANS);
    maxLat = corners[1].getLatitude(Location.RADIANS);
    maxLon = corners[1].getLongitude(Location.RADIANS);
    level = getLevel();
    corners = Location.getCorners(corners[0], corners[1]);
    coveringSet.clear();

    for (int i = 0; i < 4; i++) {
      if (coveringSet.add(HilbertQuad.encode(corners[i], level))) {
        numQuads++;
      }
    }

    return numQuads;
  }

  /**
   * Recursively expand the HilbertQuad instances in {@link coveringSet} that
   * overlap the search area until no one is further expandable, or the max
   * depth is reached.
   * 
   * "Expand" means substitute the considered HilbertQuad with its upper level
   * buckets (its four quadrants) and throw away those which don't overlap the
   * search area. This operation may increase the number of key ranges that must
   * be queried. A HilbertQuad is not expandable if expanding it leads to
   * overcome {@link MAX_RANGES}.
   * 
   * @param ranges
   *          List of key ranges to be queried.
   * @return
   */
  private int expandQuads(final List<long[]> ranges) {
    int level = -1;
    boolean expanded = true;

    /*
     * INITIALIZATION The list endRanges, that contains the HilbertQuad located
     * at the end of a range of keys is populated. The list expandable that
     * contains the HilbertQuad that can be further expanded is populated
     */
    final List<Long> rangesExtremes = new ArrayList<Long>(MAX_RANGES * 2);
    final OrderedHqList expandable = new OrderedHqList();
    HilbertQuad oldHq = null;
    /*
     * coveringSet is inserted in a TreeSet, where the HilbertQuad are sorted by
     * keys, so that it can be iterated to easily obtain key ranges.
     */
    final SortedSet<HilbertQuad> tmpCovSet = new TreeSet<HilbertQuad>(new HqComparator());
    tmpCovSet.addAll(coveringSet);
    final Iterator<HilbertQuad> covSetIterator = tmpCovSet.iterator();
    rangesExtremes.add(tmpCovSet.first().getKeysRange()[0]);
    while (covSetIterator.hasNext()) {
      final HilbertQuad hq = covSetIterator.next();
      level = hq.getLevel();
      final long index = hq.getIndex();
      if (oldHq != null && index != oldHq.getIndex() + 1) {
        rangesExtremes.add(hq.getKeysRange()[0]);
        rangesExtremes.add(oldHq.getKeysRange()[1]);
      }
      oldHq = hq;
      // Metric of the current HilbertQuad is evaluated.
      final short metric = getMetric(hq);
      expandable.addOrdered(new HqWrapper(metric, hq));
    }
    rangesExtremes.add(oldHq.getKeysRange()[1]);
    /*
     * EXPANSION The algorithm are expanded in the order of the expandable list,
     * if this doesn't exceed the max. number of ranges.
     */
    short depth = 0;
    List<HilbertQuad> nextExpandable = null;
    while (expanded && level < HilbertQuad.maxLevel && depth < MAX_DEPTH) {
      expanded = false;
      // After the first iteration in which nextExpandable is null, expandable
      // list is populated.
      if (nextExpandable == null) {
        nextExpandable = new LinkedList<HilbertQuad>();
      } else {
        final Iterator<HilbertQuad> expIt = nextExpandable.iterator();
        while (expIt.hasNext()) {
          final HilbertQuad currHq = expIt.next();
          final short metric = getMetric(currHq);
          expandable.addOrdered(new HqWrapper(metric, currHq));
        }
        nextExpandable.clear();
      }

      final Iterator<HqWrapper> expIterator = expandable.iterator();
      while (expIterator.hasNext()) {
        final HqWrapper indexQuad = expIterator.next();
        final HilbertQuad hq = indexQuad.getQuad();
        final int addedSubQuads = expandQuad(hq, rangesExtremes, nextExpandable);
        if (addedSubQuads != -1) {
          expanded = true;
        }
      }
      level++;
      depth++;
      expandable.clear();
    }

    return getNumRanges(ranges, rangesExtremes);
  }

  /*
   * When returns -1 means that the quad cannot be expanded, because it would
   * exceed the MAX_RANGES.
   */
  private int expandQuad(final HilbertQuad hq, final List<Long> extremes, final List<HilbertQuad> nextExpandable) {
    boolean start = false;
    boolean end = false;
    int counter = 0;
    HilbertQuad subHq;
    // Discovers by looking endRanges list, if the current HilbertQuad is the
    // first or the last of a certain range of keys.
    final long[] hqKeys = hq.getKeysRange();
    if (extremes.contains(hqKeys[0])) {
      start = true;
    }
    if (extremes.contains(hqKeys[1])) {
      end = true;
    }
    // Removes the current HilbertQuad from the list. End try to expand it. If
    // this is not possible is reinserted in the same position.
    final List<HilbertQuad> insertedList = new LinkedList<HilbertQuad>();
    final List<HilbertQuad> tmpExpList = new LinkedList<HilbertQuad>();
    final List<Long> tmpExtremes = new LinkedList<Long>();
    boolean prevIns = !start;
    short addedRanges = 0;
    final short numRanges = (short) (extremes.size() / 2);
    for (short i = 0; i < 4; i++) {
      // The sub-quads are visited ordered by key range.
      final SubQuad s = HilbertQuad.tableDec.get(hq.getTypeQuad()).get(i);
      subHq = hq.getQuad(s.pos, true);
      final Position subQuadPosition = checkPosition(subHq);
      final long[] subRange = subHq.getKeysRange();
      // If the sub-quad is not external to the bounding box is inserted in the
      // list.
      if (subQuadPosition != Position.external) {
        insertedList.add(subHq);

        if (!prevIns) {
          tmpExtremes.add(subRange[0]);
        }

        prevIns = true;
        counter++;

        if (i == 3 && end) {
          if (numRanges + addedRanges - 1 >= MAX_RANGES) {
            return -1;
          }
          tmpExtremes.add(subRange[1]);
        }

        if (subQuadPosition == Position.intersects) {
          tmpExpList.add(subHq);
        }
      } else {
        if (i == 3 && !end) {
          tmpExtremes.add(subRange[1] + 1);
        }
        if (prevIns) {
          if (numRanges + addedRanges >= MAX_RANGES && !end) {
            return -1;
          } else if (numRanges + addedRanges - 1 >= MAX_RANGES && end) {
            return -1;
          }
          prevIns = false;
          tmpExtremes.add(subRange[0] - 1);
          addedRanges++;
        }
      }
    }
    // The sorted set extremes and the HilbertQuad's set are updated.
    extremes.remove(hqKeys[0]);
    extremes.remove(hqKeys[1]);
    extremes.addAll(tmpExtremes);
    // Updates the list of HilbertQuad expandable on the next iteration.
    nextExpandable.addAll(tmpExpList);
    // Updates the set of HilbertQuad covering the bounding box
    coveringSet.remove(hq);
    coveringSet.addAll(insertedList);
    return counter;
  }

  /**
   * Returns the level to be used to get the covering set to be optimized. It is
   * the level at which the search area can be fully covered by a single bucket
   * (HilbertQuad).
   * 
   * @return The level to be used to determine the starting set of buckets.
   */
  private short getLevel() {
    short level = 1;
    double deltaLat = HilbertQuad.latitudeRange[1] - HilbertQuad.latitudeRange[0], deltaLon = (HilbertQuad.longitudeRange[1] - HilbertQuad.longitudeRange[0]) / 2;
    final double deltaLatBound = maxLat - minLat;
    double deltaLonBound;

    if (minLon <= maxLon) {
      deltaLonBound = maxLon - minLon;
    } else {
      /*
       * If the maxLon is less then the minLoss, the bounding box is across the
       * 180 degrees meridian for sure the minLon is in the longitude interval
       * 0,180, and maxLon is inside -180,0 ,because we have never bounding box
       * with deltaLon > 180. Then is sufficient add 360
       */
      deltaLonBound = maxLon - minLon + 2 * Math.PI;
    }
    for (level = 1; level < HilbertQuad.maxLevel; level++) {
      // If the HilbertQuads at this level are no more sufficient to cover the
      // bounding box.
      if (deltaLon < deltaLonBound || deltaLat < deltaLatBound) {
        return (short) (level - 1);
      }
      deltaLon = deltaLon / 2;
      deltaLat = deltaLat / 2;
    }
    return level;
  }

  /**
   * Returns a metric between 0 and 1000 indicating how much of the area of the
   * HilbertQuad overlaps the bounding box region. This function starts from the
   * assumption that the HilbertQuad intersects the bounding box.
   * 
   * @param hq
   *          The HilbertQuad which intersection must be checked.
   * @return The metric.
   */
  private short getMetric(final HilbertQuad hq) {
    double deltaLon, deltaLat;

    if (hq != null) {
      if (hq.getFloorLon() <= minLon && hq.getCeilLon() > minLon || hq.getFloorLon() < minLon
          && hq.getCeilLon() >= minLon) {
        deltaLon = hq.getCeilLon() - minLon;
      } else if (hq.getFloorLon() <= maxLon && hq.getCeilLon() > maxLon || hq.getFloorLon() < maxLon
          && hq.getCeilLon() >= maxLon) {
        deltaLon = maxLon - hq.getFloorLon();
      } else {
        deltaLon = hq.getCeilLon() - hq.getFloorLon();
      }

      if (hq.getFloorLat() <= minLat && hq.getCeilLat() > minLat || hq.getFloorLat() < minLat
          && hq.getCeilLat() >= minLat) {
        deltaLat = hq.getCeilLat() - minLat;
      } else if (hq.getFloorLat() <= maxLat && hq.getCeilLat() > maxLat || hq.getFloorLat() < maxLat
          && hq.getCeilLat() >= maxLat) {
        deltaLat = maxLat - hq.getFloorLat();
      } else {
        deltaLat = hq.getCeilLat() - hq.getFloorLat();
      }

      final double maxArea = (hq.getCeilLon() - hq.getFloorLon()) * (hq.getCeilLat() - hq.getFloorLat());
      final short metric = (short) Math.round(deltaLon * deltaLat / maxArea * 1000);
      return metric;

    } else {
      throw new IllegalArgumentException("null argument.");
    }
  }

  /**
   * Returns the total number of keys belonging to.
   * 
   * @param covSet
   *          Set of {@link HilbertQuad} which covers the search area.
   * @return
   */
  public static long getNumKeys(final Set<HilbertQuad> covSet) {
    long num = 0;

    if (covSet != null) {
      final Iterator<HilbertQuad> it = covSet.iterator();
      while (it.hasNext()) {
        final long[] range = it.next().getKeysRange();
        num += range[1] - range[0] + 1;
      }
      return num;
    } else {
      throw new IllegalArgumentException("null argument.");
    }
  }

  /**
   * Returns the number of key ranges belonging to, and put them on the list.
   * 
   * @param ranges
   * @param extremes
   * @return
   */
  private static int getNumRanges(final List<long[]> ranges, final List<Long> extremes) {
    int numRanges = 0;
    long[] currentRange = null;

    try {
      Collections.sort(extremes);
      final Iterator<Long> itExt = extremes.iterator();
      while (itExt.hasNext()) {
        currentRange = new long[2];
        currentRange[0] = itExt.next();
        currentRange[1] = itExt.next();
        ranges.add(currentRange);
        numRanges++;
      }
      return numRanges;
    } catch (final Exception e) {
      throw new IllegalArgumentException("null argument.");
    }
  }

  /**
   * Returns the position of the HilbertQuad with respect to the bounding box.
   * Position may be: external, internal or intersects. Position is referred to
   * the HilbertQuad with respect to the bounding box, which is defined by
   * (minLon, maxLon, minLat, maxLat). If the HilbertQuad contains the bounding
   * box the results will be intersects, if the bounding box contains the
   * HilbertQuad the results will be internal.
   * 
   * @param hq
   * @return
   */
  private Position checkPosition(final HilbertQuad hq) {
    double offset = 0.0, offsetHqLon = 0.0;

    if (minLon > maxLon) {// across 180 degrees meridian (the difference between
                          // minLon and maxLon must be < 180)
      offset = 2 * Math.PI;
      if (hq.getCeilLon() < 0) {
        offsetHqLon = 2 * Math.PI;
      }
    }
    if (hq.getFloorLat() >= minLat && hq.getCeilLat() <= maxLat && hq.getFloorLon() + offsetHqLon >= minLon
        && hq.getCeilLon() + offsetHqLon <= maxLon + offset) {
      return Position.internal;
    }
    if (hq.getCeilLat() < minLat || hq.getFloorLat() > maxLat || hq.getFloorLon() + offsetHqLon > maxLon + offset
        || hq.getCeilLon() + offsetHqLon < minLon) {
      return Position.external;
    }

    return Position.intersects;
  }
}
