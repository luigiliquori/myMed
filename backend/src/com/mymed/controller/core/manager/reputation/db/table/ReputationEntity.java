/*
 * To change this template, choose Tools | Templates and open the template in
 * the editor.
 */

package com.mymed.controller.core.manager.reputation.db.table;

import java.util.ArrayList;
import java.util.Collection;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
/**
 * This class serves as a cache to record the number of verdicts assigned to a
 * uid, and the most recently calculated reputation.
 * 
 * @author peter
 */
public class ReputationEntity extends CassandraPersistable {

    private String uid; /* reputation entity id */
    private double reputation; /* most recently calculated reputation */
    private int numberOfVerdicts; /* number of verdicts used for calculation */
    /*
     * true if verdicts have been added since last calculation
     */
    private boolean dirty;

    // Has to be default constructor for 'deserialization'
    public ReputationEntity() {
        uid = "";
        reputation = 1.0;
        numberOfVerdicts = 0;
        dirty = false;
    }

    public ReputationEntity(final String id) {
        uid = id;
        reputation = 1.0;
        numberOfVerdicts = 0;
        dirty = false;
    }

    @Override
    public String getKey() {
        return getUid();
    }

    public void persist() throws InternalBackEndException {
        TransactionManager.getInstance().insertDbTableObject(this);
    }

    public ReputationEntity get(final String id) {
        System.out.println(" from " + getColumnFamilyName() + "get " + id);
        try {
            return (ReputationEntity) TransactionManager.getInstance().loadRow(getColumnFamilyName(), id);
        } catch (final InternalBackEndException ex) {
            Logger.getLogger(ReputationEntity.class.getName()).log(Level.SEVERE, "oops", ex);
            return null;
        }
    }

    // TODO: clashes with ICassandraPersistable
    private static String columnFamilyName() {
        return "ReputationEntity";
    }

    /**
     * Get ReputationEntity corresponding to id. If inexistent, creat it.
     * 
     * @param id
     *            - uid of ReputationEntity to retrieve
     * @param createIfNone
     *            - if true and not found, create.
     * @return ReputationEntity corresponding to uid. Null if not found and
     *         createIfNone is false.
     */
    public static ReputationEntity getCreating(final String id, final boolean createIfNone) {
        ReputationEntity result;
        try {
            result = (ReputationEntity) TransactionManager.getInstance().loadRow(columnFamilyName(), id);
            if ((result == null) && createIfNone) {
                result = new ReputationEntity(id);
            }
            return result;
        } catch (final InternalBackEndException ex) {
            Logger.getLogger(ReputationEntity.class.getName()).log(Level.SEVERE, "oops", ex);
            return null;
        }
    }

    public static ReputationEntity read(final String id) {
        return getCreating(id, false);
    }

    public static ReputationEntity getCreating(final String id) {
        return getCreating(id, true);
    }

    public Collection<ReputationEntity> getAll() throws InternalBackEndException {
        final List<Object> loadTable = TransactionManager.getInstance().loadTable(getColumnFamilyName());

        final ArrayList<ReputationEntity> listResult = new ArrayList<ReputationEntity>();

        for (final Object obj : loadTable) {
            listResult.add((ReputationEntity) obj);
        }

        return listResult;
    }

    @Override
    public String toString() {
        return "<" + uid + ": " + reputation + ", " + numberOfVerdicts + ">";
    }

    // Accessors

    /**
     * @return the uid
     */
    public String getUid() {
        return uid;
    }

    /**
     * @param uid
     *            the uid to set
     */
    public void setUid(final String uid) {
        this.uid = uid;
    }

    /**
     * @return the reputation
     */
    public double getReputation() {
        return reputation;
    }

    /**
     * @param reputation
     *            the reputation to set
     */
    public void setReputation(final double reputation) {
        this.reputation = reputation;
    }

    /**
     * @return the numberOfVerdicts
     */
    public int getNumberOfVerdicts() {
        return numberOfVerdicts;
    }

    /**
     * @param numberOfVerdicts
     *            the numberOfVerdicts to set
     */
    public void setNumberOfVerdicts(final int numberOfVerdicts) {
        this.numberOfVerdicts = numberOfVerdicts;
    }

    /**
     * @return the dirty
     */
    public boolean isDirty() {
        return dirty;
    }

    /**
     * @param dirty
     *            the dirty to set
     */
    public void setDirty(final boolean dirty) {
        this.dirty = dirty;
    }

}
