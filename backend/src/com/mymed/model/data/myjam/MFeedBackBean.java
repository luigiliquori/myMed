package com.mymed.model.data.myjam;

import java.io.Serializable;

import com.mymed.model.data.AbstractMBean;

/**
 * Contains the feedback for the reports.
 * 
 * @author iacopo
 * 
 */
public class MFeedBackBean extends AbstractMBean implements Serializable {
  /**
   * Identifier used for serialization purposes.
   */
  private static final long serialVersionUID = 3889004040399952200L;

  private String userId = null;
  private Integer value = null;
  // TODO Not used at the moment.
  private Integer timestamp = null;

  public void setValue(final Integer value) {
    this.value = value;
  }
  public Integer getValue() {
    return value;
  }
  public void setUserId(final String userId) {
    this.userId = userId;
  }
  public String getUserId() {
    return userId;
  }
  public Integer getTimestamp() {
    return timestamp;
  }
  public void setTimestamp(final Integer timestamp) {
    this.timestamp = timestamp;
  }
  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    // TODO
    int result = 1;
    result = PRIME * result + (timestamp == null ? 0 : timestamp.hashCode());
    result = PRIME * result + (userId == null ? 0 : userId.hashCode());
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
    // TODO
    if (this == obj) {
      return true;
    }
    if (obj == null) {
      return false;
    }
    if (!(obj instanceof MFeedBackBean)) {
      return false;
    }
    final MFeedBackBean other = (MFeedBackBean) obj;
    if (timestamp == null) {
      if (other.timestamp != null) {
        return false;
      }
    } else if (!timestamp.equals(other.timestamp)) {
      return false;
    }
    if (userId == null) {
      if (other.userId != null) {
        return false;
      }
    } else if (!userId.equals(other.userId)) {
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
