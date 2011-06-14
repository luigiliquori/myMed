package com.mymed.model.core.wrappers.cassandra.api07;

import java.io.UnsupportedEncodingException;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.nio.ByteBuffer;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.thrift.AuthenticationException;
import org.apache.cassandra.thrift.AuthenticationRequest;
import org.apache.cassandra.thrift.AuthorizationException;
import org.apache.cassandra.thrift.Cassandra.Client;
import org.apache.cassandra.thrift.CfDef;
import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.IndexClause;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.KeyRange;
import org.apache.cassandra.thrift.KeySlice;
import org.apache.cassandra.thrift.KsDef;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.TokenRange;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransportException;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.IStorageManager;
import com.mymed.model.core.wrappers.AbstractDHTWrapper;

/**
 * Wrapper for the Cassandra API v0.7.<br />
 * For more info about the API, check <a
 * href="http://wiki.apache.org/cassandra/API">the API web page</a>.
 * 
 * @author Milo Casagrande
 * 
 */
public final class CassandraWrapper extends AbstractDHTWrapper implements ICassandraWrapper {
	private static CassandraWrapper instance = new CassandraWrapper();

	private transient TFramedTransport thriftTransport;
	private transient TSocket socket;
	private transient TProtocol thriftProtocol;
	private transient Client cassandraClient;

	private CassandraWrapper() {

		super();

		// By default it will try to connect on the localhost node if it exist
		try {
			socket = new TSocket(InetAddress.getLocalHost().getHostAddress(), PORT_NUMBER);
			thriftTransport = new TFramedTransport(socket);
			thriftProtocol = new TBinaryProtocol(thriftTransport);
			cassandraClient = new Client(thriftProtocol);
		} catch (final UnknownHostException e) {
			e.printStackTrace();
		}
	}

	public static CassandraWrapper getInstance() {
		return instance;
	}

	@Override
	public void login(final AuthenticationRequest authRequest) throws InternalBackEndException {
		try {
			cassandraClient.login(authRequest);
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final AuthenticationException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final AuthorizationException ex) {
			throw new InternalBackEndException(ex.getMessage());
		}
	}

	@Override
	public void set_keyspace(final String keySpace) throws InternalBackEndException {
		try {
			thriftTransport.open();
			cassandraClient.set_keyspace(keySpace);
		} catch (final TTransportException ex) {
			ex.printStackTrace();
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}
	}

