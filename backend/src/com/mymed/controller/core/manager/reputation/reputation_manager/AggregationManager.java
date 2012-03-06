package com.mymed.controller.core.manager.reputation.reputation_manager;

import com.mymed.controller.core.manager.reputation.db.table.VerdictAggregation;
import com.mymed.controller.core.manager.reputation.db.table.facade.DbTableAdapter;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.model.data.reputation.MReputationBean;

public class AggregationManager {
	
	DbTableAdapter adapter;
	
	public AggregationManager(CassandraWrapper w){
        adapter = new DbTableAdapter(w);
    }

    /**
     * This method returns reputation information about a given aggregation of judgments
     * @param idAggregation the id of the aggregation of judgments
     * @return reputation information about the aggregation
     */
    public MReputationBean read(String idAggregation){
    	try{
            adapter.createTransaction();
            VerdictAggregation va = adapter.getRecordVerdictAggregationById(idAggregation);
            if (va == null) {
            	adapter.clear();
            	return new MReputationBean(0,-1);
            }
            adapter.commit();
            return new MReputationBean(va.getScore(),va.getSize());
        }
        catch(Exception e){
            e.printStackTrace();
            return null;
        }
    }
}
