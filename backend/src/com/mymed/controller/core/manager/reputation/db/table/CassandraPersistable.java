/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.mymed.controller.core.manager.reputation.db.table;

/**
 * A base class to implement ICassandraPersistable interface.
 *
 * TODO: implement getKey which would use .xml to create key
 * @author peter
 */
public class CassandraPersistable implements ICassandraPersistable{

    /**
     * Default implementation returns Class name.
     * @return Last component of full Class name.
     */
    @Override
    public String getColumnFamilyName() {
            final String[] objParts = getClass().getName().split("\\.");
            return objParts[objParts.length - 1];
    }

    @Override
    public String getKey() {
        throw new UnsupportedOperationException("Not supported yet.");
    }

}
