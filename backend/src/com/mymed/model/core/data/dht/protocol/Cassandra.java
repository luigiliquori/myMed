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

import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;
import com.mymed.model.core.data.dht.factory.IDHTClient;
import com.mymed.model.core.wrapper.Wrapper;

/**
 * this Class represent a Client Connected to the local Cassandra node
 * 
 * @author lvanni
 * 
 */
public class Cassandra extends AbstractDHTClient implements IDHTClient {
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
	/* Attributes */
	/* --------------------------------------------------------- */
	/** The Cassandra instance */
	private static Cassandra singleton;

	/** Cassandra node attributes */
	private TTransport tr;
	private TProtocol proto;
	private Client client;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Private Constructor to create a singleton
	 */
	private Cassandra() {
		try { // By default it will try to connect on the localhost node if it
				// exist
			this.tr = new TSocket(InetAddress.getLocalHost().getHostAddress(),
					4201);
			this.proto = new TBinaryProtocol(tr);
			this.client = new Client(proto);
		} catch (UnknownHostException e) {
			e.printStackTrace();
		}
	}

	/**
	 * Cassandra getter
	 * 
	 * @return The only one instance of Cassandra
	 */
	public static Cassandra getInstance() {
		if (singleton == null) {
			synchronized (Cassandra.class) {
				if (singleton == null)
					singleton = new Cassandra();
			}
		}
		return singleton;
	}

	/**
	 * Setup the configuration of the client
	 * 
	 * @param address
	 * @param port
	 */
	public void setup(String address, int port) {
		if (address != null && port != 0) { // TO FIX the configuration file is
											// not managed by glassfish
			this.tr = new TSocket(address, port);
			this.proto = new TBinaryProtocol(tr);
			this.client = new Client(proto);
		}
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	/**
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @param level
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public byte[] getSimpleColumn(String keyspace, String columnFamily,
			String key, byte[] columnName, ConsistencyLevel level)
			throws InternalBackEndException, IOBackEndException {
		try {
			tr.open();
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(columnName);
			Column col = client.get(keyspace, key, colPathName, level)
					.getColumn();
			return col.value;
		} catch (TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (NotFoundException e) {
			throw new IOBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @param value
	 * @param level
	 * @throws InternalBackEndException
	 */
	public void setSimpleColumn(String keyspace, String columnFamily,
			String key, byte[] columnName, byte[] value, ConsistencyLevel level)
			throws InternalBackEndException {
		try {
			tr.open();
			long timestamp = System.currentTimeMillis();
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(columnName);
			client.insert(keyspace, key, colPathName, value, timestamp, level);
		} catch (TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
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
	 * @return An entire row of a columnFamily, defined by the key
	 * @throws InternalBackEndException
	 * @throws IOBackEndException 
	 */
	public Map<byte[], byte[]> getEntireRow(String keyspace,
			String columnFamily, String key, ConsistencyLevel level)
			throws InternalBackEndException, IOBackEndException {
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
			List<ColumnOrSuperColumn> results = client.get_slice(keyspace, key,
					parent, predicate, ConsistencyLevel.ONE);
			for (ColumnOrSuperColumn res : results) {
				Column column = res.column;
				slice.put(column.name, column.value);
			}
			if(slice.isEmpty()){ // IF NOT FOUND!
				throw new IOBackEndException("keyspace: " + keyspace
						+ ", columnFamily: " + columnFamily + ", key: " + key
						+ " - NOT FOUND!");
			}
			return slice;
		} catch (TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnNames
	 * @param level
	 * @return A range of column define by the columnNames
	 * @throws InternalBackEndException
	 */
	public Map<byte[], byte[]> getRangeColumn(String keyspace,
			String columnFamily, String key, List<byte[]> columnNames,
			ConsistencyLevel level) throws InternalBackEndException {
		Map<byte[], byte[]> slice = new HashMap<byte[], byte[]>();
		try {
			tr.open();
			SlicePredicate predicate = new SlicePredicate();
			predicate.setColumn_names(columnNames);
			ColumnParent parent = new ColumnParent(columnFamily);
			List<ColumnOrSuperColumn> results = client.get_slice(keyspace, key,
					parent, predicate, ConsistencyLevel.ONE);
			for (ColumnOrSuperColumn res : results) {
				Column column = res.column;
				slice.put(column.name, column.value);
			}
			return slice;
		} catch (TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * Remove a specific column defined by the columnName
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @param level
	 * @throws InternalBackEndException
	 */
	public void removeColumn(String keyspace, String columnFamily, String key,
			byte[] columnName, ConsistencyLevel level)
			throws InternalBackEndException {
		try {
			tr.open();
			long timestamp = System.currentTimeMillis();
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(columnName);
			client.remove(keyspace, key, colPathName, timestamp, level);
		} catch (TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * Remove an entry in the columnFamily
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param level
	 * @throws InternalBackEndException
	 */
	public void removeAll(String keyspace, String columnFamily, String key,
			ConsistencyLevel level) throws InternalBackEndException {
		try {
			tr.open();
			long timestamp = System.currentTimeMillis();
			ColumnPath colPathName = new ColumnPath(columnFamily);
			client.remove(keyspace, key, colPathName, timestamp, level);
		} catch (TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/* --------------------------------------------------------- */
	/* COMMON DHT OPERATIONS */
	/* --------------------------------------------------------- */
	@Override
	public void put(String key, byte[] value) throws InternalBackEndException {
		try {
			tr.open();
			String columnFamily = "Trips";
			ColumnPath colPathName = new ColumnPath(columnFamily);
			long timestamp = System.currentTimeMillis();
			colPathName.setColumn(key.getBytes("UTF8"));
			client.insert("Mymed", key, colPathName, value, timestamp,
					Wrapper.consistencyOnWrite);
		} catch (UnsupportedEncodingException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	@Override
	public byte[] getValue(String key) throws InternalBackEndException,
			IOBackEndException {
		try {
			tr.open();
			String columnFamily = "Trips";
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(key.getBytes("UTF8"));
			Column col = client.get("Mymed", key, colPathName,
					Wrapper.consistencyOnRead).getColumn();
			return col.value;
		} catch (UnsupportedEncodingException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (NotFoundException e) {
			throw new IOBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}
}
