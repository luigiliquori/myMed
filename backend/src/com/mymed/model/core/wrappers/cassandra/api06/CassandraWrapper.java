package com.mymed.model.core.wrappers.cassandra.api06;

import java.io.UnsupportedEncodingException;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.List;
import java.util.Map;
import java.util.Set;

import org.apache.cassandra.thrift.AuthenticationException;
import org.apache.cassandra.thrift.AuthenticationRequest;
import org.apache.cassandra.thrift.AuthorizationException;
import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.KeyRange;
import org.apache.cassandra.thrift.KeySlice;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.SlicePredicate;
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
	private TTransport tr;
	private TProtocol proto;
	private Client client;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Private Constructor to create a singleton
	 */
	public CassandraWrapper() {
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
		if (address != null && port != 0) {
			// not managed by glassfish
			this.tr = new TSocket(address, port);
			this.proto = new TBinaryProtocol(tr);
			this.client = new Client(proto);
		}
	}

	public void put(String key, byte[] value) throws InternalBackEndException {
		try {
			tr.open();
			String columnFamily = "Services"; // TODO MOVE TO THE NEW DATA
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

	public byte[] getValue(String key) throws InternalBackEndException,
			IOBackEndException {
		try {
			tr.open();
			String columnFamily = "Services"; // TODO MOVE TO THE NEW DATA
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
	 * @see ICassandraWrapper#get(String keyspace, String key, String
	 *      columnPath, ConsistencyLevel consistencyLevel)
	 */
	@Override
	public ColumnOrSuperColumn get(String keyspace, String key,
			ColumnPath colPathName, ConsistencyLevel consistencyLevel)
			throws InternalBackEndException, IOBackEndException {
		try {
			tr.open();
			ColumnOrSuperColumn col = client.get(keyspace, key, colPathName,
					consistencyLevel);
			return col;
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
	 * @see ICassandraWrapper#get_slice(String keyspace, String key, String
	 *      columnParent, String predicate, ConsistencyLevel consistencyLevel)
	 */
	@Override
	public List<ColumnOrSuperColumn> get_slice(String keyspace, String key,
			ColumnParent columnParent, SlicePredicate predicate,
			ConsistencyLevel consistencyLevel) 	throws InternalBackEndException, IOBackEndException {
				try {
					tr.open();
					List<ColumnOrSuperColumn> slice = client.get_slice(keyspace, key,
							columnParent, predicate, consistencyLevel);
					if (slice.isEmpty()) { // IF NOT FOUND!
						throw new IOBackEndException("keyspace: " + keyspace
								+ ", columnFamily: " + columnParent.getColumn_family() + ", key: " + key
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
	 * @see ICassandraWrapper#insert(String keyspace, String key, String
	 *      columnPath, String value, String timestamp, ConsistencyLevel
	 *      consistencyLevel)
	 */
	@Override
	public void insert(String keyspace, String key, ColumnPath columnPath,
			byte[] value, long timestamp, ConsistencyLevel consistencyLevel)
			throws InternalBackEndException {
		try {
			tr.open();
			client.insert(keyspace, key, columnPath, value, timestamp,
					consistencyLevel);
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
	 * @see ICassandraWrapper#batch_mutate(String, String, ConsistencyLevel)
	 */
	@Override
	public void batch_mutate(String keyspace, Map<String, Map<String, List<Mutation>>> mutationMap,
			ConsistencyLevel consistencyLevel) throws InternalBackEndException {
			try {
				tr.open();
				client.batch_mutate(keyspace, mutationMap, consistencyLevel);
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
	 * @see ICassandraWrapper#describe_cluster_name()
	 */
	@Override
	public String describe_cluster_name() throws InternalBackEndException {
			try {
				tr.open();
				return client.describe_cluster_name();
			} catch (TTransportException e) {
				throw new InternalBackEndException(e.getMessage());
			} catch (TException e) {
				throw new InternalBackEndException(e.getMessage());
			} finally {
				tr.close();
			}
	}

	/**
	 * @see ICassandraWrapper#describe_keyspace(String)
	 */
	@Override
	public Map<String, Map<String, String>> describe_keyspace(String keyspace) throws InternalBackEndException, IOBackEndException {
		try {
			tr.open();
			return client.describe_keyspace(keyspace);
		} catch (NotFoundException e) {
			throw new IOBackEndException(e.getMessage());
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#describe_keyspaces()
	 */
	@Override
	public Set<String> describe_keyspaces() throws InternalBackEndException {
		try {
			tr.open();
			return client.describe_keyspaces();
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#describe_ring(String keyspace)
	 */
	@Override
	public List<TokenRange> describe_ring(String keyspace) throws InternalBackEndException {
		try {
			tr.open();
			return client.describe_ring(keyspace);
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#describe_version()
	 */
	@Override
	public String describe_version() throws InternalBackEndException {
		try {
			tr.open();
			return client.describe_version();
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#get_count(String keyspace, String key, String
	 *      columnParent, ConsistencyLevel consistencyLevel)
	 */
	@Override
	public int get_count(String keyspace, String key, ColumnParent columnParent,
			ConsistencyLevel consistencyLevel) throws InternalBackEndException {
		try {
			tr.open();
			return client.get_count(keyspace, key, columnParent, consistencyLevel);
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#get_range_slices(String keyspace, String
	 *      columnParent, String predicate, String range, ConsistencyLevel
	 *      consistencyLevel)
	 */
	@Override
	public List<KeySlice> get_range_slices(String keyspace,
			ColumnParent columnParent, SlicePredicate predicate, KeyRange range,
			ConsistencyLevel consistencyLevel) throws InternalBackEndException {
		try {
			tr.open();
			return client.get_range_slices(keyspace, columnParent, predicate, range, consistencyLevel);
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}



	/**
	 * @see ICassandraWrapper#login(String keyspace, String authRequest)
	 */
	@Override
	public void login(String keyspace, AuthenticationRequest authRequest) throws InternalBackEndException {
		try {
			tr.open();
			client.login(keyspace, authRequest);
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (AuthenticationException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (AuthorizationException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#multiget_slice(String keyspace, String keys,
	 *      String columnParent, String predicate, ConsistencyLevel
	 *      consistencyLevel)
	 */
	@Override
	public Map<String, List<ColumnOrSuperColumn>> multiget_slice(
			String keyspace, List<String> keys, ColumnParent columnParent,
			SlicePredicate predicate, ConsistencyLevel consistencyLevel) throws InternalBackEndException {
		try {
			tr.open();
			return client.multiget_slice(keyspace, keys, columnParent, predicate, consistencyLevel);
		} catch (TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @throws InternalBackEndException 
	 * @see ICassandraWrapper#remove(String keyspace, String key, String
	 *      columnPath, String timestamp, ConsistencyLevel consistencyLevel)
	 */
	@Override
	public void remove(String keyspace, String key, ColumnPath columnPath,
			long timestamp, ConsistencyLevel consistencyLevel) throws InternalBackEndException {
		try {
			tr.open();
			client.remove(keyspace, key, columnPath, timestamp, consistencyLevel);
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
