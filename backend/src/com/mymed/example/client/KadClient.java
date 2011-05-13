package com.mymed.example.client;

import java.io.File;

import com.mymed.model.core.data.dht.configuration.Config;
import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;

/**
 * Kad Client: will start a kad node
 * @author lvanni
 */
public class KadClient extends AbstractClient {

	/**
	 * 
	 * @param type
	 * 		Type of DHT
	 */
	public KadClient(ClientType type) {
		super(type);
	}

	/**
	 * MAIN - Launch a chord client
	 * @param args
	 */
	public static void main(String args[]){
		KadClient kadCli = new KadClient(ClientType.KAD);
		Config conf = new Config(new File("./conf/config.xml"));
		kadCli.launchClient(conf);
	}

}
