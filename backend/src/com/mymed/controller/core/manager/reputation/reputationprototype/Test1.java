package com.mymed.controller.core.manager.reputation.reputationprototype;

import java.io.File;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.globals.Constants;
import com.mymed.controller.core.manager.reputation.reputation_manager.VerdictManager;
import com.mymed.controller.core.manager.reputation.reputation_manager.ReputationManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

public class Test1 {

	public static void main(String args[]) throws InternalBackEndException{
	    if (args.length != 1) {
		System.err.println("Program exits with error: it must has one argument that is the number of insert");
		System.exit(-1);
	    }
        
	    Integer nUpgrade = 0;
	    try {
		nUpgrade = Integer.parseInt(args[0]);
	    } catch (NumberFormatException e) {
		System.err.println("Program exits with error: Input argument must be an integer");
		System.exit(-2);
	    }
	    
	    final WrapperConfiguration conf = new WrapperConfiguration(new File(Constants.CONFIGURATION_FILE_PATH));
	    
	    final String listenAddress = conf.getCassandraListenAddress();
	    final int thriftPort = conf.getThriftPort();
	    
	    System.out.println("Connection information:");
	    System.out.println("\tListen Address: " + listenAddress);
	    System.out.println("\tThrift Port   : " + thriftPort);
	    System.out.println("\n");
	    
	    final CassandraWrapper wrapper = new CassandraWrapper(listenAddress, thriftPort);
	    
	    System.out.println("Opening Cassandra connection...");
//	    wrapper.open();
	    
	    wrapper.set_keyspace(Constants.KEYSPACE);
	    
	    VerdictManager interactionManager = new VerdictManager(wrapper);
	    ReputationManager reputationManager = new ReputationManager(wrapper);
	    
	    // check if aggregation 12345 exists
	    final String aggregationId = "12345";
	    String aggid = "";
	    if (reputationManager.readAggregationReputation(aggregationId).getNoOfRatings() <=0) {
        	System.out.println("Create aggregation id=" + aggregationId);
        	aggid = interactionManager.createAggregation(aggregationId);
	    }
	    final String app = "appTest";
	    
	    for(int i=0;i<nUpgrade;++i) {
        	if (! interactionManager.updateAggregation(aggid, app, "user"+i, true,  "user"+(i+1), 0.1) ) {
		    System.err.println("updateAggregation fails (1)");
		    return;
        	}
        	
        	if (! interactionManager.updateAggregation(aggid, app, "user"+i, false, "user"+(i+1), 0.1) ) {
		    System.err.println("updateAggregation fails (2)");
		    return;	
        	}
	    }
	    
	    System.out.println("Aggregation reputation: " + reputationManager.readAggregationReputation(aggid).getReputation());
	    
	    interactionManager.deleteAggregation(aggid);
	}   
}
