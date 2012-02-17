package com.mymed.utils.locator;

import java.io.Serializable;
import java.util.Comparator;

public class HqComparator implements Comparator<HilbertQuad>, Serializable {

  /**
   * Generated ID for this class
   */
  private static final long serialVersionUID = -9105177894475065554L;

  /**
   * Note that this comparator doesn't work if used for nested quads!!
   */
  @Override
  public int compare(final HilbertQuad object1, final HilbertQuad object2) {
    long ind1;
    long ind2;
    if (object1.getLevel() == object2.getLevel()) {
      ind1 = object1.getIndex();
      ind2 = object2.getIndex();
      return Long.signum(ind1 - ind2);
    } else {
      ind1 = object1.getKeysRange()[0];
      ind2 = object2.getKeysRange()[0];
      return Long.signum(ind1 - ind2);
    }
  }
}