	@Override
	public ColumnOrSuperColumn get(final String key, final ColumnPath path, final ConsistencyLevel level)
	        throws IOBackEndException, InternalBackEndException {

		final ByteBuffer keyToBuffer = StringConverter.stringToByteBuffer(key);
		ColumnOrSuperColumn result = null;

		try {
			thriftTransport.open();
			// TODO we need the Keyspace to be set to work here
			result = cassandraClient.get(keyToBuffer, path, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			ex.printStackTrace();
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final NotFoundException ex) {
			ex.printStackTrace();
			System.err.println(ex.getMessage());
			throw new IOBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return result;
	}

	@Override
	public List<ColumnOrSuperColumn> get_slice(final String key, final ColumnParent parent,
	        final SlicePredicate predicate, final ConsistencyLevel level) throws InternalBackEndException {

		final ByteBuffer keyToBuffer = StringConverter.stringToByteBuffer(key);
		List<ColumnOrSuperColumn> result = null;

		try {
			thriftTransport.open();
			result = cassandraClient.get_slice(keyToBuffer, parent, predicate, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return result;
	}

	@Override
	public Map<ByteBuffer, List<ColumnOrSuperColumn>> multiget_slice(final List<String> keys,
	        final ColumnParent parent, final SlicePredicate predicate, final ConsistencyLevel level)
	        throws InternalBackEndException {

		final List<ByteBuffer> keysToBuffer = StringConverter.stringToByteBuffer(keys);
		Map<ByteBuffer, List<ColumnOrSuperColumn>> result = null;

		try {
			thriftTransport.open();
			result = cassandraClient.multiget_slice(keysToBuffer, parent, predicate, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return result;
	}

	@Override
	public int get_count(final String key, final ColumnParent parent, final SlicePredicate predicate,
	        final ConsistencyLevel level) throws InternalBackEndException {

		final ByteBuffer keyToBuffer = StringConverter.stringToByteBuffer(key);
		int result = -1;

		try {
			thriftTransport.open();
			result = cassandraClient.get_count(keyToBuffer, parent, predicate, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return result;
	}

	@Override
	public Map<ByteBuffer, Integer> multiget_count(final List<String> keys, final ColumnParent parent,
	        final SlicePredicate predicate, final ConsistencyLevel level) throws InternalBackEndException {

		final List<ByteBuffer> keysToBuffer = StringConverter.stringToByteBuffer(keys);
		Map<ByteBuffer, Integer> result = null;

		try {
			thriftTransport.open();
			result = cassandraClient.multiget_count(keysToBuffer, parent, predicate, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return result;
	}

	@Override
	public List<KeySlice> get_range_slices(final ColumnParent parent, final SlicePredicate predicate,
	        final KeyRange range, final ConsistencyLevel level) throws InternalBackEndException {

		List<KeySlice> result = null;

		try {
			thriftTransport.open();
			result = cassandraClient.get_range_slices(parent, predicate, range, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return result;
	}

	@Override
	public List<KeySlice> get_indexed_slices(final ColumnParent parent, final IndexClause clause,
	        final SlicePredicate predicate, final ConsistencyLevel level) throws InternalBackEndException {

		List<KeySlice> result = null;

		try {
			thriftTransport.open();
			result = cassandraClient.get_indexed_slices(parent, clause, predicate, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return result;
	}

	@Override
	public void insert(final String key, final ColumnParent parent, final Column column, final ConsistencyLevel level)
	        throws InternalBackEndException {

		final ByteBuffer keyToBuffer = StringConverter.stringToByteBuffer(key);

		try {
			thriftTransport.open();
			cassandraClient.set_keyspace("TestKeyspace");
			cassandraClient.insert(keyToBuffer, parent, column, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			ex.printStackTrace();
			System.err.println(ex.getWhy());
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}
	}
	@Override
	public void batch_mutate(final Map<String, Map<String, List<Mutation>>> mutationMap, final ConsistencyLevel level)
	        throws InternalBackEndException {

		final Map<ByteBuffer, Map<String, List<Mutation>>> newMap = new HashMap<ByteBuffer, Map<String, List<Mutation>>>(
		        mutationMap.size());

		ByteBuffer keyToBuffer = null;
		Map<String, List<Mutation>> value = null;

		for (final String key : mutationMap.keySet()) {
			keyToBuffer = StringConverter.stringToByteBuffer(key);
			value = mutationMap.get(key);

			newMap.put(keyToBuffer, value);
			value.clear();
		}

		try {
			thriftTransport.open();
			cassandraClient.batch_mutate(newMap, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}
	}
	@Override
	public void remove(final String key, final ColumnPath path, final long timeStamp, final ConsistencyLevel level)
	        throws InternalBackEndException {

		final ByteBuffer keyToBuffer = StringConverter.stringToByteBuffer(key);

		try {
			thriftTransport.open();
			cassandraClient.remove(keyToBuffer, path, timeStamp, level);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}
	}

	@Override
	public void truncate(final String columnFamily) throws InternalBackEndException {
		try {
			thriftTransport.open();
			cassandraClient.truncate(columnFamily);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}
	}

	@Override
	public String describe_cluster_name() throws InternalBackEndException {
		String result = null;

		try {
			thriftTransport.open();
			result = cassandraClient.describe_cluster_name();
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return result;
	}

	@Override
	public KsDef describe_keyspace(final String keySpace) throws InternalBackEndException, IOBackEndException {

		KsDef keySpaceDef = null;

		try {
			thriftTransport.open();
			keySpaceDef = cassandraClient.describe_keyspace(keySpace);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final NotFoundException ex) {
			throw new IOBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return keySpaceDef;
	}

	@Override
	public List<KsDef> describe_keyspaces() throws InternalBackEndException {

		List<KsDef> keySpaceList = null;

		try {
			thriftTransport.open();
			keySpaceList = cassandraClient.describe_keyspaces();
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return keySpaceList;
	}

	@Override
	public String describe_partitioner() throws InternalBackEndException {

		String partitioner = null;

		try {
			thriftTransport.open();
			partitioner = cassandraClient.describe_partitioner();
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return partitioner;
	}

	@Override
	public List<TokenRange> describe_ring(final String keySpace) throws InternalBackEndException {

		List<TokenRange> ring = null;

		try {
			thriftTransport.open();
			ring = cassandraClient.describe_ring(keySpace);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return ring;
	}

	@Override
	public String describe_snitch() throws InternalBackEndException {

		String snitch = null;

		try {
			thriftTransport.open();
			snitch = cassandraClient.describe_snitch();
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return snitch;
	}

	@Override
	public String describe_version() throws InternalBackEndException {

		String version = null;

		try {
			thriftTransport.open();
			version = cassandraClient.describe_version();
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return version;
	}

	@Override
	public String system_add_column_family(final CfDef cfDef) throws InternalBackEndException {

		String schemaId = null;

		try {
			thriftTransport.open();
			schemaId = cassandraClient.system_add_column_family(cfDef);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getWhy());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return schemaId;
	}

	@Override
	public String system_drop_column_family(final String columnFamily) throws InternalBackEndException {

		String schemaId = null;

		try {
			thriftTransport.open();
			schemaId = cassandraClient.system_drop_column_family(columnFamily);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return schemaId;
	}

	@Override
	public String system_add_keyspace(final KsDef ksDef) throws InternalBackEndException {

		String schemaId = null;

		try {
			thriftTransport.open();
			schemaId = cassandraClient.system_add_keyspace(ksDef);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			ex.printStackTrace();
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return schemaId;
	}
	@Override
	public String system_drop_keyspace(final String keySpace) throws InternalBackEndException {

		String schemaId = null;

		try {
			thriftTransport.open();
			schemaId = cassandraClient.system_drop_keyspace(keySpace);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getWhy());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return schemaId;
	}

	@Override
	public String system_update_column_family(final CfDef columnFamily) throws InternalBackEndException {

		String newSchemaId = null;

		try {
			thriftTransport.open();
			// TODO we need the keyspace to be set to work here
			newSchemaId = cassandraClient.system_update_column_family(columnFamily);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			ex.printStackTrace();
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return newSchemaId;
	}
	@Override
	public String system_update_keyspace(final KsDef keySpace) throws InternalBackEndException {

		String newSchemaId = null;

		try {
			thriftTransport.open();
			newSchemaId = cassandraClient.system_update_keyspace(keySpace);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return newSchemaId;
	}

	/**
	 * Setup the configuration of the client
	 * 
	 * @param address
	 * @param port
	 */
	@Override
	public void setup(final String address, final int port) {
		// TODO the configuration file is not managed by glassfish
		if (address != null && port != 0) {
			socket = new TSocket(address, port);
			thriftTransport = new TFramedTransport(socket);
			thriftProtocol = new TBinaryProtocol(thriftTransport);
			cassandraClient = new Client(thriftProtocol);
		}
	}

	@Override
	public void put(final String key, final byte[] value) throws IOBackEndException, InternalBackEndException {
		try {
			// TODO MOVE TO THE NEW DATA STRUCTURE
			final String columnFamily = "Services";

			final ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(key.getBytes("UTF8"));

			final long timestamp = System.currentTimeMillis();

			final Column column = new Column();
			column.setTimestamp(timestamp);
			column.setValue(value);

			thriftTransport.open();

			// TODO the insert method has changed from version 0.6! Need to fix
			// it!

			// final ByteBuffer keyToBuffer =
			// StringConverter.stringToByteBuffer(key);

			// cassandraClient.insert(key, colPathName, value, timestamp,
			// IStorageManager.consistencyOnWrite);
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnsupportedEncodingException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

	}

	// TODO we need to redefine this function: we need to pass everything that
	// is needed
	@Override
	public byte[] getValue(final String key) throws IOBackEndException, InternalBackEndException {

		byte[] returnValue = null;

		try {
			// TODO MOVE TO THE NEW DATA STRUCTURE
			final String columnFamily = "Services";
			final ColumnPath colPathName = new ColumnPath(columnFamily);
			colPathName.setColumn(key.getBytes("UTF8"));

			thriftTransport.open();

			final ByteBuffer keyToBuffer = StringConverter.stringToByteBuffer(key);

			// TODO we need the Keyspace to be set to work here
			final Column col = cassandraClient.get(keyToBuffer, colPathName, IStorageManager.consistencyOnRead)
			        .getColumn();

			returnValue = col.value.array();
		} catch (final TTransportException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final UnsupportedEncodingException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final InvalidRequestException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final NotFoundException ex) {
			throw new IOBackEndException(ex.getMessage());
		} catch (final UnavailableException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TimedOutException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} catch (final TException ex) {
			throw new InternalBackEndException(ex.getMessage());
		} finally {
			thriftTransport.close();
		}

		return returnValue;
	}
}
