/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table;

import org.apache.commons.lang.builder.EqualsBuilder;
import org.apache.commons.lang.builder.HashCodeBuilder;

/**
 *
 * @author piccolo
 */
public class ApplicationReputation {
    private String applicationReputationId;
    private String applicationId;
    private String verdictList_producers;
    private String verdictList_consumers;
    private double score;
    private int size;
    private String outcomeList;
    private int engineTuningParameter1;
    private int engineTuningParameter2;
    private int engineTuningParameter3;
    private int maxVerdictNumber;

    @Override
    public String toString() {
        return "ApplicationReputation{" + "applicationReputationId=" + applicationReputationId + ", applicationId=" + applicationId + ", verdictList_producers=" + verdictList_producers + ", verdictList_consumers=" + verdictList_consumers + ", score=" + score + ", size=" + size + ", outcomeList=" + outcomeList + ", engineTuningParameter1=" + engineTuningParameter1 + ", engineTuningParameter2=" + engineTuningParameter2 + ", engineTuningParameter3=" + engineTuningParameter3 + ", maxVerdictNumber=" + maxVerdictNumber + '}';
    }

    public String getApplicationId() {
        return applicationId;
    }

    public void setApplicationId(String applicationId) {
        this.applicationId = applicationId;
    }

    public String getApplicationReputationId() {
        return applicationReputationId;
    }

    public void setApplicationReputationId(String applicationReputationId) {
        this.applicationReputationId = applicationReputationId;
    }

    public int getEngineTuningParameter1() {
        return engineTuningParameter1;
    }

    public void setEngineTuningParameter1(int engineTuningParameter1) {
        this.engineTuningParameter1 = engineTuningParameter1;
    }

    public int getEngineTuningParameter2() {
        return engineTuningParameter2;
    }

    public void setEngineTuningParameter2(int engineTuningParameter2) {
        this.engineTuningParameter2 = engineTuningParameter2;
    }

    public int getEngineTuningParameter3() {
        return engineTuningParameter3;
    }

    public void setEngineTuningParameter3(int engineTuningParameter3) {
        this.engineTuningParameter3 = engineTuningParameter3;
    }

    public int getMaxVerdictNumber() {
        return maxVerdictNumber;
    }

    public void setMaxVerdictNumber(int maxVerdictNumber) {
        this.maxVerdictNumber = maxVerdictNumber;
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

    public String getVerdictList_consumers() {
        return verdictList_consumers;
    }

    public void setVerdictList_consumers(String verdictList_consumers) {
        this.verdictList_consumers = verdictList_consumers;
    }

    public String getVerdictList_producers() {
        return verdictList_producers;
    }

    public void setVerdictList_producers(String verdictList_producers) {
        this.verdictList_producers = verdictList_producers;
    }

    @Override
    public boolean equals(Object o) {
        ApplicationReputation ao = (ApplicationReputation)o;
        
        return EqualsBuilder.reflectionEquals(this, ao, true);
    }

    @Override
    public int hashCode() {
        return new HashCodeBuilder().append(applicationId).hashCode();
    }
    
}
