/*
 * Copyright 2012 INRIA
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
package com.mymed.model.data.application;

import com.mymed.model.data.AbstractMBean;

/**
 * This class represent an ontology
 * 
 * @author lvanni
 */
public final class MOntologyBean extends AbstractMBean {
  private String name;
  private String type;
  private boolean isPredicate;

  /**
   * Create a new empty ontology bean
   */
  public MOntologyBean() {
    super();
  }

  /**
   * Copy constructor.
   * <p>
   * Provide a clone of the passed MOntologyBean
   * 
   * @param toClone
   *          the ontology bean to clone
   */
  protected MOntologyBean(final MOntologyBean toClone) {
    super();

    name = toClone.getName();
    type = toClone.getType();
    isPredicate = toClone.isPredicate();
  }

  @Override
  public MOntologyBean clone() {
    return new MOntologyBean(this);
  }

  public String getName() {
    return name;
  }

  public void setName(final String name) {
    this.name = name;
  }

  public String getType() {
    return type;
  }

  public void setType(final String type) {
    this.type = type;
  }

  public boolean isPredicate() {
    return isPredicate;
  }

  public void setPredicate(final boolean isPredicate) {
    this.isPredicate = isPredicate;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    int result = 1;
    result = PRIME * result + (isPredicate ? 1231 : 1237);
    result = PRIME * result + (name == null ? 0 : name.hashCode());
    result = PRIME * result + (type == null ? 0 : type.hashCode());
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
    } else if (obj instanceof MOntologyBean) {
      final MOntologyBean comparable = (MOntologyBean) obj;

      equal = true;

      equal &= isPredicate == comparable.isPredicate();

      if (name != null && comparable.getName() != null) {
        equal &= name.equals(comparable.getName());
      } else if (name == null && comparable.getName() != null || name != null && comparable.getName() == null) {
        equal &= false;
      }

      if (type != null && comparable.getType() != null) {
        equal &= type.equals(comparable.getType());
      } else if (type == null && comparable.getType() != null || type != null && comparable.getType() == null) {
        equal &= false;
      }
    }

    return equal;
  }
}
