/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.table.facade;
import java.util.ArrayList;
import java.util.Collection;
import java.util.List;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.facade.CassandraDescTable;
import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
import com.mymed.controller.core.manager.reputation.db.table.VerdictAggregation;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;


/**
 *
 * @author piccolo
 */
public class VerdictAggregationFacade {
	
	 TransactionManager transManager;
	    
	 public VerdictAggregationFacade(CassandraWrapper w){
	    transManager = TransactionManager.getNewInstance(w);
	 }
	
	
    /**
     *   it returns the collection of all entries of the table VerdictAggregation.  
     * In an SQL-like sintax, it returns the result of the query
     * 
     * SELECT * FROM ComplexInteraction;
     * @return 
     */
    public Collection<VerdictAggregation> getAllRecords() throws InternalBackEndException{
    	
    	List<Object> loadTable = transManager.loadTable("VerdictAggregation");
    	          
    	ArrayList<VerdictAggregation> listResult = new ArrayList<VerdictAggregation>();
    	            
    	for(Object obj : loadTable){
    	   listResult.add(((VerdictAggregation) obj));
    	}
    	            
    	return listResult;    
    }

    /**
     * this methods persists the object given in input in the database 
     * (he could do an INSERT statement or an UPDATE statement depending on the 
     * fact that the object given in input has a new id or not). It throws an 
     * Exception if the persist operation fails. If it inserts a 
     * new item, then it updates the corresponding primary key
     */
    public void persist(VerdictAggregation va) throws PersistException{
             CassandraDescTable descTable = CassandraDescTable.getNewInstance();
             
             //generating primary key
             String primaryKey = descTable.generateKeyForColumnFamily(va);
             va.setVerdictAggregationId(primaryKey);
                      
             //inserting the new object into the db
             transManager.insertDbTableObject(va);            
  	
    }
    
    /**
     * this method adds the verdict object to the verdict aggregation whose 
     * object is in input. 
     * @param va verdict aggregation where the new judgment will be added 
     * @param v the Verdict object to be added to the aggregation
     */
    public void persist(VerdictAggregation va, Verdict v) {
    		CassandraDescTable descTable = CassandraDescTable.getNewInstance();
    		
    		transManager.insertIntoList("TimeOrderVerdictList", va.getVerdictListId(),
                     v.getVerdictId());
    		transManager.insertIntoList("VerdictAggregationList", v.getVerdictAggregationList() ,
                    va.getVerdictListId());
    }
    
    /**
     * this method delete a previously created VerdictAggregation
     * @param idAggregation  
     * @throws PersistException if the writing operation fails
     */
    public void deleteAggregation(String idAggreagation) throws InternalBackEndException{
    		VerdictAggregation va = (VerdictAggregation)transManager.loadRow("VerdictAggregation", idAggreagation);
    	   	 
    	   	List<String> keys = transManager.readSuperColummFamily("TimeOrderVerdictList", va.getVerdictListId());
    	   	transManager.deleteRow("TimeOrderVerdictList", va.getVerdictListId());
    	   	transManager.deleteRow("VerdictAggregation", idAggreagation);
           	
    }
    
   /**
     * it returns the entry of the table VerdictAggregation having the specified id. 
     * In an SQL-like syntax, it returns the result of the query
     * 
     * SELECT * FROM VerdictAggregation WHERE id = $id;
     * @param id
     * @return 
     */
    public VerdictAggregation getRecordById(String idAggregation) throws InternalBackEndException{
          VerdictAggregation result = (VerdictAggregation)transManager.loadRow("VerdictAggregation", idAggregation);
          return result;
    }
    
    /**
     * it returns the list of all aggregations to which a given verdict belongs
     * @param idVerdict
     * @return 
     */
    public Collection<VerdictAggregation> getListOfAggregationByVerdict(String idVerdict) throws InternalBackEndException{
       	 
       	 Verdict v = (Verdict)transManager.loadRow("Verdict", idVerdict);
       	 
       	 List<String> keys = transManager.readSuperColummFamily("VerdictAggregationList", v.getVerdictAggregationList());
       	 List<Object> objResults = transManager.getListOfObjectFromListOfKeys(keys, "VerdictAggregation");

       	 ArrayList<VerdictAggregation> results = new ArrayList<VerdictAggregation>();

       	 for(int i = objResults.size() - 1; i>= 0 ;i--){
       		 results.add((VerdictAggregation) objResults.get(i));
       	 }
       	 return results;
    }
}
