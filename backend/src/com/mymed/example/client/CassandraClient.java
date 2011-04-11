package com.mymed.example.client;

import java.io.File;

import com.mymed.model.core.data.dht.configuration.Config;
import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;

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
	public CassandraClient(ClientType type) {
		super(type);
	}

	/**
	 * MAIN - Launch a chord client
	 * @param args
	 */
	public static void main(String args[]){
		CassandraClient cassandraCli = new CassandraClient(ClientType.CASSANDRA);
		Config conf = new Config(new File("./conf/config.xml"));
		cassandraCli.launchClient(conf);
	}

}
