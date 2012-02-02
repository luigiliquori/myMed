/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.mymed.controller.core.manager.reputation.db.table;

/**
 * The interface a Class which wants to be persistable to Cassandra must satisfy.
 *
 * Essentially it must specify what ColumnFamilyName to use, and how to get the key.
 * @author peter
 */
public interface ICassandraPersistable {

    public String getColumnFamilyName();

    public String getKey();
}
