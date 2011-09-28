/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.globals;

/**
 *
 * @author piccolo
 */
public class Constants {
    public static final String KEYSPACE = "prova3";
    public static final int REPLICATION_FACTOR = 1;
    public static final String CONFIGURATION_FILE_PATH = "conf/config.xml";
    public static final String DATA_MODEL_FILE_PATH = "conf/reputation-data-model.xml";
    public static final String DEFAULT_COMPARATOR = "primary_key.comparator.OrderByTimeVerdict";
    public static final String CASSANDRA_STRATEGY = "org.apache.cassandra.locator.SimpleStrategy";
    public static final String PRIMARY_KEY_PACKAGE = "com.mymed.controller.core.manager.reputation.primary_key";
    public static final String DB_TABLE_PACKAGE = "com.mymed.controller.core.manager.reputation.db.table";
}
