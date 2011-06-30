package com.mymed.controller.example.client;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.IStorageManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.factory.DHTWrapperFactory;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;

/**
 * @author lvanni
 */
public class AbstractClient {

	/** Type of DHT */
	private WrapperType type;

	/**
	 * Constructor
	 * 
	 * @param type
	 */
	public AbstractClient(WrapperType type) {
		this.type = type;
	}

	/**
	 * Launch the Client
	 * @throws InternalBackEndException 
	 */
	public void launchClient(WrapperConfiguration conf) throws InternalBackEndException {
		IStorageManager storageManager = new StorageManager(type, conf);

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
					System.out.println("\n" + storageManager + "\n");
					break;
				case 1:
					System.out.print("\nkey = ");
					key = input.readLine();
					System.out.print("value = ");
					String value = input.readLine();
					storageManager.put(key, value.getBytes("UTF8"));
					break;
				case 2:
					System.out.print("\nkey = ");
					key = input.readLine();
					String found;
					found = new String(storageManager.get(key), "UTF8");
					System.out.println("found: " + found);
					break;
				case 3:
					if (type != WrapperType.CASSANDRA) {
						DHTWrapperFactory.destroyDHTWrapper(type);
						DHTWrapperFactory.destroyDHTWrapper(type);
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
			} catch (IOBackEndException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}
}
