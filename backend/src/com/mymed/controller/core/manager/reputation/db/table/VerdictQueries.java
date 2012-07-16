/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.mymed.controller.core.manager.reputation.db.table;

import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.List;

import org.apache.cassandra.thrift.ColumnOrSuperColumn;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import com.mymed.utils.MConverter;
import com.mymed.utils.TimeUuid;

/**
 * This class performs some queries to return verdicts.
 * @author peter
 */
public class VerdictQueries {

    /**
     * Returns an ArrayList of the most recent verdicts of a reputation entity
     *
     * @param repEntityId - The reputation entity
     * @param numResults - The number of verdicts to return
     * @return - Arraylist of verdicts
     * @throws InternalBackEndException
     */
    public static ArrayList<Verdict> getVerdictsOfJudged(String repEntityId, int numResults) throws InternalBackEndException {
        ArrayList<Verdict> verdicts = new ArrayList<Verdict>();
        List <ColumnOrSuperColumn> cols = TransactionManager.getInstance().getVerdictListColumns(repEntityId, numResults);
        // for each column, get the verdict
        Verdict bogus = new Verdict();  // only because method is not yet static
        for (ColumnOrSuperColumn col : cols) {
            java.util.UUID id = TimeUuid.toUUID(col.getColumn().getName());
            String verdictKey = id.toString();
            Verdict v = bogus.get(verdictKey);
            verdicts.add(v);
        }
        return verdicts;
    }

    /**
     * This function returns only the votes, not the whole verdict.  This can save
     * time if the algorithm only needs the votes.
     *
     * @param repEntityId - The reputation entity
     * @param numResults - The number of verdicts to return
     * @return - Arraylist of Double
     * @throws InternalBackEndException
     */
    public static ArrayList<Double> getVotesOfJudged(String repEntityId, int numResults) throws InternalBackEndException {
        //System.out.println("GetVotesOfJudged " + repEntityId);
        ArrayList<Double> votes = new ArrayList<Double>();
        List <ColumnOrSuperColumn> cols = TransactionManager.getInstance().getVerdictListColumns(repEntityId, numResults);
        // for each column, get the vote
        for (ColumnOrSuperColumn col : cols) {
            Double vote = MConverter.byteBufferToDouble(ByteBuffer.wrap(col.getColumn().getValue()));
            votes.add(vote);
        }
        return votes;
    }


}
