/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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
    public boolean equals(Object obj) {
        if (obj == null) {
            return false;
        }
        if (getClass() != obj.getClass()) {
            return false;
        }
        final Verdict other = (Verdict) obj;
        if ((this.verdictId == null) ? (other.verdictId != null) : !this.verdictId.equals(other.verdictId)) {
            return false;
        }
        if ((this.judgeId == null) ? (other.judgeId != null) : !this.judgeId.equals(other.judgeId)) {
            return false;
        }
        if ((this.chargedId == null) ? (other.chargedId != null) : !this.chargedId.equals(other.chargedId)) {
            return false;
        }
        if ((this.applicationId == null) ? (other.applicationId != null) : !this.applicationId.equals(other.applicationId)) {
            return false;
        }
        if (this.time != other.time) {
            return false;
        }
        if (this.isJudgeProducer != other.isJudgeProducer) {
            return false;
        }
        if ((this.verdictAggregationList == null) ? (other.verdictAggregationList != null) : !this.verdictAggregationList.equals(other.verdictAggregationList)) {
            return false;
        }
        if (Double.doubleToLongBits(this.vote) != Double.doubleToLongBits(other.vote)) {
            return false;
        }
        return true;
    }

    @Override
    public int hashCode() {
        int hash = 5;
        hash = 37 * hash + (this.judgeId != null ? this.judgeId.hashCode() : 0);
        hash = 37 * hash + (this.chargedId != null ? this.chargedId.hashCode() : 0);
        hash = 37 * hash + (this.applicationId != null ? this.applicationId.hashCode() : 0);
        hash = 37 * hash + (int) (this.time ^ (this.time >>> 32));
        return hash;
    }

    @Override
    public String toString() {
        return "Verdict{" + "verdictId=" + verdictId + ", judgeId=" + judgeId + ", chargedId=" + chargedId + ", applicationId=" + applicationId + ", time=" + time + ", isJudgeProducer=" + isJudgeProducer + ", verdictAggregationList=" + verdictAggregationList + ", vote=" + vote + '}';
    }

    public String getApplicationId() {
        return applicationId;
    }

    public void setApplicationId(String applicationId) {
        this.applicationId = applicationId;
    }

    public String getChargedId() {
        return chargedId;
    }

    public boolean getIsJudgeProducer() {
        return isJudgeProducer;
    }

    public void setIsJudgeProducer(boolean isJudgeProducer) {
        this.isJudgeProducer = isJudgeProducer;
    }

    public void setChargedId(String chargedId) {
        this.chargedId = chargedId;
    }

    public String getJudgeId() {
        return judgeId;
    }

    public void setJudgeId(String judgeId) {
        this.judgeId = judgeId;
    }

    public long getTime() {
        return time;
    }

    public void setTime(long time) {
        this.time = time;
    }

    public String getVerdictAggregationList() {
        return verdictAggregationList;
    }

    public void setVerdictAggregationList(String verdictAggregationList) {
        this.verdictAggregationList = verdictAggregationList;
    }

    public String getVerdictId() {
        return verdictId;
    }

    public void setVerdictId(String verdictId) {
        this.verdictId = verdictId;
    }

    public double getVote() {
        return vote;
    }

    public void setVote(double vote) {
        this.vote = vote;
    }
    
    
}
