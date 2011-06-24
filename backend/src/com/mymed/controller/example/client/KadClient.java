package com.mymed.controller.example.client;

import java.io.File;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;

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
	public KadClient(WrapperType type) {
		super(type);
	}

	/**
	 * MAIN - Launch a chord client
	 * @param args
	 */
	public static void main(String args[]){
		KadClient kadCli = new KadClient(WrapperType.KAD);
		WrapperConfiguration conf = new WrapperConfiguration(new File("./conf/config.xml"));
		try {
			kadCli.launchClient(conf);
		} catch (InternalBackEndException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

}
