package com.mymed.example.client;

import java.io.File;

import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;
import com.mymed.model.core.data.dht.configuration.Config;
import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;

/**
 * Chord Client: will start a chord node
 * @author lvanni
 */
public class ChordClient extends AbstractClient {

	/**
	 * 
	 * @param type
	 * 		Type of DHT
	 */
	public ChordClient(ClientType type) {
		super(type);
	}

	/**
	 * MAIN - Launch a chord client
	 * @param args
	 */
	public static void main(String args[]){
		ChordClient chordCli = new ChordClient(ClientType.CHORD);
		Config conf = new Config(new File("./conf/config.xml"));
		try {
			chordCli.launchClient(conf);
		} catch (InternalBackEndException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

}
