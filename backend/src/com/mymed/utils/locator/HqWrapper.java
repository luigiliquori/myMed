/*
 * Copyright 2012 UNITO 
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

/**
 * Class to wrap a {@link HilbertQuad} in order to add a metric with which it
 * can be ordered.
 * 
 * @author iacopo
 * 
 */
public class HqWrapper {
  private short metric;
  private HilbertQuad quad;

  public HqWrapper(final short metric, final HilbertQuad quad) {
    this.metric = metric;
    this.quad = quad;
  }

  public void setMetric(final short metric) {
    this.metric = metric;
  }

  public short getMetric() {
    return metric;
  }

  public void setQuad(final HilbertQuad quad) {
    this.quad = quad;
  }

  public HilbertQuad getQuad() {
    return quad;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    final int prime = 31;
    int result = 1;
    result = prime * result + metric;
    result = prime * result + (quad == null ? 0 : quad.hashCode());
    return result;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#equals(java.lang.Object)
   */
  @Override
  public boolean equals(final Object obj) {

    boolean equal = false;

    if (this == obj) {
      equal = true;
    } else if (obj instanceof HqWrapper) {
      final HqWrapper comparable = (HqWrapper) obj;

      equal = true;

      equal &= metric == comparable.getMetric();

      if (quad == null && comparable.getQuad() != null || quad != null && comparable.getQuad() == null) {
        equal &= false;
      } else if (quad != null && comparable.getQuad() != null) {
        equal &= quad.equals(comparable.getQuad());
      }
    }

    return equal;
  }
}
