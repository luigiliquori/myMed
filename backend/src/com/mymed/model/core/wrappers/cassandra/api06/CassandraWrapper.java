package com.mymed.model.core.wrappers.cassandra.api06;

import java.io.UnsupportedEncodingException;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Set;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.KeySlice;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.TokenRange;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.cassandra.thrift.Cassandra.Client;
import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;
import org.apache.thrift.transport.TTransportException;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.wrappers.AbstractDHTWrapper;
import com.sun.xml.rpc.processor.modeler.j2ee.xml.string;

/**
 * this Class represent a Client Connected to the local Cassandra node
 * 
 * @author lvanni
 * 
 */
@Deprecated
public class CassandraWrapper extends AbstractDHTWrapper implements
		ICassandraWrapper {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** The Cassandra instance */
	private static CassandraWrapper singleton;

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
	private CassandraWrapper() {
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
	public static CassandraWrapper getInstance() {
		if (singleton == null) {
			synchronized (CassandraWrapper.class) {
				if (singleton == null)
					singleton = new CassandraWrapper();
			}
		}
		return singleton;
	}

	/* --------------------------------------------------------- */
	/* EXTENDS AbstractDHTWrapper */
	/* --------------------------------------------------------- */
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

	// THE COLUMNFAMILY TRIPS IS ONLY USED FOR TESTING
	public void put(String key, byte[] value) throws InternalBackEndException {
		try {
			tr.open();
			String columnFamily = "Trips"; // TODO MOVE TO THE NEW DATA
			// STRUCTURE
			ColumnPath colPathName = new ColumnPath(columnFamily);
			long timestamp = System.currentTimeMillis();
			colPathName.setColumn(key.getBytes("UTF8"));
			client.insert("Mymed", key, colPathName, value, timestamp,
					StorageManager.consistencyOnWrite);
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

	// THE COLUMNFAMILY TRIPS IS ONLY USED FOR TESTING
	public byte[] getValue(String key) throws InternalBackEndException,
			IOBackEndException {
		try {
			tr.open();
			String columnFamily = "Trips"; // TODO MOVE TO THE NEW DATA
			// STRUCTURE
			ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(key.getBytes("UTF8"));
			Column col = client.get("Mymed", key, colPathName,
					StorageManager.consistencyOnRead).getColumn();
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

	/* --------------------------------------------------------- */
	/* IMPLEMENTS api06.ICassandraWrapper */
	/* --------------------------------------------------------- */
	/**
	 * @see ICassandraWrapper#batch_mutate(String, String, ConsistencyLevel)
	 */
	@Override
	public void batch_mutate(String keyspace, String mutationMap,
			ConsistencyLevel consistencyLevel) {
		// TODO Auto-generated method stub

	}

	/**
	 * @see ICassandraWrapper#describe_cluster_name()
	 */
	@Override
	public String describe_cluster_name() {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see ICassandraWrapper#describe_keyspace(String)
	 */
	@Override
	public Map<string, Map<string, string>> describe_keyspace(String keyspace) {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see ICassandraWrapper#describe_keyspaces()
	 */
	@Override
	public Set<string> describe_keyspaces() {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see ICassandraWrapper#describe_ring(String keyspace)
	 */
	@Override
	public List<TokenRange> describe_ring(String keyspace) {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see ICassandraWrapper#describe_version()
	 */
	@Override
	public String describe_version() {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see ICassandraWrapper#get(String keyspace, String key, String
	 *      columnPath, ConsistencyLevel consistencyLevel)
	 */
	@Override
	public ColumnOrSuperColumn get(String keyspace, String key,
			String columnPath, ConsistencyLevel consistencyLevel) {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see ICassandraWrapper#get_count(String keyspace, String key, String
	 *      columnParent, ConsistencyLevel consistencyLevel)
	 */
	@Override
	public int get_count(String keyspace, String key, String columnParent,
			ConsistencyLevel consistencyLevel) {
		// TODO Auto-generated method stub
		return 0;
	}

	/**
	 * @see ICassandraWrapper#get_range_slices(String keyspace, String
	 *      columnParent, String predicate, String range, ConsistencyLevel
	 *      consistencyLevel)
	 */
	@Override
	public List<KeySlice> get_range_slices(String keyspace,
			String columnParent, String predicate, String range,
			ConsistencyLevel consistencyLevel) {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see ICassandraWrapper#get_slice(String keyspace, String key, String
	 *      columnParent, String predicate, ConsistencyLevel consistencyLevel)
	 */
	@Override
	public List<ColumnOrSuperColumn> get_slice(String keyspace, String key,
			String columnParent, String predicate,
			ConsistencyLevel consistencyLevel) {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see ICassandraWrapper#insert(String keyspace, String key, String
	 *      columnPath, String value, String timestamp, ConsistencyLevel
	 *      consistencyLevel)
	 */
	@Override
	public void insert(String keyspace, String key, String columnPath,
			String value, String timestamp, ConsistencyLevel consistencyLevel) {
		// TODO Auto-generated method stub

	}

	/**
	 * @see ICassandraWrapper#login(String keyspace, String authRequest)
	 */
	@Override
	public void login(String keyspace, String authRequest) {
		// TODO Auto-generated method stub

	}

	/**
	 * @see ICassandraWrapper#multiget_slice(String keyspace, String keys,
	 *      String columnParent, String predicate, ConsistencyLevel
	 *      consistencyLevel)
	 */
	@Override
	public Map<string, List<ColumnOrSuperColumn>> multiget_slice(
			String keyspace, String keys, String columnParent,
			String predicate, ConsistencyLevel consistencyLevel) {
		// TODO Auto-generated method stub
		return null;
	}

	/**
	 * @see ICassandraWrapper#remove(String keyspace, String key, String
	 *      columnPath, String timestamp, ConsistencyLevel consistencyLevel)
	 */
	@Override
	public void remove(String keyspace, String key, String columnPath,
			String timestamp, ConsistencyLevel consistencyLevel) {
		// TODO Auto-generated method stub

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
			if (slice.isEmpty()) { // IF NOT FOUND!
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

}
