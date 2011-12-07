package com.mymed.controller.core.manager.reputation.db.table;

/**
 * 
 * @author piccolo
 */
public class ApplicationReputation {
  private static final int PRIME = 31;

  private String applicationReputationId;
  private String applicationId;
  private String verdictList_producers;
  private String verdictList_consumers;
  private String outcomeList;
  private double score;
  private int size;
  private int engineTuningParameter1;
  private int engineTuningParameter2;
  private int engineTuningParameter3;
  private int maxVerdictNumber;

  @Override
  public String toString() {
    return "ApplicationReputation{" + "applicationReputationId=" + applicationReputationId + ", applicationId="
        + applicationId + ", verdictList_producers=" + verdictList_producers + ", verdictList_consumers="
        + verdictList_consumers + ", score=" + score + ", size=" + size + ", outcomeList=" + outcomeList
        + ", engineTuningParameter1=" + engineTuningParameter1 + ", engineTuningParameter2=" + engineTuningParameter2
        + ", engineTuningParameter3=" + engineTuningParameter3 + ", maxVerdictNumber=" + maxVerdictNumber + '}';
  }

  public String getApplicationId() {
    return applicationId;
  }

  public void setApplicationId(final String applicationId) {
    this.applicationId = applicationId;
  }

  public String getApplicationReputationId() {
    return applicationReputationId;
  }

  public void setApplicationReputationId(final String applicationReputationId) {
    this.applicationReputationId = applicationReputationId;
  }

  public int getEngineTuningParameter1() {
    return engineTuningParameter1;
  }

  public void setEngineTuningParameter1(final int engineTuningParameter1) {
    this.engineTuningParameter1 = engineTuningParameter1;
  }

  public int getEngineTuningParameter2() {
    return engineTuningParameter2;
  }

  public void setEngineTuningParameter2(final int engineTuningParameter2) {
    this.engineTuningParameter2 = engineTuningParameter2;
  }

  public int getEngineTuningParameter3() {
    return engineTuningParameter3;
  }

  public void setEngineTuningParameter3(final int engineTuningParameter3) {
    this.engineTuningParameter3 = engineTuningParameter3;
  }

  public int getMaxVerdictNumber() {
    return maxVerdictNumber;
  }

  public void setMaxVerdictNumber(final int maxVerdictNumber) {
    this.maxVerdictNumber = maxVerdictNumber;
  }

  public String getOutcomeList() {
    return outcomeList;
  }

  public void setOutcomeList(final String outcomeList) {
    this.outcomeList = outcomeList;
  }

  public double getScore() {
    return score;
  }

  public void setScore(final double score) {
    this.score = score;
  }

  public int getSize() {
    return size;
  }

  public void setSize(final int size) {
    this.size = size;
  }

  public String getVerdictList_consumers() {
    return verdictList_consumers;
  }

  public void setVerdictList_consumers(final String verdictList_consumers) {
    this.verdictList_consumers = verdictList_consumers;
  }

  public String getVerdictList_producers() {
    return verdictList_producers;
  }

  public void setVerdictList_producers(final String verdictList_producers) {
    this.verdictList_producers = verdictList_producers;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    int result = 1;
    result = PRIME * result + (applicationId == null ? 0 : applicationId.hashCode());
    result = PRIME * result + (applicationReputationId == null ? 0 : applicationReputationId.hashCode());
    result = PRIME * result + engineTuningParameter1;
    result = PRIME * result + engineTuningParameter2;
    result = PRIME * result + engineTuningParameter3;
    result = PRIME * result + maxVerdictNumber;
    result = PRIME * result + (outcomeList == null ? 0 : outcomeList.hashCode());
    long temp;
    temp = Double.doubleToLongBits(score);
    result = PRIME * result + (int) (temp ^ temp >>> 32);
    result = PRIME * result + size;
    result = PRIME * result + (verdictList_consumers == null ? 0 : verdictList_consumers.hashCode());
    result = PRIME * result + (verdictList_producers == null ? 0 : verdictList_producers.hashCode());
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
    if (!(obj instanceof ApplicationReputation)) {
      return false;
    }
    final ApplicationReputation other = (ApplicationReputation) obj;
    if (applicationId == null) {
      if (other.applicationId != null) {
        return false;
      }
    } else if (!applicationId.equals(other.applicationId)) {
      return false;
    }
    if (applicationReputationId == null) {
      if (other.applicationReputationId != null) {
        return false;
      }
    } else if (!applicationReputationId.equals(other.applicationReputationId)) {
      return false;
    }
    if (engineTuningParameter1 != other.engineTuningParameter1) {
      return false;
    }
    if (engineTuningParameter2 != other.engineTuningParameter2) {
      return false;
    }
    if (engineTuningParameter3 != other.engineTuningParameter3) {
      return false;
    }
    if (maxVerdictNumber != other.maxVerdictNumber) {
      return false;
    }
    if (outcomeList == null) {
      if (other.outcomeList != null) {
        return false;
      }
    } else if (!outcomeList.equals(other.outcomeList)) {
      return false;
    }
    if (Double.doubleToLongBits(score) != Double.doubleToLongBits(other.score)) {
      return false;
    }
    if (size != other.size) {
      return false;
    }
    if (verdictList_consumers == null) {
      if (other.verdictList_consumers != null) {
        return false;
      }
    } else if (!verdictList_consumers.equals(other.verdictList_consumers)) {
      return false;
    }
    if (verdictList_producers == null) {
      if (other.verdictList_producers != null) {
        return false;
      }
    } else if (!verdictList_producers.equals(other.verdictList_producers)) {
      return false;
    }
    return true;
  }
}
