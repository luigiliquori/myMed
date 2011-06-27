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
import org.apache.cassandra.thrift.Cassandra.Client;
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
import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;
import org.apache.thrift.transport.TTransportException;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.IStorageManager;
import com.mymed.model.core.wrappers.AbstractDHTWrapper;

/**
 * this Class represent a Client Connected to the local Cassandra node
 * 
 * @author lvanni
 * 
 */
@Deprecated
public class CassandraWrapper extends AbstractDHTWrapper implements ICassandraWrapper {
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
			tr = new TSocket(InetAddress.getLocalHost().getHostAddress(), 4201);
			proto = new TBinaryProtocol(tr);
			client = new Client(proto);
		} catch (final UnknownHostException e) {
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
	@Override
	public void setup(final String address, final int port) {
		System.out.println("*****address: " + address);
		System.out.println("*****port: " + port);
		if (address != null && port != 0) {
			// not managed by glassfish
			tr = new TSocket(address, port);
			proto = new TBinaryProtocol(tr);
			client = new Client(proto);
		}
	}

	@Override
	public void put(final String key, final byte[] value) throws InternalBackEndException {
		try {
			tr.open();
			final String columnFamily = "Services"; // TODO MOVE TO THE NEW DATA
			// STRUCTURE
			final ColumnPath colPathName = new ColumnPath(columnFamily);
			final long timestamp = System.currentTimeMillis();
			colPathName.setColumn(key.getBytes("UTF8"));
			client.insert("Mymed", key, colPathName, value, timestamp, IStorageManager.consistencyOnWrite);
		} catch (final UnsupportedEncodingException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	@Override
	public byte[] getValue(final String key) throws InternalBackEndException, IOBackEndException {
		try {
			tr.open();
			final String columnFamily = "Services"; // TODO MOVE TO THE NEW DATA
			// STRUCTURE
			final ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(key.getBytes("UTF8"));
			final Column col = client.get("Mymed", key, colPathName, IStorageManager.consistencyOnRead).getColumn();
			return col.value;
		} catch (final UnsupportedEncodingException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final NotFoundException e) {
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
	public ColumnOrSuperColumn get(final String keyspace, final String key, final ColumnPath colPathName,
	        final ConsistencyLevel consistencyLevel) throws InternalBackEndException, IOBackEndException {
		try {
			tr.open();
			final ColumnOrSuperColumn col = client.get(keyspace, key, colPathName, consistencyLevel);
			return col;
		} catch (final TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final NotFoundException e) {
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
	public List<ColumnOrSuperColumn> get_slice(final String keyspace, final String key,
	        final ColumnParent columnParent, final SlicePredicate predicate, final ConsistencyLevel consistencyLevel)
	        throws InternalBackEndException, IOBackEndException {
		try {
			tr.open();
			final List<ColumnOrSuperColumn> slice = client.get_slice(keyspace, key, columnParent, predicate,
			        consistencyLevel);
			if (slice.isEmpty()) { // IF NOT FOUND!
				throw new IOBackEndException("keyspace: " + keyspace + ", columnFamily: "
				        + columnParent.getColumn_family() + ", key: " + key + " - NOT FOUND!");
			}
			return slice;
		} catch (final TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TException e) {
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
	public void insert(final String keyspace, final String key, final ColumnPath columnPath, final byte[] value,
	        final long timestamp, final ConsistencyLevel consistencyLevel) throws InternalBackEndException {
		try {
			tr.open();
			client.insert(keyspace, key, columnPath, value, timestamp, consistencyLevel);
		} catch (final TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#batch_mutate(String, String, ConsistencyLevel)
	 */
	@Override
	public void batch_mutate(final String keyspace, final Map<String, Map<String, List<Mutation>>> mutationMap,
	        final ConsistencyLevel consistencyLevel) throws InternalBackEndException {
		try {
			tr.open();
			client.batch_mutate(keyspace, mutationMap, consistencyLevel);
		} catch (final TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TException e) {
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
		} catch (final TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#describe_keyspace(String)
	 */
	@Override
	public Map<String, Map<String, String>> describe_keyspace(final String keyspace) throws InternalBackEndException,
	        IOBackEndException {
		try {
			tr.open();
			return client.describe_keyspace(keyspace);
		} catch (final NotFoundException e) {
			throw new IOBackEndException(e.getMessage());
		} catch (final TException e) {
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
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#describe_ring(String keyspace)
	 */
	@Override
	public List<TokenRange> describe_ring(final String keyspace) throws InternalBackEndException {
		try {
			tr.open();
			return client.describe_ring(keyspace);
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
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
		} catch (final TException e) {
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
	public int get_count(final String keyspace, final String key, final ColumnParent columnParent,
	        final ConsistencyLevel consistencyLevel) throws InternalBackEndException {
		try {
			tr.open();
			return client.get_count(keyspace, key, columnParent, consistencyLevel);
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
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
	public List<KeySlice> get_range_slices(final String keyspace, final ColumnParent columnParent,
	        final SlicePredicate predicate, final KeyRange range, final ConsistencyLevel consistencyLevel)
	        throws InternalBackEndException {
		try {
			tr.open();
			return client.get_range_slices(keyspace, columnParent, predicate, range, consistencyLevel);
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * @see ICassandraWrapper#login(String keyspace, String authRequest)
	 */
	@Override
	public void login(final String keyspace, final AuthenticationRequest authRequest) throws InternalBackEndException {
		try {
			tr.open();
			client.login(keyspace, authRequest);
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final AuthenticationException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final AuthorizationException e) {
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
	public Map<String, List<ColumnOrSuperColumn>> multiget_slice(final String keyspace, final List<String> keys,
	        final ColumnParent columnParent, final SlicePredicate predicate, final ConsistencyLevel consistencyLevel)
	        throws InternalBackEndException {
		try {
			tr.open();
			return client.multiget_slice(keyspace, keys, columnParent, predicate, consistencyLevel);
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
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
	public void remove(final String keyspace, final String key, final ColumnPath columnPath, final long timestamp,
	        final ConsistencyLevel consistencyLevel) throws InternalBackEndException {
		try {
			tr.open();
			client.remove(keyspace, key, columnPath, timestamp, consistencyLevel);
		} catch (final TTransportException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final InvalidRequestException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final UnavailableException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TimedOutException e) {
			throw new InternalBackEndException(e.getMessage());
		} catch (final TException e) {
			throw new InternalBackEndException(e.getMessage());
		} finally {
			tr.close();
		}
	}
}
