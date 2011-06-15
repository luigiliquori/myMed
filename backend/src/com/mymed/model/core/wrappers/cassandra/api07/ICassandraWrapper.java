package com.mymed.model.core.wrappers.cassandra.api07;

import java.nio.ByteBuffer;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.db.ColumnFamily;
import org.apache.cassandra.thrift.AuthenticationRequest;
import org.apache.cassandra.thrift.CfDef;
import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.IndexClause;
import org.apache.cassandra.thrift.KeyRange;
import org.apache.cassandra.thrift.KeySlice;
import org.apache.cassandra.thrift.KsDef;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SuperColumn;
import org.apache.cassandra.thrift.TokenRange;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;

/**
 * CassandraWrapper API - for Cassandra starting at version 0.7
 * 
 * @author Milo Casagrande
 * 
 */
public interface ICassandraWrapper {
	/**
	 * The port number to use for interacting with Cassandra
	 */
	int PORT_NUMBER = 9160;

	/**
	 * Authenticates with the cluster for operations on the specified keyspace
	 * using the specified {@link AuthenticationRequest} credentials
	 * 
	 * @param authRequest
	 * @throws InternalBackEndException
	 */
	void login(AuthenticationRequest authRequest) throws InternalBackEndException;

	/**
	 * Set the keyspace to work on
	 * 
	 * @param keySpace
	 *            the name of the keyspace
	 * @throws InternalBackEndException
	 */
	void set_keyspace(String keySpace) throws InternalBackEndException;

	/**
	 * Get the {@link Column} or {@link SuperColumn} at the given column path
	 * 
	 * @param key
	 * @param path
	 * @param level
	 *            the ConsistencyLevel to use
	 * @return the {@link Column} or {@link SuperColumn} at the given column
	 *         path
	 * @throws IOBackendException
	 *             if no value is present
	 * @throws InternalBackEndException
	 */
	ColumnOrSuperColumn get(String key, ColumnPath path, ConsistencyLevel level) throws IOBackEndException,
	        InternalBackEndException;

	/**
	 * Get the group of columns contained by the column parent specified by the
	 * given predicate
	 * 
	 * @param key
	 * @param parent
	 *            either a {@link ColumnFamily} or a {@link ColumnFamily}/
	 *            {@link SuperColum} pair
	 * @param predicate
	 *            the {@link SlicePredicate}
	 * @param level
	 *            the {@link ConsistencyLevel} to use
	 * @return the group of columns contained by the column parent
	 * @throws InternalBackEndException
	 */
	List<ColumnOrSuperColumn> get_slice(String key, ColumnParent parent, SlicePredicate predicate,
	        ConsistencyLevel level) throws InternalBackEndException;

	/**
	 * Retrieve slices for column parent and predicate on each of the given keys
	 * in parallel
	 * 
	 * @param keys
	 *            the list of keys
	 * @param parent
	 * @param predicate
	 *            the {@link SlicePredicate}
	 * @param level
	 *            the {@link ConsistencyLevel} to use
	 * @return the slices for the given keys
	 * @throws InternalBackEndException
	 */
	Map<ByteBuffer, List<ColumnOrSuperColumn>> multiget_slice(List<String> keys, ColumnParent parent,
	        SlicePredicate predicate, ConsistencyLevel level) throws InternalBackEndException;

	/**
	 * Counts the column present in the column parent within the predicate<br />
	 * The method is not O(1). It takes all the columns from disk to calculate
	 * the answer. The only benefit of the method is that you do not need to
	 * pull all the columns over Thrift interface to count them.
	 * 
	 * @param key
	 * @param parent
	 * @param predicate
	 *            the {@link SlicePredicate}
	 * @param level
	 *            the {@link ConsistencyLevel} to use
	 * @return the number of columns
	 * @throws InternalBackEndException
	 */
	int get_count(String key, ColumnParent parent, SlicePredicate predicate, ConsistencyLevel level)
	        throws InternalBackEndException;

	/**
	 * A combination of {@code multiget_slice} and {@code get_count}
	 * 
	 * @param keys
	 * @param parent
	 * @param predicate
	 *            the {@link SlicePredicate}
	 * @param level
	 *            the {@link ConsistencyLevel} to use
	 * @return
	 * @throws InternalBackEndException
	 */
	Map<ByteBuffer, Integer> multiget_count(List<String> keys, ColumnParent parent, SlicePredicate predicate,
	        ConsistencyLevel level) throws InternalBackEndException;

	/**
	 * Return a list of slices for the keys within the specified
	 * {@link KeyRange}<br />
	 * Note that when using RandomPartitioner, keys are stored in the order of
	 * their MD5 hash, making it impossible to get a meaningful range of keys
	 * between two endpoints.
	 * 
	 * @param parent
	 * @param predicate
	 *            the {@link SlicePredicate}
	 * @param range
	 *            the {@link KeyRange}
	 * @param level
	 *            the {@link ConsistencyLevel} to use
	 * @return a list of slices
	 * @throws InternalBackEndException
	 */
	List<KeySlice> get_range_slices(ColumnParent parent, SlicePredicate predicate, KeyRange range,
	        ConsistencyLevel level) throws InternalBackEndException;

