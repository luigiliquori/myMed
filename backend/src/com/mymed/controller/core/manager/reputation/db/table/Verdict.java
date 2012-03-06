package com.mymed.controller.core.manager.reputation.db.table;

/**
 * 
 * @author piccolo
 */
public class Verdict {

  private String verdictId;
  private String judgeId;
  private String chargedId;
  private String applicationId;
  private long time;
  private boolean isJudgeProducer;
  private String verdictAggregationList;
  private double vote;

  @Override
  public boolean equals(final Object obj) {
    if (obj == null) {
      return false;
    }
    if (getClass() != obj.getClass()) {
      return false;
    }
    final Verdict other = (Verdict) obj;
    if (verdictId == null ? other.verdictId != null : !verdictId.equals(other.verdictId)) {
      return false;
    }
    if (judgeId == null ? other.judgeId != null : !judgeId.equals(other.judgeId)) {
      return false;
    }
    if (chargedId == null ? other.chargedId != null : !chargedId.equals(other.chargedId)) {
      return false;
    }
    if (applicationId == null ? other.applicationId != null : !applicationId.equals(other.applicationId)) {
      return false;
    }
    if (time != other.time) {
      return false;
    }
    if (isJudgeProducer != other.isJudgeProducer) {
      return false;
    }
    if (verdictAggregationList == null ? other.verdictAggregationList != null : !verdictAggregationList
        .equals(other.verdictAggregationList)) {
      return false;
    }
    if (Double.doubleToLongBits(vote) != Double.doubleToLongBits(other.vote)) {
      return false;
    }
    return true;
  }

  @Override
  public int hashCode() {
    int hash = 5;
    hash = 37 * hash + (judgeId != null ? judgeId.hashCode() : 0);
    hash = 37 * hash + (chargedId != null ? chargedId.hashCode() : 0);
    hash = 37 * hash + (applicationId != null ? applicationId.hashCode() : 0);
    hash = 37 * hash + (int) (time ^ time >>> 32);
    return hash;
  }

  @Override
  public String toString() {
    return "Verdict{" + "verdictId=" + verdictId + ", judgeId=" + judgeId + ", chargedId=" + chargedId
        + ", applicationId=" + applicationId + ", time=" + time + ", isJudgeProducer=" + isJudgeProducer
        + ", verdictAggregationList=" + verdictAggregationList + ", vote=" + vote + '}';
  }

  public String getApplicationId() {
    return applicationId;
  }

  public void setApplicationId(final String applicationId) {
    this.applicationId = applicationId;
  }

  public String getChargedId() {
    return chargedId;
  }

  public boolean getIsJudgeProducer() {
    return isJudgeProducer;
  }

  public void setIsJudgeProducer(final boolean isJudgeProducer) {
    this.isJudgeProducer = isJudgeProducer;
  }

  public void setChargedId(final String chargedId) {
    this.chargedId = chargedId;
  }

  public String getJudgeId() {
    return judgeId;
  }

  public void setJudgeId(final String judgeId) {
    this.judgeId = judgeId;
  }

  public long getTime() {
    return time;
  }

  public void setTime(final long time) {
    this.time = time;
  }

  public String getVerdictAggregationList() {
    return verdictAggregationList;
  }

  public void setVerdictAggregationList(final String verdictAggregationList) {
    this.verdictAggregationList = verdictAggregationList;
  }

  public String getVerdictId() {
    return verdictId;
  }

  public void setVerdictId(final String verdictId) {
    this.verdictId = verdictId;
  }

  public double getVote() {
    return vote;
  }

  public void setVote(final double vote) {
    this.vote = vote;
  }

}
