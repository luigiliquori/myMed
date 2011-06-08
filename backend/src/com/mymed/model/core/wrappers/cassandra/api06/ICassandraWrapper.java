package com.mymed.model.core.wrappers.cassandra.api06;

import java.util.List;
import java.util.Map;
import java.util.Set;

import org.apache.cassandra.thrift.AuthenticationRequest;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.KeyRange;
import org.apache.cassandra.thrift.KeySlice;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.TokenRange;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;

/**
 * CassandraWrapper API - for Cassandra 0.6
 * 
 * @author lvanni
 * 
 */
public interface ICassandraWrapper {

	/**
	 * Authenticates with the cluster for operations on the specified keyspace
	 * using the specified AuthenticationRequest credentials. Throws
	 * AuthenticationException if the credentials are invalid or
	 * AuthorizationException if the credentials are valid, but not for the
	 * specified keyspace.
	 * 
	 * @param keyspace
	 * @param auth_request
	 */
	void login(String keyspace, AuthenticationRequest authRequest) throws InternalBackEndException ;

	/**
	 * Get the Column or SuperColumn at the given column_path. If no value is
	 * present, NotFoundException is thrown. (This is the only method that can
	 * throw an exception under non-failure conditions.)
	 * 
	 * @param keyspace
	 * @param key
	 * @param column_path
	 * @param consistency_level
	 * @return
	 */
	ColumnOrSuperColumn get(String keyspace, String key,
			ColumnPath colPathName, ConsistencyLevel consistencyLevel)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * Get the group of columns contained by column_parent (either a
	 * ColumnFamily name or a ColumnFamily/SuperColumn name pair) specified by
	 * the given SlicePredicate struct.
	 * 
	 * @param keyspace
	 * @param key
	 * @param column_parent
	 * @param predicate
	 * @param consistency_level
	 * @return
	 */
	List<ColumnOrSuperColumn> get_slice(String keyspace, String key,
			ColumnParent columnParent, SlicePredicate predicate,
			ConsistencyLevel consistencyLevel)  throws InternalBackEndException, IOBackEndException;

	/**
	 * Retrieves slices for column_parent and predicate on each of the given
	 * keys in parallel. Keys are a `list<string> of the keys to get slices for.
	 * This is similar to get_range_slice (Cassandra 0.5) except operating on a
	 * set of non-contiguous keys instead of a range of keys.
	 * 
	 * @param keyspace
	 * @param keys
	 * @param column_parent
	 * @param predicate
	 * @param consistency_level
	 * @return
	 */
	Map<String, List<ColumnOrSuperColumn>> multiget_slice(
			String keyspace, List<String> keys, ColumnParent columnParent,
			SlicePredicate predicate, ConsistencyLevel consistencyLevel) throws InternalBackEndException;

	/**
	 * Counts the columns present in column_parent. The method is not O(1). It
	 * takes all the columns from disk to calculate the answer. The only benefit
	 * of the method is that you do not need to pull all the columns over Thrift
	 * interface to count them.
	 * 
	 * @param keyspace
	 * @param key
	 * @param column_parent
	 * @param consistency_level
	 * @return
	 */
	int get_count(String keyspace, String key, ColumnParent columnParent,
			ConsistencyLevel consistency_level) throws InternalBackEndException;

	/**
	 * Returns a list of slices for the keys within the specified KeyRange.
	 * Unlike get_key_range, this applies the given predicate to all keys in the
	 * range, not just those with undeleted matching data. This method is only
	 * allowed when using an order-preserving partitioner.
	 * 
	 * @param keyspace
	 * @param column_parent
	 * @param predicate
	 * @param range
	 * @param consistency_level
	 * @return
	 */
	List<KeySlice> get_range_slices(String keyspace,
			ColumnParent columnParent, SlicePredicate predicate, KeyRange range,
			ConsistencyLevel consistencyLevel)  throws InternalBackEndException;

	/**
	 * Insert a Column consisting of (column_path.column, value, timestamp) at
	 * the given column_path.column_family and optional
	 * column_path.super_column. Note that column_path.column is here required,
	 * since a SuperColumn cannot directly contain binary values -- it can only
	 * contain sub-Columns.
	 * 
	 * @param keyspace
	 * @param key
	 * @param column_path
	 * @param value
	 * @param timestamp
	 * @param consistency_level
	 */
	void insert(String keyspace, String key, ColumnPath columnPath,
			byte[] value, long timestamp, ConsistencyLevel consistencyLevel)
			throws InternalBackEndException;

	/**
	 * xecutes the specified mutations on the keyspace. mutation_map is a
	 * map<string, map<string, list<Mutation>>>; the outer map maps the key to
	 * the inner map, which maps the column family to the Mutation; can be read
	 * as: map<key : string, map<column_family : string, list<Mutation>>>. To be
	 * more specific, the outer map key is a row key, the inner map key is the
	 * column family name. A Mutation specifies either columns to insert or
	 * columns to delete. See Mutation and Deletion above for more details.
	 * 
	 * @param keyspace
	 * @param mutation_map
	 * @param consistency_level
	 */
	void batch_mutate(String keyspace, Map<String, Map<String, List<Mutation>>> mutation_map,
			ConsistencyLevel consistency_level) throws InternalBackEndException;

	/**
	 * Remove data from the row specified by key at the granularity specified by
	 * column_path, and the given timestamp. Note that all the values in
	 * column_path besides column_path.column_family are truly optional: you can
	 * remove the entire row by just specifying the ColumnFamily, or you can
	 * remove a SuperColumn or a single Column by specifying those levels too.
	 * Note that the timestamp is needed, so that if the commands are replayed
	 * in a different order on different nodes, the same result is produced.
	 * 
	 * @param keyspace
	 * @param key
	 * @param column_path
	 * @param timestamp
	 * @param consistency_level
	 */
	void remove(String keyspace, String key, ColumnPath columnPath,
			long timestamp, ConsistencyLevel consistencyLevel) throws InternalBackEndException;

	/**
	 * Gets information about the specified keyspace.
	 * 
	 * @param keyspace
	 * @return
	 */
	Map<String, Map<String, String>> describe_keyspace(String keyspace) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * Gets a list of all the keyspaces configured for the cluster.
	 * 
	 * @return
	 */
	Set<String> describe_keyspaces() throws InternalBackEndException;

	/**
	 * Gets the name of the cluster.
	 * 
	 * @return
	 */
	String describe_cluster_name() throws InternalBackEndException;

	/**
	 * Gets the CassandraWrapper API version.
	 * 
	 * @return
	 */
	String describe_version() throws InternalBackEndException;

	/**
	 * Gets the token ring; a map of ranges to host addresses. Represented as a
	 * set of TokenRange instead of a map from range to list of endpoints,
	 * because you can't use Thrift structs as map keys:
	 * https://issues.apache.org/jira/browse/THRIFT-162 for the same reason, we
	 * can't return a set here, even though order is neither important nor
	 * predictable.
	 * 
	 * @param keyspace
	 * @return
	 */
	List<TokenRange> describe_ring(String keyspace) throws InternalBackEndException;
}
