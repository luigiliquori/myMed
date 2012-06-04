package com.mymed.controller.core.manager.reputation.db.table;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import java.util.logging.Level;
import java.util.logging.Logger;
/**
 * 
 * A Verdict encapsulated the information of a vote.
 */
public class Verdict extends CassandraPersistable {

    private String judgeId;     /** id of the vote giver */
    private String chargedId;   /** id of the vote reciever */
    private double vote;        /** the vote */
    private String comment;     /** Any comment on the vote */
    private java.util.UUID timeUuid;  /** Time-based UID to enable chronological ordering */
    private double weight;      /** A verdict may have a weight that algorithms may use.  Default is 1.0 */

    public Verdict(){
    }

    public Verdict(String judgeId, String chargedId, double vote) {
        this.judgeId = judgeId;
        this.chargedId = chargedId;
        this.vote = vote;
        timeUuid = com.mymed.utils.TimeUuid.getTimeUUID();
        weight = 1.0;
    }

    public Verdict(String judgeId, String chargedId, double vote, double weight) {
        this.judgeId = judgeId;
        this.chargedId = chargedId;
        this.vote = vote;
        timeUuid = com.mymed.utils.TimeUuid.getTimeUUID();
        this.weight = weight;
    }

    /**
     * Persist the object to a Cassandra ColumnFamily
     * @throws InternalBackEndException
     */
    public void persist() throws InternalBackEndException {
        TransactionManager.getInstance().insertDbTableObject(this);
    }

    // TODO make static
    /**
     * Given a uid, find the Verdict it corresponds to in the ColumnFamily,
     * recreate a Verdict object, and return it.
     * @param uuidString
     * @return
     */
    public Verdict get(String uuidString) {
        Verdict result;
        try {
            result = (Verdict) TransactionManager.getInstance().loadRow(getColumnFamilyName(),uuidString);
            // have to fill in TimeUuid
            result.timeUuid = java.util.UUID.fromString(uuidString);
            return result;
        } catch (InternalBackEndException ex) {
            Logger.getLogger(ReputationEntity.class.getName()).log(Level.SEVERE, "oops", ex);
            return null;
        }
    }


    @Override
    public boolean equals(final Object obj) {
        if (obj == null) {
            return false;
        }
        if (getClass() != obj.getClass()) {
            return false;
        }
        final Verdict other = (Verdict) obj;
        if (judgeId == null ? other.judgeId != null : !judgeId.equals(other.judgeId)) {
            return false;
        }
        if (chargedId == null ? other.chargedId != null : !chargedId.equals(other.chargedId)) {
            return false;
        }
//        if (verdictAggregationList == null ? other.verdictAggregationList != null : !verdictAggregationList.equals(other.verdictAggregationList)) {
//            return false;
//        }
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
        return hash;
    }

    @Override
    public String toString() {
        return "Verdict{" + "judgeId=" + judgeId + ", chargedId=" + chargedId + ", vote=" + vote
                + ", key=" + getKey()
                + "commment=" + comment + '}';
    }

    public String getChargedId() {
        return chargedId;
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

    public double getVote() {
        return vote;
    }

    public void setVote(final double vote) {
        this.vote = vote;
    }

    @Override
    public String getColumnFamilyName() {
        return "Verdict";
    }

    @Override
    public String getKey() {
        return timeUuid.toString();
    }

    // for backward compatibility
    // TODO: either get rid of it, or change getKey
    public String getVerdictId() {
        return getKey();
    }

    /**
     * @return the timeUuid
     */
    public java.util.UUID getTimeUuid() {
        return timeUuid;
    }

    /**
     * @param timeUuid the timeUuid to set
     */
    public void setTimeUuid(java.util.UUID timeUuid) {
        this.timeUuid = timeUuid;
    }

    /**
     * @return the comment
     */
    public String getComment() {
        return comment;
    }

    /**
     * @param comment the comment to set
     */
    public void setComment(String comment) {
        this.comment = comment;
    }

    /**
     * @return the weight
     */
    public double getWeight() {
        return weight;
    }

    /**
     * @param weight the weight to set
     */
    public void setWeight(double weight) {
        this.weight = weight;
    }
}
