/*
 * Copyright 2012 POLITO 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
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
