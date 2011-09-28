/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputation_manager;

import com.mymed.controller.core.manager.reputation.db.table.facade.DbTableAdapter;

/**
 *
 * @author piccolo
 */
public interface ReputationAlgorithm {
    public double computeReputation(String idUser,String idApplication,boolean isProducer,DbTableAdapter adapter);
    public double computeReputation(String idAggregation, DbTableAdapter adapter);
}
