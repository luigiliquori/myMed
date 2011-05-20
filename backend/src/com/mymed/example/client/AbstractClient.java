package com.mymed.example.client;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;
import com.mymed.model.core.data.dht.configuration.Config;
import com.mymed.model.core.data.dht.factory.DHTClientFactory;
import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;
import com.mymed.model.core.wrapper.IWrapper;
import com.mymed.model.core.wrapper.Wrapper;

/**
 * @author lvanni
 */
public class AbstractClient {

	/** Type of DHT */
	private ClientType type;

	/**
	 * Constructor
	 * 
	 * @param type
	 */
	public AbstractClient(ClientType type) {
		this.type = type;
	}

	/**
	 * Launch the Client
	 * @throws InternalBackEndException 
	 */
	public void launchClient(Config conf) throws InternalBackEndException {
		IWrapper wrapper = new Wrapper(type, conf);

		BufferedReader input = new BufferedReader(new InputStreamReader(
				System.in));
		while (true) {
			System.out.println("0) Status");
			System.out.println("1) Publish");
			System.out.println("2) Search");
			System.out.println("3) Quit");
			System.out.print("---> ");
			int chx;
			try {
				chx = Integer.parseInt(input.readLine().trim());
				String key;
				switch (chx) {
				case 0:
					System.out.println("\n" + wrapper + "\n");
					break;
				case 1:
					System.out.print("\nkey = ");
					key = input.readLine();
					System.out.print("value = ");
					String value = input.readLine();
					wrapper.put(key, value.getBytes("UTF8"));
					break;
				case 2:
					System.out.print("\nkey = ");
					key = input.readLine();
					String found;
					found = new String(wrapper.get(key), "UTF8");
					System.out.println("found: " + found);
					break;
				case 3:
					if (type != ClientType.CASSANDRA) {
						DHTClientFactory.destroyDHTClient(type);
						DHTClientFactory.destroyDHTClient(type);
					}
					System.exit(0);
				default:
					break;
				}
				System.out.println("\n");
			} catch (NumberFormatException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}
}
