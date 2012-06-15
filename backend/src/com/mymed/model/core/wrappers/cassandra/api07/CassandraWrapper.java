/*
 * Copyright 2012 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package com.mymed.model.core.wrappers.cassandra.api07;

import java.net.InetAddress;
import java.net.UnknownHostException;
import java.nio.ByteBuffer;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

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

import ch.qos.logback.classic.Logger;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.connection.Connection;
import com.mymed.controller.core.manager.connection.ConnectionManager;
import com.mymed.utils.MConverter;
import com.mymed.utils.MLogger;

/**
 * Wrapper for the Cassandra API v0.7.<br />
 * For more info about the API, check <a href="http://wiki.apache.org/cassandra/API">the API web page</a>.
 * 
 * @author Milo Casagrande
 */
public class CassandraWrapper implements ICassandraWrapper {
    // The port number to use for interacting with Cassandra
    private static final int PORT_NUMBER = 4201;

    /**
     * Error string when trying to connect to a host.
     */
    private static final String ERROR_CONNECTION = "Impossible to connect to a host";

    /**
     * The name of the default keyspace we use for Cassandra.
     */
    private static final String KEYSPACE = "Mymed";

    /**
     * Default logger.
     */
    private static final Logger LOGGER = MLogger.getLogger();

    /**
     * The connection manager that provides the connection to Cassandra
     */
    private static final ConnectionManager manager = ConnectionManager.getInstance();

    /**
     * The real connection.
     */
    private Connection connection;

    // Address and port to use for establishing a connection
    private final String address;
    private final int port;

    /**
     * Object to synchronize with.
     */
    private static final Object LOCK = new Object();

    /**
     * Empty constructor to create a normal Cassandra client, with address the machine address, and port the default
     * port
     * 
     * @throws UnknownHostException
     */
    public CassandraWrapper() throws UnknownHostException {
        this(InetAddress.getLocalHost().getHostAddress(), PORT_NUMBER);
    }

    /**
     * Create the Cassandra client
     * 
     * @param address
     *            the address to use for the connection
     * @param port
     *            the port to use for the connection
     */
    public CassandraWrapper(final String address, final int port) {
        this.address = address;
        this.port = port;
    }

