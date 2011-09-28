/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputationprototype;

import java.io.File;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.globals.Constants;
import com.mymed.controller.core.manager.reputation.reputation_manager.InteractionManager;
import com.mymed.controller.core.manager.reputation.reputation_manager.ReputationManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 *
 * @author piccolo
 */
public class Test2 {
    
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
//        wrapper.open();

        wrapper.set_keyspace(Constants.KEYSPACE);

        InteractionManager interactionManager = new InteractionManager(wrapper);
        ReputationManager reputationManager = new ReputationManager(wrapper);
        
        interactionManager.updateReputation("app1", "utente1", true, "utente2", 0.95);
        
     }
}
