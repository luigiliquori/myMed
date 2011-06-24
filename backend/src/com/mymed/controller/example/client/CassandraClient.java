package com.mymed.controller.example.client;

import java.io.File;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;

/**
 * Lightweight client for Cassandra
 * @author lvanni
 */
public class CassandraClient extends AbstractClient {

	/**
	 * 
	 * @param type
	 * 		Type of DHT
	 */
	public CassandraClient(WrapperType type) {
		super(type);
	}

	/**
	 * MAIN - Launch a chord client
	 * @param args
	 */
	public static void main(String args[]){
		CassandraClient cassandraCli = new CassandraClient(WrapperType.CASSANDRA);
		WrapperConfiguration conf = new WrapperConfiguration(new File("./conf/config.xml"));
		try {
			cassandraCli.launchClient(conf);
		} catch (InternalBackEndException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

}