	/**
	 * Return a list of slices, but uses {@link IndexClause} instead of
	 * {@link KeyRange}. To use this method, the underlying {@link ColumnFamily}
	 * of the {@link ColumnParent} must have been configured with a
	 * column_metadata attribute, specifying at least the name and index_type
	 * attributes. See {@link CfDef} and {@link ColumnDef} for the list of
	 * attributes.
	 * 
	 * @param parent
	 * @param clause
	 *            the {@link IndexClause} to use
	 * @param predicate
	 *            the {@link SlicePredicate}
	 * @param level
	 *            the {@link ConsistencyLevel} to use
	 * @return a list of slices
	 * @throws InternalBackEndException
	 */
	List<KeySlice> get_indexed_slices(ColumnParent parent, IndexClause clause, SlicePredicate predicate,
	        ConsistencyLevel level) throws InternalBackEndException;

	/**
	 * Insert a {@link Column} consisting of (name, value, timestamp) at the
	 * given ColumnPath.column_family and optional ColumnPath.super_column. Note
	 * that a {@link SuperColumn} cannot directly contain binary values, it can
	 * only contain sub-Columns. Only one sub-Column may be inserted at a time,
	 * as well.
	 * 
	 * @param key
	 * @param parent
	 * @param column
	 * @param level
	 * @throws InternalBackEndException
	 */
	void insert(String key, ColumnParent parent, Column column, ConsistencyLevel level) throws InternalBackEndException;

	/**
	 * Execute the specified mutations on the keyspace<br />
	 * A {@link Mutation} specifies either columns to insert or columns to
	 * delete
	 * 
	 * @param mutationMap
	 * @param level
	 * @throws InternalBackEndException
	 */
	void batch_mutate(Map<String, Map<String, List<Mutation>>> mutationMap, ConsistencyLevel level)
	        throws InternalBackEndException;

	/**
	 * Remove data from the row specified by {@code key} at the granularity
	 * specified by the {@link ColumnPath} {@code path}, and the given
	 * timestamp.
	 * 
	 * @param key
	 * @param path
	 * @param timeStamp
	 * @param level
	 *            the {@link ConsistencyLevel} to use
	 * @throws InternalBackEndException
	 */
	void remove(String key, ColumnPath path, long timeStamp, ConsistencyLevel level) throws InternalBackEndException;

	/**
	 * Remove all the rows from the given column family
	 * 
	 * @param columnFamily
	 * @throws InternalBackEndException
	 */
	void truncate(String columnFamily) throws InternalBackEndException;

	/**
	 * @return the name of the cluster
	 * @throws InternalBackEndException
	 */
	String describe_cluster_name() throws InternalBackEndException;

	/**
	 * @param keySpace
	 * @return the information about the specified keyspace
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	KsDef describe_keyspace(String keySpace) throws InternalBackEndException, IOBackEndException;

	/**
	 * @return a list of all the keyspaces configured for the cluster
	 * @throws InternalBackEndException
	 */
	List<KsDef> describe_keyspaces() throws InternalBackEndException;

	/**
	 * @return the name of the partitioner for the cluster
	 * @throws InternalBackEndException
	 */
	String describe_partitioner() throws InternalBackEndException;

	/**
	 * Get the token ring; a map of ranges to host addresses. Represented as a
	 * set of {@link TokenRange} instead of a map from range to list of end
	 * points
	 * 
	 * @param keySpace
	 * @return
	 * @throws InternalBackEndException
	 */
	List<TokenRange> describe_ring(String keySpace) throws InternalBackEndException;

	/**
	 * @return the name of the snitch used for the cluster
	 * @throws InternalBackEndException
	 */
	String describe_snitch() throws InternalBackEndException;

	/**
	 * @return the Thrift API version
	 * @throws InternalBackEndException
	 */
	String describe_version() throws InternalBackEndException;

	/**
	 * Adds a column family. This method will throw an exception if a column
	 * family with the same name is already associated with the keyspace
	 * 
	 * @param cfDef
	 * @return the new schema version ID
	 * @throws InternalBackEndException
	 */
	String system_add_column_family(CfDef cfDef) throws InternalBackEndException;

	/**
	 * Drops a column family. Creates a snapshot and then submits a 'graveyard'
	 * compaction during which the abandoned files will be deleted
	 * 
	 * @param columnFamily
	 * @return the new schema version ID
	 * @throws InternalBackEndException
	 */
	String system_drop_column_family(String columnFamily) throws InternalBackEndException;

	/**
	 * Creates a new keyspace and any column families defined with it. Callers
	 * are not required to first create an empty keyspace and then create column
	 * families for it.
	 * 
	 * @param ksDef
	 * @return the new schema version ID
	 * @throws InternalBackEndException
	 */
	String system_add_keyspace(KsDef ksDef) throws InternalBackEndException;

	/**
	 * Drops a keyspace. Creates a snapshot and then submits a 'graveyard'
	 * compaction during which the abandoned files will be deleted
	 * 
	 * @param keySpace
	 * @return the new schema version ID
	 * @throws InternalBackEndException
	 */
	String system_drop_keyspace(String keySpace) throws InternalBackEndException;

	/**
	 * Update the properties of a column family
	 * 
	 * @param columnFamily
	 * @return the new schema version ID
	 * @throws InternalBackEndException
	 */
	String system_update_column_family(CfDef columnFamily) throws InternalBackEndException;

	/**
	 * Update the properties of a keyspace
	 * 
	 * @param keySpace
	 * @return the new schema version ID
	 * @throws InternalBackEndException
	 */
	String system_update_keyspace(KsDef keySpace) throws InternalBackEndException;
}
