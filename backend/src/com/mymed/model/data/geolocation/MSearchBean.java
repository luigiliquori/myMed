package com.mymed.model.data.geolocation;

import com.mymed.model.data.AbstractMBean;

/**
 * Class used to exchange located reports, without details.
 * 
 * @author iacopo
 * 
 */
public class MSearchBean extends AbstractMBean {
  // Used for the hashCode()
  private static final int PRIME = 31;

  private String id;
  private Long locationId;
  private Integer latitude;
  private Integer longitude;
  private String value;
  private Integer distance;
  private Long date;
  private Long expirationDate;

  /** Getter and Setter */
  public void setId(final String id) {
    this.id = id;
  }

  public String getId() {
    return id;
  }

  public Long getLocationId() {
    return locationId;
  }

  public void setLocationId(final Long locationId) {
    this.locationId = locationId;
  }

  public void setLatitude(final Integer latitude) {
    this.latitude = latitude;
  }

  public Integer getLatitude() {
    return latitude;
  }

  public void setLongitude(final Integer longitude) {
    this.longitude = longitude;
  }

  public Integer getLongitude() {
    return longitude;
  }

  public Integer getDistance() {
    return distance;
  }

  public void setDistance(final Integer distance) {
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

  public void setDate(final Long date) {
    this.date = date;
  }

  public Long getExpirationDate() {
    return expirationDate;
  }

  public void setExpirationDate(final Long expirationDate) {
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
    result = PRIME * result + (distance == null ? 0 : distance.hashCode());
    result = PRIME * result + (id == null ? 0 : id.hashCode());
    result = PRIME * result + (latitude == null ? 0 : latitude.hashCode());
    result = PRIME * result + (locationId == null ? 0 : locationId.hashCode());
    result = PRIME * result + (longitude == null ? 0 : longitude.hashCode());
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
    if (this == obj) {
      return true;
    }
    if (obj == null) {
      return false;
    }
    if (!(obj instanceof MSearchBean)) {
      return false;
    }
    final MSearchBean other = (MSearchBean) obj;
    if (distance == null) {
      if (other.distance != null) {
        return false;
      }
    } else if (!distance.equals(other.distance)) {
      return false;
    }
    if (id == null) {
      if (other.id != null) {
        return false;
      }
    } else if (!id.equals(other.id)) {
      return false;
    }
    if (latitude == null) {
      if (other.latitude != null) {
        return false;
      }
    } else if (!latitude.equals(other.latitude)) {
      return false;
    }
    if (locationId == null) {
      if (other.locationId != null) {
        return false;
      }
    } else if (!locationId.equals(other.locationId)) {
      return false;
    }
    if (longitude == null) {
      if (other.longitude != null) {
        return false;
      }
    } else if (!longitude.equals(other.longitude)) {
      return false;
    }
    if (value == null) {
      if (other.value != null) {
        return false;
      }
    } else if (!value.equals(other.value)) {
      return false;
    }
    return true;
  }

}
