/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table;

/**
 *
 * @author piccolo
 */
public class UserReputation {
    private String userReputationId;
    private String userId;
    private String verdictListId_judged_as_producers;
    private String verdictListId_judged_as_consumers;
    private String outcomeList;

    @Override
    public String toString() {
        return "UserReputation{" + "userReputationId=" + userReputationId + ", userId=" + userId + ", verdictListId_judged_as_producers=" + verdictListId_judged_as_producers + ", verdictListId_judged_as_consumers=" + verdictListId_judged_as_consumers + ", outcomeList=" + outcomeList + '}';
    }

    public String getOutcomeList() {
        return outcomeList;
    }

    public void setOutcomeList(String outcomeList) {
        this.outcomeList = outcomeList;
    }

    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }

    public String getUserReputationId() {
        return userReputationId;
    }

    public void setUserReputationId(String userReputationId) {
        this.userReputationId = userReputationId;
    }

    public String getVerdictListId_judged_as_consumers() {
        return verdictListId_judged_as_consumers;
    }

    public void setVerdictListId_judged_as_consumers(String verdictListId_judged_as_consumers) {
        this.verdictListId_judged_as_consumers = verdictListId_judged_as_consumers;
    }

    public String getVerdictListId_judged_as_producers() {
        return verdictListId_judged_as_producers;
    }

    public void setVerdictListId_judged_as_producers(String verdictListId_judged_as_producers) {
        this.verdictListId_judged_as_producers = verdictListId_judged_as_producers;
    }
    
    
}
