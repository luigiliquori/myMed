package com.mymed.example;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

import com.mymed.model.core.wrapper.IWrapper;
import com.mymed.model.core.wrapper.Wrapper;
import com.mymed.model.core.wrapper.exception.WrapperException;

/**
 * This class is an example of the common put/get DHT operation
 * @author lvanni
 *
 */
public class WrapperUsualOperations {

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
			IWrapper wrapper = new Wrapper(); // == new Wrapper(ClientType.CASSANDRA)

			// Cassandra is DHT based, the wrapper provide the usual DHT operations:
			// PUT Operation
			wrapper.put("id", "12345".getBytes("UTF8")); 				// key="id", value="12345"
			wrapper.put("name", "Miss Cassandra".getBytes("UTF8"));  // key="name", value="Miss Cassandra"

			// Get Operation
			System.out.println("1) PUT/GET:");
			System.out.println("\tid = " + new String(wrapper.get("id"), "UTF8"));
			System.out.println("\tname = " + new String(wrapper.get("name"), "UTF8"));
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
			if(!wrapper.insertInto("Users", "id", arguments)){
				System.err.println("Users not insered!");
				System.exit(1);
			}

			// Then to retrieve a the value of a simple column you can call:
			System.out.println("2) selectColum:");
			System.out.println("\t" + new String(wrapper.selectColumn("Users", "12345", "name")));
			System.out.println();

			// To retrieve all the values just call: selectAll(tableName, primaryKey)
			System.out.println("3) selectAll:");
			Map<byte[], byte[]> values = wrapper.selectAll("Users", "12345");
			for(byte[] s : values.keySet()){
				System.out.println("\t" + new String(values.get(s)));
			}
			System.out.println();

			// To update the value of a specific column 
			wrapper.updateColumn("Users", "12345", "name", "Mrs Robinson".getBytes("UTF8"));
			System.out.println("4) update Value:");
			values = wrapper.selectAll("Users", "12345");
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

	}
}
