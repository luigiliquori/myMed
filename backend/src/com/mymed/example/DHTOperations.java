package com.mymed.example;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

import com.mymed.model.core.data.dht.DHTClientFactory;
import com.mymed.model.core.data.dht.IDHTClient.ClientType;
import com.mymed.model.core.wrapper.IWrapper;
import com.mymed.model.core.wrapper.Wrapper;
import com.mymed.model.core.wrapper.exception.WrapperException;

/**
 * This class is an example of the common put/get DHT operation
 * @author lvanni
 *
 */
public class DHTOperations {

	public static void main(String args[]){
		
//		// CHORD ------------------------------------------------------
//		// Simple Example with a wrapper based on chord protocol.
//		// the wrapper will create and start a new node
//		// this node is a singleton, there is only one instance by jvm
//		IWrapper chordWrapper = new Wrapper(ClientType.CHORD);
//		// PUT Operation
//		chordWrapper.put("id", "1234");
//		chordWrapper.put("name", "Mr Chord");
//		// Get Operation
//		System.out.println("id = " + chordWrapper.get("id"));
//		System.out.println("name = " + chordWrapper.get("name"));
//	
//		// KAD -------------------------------------------------------
//		// the same with a wrapper based on kad protocol
//		IWrapper kadWrapper = new Wrapper(ClientType.KAD);
//		// PUT Operation
//		kadWrapper.put("id", "5678");
//		kadWrapper.put("name", "Mr Kad");
//		// Get Operation
//		System.out.println("\nid = " + kadWrapper.get("id"));
//		System.out.println("name = " + kadWrapper.get("name"));
		
		// CASSANDRA -------------------------------------------------
		// of course Cassandra provide the same operation
		// Cassandra is the default choice of the wrapper
		IWrapper cassandraWrapper = new Wrapper(); // == new Wrapper(ClientType.CASSANDRA)
		// PUT Operation
		cassandraWrapper.put("id", "91011");
		cassandraWrapper.put("name", "Miss Cassandra");
		// Get Operation
		System.out.println("\nid = " + cassandraWrapper.get("id"));
		System.out.println("name = " + cassandraWrapper.get("name"));

		// Cassandra provide more flexibility with the data structure
		Map<String, byte[]> arguments = new HashMap<String, byte[]>();
		try {
			arguments.put("id", "91011".getBytes("UTF8"));
			arguments.put("name", "Miss Cassandra".getBytes("UTF8"));
			cassandraWrapper.insertInto("Users", "id", arguments);
			Map<byte[], byte[]> values = cassandraWrapper.selectAll("Users", "91011");
			System.out.println("getSlice:");
			System.out.println(values.isEmpty());
			for(byte[] s : values.keySet()){
				System.out.println(new String(s));
			}
			System.out.println(values.get("id"));
		} catch (UnsupportedEncodingException e1) {
			e1.printStackTrace();
		} catch (WrapperException e) {
			e.printStackTrace();
		} catch (InvalidRequestException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (NotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (UnavailableException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (TimedOutException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (TException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		// you must explicit call destroyDHTClient to shutdown the running node
		DHTClientFactory.destroyDHTClient(ClientType.CHORD);
		DHTClientFactory.destroyDHTClient(ClientType.KAD);
		
		System.exit(0);
	}
}
