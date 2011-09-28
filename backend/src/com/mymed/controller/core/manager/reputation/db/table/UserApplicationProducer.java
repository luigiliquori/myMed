/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table;

/**
 *
 * @author piccolo
 */
public class UserApplicationProducer {
    private String userApplicationProducerId;
    private String userId;
    private String applicationId;
    private String verdictList_userCharging;
    private String outcomeList;
    private double score;
    private int size;

    @Override
    public boolean equals(Object obj) {
        if (obj == null) {
            return false;
        }
        if (getClass() != obj.getClass()) {
            return false;
        }
        final UserApplicationProducer other = (UserApplicationProducer) obj;
        if ((this.userApplicationProducerId == null) ? (other.userApplicationProducerId != null) : !this.userApplicationProducerId.equals(other.userApplicationProducerId)) {
            return false;
        }
        if ((this.userId == null) ? (other.userId != null) : !this.userId.equals(other.userId)) {
            return false;
        }
        if ((this.applicationId == null) ? (other.applicationId != null) : !this.applicationId.equals(other.applicationId)) {
            return false;
        }
        if ((this.verdictList_userCharging == null) ? (other.verdictList_userCharging != null) : !this.verdictList_userCharging.equals(other.verdictList_userCharging)) {
            return false;
        }
        if ((this.outcomeList == null) ? (other.outcomeList != null) : !this.outcomeList.equals(other.outcomeList)) {
            return false;
        }
        if (Double.doubleToLongBits(this.score) != Double.doubleToLongBits(other.score)) {
            return false;
        }
        if (this.size != other.size) {
            return false;
        }
        return true;
    }

    @Override
    public int hashCode() {
        int hash = 7;
        hash = 67 * hash + (this.userId != null ? this.userId.hashCode() : 0);
        hash = 67 * hash + (this.applicationId != null ? this.applicationId.hashCode() : 0);
        return hash;
    }

    @Override
    public String toString() {
        return "UserApplicationProducer{" + "userApplicationProducerId=" + userApplicationProducerId + ", userId=" + userId + ", applicationId=" + applicationId + ", verdictList_userCharging=" + verdictList_userCharging + ", outcomeList=" + outcomeList + ", score=" + score + ", size=" + size + '}';
    }

    public String getApplicationId() {
        return applicationId;
    }

    public void setApplicationId(String applicationId) {
        this.applicationId = applicationId;
    }

    public String getOutcomeList() {
        return outcomeList;
    }

    public void setOutcomeList(String outcomeList) {
        this.outcomeList = outcomeList;
    }

    public double getScore() {
        return score;
    }

    public void setScore(double score) {
        this.score = score;
    }

    public int getSize() {
        return size;
    }

    public void setSize(int size) {
        this.size = size;
    }

    public String getUserApplicationProducerId() {
        return userApplicationProducerId;
    }

    public void setUserApplicationProducerId(String userApplicationProducerId) {
        this.userApplicationProducerId = userApplicationProducerId;
    }

    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }

    public String getVerdictList_userCharging() {
        return verdictList_userCharging;
    }

    public void setVerdictList_userCharging(String verdictList_userCharging) {
        this.verdictList_userCharging = verdictList_userCharging;
    }
    
    
}
