/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputation_manager;

import java.io.File;
import java.util.ArrayList;
import java.util.Collection;
import java.util.logging.Level;
import java.util.logging.Logger;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationProducer;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
import com.mymed.controller.core.manager.reputation.db.table.VerdictAggregation;
import com.mymed.controller.core.manager.reputation.db.table.facade.DbTableAdapter;
import com.mymed.controller.core.manager.reputation.db.table.facade.PersistException;
import com.mymed.controller.core.manager.reputation.globals.Constants;
import com.mymed.controller.core.manager.reputation.reputation_manager.ReputationManager.ReputationObject;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 * This class contains methods allowing the application level to store feedbacks 
 * concerning an atomic interaction between producer and consumer. 
 * @author piccolo
 */
public class InteractionManager {
    
    private DbTableAdapter adapter;
    private ReputationAlgorithm reputationAlgorithm;
    private ReputationUpdaterFactory reputationUpdaterFactory;
        
    public InteractionManager(CassandraWrapper w){
        adapter = new DbTableAdapter(w);
        reputationAlgorithm = new AverageReputationAlgorithm();
        reputationUpdaterFactory = new ReputationUpdaterFactory(w, reputationAlgorithm);
    }
    
    /**
     * This method causes the update of a reputation of a given user (the charged) caused
     * by the submission of a new feedback by an other user (the judge)
     * @param idApp the id of the used application
     * @param idJudge the id of the user playing the role of judge
     * @param isJudgeProducer a flag telling us whether the judge is a producer of a consumer
     * @param idCharged the id of the user playing the role of charged
     * @param vote the provided feedback
     * @return true if the update operation exits with success, false otherwise
     */
    public boolean updateReputation(final String idApp, String idJudge,final boolean isJudgeProducer, final String idCharged, double vote){
        //starting a new transaction
        adapter.createTransaction();
        
        //creating a new verdict with accociated data
        Verdict v = new Verdict();
        v.setApplicationId(idApp);
        v.setJudgeId(idJudge);
        v.setChargedId(idCharged);
        v.setTime(System.currentTimeMillis());
        v.setVote(vote);
        v.setIsJudgeProducer(isJudgeProducer);
        
        try{
            // checking if the user being the judge and the user being the charged have been created in
            // the table UserApplicationProducer and UserApplicationConsumer
            UserApplicationConsumer c = null;
            UserApplicationProducer p = null;
            if(isJudgeProducer){
                p = adapter.getRecordByIdUserApplicationProducer(idJudge, idApp);
                c = adapter.getRecordUserApplicationConsumerById(idCharged, idApp);
            }
            else{
                p = adapter.getRecordByIdUserApplicationProducer(idCharged, idApp);
                c = adapter.getRecordUserApplicationConsumerById(idJudge, idApp);
            }
            if(p == null){
                    p = new UserApplicationProducer();
                    p.setUserId(isJudgeProducer? idJudge : idCharged);
                    p.setApplicationId(idApp);
                    adapter.persistUserApplicationProducer(p); 
            }
            if(c == null){
                    c = new UserApplicationConsumer();
                    c.setUserId(isJudgeProducer? idCharged : idJudge);
                    c.setApplicationId(idApp);
                    adapter.persistUserApplicationConsumer(c);
            }
            //persisting the newly created verdict
            adapter.persistVerdict(v);

            adapter.commit();
            
            //TODO: da ottimizzare
            Thread t = new Thread(reputationUpdaterFactory.
                    createNewReputationUpdater(idCharged, idApp, isJudgeProducer));
            t.start();
        }
        catch (PersistException ex) {
            Logger.getLogger(InteractionManager.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }        
        catch(InternalBackEndException ex){
            Logger.getLogger(InteractionManager.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }
            
        
        return true;
    }
    
    /**
     * This method creates an empty aggregation of judgments
     * @param id a value allowing us to identify uniquely the aggregation
     * @return the id of the newly created aggregation 
     */
    public String createAggregation(String id) {
    	
    	String idAggregation = id + String.valueOf(System.currentTimeMillis());
    	
    	VerdictAggregation va = new VerdictAggregation();
        //we use the aggregation id to refer to the list of aggregations  
    	va.setVerdictListId(idAggregation);
    	va.setSize(0);
    	va.setScore(1);
    	va.setApplicationId("");
    	
    	//starting a new transaction
        adapter.createTransaction();
        try{
        	//persisting the newly created empty aggregation
            adapter.persistVerdictAggregation(va);
            adapter.commit();
            return idAggregation;
        }
        catch (PersistException ex) {
            Logger.getLogger(InteractionManager.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (InternalBackEndException ex) {
            Logger.getLogger(InteractionManager.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }
    
    /**
     * This method deletes an aggregation of judgments (note that it does not delete
     * the judgments being part of the aggregation)
     * @param idAggregation  the id of the aggregation to be deleted
     */
    public boolean deleteAggregation(String idAggregation){
        try {
            adapter.createTransaction();	

        adapter.deleteAggregation(idAggregation);
        adapter.commit();
        return true;
        } catch (InternalBackEndException ex) {
            Logger.getLogger(InteractionManager.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }

    }
    
    /**
     * This methods creates a new judgment given by a judge about a given charged and
     * adds this judgment to an already created aggregation
     * @param idAggregation the id of aggregation where the new judgment will be added
     * @param idApp the id of used application
     * @param idJudge the id of the user being the judge
     * @param isJudgeProducer a flag telling us whether the judge is a producer or a consumer
     * @param idCharged the id of the used being the charged
     * @param vote the provided feedback
     * @return true if the update operation exits with success, false otherwise
     */
    public boolean updateAggregation(String idAggregation, String idApp, String idJudge,boolean isJudgeProducer, String idCharged, double vote){
    	//starting a new transaction
        adapter.createTransaction();
        
        //creating a new verdict with associated data
        Verdict v = new Verdict();
        v.setApplicationId(idApp);
        v.setJudgeId(idJudge);
        v.setChargedId(idCharged);
        v.setTime(System.currentTimeMillis());
        v.setVote(vote);
        v.setIsJudgeProducer(isJudgeProducer);
        
        try{
            // checking if the user being the judge and the user being the charged have been created in
            // the table UserApplicationProducer and UserApplicationConsumer
            UserApplicationConsumer c = null;
            UserApplicationProducer p = null;
            if(isJudgeProducer){
                p = adapter.getRecordByIdUserApplicationProducer(idJudge, idApp);
                c = adapter.getRecordUserApplicationConsumerById(idCharged, idApp);
            }
            else{
                p = adapter.getRecordByIdUserApplicationProducer(idCharged, idApp);
                c = adapter.getRecordUserApplicationConsumerById(idJudge, idApp);
            }
            if(p == null){
                    p = new UserApplicationProducer();
                    p.setUserId(isJudgeProducer? idJudge : idCharged);
                    p.setApplicationId(idApp);
                    adapter.persistUserApplicationProducer(p);
            }
            if(c == null){
                	c = new UserApplicationConsumer();
            		c.setUserId(isJudgeProducer? idCharged : idJudge);
                    c.setApplicationId(idApp);
                    adapter.persistUserApplicationConsumer(c);
            }

            //persisting the newly created verdict
            adapter.persistVerdict(v);
            
            VerdictAggregation va = adapter.getRecordVerdictAggregationById(idAggregation);
            
            if (va == null) {
            	adapter.clear();
            	return false;
            }
            
            // the following method updates the VerdictAggregationList and the AuxOrderVerdictList tables
            adapter.persistVerdictToAggregation(va, v);
            
            adapter.commit(); // FIX remove this commit
          
            adapter.createTransaction();
            double newScore = reputationAlgorithm.computeReputation(idAggregation, adapter);
            va.setScore(newScore);
            va.setSize(va.getSize()+1);
            adapter.persistVerdictAggregation(va);
            adapter.commit();            
        }   
        catch (PersistException ex) {
            Logger.getLogger(InteractionManager.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }        catch (InternalBackEndException ex) {
            Logger.getLogger(InteractionManager.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }
        return true;
    }
    
    public static void main(String a[]) throws InternalBackEndException{
        final WrapperConfiguration conf = new WrapperConfiguration(new File(Constants.CONFIGURATION_FILE_PATH));
        
        final String listenAddress = conf.getCassandraListenAddress();
        final int thriftPort = conf.getThriftPort();

        System.out.println("Connection information:");
        System.out.println("\tListen Address: " + listenAddress);
        System.out.println("\tThrift Port   : " + thriftPort);
        System.out.println("\n");

        final CassandraWrapper wrapper = new CassandraWrapper(listenAddress, thriftPort);
        
        System.out.println("Opening Cassandra connection...");
        
//        wrapper.set_keyspace(Constants.KEYSPACE);
        
        InteractionManager interactionManager = new InteractionManager(wrapper);
        ReputationManager reputationManager = new ReputationManager(wrapper);
        
        ReputationObject readReputation = reputationManager.readReputation("6utent2", "app1", false);
        System.out.println("Reputazione iniziale u2: " + readReputation.getReputation());
        System.out.println("numero di giudizi: " + readReputation.getNoOfRatings());
   
              
        interactionManager.updateReputation("app1", "utente1", true, "6utent2", 0.7);        
        System.out.println("Reputazione dopo il primo voto: " + reputationManager.readReputation("6utent2", "app1", false).getReputation());
        interactionManager.updateReputation("app1", "utente3", true, "6utent2", 0.5);
        
        System.out.println("Reputazione dopo il secondo voto: " + reputationManager.readReputation("6utent2", "app1", false).getReputation());
      
        
        String aggid1 = interactionManager.createAggregation("12345");
        
        System.out.println("Aggregation " + aggid1 + " created");
        interactionManager.updateAggregation(aggid1, "app1", "utente1", true, "6utent2", 0.4);
        System.out.println("Aggregation score dopo il primo verdetto: " + reputationManager.readAggregationReputation(aggid1).getReputation());
        interactionManager.updateAggregation(aggid1, "app1", "utente2", true, "6utent2", 0.6);
        System.out.println("Aggregation score dopo il secondo verdetto: " + reputationManager.readAggregationReputation(aggid1).getReputation());

        interactionManager.updateAggregation(aggid1, "app1", "utente2", true, "utente3", 0.5);
        System.out.println("Aggregation score dopo il secondo verdetto: " + reputationManager.readAggregationReputation(aggid1).getReputation());
        
        String aggid2 = interactionManager.createAggregation("22345");
        System.out.println("Aggregation " + aggid2 + " created");
        interactionManager.updateAggregation(aggid2, "app2", "utente1", true, "utente2", 0.2);
        System.out.println("Aggregation score dopo il primo verdetto: " + reputationManager.readAggregationReputation(aggid2).getReputation());
        
        
        try {
        	DbTableAdapter db = new DbTableAdapter(wrapper);
        	ArrayList<Verdict> vcoll = new ArrayList(db.getAllRecordsVerdict());
        	
        	for(int i=0; i<vcoll.size();++i) {
        	       	
        		Verdict v = vcoll.get(i);
        	
        		Collection<VerdictAggregation> coll = db.getListOfAggregationByVerdict(v.getVerdictId());
        		if (coll.isEmpty()) {
        			System.out.println("No element found for verdict: " + v.getVerdictId() + " producer= " + v.getJudgeId() + " consumer= "+ v.getChargedId());
        		} else {
        			System.out.println("Verdict: " + v.getVerdictId() + " is part of the following aggregations ");
        		}
        		for(VerdictAggregation va : coll){
        			System.out.println("Aggregationid: " + va.getVerdictAggregationId());
        		}	
        	}
        } 
        catch(Exception e){
            e.printStackTrace();
        }
        
        interactionManager.deleteAggregation(aggid1);
        if (!interactionManager.updateAggregation(aggid1, "app1", "utente1", true, "6utent2", 0.4)) {
        	System.out.println("updateAggregation: aggregation pointed by "+ aggid1 + " does not exist");
        	
        }
        interactionManager.deleteAggregation(aggid2);
                     
        System.out.println("Reputazione dopo il secondo voto: " + reputationManager.readReputation("6utent2", "app1", false).getReputation());
               
        //System.out.println(new SimpleReputationEngine(new UserApplicationConsumerFacade(wrapper),
        //        new UserApplicationProducerFacade(wrapper), new VerdictFacade(wrapper)).readReputation("user2", "app1", false).getReputation());
    }
}
