/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table;

/**
 *
 * @author piccolo
 */
public class Outcome {
    private String outcomeId;
    private int start;
    private int end;
    private double score;

    @Override
    public String toString() {
        return "Outcome{" + "outcomeId=" + outcomeId + ", start=" + start + ", end=" + end + ", score=" + score + '}';
    }

    public int getEnd() {
        return end;
    }

    public void setEnd(int end) {
        this.end = end;
    }

    public String getOutcomeId() {
        return outcomeId;
    }

    public void setOutcomeId(String outcomeId) {
        this.outcomeId = outcomeId;
    }

    public double getScore() {
        return score;
    }

    public void setScore(double score) {
        this.score = score;
    }

    public int getStart() {
        return start;
    }

    public void setStart(int start) {
        this.start = start;
    }
}
