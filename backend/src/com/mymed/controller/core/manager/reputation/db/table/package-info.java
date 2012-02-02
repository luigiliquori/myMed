/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This package provides the classes which
 * need to be readable and writable to and from Cassandra
 * for the reputation system.
 * <p>
 * To be persistable, a class must implement the ICassandraPersistable
 * interface.  CassandraPersistable is a base class which partially implements
 * it and can be extended.
 * <p>
 * A Verdict is the principle class to be persisted, it encapsulates the votes
 * given.
 * <p>
 * A ReputationEntity is basically a cache for holding the number of votes and the
 * calculated reputation of a reputation entity.
 * <p>
 * A 'reputation entity' is simply a String consisting of a unique ID.
 */
package com.mymed.controller.core.manager.reputation.db.table;
