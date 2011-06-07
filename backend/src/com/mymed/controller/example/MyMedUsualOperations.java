package com.mymed.controller.example;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.manager.IStorageManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;

/**
 * This class is an example of the common put/get DHT operation
 * @author lvanni
 *
 */
public class MyMedUsualOperations {

	/* CASSANDRA STRUCTURE:

	  Keyspace
	  -----------------------------------------------------
	  | columnFamily					  				  |
	  | ----------------------------------- 			  |
	  | | 			| columnName -> value | 			  |
	  | |	key		| columnName -> value | 			  |
	  | |			| columnName -> value | 			  |
	  | |-----------|---------------------|				  |
	  | | 			| columnName -> value |				  |
	  | |	key		| columnName -> value |				  |
	  | |			| columnName -> value |				  | 
	  | -----------------------------------				  |
	  |								  	 			      |
	  | SuperColumnFamily				  				  |
	  | ------------------------------------------------- |
	  | | 			| columnFamily					    | |
	  |	|           | --------------------------------- | |
	  | |			| |			| columnName -> value | | |
	  | |			| |   key	| columnName -> value | | |
	  | |			| |			| columnName -> value | | |
	  | |			| |---------|---------------------| | |
	  | |			| |			| columnName -> value | | |
	  | |			| |	  key	| columnName -> value | | |
	  | |			| |			| columnName -> value | | |
	  | |			| --------------------------------- | |
	  | | superKey	| columnFamily					    | |
	  |	|           | --------------------------------- | |
	  | |			| |			| columnName -> value | | |
	  | |			| |   key	| columnName -> value | | |
	  | |			| |			| columnName -> value | | |
	  | |			| |---------|---------------------| | |
	  | |			| |			| columnName -> value | | |
	  | |			| |	  key	| columnName -> value | | |
	  | |			| |			| columnName -> value | | |
	  | |			| --------------------------------- | |
	  | ------------------------------------------------- |
	  -----------------------------------------------------

	 */

	public static void main(String args[]){

		try {
			// Cassandra protocol is the default choice of the wrapper
			// But the wrapper can be use with Chord and Kad also:
			// see WrapperDHTOperations
			WrapperConfiguration config = new WrapperConfiguration(new File("/local/mymed/backend/conf/config.xml"));
			
			IStorageManager storageManager = new StorageManager(config); // == new Wrapper(ClientType.CASSANDRA)

			// Cassandra is DHT based, the wrapper provide the usual DHT operations:
			// PUT Operation
			storageManager.put("id", "12345".getBytes("UTF8")); 			 // key="id", value="12345"
			storageManager.put("name", "Miss Cassandra".getBytes("UTF8"));  // key="name", value="Miss Cassandra"

			// Get Operation
			System.out.println("1) PUT/GET:");
			System.out.println("\tid = " + new String(storageManager.get("id"), "UTF8"));
			System.out.println("\tname = " + new String(storageManager.get("name"), "UTF8"));
			System.out.println();

			// Cassandra provide more flexibility with the specific data structure:
			// The wrapper provide the methods to manage this data structure:
			// Example: to store an user into the colomnFamily "Users"
			// Define a Map with all the columnName/Value you need
			//		. the columnName is a String
			//		. the value is a byte[]
			Map<String, byte[]> arguments = new HashMap<String, byte[]>();
			arguments.put("id", "12345".getBytes("UTF8"));
			arguments.put("name", "Miss Cassandra".getBytes("UTF8"));

			// Call the "insertInto(tableName, primaryKey, args)" method:
			//		. you have to specify the primary key like in sql
			if(!storageManager.insertSlice("Users", "id", arguments)){
				System.err.println("Users not insered!");
				System.exit(1);
			}

			// Then to retrieve a the value of a simple column you can call:
			System.out.println("2) selectColum:");
			System.out.println("\t" + new String(storageManager.selectColumn("Users", "12345", "name")));
			System.out.println();

			// To retrieve all the values just call: selectAll(tableName, primaryKey)
			System.out.println("3) selectAll:");
			Map<byte[], byte[]> values = storageManager.selectAll("Users", "12345");
			for(byte[] s : values.keySet()){
				System.out.println("\t" + new String(values.get(s)));
			}
			System.out.println();

			// To update the value of a specific column 
			storageManager.insertColumn("Users", "12345", "name", "Mrs Robinson".getBytes("UTF8"));
			System.out.println("4) update Value:");
			values = storageManager.selectAll("Users", "12345");
			for(byte[] s : values.keySet()){
				System.out.println("\t" + new String(values.get(s)));
			}
			System.out.println();

			// To remove a specific column
			//			wrapper.removeColumn("Users", "12345", "name");
			//			System.out.println("5) remove column:");
			//			values = wrapper.selectAll("Users", "12345");
			//			for(byte[] s : values.keySet()){
			//				System.out.println("\t" + new String(values.get(s)));
			//			}
			//			System.out.println();
			
			Map<String, byte[]> arguments2 = new HashMap<String, byte[]>();
			List<String> columnNames = new ArrayList<String>();
			arguments2.put("id", "rangetest".getBytes("UTF8"));
			for(int i=0; i<10 ; i++){
				arguments2.put("test" + i, ("value" + i).getBytes("UTF8"));
				columnNames.add("test" + i);
			}

			// Call the "insertInto(tableName, primaryKey, args)" method:
			//		. you have to specify the primary key like in sql
			if(!storageManager.insertSlice("Users", "id", arguments2)){
				System.err.println("Users not insered!");
				System.exit(1);
			}
			// To retrieve all the values just call: selectAll(tableName, primaryKey)
			System.out.println("3) selectRange:");
			Map<byte[], byte[]> values2 = storageManager.selectRange("Users", "rangetest", columnNames); 
			for(byte[] s : values2.keySet()){
				System.out.println("\t" + new String(values2.get(s)));
			}
			System.out.println();

		} catch (Exception e) {
			e.printStackTrace();
		} 
	}
}
