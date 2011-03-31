package com.mymed.model.core.data.dht.protocol;

import java.io.UnsupportedEncodingException;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
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
import com.mymed.model.core.wrapper.Wrapper;

/**
 * this Class represent a Client Connected to
 * the local Cassandra node
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
	 * @throws UnknownHostException 
	 * @throws UnknownHostException 
	 */
	private Cassandra() throws UnknownHostException {
		super(InetAddress.getLocalHost().getHostAddress(), 4201);
		this.tr = new TSocket(address, port);
		this.proto = new TBinaryProtocol(tr);
		this.client = new Client(proto);
	}

	/**
	 * Cassandra getter
	 * @return
	 * 		The only one instance of Cassandra
	 * @throws UnknownHostException 
	 */
	public static Cassandra getInstance() {
		if (null == singleton) {
			try {
				singleton = new Cassandra();
			} catch (UnknownHostException e) {
				e.printStackTrace();
			}
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
	 * @throws TException 
	 * @throws TimedOutException 
	 * @throws UnavailableException 
	 * @throws NotFoundException 
	 * @throws InvalidRequestException 
	 */
	public byte[] getSimpleColumn(String keyspace, String columnFamily, String key, byte[] columnName, ConsistencyLevel level) {
		try {
			tr.open();
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(columnName);
			Column col = client.get(keyspace, key, colPathName, level).getColumn();
			return col.value;
		} catch (TTransportException e) {
			e.printStackTrace();
		} catch (InvalidRequestException e) {
			e.printStackTrace();
		} catch (NotFoundException e) {
			System.out.println("\nKEY:" +
					"\n\tkeysapce = " + keyspace +
					"\n\tcolumnFamily = " + columnFamily + 
					"\n\tkey = " + key + 
					"\n\tcolumnName = " + new String(columnName) + 
			"\n---> NOT FOUND!\n");

		} catch (UnavailableException e) {
			e.printStackTrace();
		} catch (TimedOutException e) {
			e.printStackTrace();
		} catch (TException e) {
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
	 * @throws TException 
	 * @throws TimedOutException 
	 * @throws UnavailableException 
	 * @throws InvalidRequestException 
	 */
	public void setSimpleColumn(String keyspace, String columnFamily, String key, byte[] columnName, byte[] value, ConsistencyLevel level) {
		try {
			tr.open();
			long timestamp = System.currentTimeMillis();
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(columnName);
			client.insert(keyspace, key, colPathName, value, timestamp, level);
		} catch (TTransportException e) {
			e.printStackTrace();
		} catch (InvalidRequestException e) {
			e.printStackTrace();
		} catch (UnavailableException e) {
			e.printStackTrace();
		} catch (TimedOutException e) {
			e.printStackTrace();
		} catch (TException e) {
			e.printStackTrace();
		} finally {
			tr.close();
		}
	}

	/**
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @param level
	 * @return
	 */
	public Map<byte[], byte[]> getSlice(String keyspace, String columnFamily, String key, ConsistencyLevel level) {
		Map<byte[], byte[]> slice = new HashMap<byte[], byte[]>();
		try {
			tr.open();
			// read entire row
			SlicePredicate predicate = new SlicePredicate();
			SliceRange sliceRange = new SliceRange();
			sliceRange.setStart(new byte[0]);
			sliceRange.setFinish(new byte[0]);
			predicate.setSlice_range(sliceRange);

			ColumnParent parent = new ColumnParent(columnFamily);
			List<ColumnOrSuperColumn> results = client.get_slice(keyspace,
					key, parent, predicate, ConsistencyLevel.ONE);
			for (ColumnOrSuperColumn res : results) {
				Column column = res.column;
				slice.put(column.name, column.value);
			}
		} catch (TException e) {
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
		} finally {
			tr.close();
		}
		return slice;
	}

	/* --------------------------------------------------------- */
	/*                    COMMON DHT OPERATIONS                  */
	/* --------------------------------------------------------- */
	@Override
	public void put(String key, String value) {
		try {
			tr.open();
			String columnFamily = "Trips";
			ColumnPath colPathName = new ColumnPath(columnFamily);
			long timestamp = System.currentTimeMillis();
			colPathName.setColumn(key.getBytes("UTF8"));
			client.insert("Mymed", key, colPathName, value.getBytes("UTF8"), timestamp,
					Wrapper.consistencyOnWrite);
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
			String columnFamily = "Trips";
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(key.getBytes("UTF8"));
			Column col = client.get("Mymed", key, colPathName,
					Wrapper.consistencyOnRead).getColumn();
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
