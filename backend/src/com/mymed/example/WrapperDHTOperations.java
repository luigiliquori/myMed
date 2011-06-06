package com.mymed.example;

import java.io.UnsupportedEncodingException;

import com.mymed.model.core.data.dht.factory.DHTClientFactory;
import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;
import com.mymed.model.core.wrapper.IWrapper;
import com.mymed.model.core.wrapper.Wrapper;

public class WrapperDHTOperations {

	public static void main(String args[]){
		// The is multi-protocols and provide clients for Chord and Kad
		try {
			// CHORD ------------------------------------------------------
			// Simple Example with a wrapper based on chord protocol.
			IWrapper chordWrapper = new Wrapper(ClientType.CHORD);
			// A Chord Node will be created and started 
			// this node is a singleton, there is only one instance by jvm
			// This node will join an existing Chord ring (if it exists)
			// The tracker is managed by Cassandra
			// To Create a Ring Launch several ChordClient

			// PUT Operation
			chordWrapper.put("name", "Mr Chord".getBytes("UTF8"));

			// GET Operation
			System.out.println("name = " + chordWrapper.get("name"));

			// KAD -------------------------------------------------------
			// the same with a wrapper based on kad protocol
			IWrapper kadWrapper = new Wrapper(ClientType.KAD);
			
			// PUT Operation
			kadWrapper.put("name", "Mr Kad".getBytes("UTF8"));
			
			// GET Operation
			System.out.println("name = " + kadWrapper.get("name"));

			// you must explicit call destroyDHTClient to shutdown the running node
			DHTClientFactory.destroyDHTClient(ClientType.CHORD);
			DHTClientFactory.destroyDHTClient(ClientType.KAD);
			
		} catch (Exception e) {
			e.printStackTrace();
		}
		System.exit(0);
	}
}
