package com.mymed.controller.example.client;

import java.io.File;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;

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
	public ChordClient(WrapperType type) {
		super(type);
	}

	/**
	 * MAIN - Launch a chord client
	 * @param args
	 */
	public static void main(String args[]){
		ChordClient chordCli = new ChordClient(WrapperType.CHORD);
		WrapperConfiguration conf = new WrapperConfiguration(new File("./conf/config.xml"));
		try {
			chordCli.launchClient(conf);
		} catch (InternalBackEndException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

}
