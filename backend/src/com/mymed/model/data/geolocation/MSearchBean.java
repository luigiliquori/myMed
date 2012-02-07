package com.mymed.model.data.geolocation;

import com.mymed.model.data.AbstractMBean;

/**
 * Class used to exchange located reports, without details.
 * 
 * @author iacopo
 * 
 */
public class MSearchBean extends AbstractMBean {
  private String id;
  private long locationId;
  private int latitude;
  private int longitude;
  private String value;
  private int distance;
  private long date;
  private long expirationDate;

  public void setId(final String id) {
    this.id = id;
  }

  public String getId() {
    return id;
  }

  public long getLocationId() {
    return locationId;
  }

  public void setLocationId(final long locationId) {
    this.locationId = locationId;
  }

  public void setLatitude(final int latitude) {
    this.latitude = latitude;
  }

  public int getLatitude() {
    return latitude;
  }

  public void setLongitude(final int longitude) {
    this.longitude = longitude;
  }

  public int getLongitude() {
    return longitude;
  }

  public int getDistance() {
    return distance;
  }

  public void setDistance(final int distance) {
    this.distance = distance;
  }

  public void setValue(final String value) {
    this.value = value;
  }

  public String getValue() {
    return value;
  }

  public long getDate() {
    return date;
  }

  public void setDate(final long date) {
    this.date = date;
  }

  public long getExpirationDate() {
    return expirationDate;
  }

  public void setExpirationDate(final long expirationDate) {
    this.expirationDate = expirationDate;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    int result = 1;
    result = PRIME * result + (int) (date ^ date >>> 32);
    result = PRIME * result + distance;
    result = PRIME * result + (id == null ? 0 : id.hashCode());
    result = PRIME * result + latitude;
    result = PRIME * result + (int) (locationId ^ locationId >>> 32);
    result = PRIME * result + longitude;
    result = PRIME * result + (value == null ? 0 : value.hashCode());
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
    } else if (obj != null && obj instanceof MSearchBean) {
      final MSearchBean comparable = (MSearchBean) obj;

      equal = true;

      if (id != null && comparable.getId() != null) {
        equal &= id.equals(comparable.getId());
      } else if (id == null && comparable.getId() != null || id != null && comparable.getId() == null) {
        equal &= false;
      }

      if (value != null && comparable.getValue() != null) {
        equal &= value.equals(comparable.getValue());
      } else if (value == null && comparable.getValue() != null || value != null && comparable.getValue() == null) {
        equal &= false;
      }

      equal &= date == comparable.getDate();
      equal &= distance == comparable.getDistance();
      equal &= latitude == comparable.getLatitude();
      equal &= locationId == comparable.getLocationId();
      equal &= longitude == comparable.getLongitude();
    }

    return equal;
  }
}
