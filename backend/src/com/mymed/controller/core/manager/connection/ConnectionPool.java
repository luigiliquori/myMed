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
package com.mymed.controller.core.manager.connection;

import java.util.concurrent.ArrayBlockingQueue;
import java.util.concurrent.atomic.AtomicInteger;

import ch.qos.logback.classic.Logger;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.utils.MLogger;

/**
 * Implementation of a connection pool
 * 
 * @author Milo Casagrande
 * 
 */
public class ConnectionPool implements IConnectionPool {
  // The default capacity of the pool, this is set to a power of 2: 2^7
  private static final int DEFAULT_CAP = 128;

  // The maximum capacity of the pool, this is set to a power of 2: 2^12
  private static final int MAX_CAP = 4096;

  private static final Logger LOGGER = MLogger.getLogger();

  // The real capacity of the pool
  private final int capacity;

  // Number of connections
  private final AtomicInteger checkedOut = new AtomicInteger(0);

  private final String address;

  private final int port;

  // The real pool
  private final ArrayBlockingQueue<IConnection> available;

  // Object used to sync operations
  private final Object SYNC = new Object();

  /**
   * Create a new connection pool with a maximum capacity of 100.
   * 
   * @param address
   *          the address where to connect to
   * @param port
   *          the port to use for the connection
   */
  public ConnectionPool(final String address, final int port) {
    this(address, port, DEFAULT_CAP);
  }

  /**
   * Create a new connection pool with initial capacity defined by
   * {@code capacity}. If {@code capacity} is zero, the pool is limit-less.
   * 
   * @param address
   *          the address where to connect to
   * @param port
   *          the port to use for the connection
   * @param capacity
   *          the maximum capacity of the pool
   */
  public ConnectionPool(final String address, final int port, final int capacity) {
    if (capacity > MAX_CAP || capacity == 0) {
      this.capacity = MAX_CAP;
    } else {
      this.capacity = capacity;
    }

    this.address = address;
    this.port = port;

    available = new ArrayBlockingQueue<IConnection>(this.capacity, true);
  }

  /**
   * Create a new connection and open it
   * 
   * @return the opened connection or null if there are errors opening the
   *         connection
   */
  private IConnection newConnection() {
    IConnection con = null;

    try {
      con = new Connection(address, port);
      con.open();
    } catch (final InternalBackEndException ex) {
      // If we cannot open the connection, we return null
      LOGGER.info(ex.getMessage());
      LOGGER.debug(ex.getMessage(), ex.getCause());
      con = null; // NOPMD
    }

    return con;
  }

  /*
   * (non-Javadoc)
   * 
   * @see
   * com.mymed.controller.core.manager.connection.IConnectionPool#checkOut()
   */
  @Override
  public IConnection checkOut() {
    IConnection con = null;

    synchronized (SYNC) {
      if (getSize() > 0) {
        con = available.poll();

        if (!con.isOpen()) {
          // If we had a closed or null connection we try again
          LOGGER.info("Got a closed connection. Retrying...");
          con = checkOut();
        }
      } else if (capacity == 0 || checkedOut.get() < capacity) {
        con = newConnection();
      }

      if (con != null) {
        checkedOut.incrementAndGet();
      }

      SYNC.notifyAll();
    }

    return con;
  }

  /*
   * (non-Javadoc)
   * 
   * @see com.mymed.controller.core.manager.connection.IConnectionPool#checkIn(
   * com.mymed.controller.core.manager.connection.IConnection)
   */
  @Override
  public void checkIn(final IConnection connection) {
    synchronized (SYNC) {
      available.add(connection);
      checkedOut.decrementAndGet();

      SYNC.notifyAll();
    }
  }

  /*
   * (non-Javadoc)
   * 
   * @see com.mymed.controller.core.manager.connection.IConnectionPool#getSize()
   */
  @Override
  public int getSize() {
    return available.size();
  }

  /*
   * (non-Javadoc)
   * 
   * @see
   * com.mymed.controller.core.manager.connection.IConnectionPool#getCapacity ()
   */
  @Override
  public int getCapacity() {
    return capacity;
  }
}
