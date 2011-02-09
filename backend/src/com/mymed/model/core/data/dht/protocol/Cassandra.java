package com.mymed.model.core.data.dht.protocol;

import java.io.UnsupportedEncodingException;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.cassandra.thrift.Cassandra.Client;
import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;
import org.apache.thrift.transport.TTransportException;

import com.mymed.model.core.data.dht.AbstractDHT;

/**
 * 
 * @author lvanni
 *
 */
public class Cassandra extends AbstractDHT {
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


	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	/** The Cassandra instance */
	private static Cassandra singleton;

	/** Cassandra node attributes */
	private TTransport tr;
	private TProtocol proto;
	private Client client;

	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
	/**
	 * Private Constructor to create a singleton
	 */
	private Cassandra() {
		super("138.96.242.2", 4201);

		this.tr = new TSocket(address, port);
		this.proto = new TBinaryProtocol(tr);
		this.client = new Client(proto);
	}

	/**
	 * Cassandra getter
	 * @return
	 * 		The only one instance of Cassandra
	 */
	public static Cassandra getInstance() {
		if (null == singleton) {
			singleton = new Cassandra();
		}
		return singleton;
	}

	/* --------------------------------------------------------- */
	/*                      Public methods                       */
	/* --------------------------------------------------------- */
	/**
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @param level
	 * @return
	 */
	public byte[] getSimpleColumn(String keyspace, String columnFamily, String key, byte[] columnName, ConsistencyLevel level){
		try {
			tr.open();
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(columnName);
			Column col = client.get(keyspace, key, colPathName, level).getColumn();
			return col.value;
		} catch (TTransportException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (InvalidRequestException e) {
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
		} catch (NotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally {
			tr.close();
		}
		return null;
	}

	/**
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @param value
	 * @param level
	 */
	public void setSimpleColumn(String keyspace, String columnFamily, String key, byte[] columnName, byte[] value, ConsistencyLevel level){
		try {
			tr.open();
			long timestamp = System.currentTimeMillis();
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(columnName);
			client.insert(keyspace, key, colPathName, value, timestamp,
					level);
		} catch (TTransportException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (InvalidRequestException e) {
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
		} finally {
			tr.close();
		}
	}

	/* --------------------------------------------------------- */
	/*                    COMMON DHT OPERATIONS                  */
	/* --------------------------------------------------------- */
	@Override
	public void put(String key, String value) {
		try {
			tr.open();
			String columnFamily = "Standard1";
			ColumnPath colPathName = new ColumnPath(columnFamily);
			long timestamp = System.currentTimeMillis();
			colPathName.setColumn(key.getBytes("UTF8"));
			client.insert("Keyspace1", "1", colPathName, value.getBytes("UTF8"), timestamp,
					ConsistencyLevel.ANY);
		} catch (InvalidRequestException e) {
			e.printStackTrace();
		} catch (UnavailableException e) {
			e.printStackTrace();
		} catch (TimedOutException e) {
			e.printStackTrace();
		} catch (TException e) {
			e.printStackTrace();
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		} finally {
			tr.close();
		}
	}

	@Override
	public String get(String key) {
		try {
			tr.open();
			String columnFamily = "Standard1";
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(key.getBytes("UTF8"));
			Column col = client.get("Keyspace1", "1", colPathName,
					ConsistencyLevel.ANY).getColumn();
			return new String(col.value, "UTF8");
		} catch (InvalidRequestException e) {
			e.printStackTrace();
		} catch (UnavailableException e) {
			e.printStackTrace();
		} catch (TimedOutException e) {
			e.printStackTrace();
		} catch (TException e) {
			e.printStackTrace();
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		} catch (NotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally {
			tr.close();
		}
		return "";
	}
}