    /**
     * Retrieve a connection from the pool and return the Cassandra client
     * 
     * @return the Cassandra client of the connection
     * @throws InternalBackEndException
     */
    private Client getClient() throws InternalBackEndException {
        synchronized (LOCK) {
            connection = (Connection) manager.checkOut(address, port);
            
            if (connection == null) {
            	throw new InternalBackEndException("The backend is unable to connect to Cassandra on: " + address + ":" + port);
            }
            
            try {
                //LOGGER.info("Setting keyspace to '{}'", KEYSPACE);
                connection.getClient().set_keyspace(KEYSPACE);
            } catch (final InvalidRequestException ex) {
                LOGGER.debug("Error setting the keyspace '{}'", KEYSPACE, ex);
                throw new InternalBackEndException("Error setting the keyspace"); // NOPMD
            } catch (final TException ex) {
                LOGGER.debug("Error setting the keyspace '{}'", KEYSPACE, ex);
                throw new InternalBackEndException("Error setting the keyspace"); // NOPMD
            }

            LOCK.notifyAll();
            return connection.getClient();
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#login(org.apache.cassandra.thrift.
     * AuthenticationRequest)
     */
    @Override
    public void login(final AuthenticationRequest authRequest) throws InternalBackEndException {
        synchronized (LOCK) {
            try {
                getClient().login(authRequest);
            } catch (final AuthenticationException ex) {
                throw new InternalBackEndException(ex);
            } catch (final AuthorizationException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#set_keyspace(java.lang.String)
     */
    @Override
    public void set_keyspace(final String keySpace) throws InternalBackEndException {
        synchronized (LOCK) {
            try {
                getClient().set_keyspace(keySpace);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#get(java.lang.String,
     * org.apache.cassandra.thrift.ColumnPath, org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public ColumnOrSuperColumn get(final String key, final ColumnPath path, final ConsistencyLevel level)
                    throws IOBackEndException, InternalBackEndException {
        /*
         * Little trick in order to avoid TimedOutException on Cassandra. We experienced some timeouts caused by one or
         * two nodes to be down, but still part of the ring. Looks like Cassandra in the version we use tries to connect
         * to a node that is down nonetheless, resulting in timeout exceptions. We retry three times the get operation,
         * in order to pass over this error.
         */
        int ttl = 3;

        do {
            try {
                return get(key, path, level, ttl--);
            } catch (final TimedOutException ex) {
                LOGGER.debug(ERROR_CONNECTION, ex);
                continue;
            }
        } while (ttl > 0);

        throw new InternalBackEndException("TimedOutException");
    }

    /*
     * Where we perform the real get operation
     */
    private ColumnOrSuperColumn get(final String key, final ColumnPath path, final ConsistencyLevel level, final int ttl)
                    throws IOBackEndException, InternalBackEndException, TimedOutException {

        final ByteBuffer keyToBuffer = MConverter.stringToByteBuffer(key);

        synchronized (LOCK) {
            ColumnOrSuperColumn result = null;

            try {
                result = getClient().get(keyToBuffer, path, level);
            } catch (final NotFoundException ex) {
                throw new IOBackEndException(ex.getMessage(), 404); // NOPMD
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return result;
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#get_slice(java.lang.String,
     * org.apache.cassandra.thrift.ColumnParent, org.apache.cassandra.thrift.SlicePredicate,
     * org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public List<ColumnOrSuperColumn> get_slice(final String key, final ColumnParent parent,
                    final SlicePredicate predicate, final ConsistencyLevel level) throws InternalBackEndException {
        /*
         * Little trick in order to avoid TimedOutException on Cassandra. We experienced some timeouts caused by one or
         * two nodes to be down, but still part of the ring. Looks like Cassandra in the version we use tries to connect
         * to a node that is down nonetheless, resulting in timeout exceptions. We retry three times the get operation,
         * in order to pass over this error.
         */
        int ttl = 3;

        do {
            try {
                return get_slice(key, parent, predicate, level, ttl--);
            } catch (final TimedOutException ex) {
                LOGGER.debug(ERROR_CONNECTION, ex);
                continue;
            }
        } while (ttl > 0);

        throw new InternalBackEndException("TimedOutException");
    }

    /*
     * Where we perform the real get operation
     */
    private List<ColumnOrSuperColumn> get_slice(final String key, final ColumnParent parent,
                    final SlicePredicate predicate, final ConsistencyLevel level, final int ttl)
                    throws InternalBackEndException, TimedOutException {

        final ByteBuffer keyToBuffer = MConverter.stringToByteBuffer(key);

        synchronized (LOCK) {
            List<ColumnOrSuperColumn> result = null;

            try {
                result = getClient().get_slice(keyToBuffer, parent, predicate, level);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return result;
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#multiget_slice(java.util.List,
     * org.apache.cassandra.thrift.ColumnParent, org.apache.cassandra.thrift.SlicePredicate,
     * org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public Map<ByteBuffer, List<ColumnOrSuperColumn>> multiget_slice(final List<String> keys,
                    final ColumnParent parent, final SlicePredicate predicate, final ConsistencyLevel level)
                    throws InternalBackEndException {
        /*
         * Little trick in order to avoid TimedOutException on Cassandra. We experienced some timeouts caused by one or
         * two nodes to be down, but still part of the ring. Looks like Cassandra in the version we use tries to connect
         * to a node that is down nonetheless, resulting in timeout exceptions. We retry three times the get operation,
         * in order to pass over this error.
         */
        int ttl = 3;

        do {
            try {
                return multiget_slice(keys, parent, predicate, level, ttl--);
            } catch (final TimedOutException ex) {
                LOGGER.debug(ERROR_CONNECTION, ex);
                continue;
            }
        } while (ttl > 0);

        throw new InternalBackEndException("TimedOutException");
    }

    /*
     * Where we perform the real get operation
     */
    private Map<ByteBuffer, List<ColumnOrSuperColumn>> multiget_slice(final List<String> keys,
                    final ColumnParent parent, final SlicePredicate predicate, final ConsistencyLevel level,
                    final int ttl) throws InternalBackEndException, TimedOutException {

        final List<ByteBuffer> keysToBuffer = MConverter.stringToByteBuffer(keys);

        synchronized (LOCK) {
            Map<ByteBuffer, List<ColumnOrSuperColumn>> result = null;

            try {
                result = getClient().multiget_slice(keysToBuffer, parent, predicate, level);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return result;
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#get_count(java.lang.String,
     * org.apache.cassandra.thrift.ColumnParent, org.apache.cassandra.thrift.SlicePredicate,
     * org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public int get_count(final String key, final ColumnParent parent, final SlicePredicate predicate,
                    final ConsistencyLevel level) throws InternalBackEndException {
        /*
         * Little trick in order to avoid TimedOutException on Cassandra. We experienced some timeouts caused by one or
         * two nodes to be down, but still part of the ring. Looks like Cassandra in the version we use tries to connect
         * to a node that is down nonetheless, resulting in timeout exceptions. We retry three times the get operation,
         * in order to pass over this error.
         */
        int ttl = 3;

        do {
            try {
                return get_count(key, parent, predicate, level, ttl--);
            } catch (final TimedOutException ex) {
                LOGGER.debug(ERROR_CONNECTION, ex);
                continue;
            }
        } while (ttl > 0);

        throw new InternalBackEndException("TimedOutException");
    }

    /*
     * Where we perform the real get operation
     */
    private int get_count(final String key, final ColumnParent parent, final SlicePredicate predicate,
                    final ConsistencyLevel level, final int ttl) throws InternalBackEndException, TimedOutException {

        final ByteBuffer keyToBuffer = MConverter.stringToByteBuffer(key);

        synchronized (LOCK) {
            int result = -1;

            try {
                result = getClient().get_count(keyToBuffer, parent, predicate, level);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return result;
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#multiget_count(java.util.List,
     * org.apache.cassandra.thrift.ColumnParent, org.apache.cassandra.thrift.SlicePredicate,
     * org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public Map<ByteBuffer, Integer> multiget_count(final List<String> keys, final ColumnParent parent,
                    final SlicePredicate predicate, final ConsistencyLevel level) throws InternalBackEndException {
        /*
         * Little trick in order to avoid TimedOutException on Cassandra. We experienced some timeouts caused by one or
         * two nodes to be down, but still part of the ring. Looks like Cassandra in the version we use tries to connect
         * to a node that is down nonetheless, resulting in timeout exceptions. We retry three times the get operation,
         * in order to pass over this error.
         */
        int ttl = 3;

        do {
            try {
                return multiget_count(keys, parent, predicate, level, ttl--);
            } catch (final TimedOutException ex) {
                LOGGER.debug(ERROR_CONNECTION, ex);
                continue;
            }
        } while (ttl > 0);

        throw new InternalBackEndException("TimedOutException");
    }

    /*
     * Where we perform the real get operation
     */
    private Map<ByteBuffer, Integer> multiget_count(final List<String> keys, final ColumnParent parent,
                    final SlicePredicate predicate, final ConsistencyLevel level, final int ttl)
                    throws InternalBackEndException, TimedOutException {

        final List<ByteBuffer> keysToBuffer = MConverter.stringToByteBuffer(keys);

        synchronized (LOCK) {
            Map<ByteBuffer, Integer> result = null;

            try {
                result = getClient().multiget_count(keysToBuffer, parent, predicate, level);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return result;
        }
    }

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#get_range_slices(org.apache.cassandra.thrift.
     * ColumnParent, org.apache.cassandra.thrift.SlicePredicate, org.apache.cassandra.thrift.KeyRange,
     * org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public List<KeySlice> get_range_slices(final ColumnParent parent, final SlicePredicate predicate,
                    final KeyRange range, final ConsistencyLevel level) throws InternalBackEndException {
        /*
         * Little trick in order to avoid TimedOutException on Cassandra. We experienced some timeouts caused by one or
         * two nodes to be down, but still part of the ring. Looks like Cassandra in the version we use tries to connect
         * to a node that is down nonetheless, resulting in timeout exceptions. We retry three times the get operation,
         * in order to pass over this error.
         */
        int ttl = 3;

        do {
            try {
                return get_range_slices(parent, predicate, range, level, ttl--);
            } catch (final TimedOutException ex) {
                LOGGER.debug(ERROR_CONNECTION, ex);
                continue;
            }
        } while (ttl > 0);

        throw new InternalBackEndException("TimedOutException");
    }

    /*
     * Where we perform the real get operation
     */
    private List<KeySlice> get_range_slices(final ColumnParent parent, final SlicePredicate predicate,
                    final KeyRange range, final ConsistencyLevel level, final int ttl) throws InternalBackEndException,
                    TimedOutException {

        synchronized (LOCK) {
            List<KeySlice> result = null;

            try {
                result = getClient().get_range_slices(parent, predicate, range, level);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return result;
        }
    }

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#get_indexed_slices(org.apache.cassandra.thrift
     * .ColumnParent, org.apache.cassandra.thrift.IndexClause, org.apache.cassandra.thrift.SlicePredicate,
     * org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public List<KeySlice> get_indexed_slices(final ColumnParent parent, final IndexClause clause,
                    final SlicePredicate predicate, final ConsistencyLevel level) throws InternalBackEndException {

        synchronized (LOCK) {
            List<KeySlice> result = null;

            try {
                result = getClient().get_indexed_slices(parent, clause, predicate, level);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TimedOutException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return result;
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#insert(java.lang.String,
     * org.apache.cassandra.thrift.ColumnParent, org.apache.cassandra.thrift.Column,
     * org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public void insert(final String key, final ColumnParent parent, final Column column, final ConsistencyLevel level)
                    throws InternalBackEndException {

        final ByteBuffer keyToBuffer = MConverter.stringToByteBuffer(key);

        synchronized (LOCK) {
            try {
                getClient().insert(keyToBuffer, parent, column, level);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TimedOutException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#batch_mutate(java.util.Map,
     * org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public void batch_mutate(final Map<String, Map<String, List<Mutation>>> mutationMap, final ConsistencyLevel level)
                    throws InternalBackEndException {

        final Map<ByteBuffer, Map<String, List<Mutation>>> newMap = new HashMap<ByteBuffer, Map<String, List<Mutation>>>(
                        mutationMap.size());

        ByteBuffer keyToBuffer = null;
        Map<String, List<Mutation>> value = null;

        final Iterator<Entry<String, Map<String, List<Mutation>>>> iterator = mutationMap.entrySet().iterator();
        while (iterator.hasNext()) {
            final Entry<String, Map<String, List<Mutation>>> entry = iterator.next();
            keyToBuffer = MConverter.stringToByteBuffer(entry.getKey());
            value = entry.getValue();

            newMap.put(keyToBuffer, value);
        }

        batchMutate(newMap, level);
    }

    public void batchMutate(final Map<ByteBuffer, Map<String, List<Mutation>>> mutationMap, final ConsistencyLevel level)
                    throws InternalBackEndException {
        synchronized (LOCK) {
            try {
                getClient().batch_mutate(mutationMap, level);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TimedOutException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#remove(java.lang.String,
     * org.apache.cassandra.thrift.ColumnPath, long, org.apache.cassandra.thrift.ConsistencyLevel)
     */
    @Override
    public void remove(final String key, final ColumnPath path, final long timeStamp, final ConsistencyLevel level)
                    throws InternalBackEndException {

        final ByteBuffer keyToBuffer = MConverter.stringToByteBuffer(key);

        synchronized (LOCK) {
            try {
                getClient().remove(keyToBuffer, path, timeStamp, level);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TimedOutException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#truncate(java.lang.String)
     */
    @Override
    public void truncate(final String columnFamily) throws InternalBackEndException {
        synchronized (LOCK) {
            try {
                getClient().truncate(columnFamily);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final UnavailableException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#describe_cluster_name()
     */
    @Override
    public String describe_cluster_name() throws InternalBackEndException {
        String result = null;

        try {
            result = getClient().describe_cluster_name();
        } catch (final TException ex) {
            throw new InternalBackEndException(ex);
        } finally {
            manager.checkIn(connection);
        }

        return result;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#describe_keyspace(java.lang.String)
     */
    @Override
    public KsDef describe_keyspace(final String keySpace) throws InternalBackEndException, IOBackEndException {

        KsDef keySpaceDef = null;

        try {
            keySpaceDef = getClient().describe_keyspace(keySpace);
        } catch (final NotFoundException ex) {
            throw new IOBackEndException(ex.getMessage(), 404); // NOPMD
        } catch (final InvalidRequestException ex) {
            throw new InternalBackEndException(ex);
        } catch (final TException ex) {
            throw new InternalBackEndException(ex);
        } finally {
            manager.checkIn(connection);
        }

        return keySpaceDef;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#describe_keyspaces()
     */
    @Override
    public List<KsDef> describe_keyspaces() throws InternalBackEndException {

        List<KsDef> keySpaceList = null;

        try {
            keySpaceList = getClient().describe_keyspaces();
        } catch (final InvalidRequestException ex) {
            throw new InternalBackEndException(ex);
        } catch (final TException ex) {
            throw new InternalBackEndException(ex);
        } finally {
            manager.checkIn(connection);
        }

        return keySpaceList;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#describe_partitioner()
     */
    @Override
    public String describe_partitioner() throws InternalBackEndException {

        String partitioner = null;

        try {
            partitioner = getClient().describe_partitioner();
        } catch (final TException ex) {
            throw new InternalBackEndException(ex);
        } finally {
            manager.checkIn(connection);
        }

        return partitioner;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#describe_ring(java.lang.String)
     */
    @Override
    public List<TokenRange> describe_ring(final String keySpace) throws InternalBackEndException {

        List<TokenRange> ring = null;

        try {
            ring = getClient().describe_ring(keySpace);
        } catch (final InvalidRequestException ex) {
            throw new InternalBackEndException(ex);
        } catch (final TException ex) {
            throw new InternalBackEndException(ex);
        } finally {
            manager.checkIn(connection);
        }

        return ring;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#describe_snitch()
     */
    @Override
    public String describe_snitch() throws InternalBackEndException {

        String snitch = null;

        try {
            snitch = getClient().describe_snitch();
        } catch (final TException ex) {
            throw new InternalBackEndException(ex);
        } finally {
            manager.checkIn(connection);
        }

        return snitch;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#describe_version()
     */
    @Override
    public String describe_version() throws InternalBackEndException {

        String version = null;

        try {
            version = getClient().describe_version();
        } catch (final TException ex) {
            throw new InternalBackEndException(ex);
        } finally {
            manager.checkIn(connection);
        }

        return version;
    }

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#system_add_column_family(org.apache.cassandra
     * .thrift.CfDef)
     */
    @Override
    public String system_add_column_family(final CfDef cfDef) throws InternalBackEndException {
        synchronized (LOCK) {
            String schemaId = null;

            try {
                schemaId = getClient().system_add_column_family(cfDef);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return schemaId;
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#system_drop_column_family(java.lang.String)
     */
    @Override
    public String system_drop_column_family(final String columnFamily) throws InternalBackEndException {
        synchronized (LOCK) {
            String schemaId = null;

            try {
                schemaId = getClient().system_drop_column_family(columnFamily);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return schemaId;
        }
    }

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#system_add_keyspace(org.apache.cassandra.thrift
     * .KsDef)
     */
    @Override
    public String system_add_keyspace(final KsDef ksDef) throws InternalBackEndException {
        synchronized (LOCK) {
            String schemaId = null;

            try {
                schemaId = getClient().system_add_keyspace(ksDef);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return schemaId;
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#system_drop_keyspace(java.lang.String)
     */
    @Override
    public String system_drop_keyspace(final String keySpace) throws InternalBackEndException {
        synchronized (LOCK) {
            String schemaId = null;

            try {
                schemaId = getClient().system_drop_keyspace(keySpace);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return schemaId;
        }
    }

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#system_update_column_family(org.apache.cassandra
     * .thrift.CfDef)
     */
    @Override
    public String system_update_column_family(final CfDef columnFamily) throws InternalBackEndException {
        synchronized (LOCK) {
            String newSchemaId = null;

            try {
                newSchemaId = getClient().system_update_column_family(columnFamily);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return newSchemaId;
        }
    }

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.model.core.wrappers.cassandra.api07.ICassandraWrapper#system_update_keyspace(org.apache.cassandra.thrift
     * .KsDef)
     */
    @Override
    public String system_update_keyspace(final KsDef keySpace) throws InternalBackEndException {
        synchronized (LOCK) {
            String newSchemaId = null;

            try {
                newSchemaId = getClient().system_update_keyspace(keySpace);
            } catch (final InvalidRequestException ex) {
                throw new InternalBackEndException(ex);
            } catch (final TException ex) {
                throw new InternalBackEndException(ex);
            } finally {
                manager.checkIn(connection);
                LOCK.notifyAll();
            }

            return newSchemaId;
        }
    }

    /**
     * Retrieve the specified column family id in the given keyspace
     * 
     * @param keySpaceName
     *            the keyspace where the column family is located
     * @param columnFamilyName
     *            the column family to search
     * @return the id of the column family
     * @throws InternalBackEndException
     * @throws IOBackEndException
     */
    public int get_cf_id(final String keySpaceName, final String columnFamilyName) throws InternalBackEndException,
                    IOBackEndException {

        int cfId = 0;
        final Iterator<CfDef> iter = describe_keyspace(keySpaceName).getCf_defsIterator();

        while (iter.hasNext()) {
            final CfDef def = iter.next();

            if (columnFamilyName.equals(def.getName())) {
                cfId = def.getId();
                break;
            }
        }

        return cfId;
    }
}
