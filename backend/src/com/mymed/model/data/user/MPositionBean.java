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
package com.mymed.model.data.user;

import com.mymed.model.data.AbstractMBean;

public class MPositionBean extends AbstractMBean {
  /* --------------------------------------------------------- */
  /* Attributes */
  /* --------------------------------------------------------- */
  /** == Position ID */
  private String userID;
  /** GPS information */
  private String latitude;
  private String longitude;

  /** Adress information */
  private String city;
  private String zipCode;
  private String country;
  private String formattedAddress;

  /* --------------------------------------------------------- */
  /* Constructors */
  /* --------------------------------------------------------- */
  /**
   * Create a new empty MPositionBean
   */
  public MPositionBean() {
    // Empty constructor, needed because of the copy constructor
    super();
  }

  /**
   * Copy constructor.
   * <p>
   * Provide a clone of the passed MPositionBean
   * 
   * @param toClone
   *          the position bean to clone
   */
  protected MPositionBean(final MPositionBean toClone) {
    super();

    userID = toClone.getUserID();
    latitude = toClone.getLatitude();
    longitude = toClone.getLongitude();
    city = toClone.getCity();
    zipCode = toClone.getZipCode();
    country = toClone.getCountry();
    formattedAddress = toClone.getFormattedAddress();
  }

  @Override
  public MPositionBean clone() {
    return new MPositionBean(this);
  }

  /* --------------------------------------------------------- */
  /* Override methods */
  /* --------------------------------------------------------- */
  /**
   * @see java.lang.Object#equals()
   */
  @Override
  public boolean equals(final Object object) {

    boolean equal = false;

    if (this == object) {
      equal = true;
    } else if (object instanceof MPositionBean) {
      final MPositionBean comparable = (MPositionBean) object;
      equal = true;

      if (latitude == null && comparable.getLatitude() != null || latitude != null && comparable.getLatitude() == null) {
        equal &= false;
      } else if (latitude != null && comparable.getLatitude() != null) {
        equal &= latitude.equals(comparable.getLatitude());
      }

      if (longitude == null && comparable.getLongitude() != null || longitude != null
          && comparable.getLongitude() == null) {
        equal &= false;
      } else if (longitude != null && comparable.getLongitude() != null) {
        equal &= longitude.equals(comparable.getLongitude());
      }
    }

    return equal;
  }

  /**
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    final int prime = 31;
    int result = 1;

    result = prime * result + (userID == null ? 0 : userID.hashCode());
    result = prime * result + (longitude == null ? 0 : longitude.hashCode());
    result = prime * result + (latitude == null ? 0 : latitude.hashCode());

    return result;
  }

  @Override
  public String toString() {
    return "Position:\n" + super.toString();
  }

  /* --------------------------------------------------------- */
  /* GETTER AND SETTER */
  /* --------------------------------------------------------- */
  public String getUserID() {
    return userID;
  }

  public void setUserID(final String id) {
    userID = id;
  }

  public String getLatitude() {
    return latitude;
  }

  public void setLatitude(final String latitude) {
    this.latitude = latitude;
  }

  public String getLongitude() {
    return longitude;
  }

  public void setLongitude(final String longitude) {
    this.longitude = longitude;
  }

  public String getCity() {
    return city;
  }

  public void setCity(final String city) {
    this.city = city;
  }

  public String getZipCode() {
    return zipCode;
  }

  public void setZipCode(final String zipCode) {
    this.zipCode = zipCode;
  }

  public String getCountry() {
    return country;
  }

  public void setCountry(final String country) {
    this.country = country;
  }

  public String getFormattedAddress() {
    return formattedAddress;
  }

  public void setFormattedAddress(final String formattedAddress) {
    this.formattedAddress = formattedAddress;
  }
}
