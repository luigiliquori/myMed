package com.mymed.model.data.myjam;

import java.io.Serializable;

import com.mymed.model.data.AbstractMBean;

/**
 * Contains the details of the report
 * 
 * @author iacopo
 * 
 */
public class MReportBean extends AbstractMBean implements Serializable {
  /**
   * Identifier used for serialization purposes.
   */
  private static final long serialVersionUID = -7725479086699369838L;

  private String id;
  private String userName;
  private String userId;
  private String reportType;
  private String transitType;
  private String trafficFlowType;
  private String comment;
  private Long locationId;
  private Long timestamp;

  public String getId() {
    return id;
  }

  public void setId(final String id) {
    this.id = id;
  }

  public void setUserName(final String userName) {
    this.userName = userName;
  }

  public String getUserName() {
    return userName;
  }

  public void setUserId(final String userId) {
    this.userId = userId;
  }

  public String getUserId() {
    return userId;
  }

  public void setReportType(final String reportType) {
    this.reportType = reportType;
  }

  public String getReportType() {
    return reportType;
  }

  public void setTransitType(final String transitType) {
    this.transitType = transitType;
  }

  public String getTransitType() {
    return transitType;
  }

  public void setTrafficFlowType(final String trafficFlowType) {
    this.trafficFlowType = trafficFlowType;
  }

  public String getTrafficFlowType() {
    return trafficFlowType;
  }

  public void setComment(final String comment) {
    this.comment = comment;
  }

  public String getComment() {
    return comment;
  }

  public void setLocationId(final long locationId) {
    this.locationId = locationId;
  }

  public Long getLocationId() {
    return locationId;
  }

  public void setTimestamp(final long timestamp) {
    this.timestamp = timestamp;
  }

  public Long getTimestamp() {
    return timestamp;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    // TODO
    final int prime = 31;
    int result = 1;
    result = prime * result + (comment == null ? 0 : comment.hashCode());
    result = prime * result + (id == null ? 0 : id.hashCode());
    result = prime * result + (locationId == null ? 0 : locationId.hashCode());
    result = prime * result + (reportType == null ? 0 : reportType.hashCode());
    result = prime * result + (timestamp == null ? 0 : timestamp.hashCode());
    result = prime * result + (trafficFlowType == null ? 0 : trafficFlowType.hashCode());
    result = prime * result + (transitType == null ? 0 : transitType.hashCode());
    result = prime * result + (userId == null ? 0 : userId.hashCode());
    result = prime * result + (userName == null ? 0 : userName.hashCode());
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
    if (!(obj instanceof MReportBean)) {
      return false;
    }
    final MReportBean other = (MReportBean) obj;
    if (comment == null) {
      if (other.comment != null) {
        return false;
      }
    } else if (!comment.equals(other.comment)) {
      return false;
    }
    if (id == null) {
      if (other.id != null) {
        return false;
      }
    } else if (!id.equals(other.id)) {
      return false;
    }
    if (locationId == null) {
      if (other.locationId != null) {
        return false;
      }
    } else if (!locationId.equals(other.locationId)) {
      return false;
    }
    if (reportType == null) {
      if (other.reportType != null) {
        return false;
      }
    } else if (!reportType.equals(other.reportType)) {
      return false;
    }
    if (timestamp == null) {
      if (other.timestamp != null) {
        return false;
      }
    } else if (!timestamp.equals(other.timestamp)) {
      return false;
    }
    if (trafficFlowType == null) {
      if (other.trafficFlowType != null) {
        return false;
      }
    } else if (!trafficFlowType.equals(other.trafficFlowType)) {
      return false;
    }
    if (transitType == null) {
      if (other.transitType != null) {
        return false;
      }
    } else if (!transitType.equals(other.transitType)) {
      return false;
    }
    if (userId == null) {
      if (other.userId != null) {
        return false;
      }
    } else if (!userId.equals(other.userId)) {
      return false;
    }
    if (userName == null) {
      if (other.userName != null) {
        return false;
      }
    } else if (!userName.equals(other.userName)) {
      return false;
    }
    return true;
  }

  // @Override
  // public Map<String, byte[]> getAttributeToMap() throws
  // InternalBackEndException {
  // final Map<String, byte[]> args = new HashMap<String, byte[]>();
  // byte[] returnValueByteArray = null;
  //
  // for (final Method method : this.getClass().getDeclaredMethods()) {
  // final String methodName = method.getName();
  // ClassType returnType;
  // if (methodName.startsWith("get") && methodName != "getAttributeToMap") {
  // try {
  // final Object returnValue = method.invoke(this, (Object[]) null);
  // if (returnValue != null) {
  // returnType = ClassType.inferTpye(method.getReturnType());
  // returnValueByteArray = ClassType.objectToByteArray(returnType,
  // returnValue);
  // String attName = methodName.replaceFirst("get", "");
  // attName = attName.substring(0, 1).toLowerCase() + attName.substring(1);
  // args.put(attName, returnValueByteArray);
  // }
  // } catch (final Exception e) {
  // throw new
  // InternalBackEndException("getAttribueToMap failed!: Introspection error");
  // }
  // }
  // }
  // return args;
  // }
}
