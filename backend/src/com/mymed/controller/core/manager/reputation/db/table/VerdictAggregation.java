/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table;

/**
 *
 * @author piccolo
 */
public class VerdictAggregation {
    private String verdictAggregationId;
    private String applicationId;
    private String verdictListId;
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
        final VerdictAggregation other = (VerdictAggregation) obj;
        if ((this.verdictAggregationId == null) ? (other.verdictAggregationId != null && !other.verdictAggregationId.equals("")) : !this.verdictAggregationId.equals(other.verdictAggregationId)) {
	        return false;
        }
        if ((this.applicationId == null) ? (other.applicationId != null && !other.applicationId.equals("")) : !this.applicationId.equals(other.applicationId)) {
	        return false;
        }
        if ((this.verdictListId == null) ? (other.verdictListId != null && !other.verdictListId.equals("")) : !this.verdictListId.equals(other.verdictListId)) {
	        return false;
        }
        if ((this.outcomeList == null) ? (other.outcomeList != null && !other.outcomeList.equals("")) : !this.outcomeList.equals(other.outcomeList)) {
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
        int hash = 5;
        hash = 37 * hash + (this.verdictAggregationId != null ? this.verdictAggregationId.hashCode() : 0);
        return hash;
    }

    @Override
    public String toString() {
        return "VerdictAggregation{" + "verdictAggregationId=" + verdictAggregationId + ", applicationId=" + applicationId + ", verdictListId=" + verdictListId + ", outcomeList=" + outcomeList + ", score=" + score + ", size=" + size + '}';
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

    public String getVerdictAggregationId() {
        return verdictAggregationId;
    }

    public void setVerdictAggregationId(String verdictAggregationId) {
        this.verdictAggregationId = verdictAggregationId;
    }

    public String getVerdictListId() {
        return verdictListId;
    }

    public void setVerdictListId(String verdictListId) {
        this.verdictListId = verdictListId;
    }
    
}
