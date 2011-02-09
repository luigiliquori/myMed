package edu.lognet.experiments.networks2010.synapse;

import edu.lognet.core.synapse.AbstractSynapse;

public class Synapse extends AbstractSynapse{

	// /////////////////////////////////////////// //
	//                 CONSTRUCTOR                 //
	// /////////////////////////////////////////// //
	public Synapse(String ip, int port){
		super(ip, port, "synapse");
	}
}
