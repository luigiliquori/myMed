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
package com.mymed.controller.core.manager.storage.v2;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 * This class represent the DAO pattern: Access to data varies depending on the
 * source of the data. Access to persistent storage, such as to a database,
 * varies greatly depending on the type of storage
 * 
 * Use a Data Access Object (DAO) to abstract and encapsulate all access to the
 * data source. The DAO manages the connection with the data source to obtain
 * and store data.
 * 
 * @author lvanni
 * 
 */
public class StorageManager extends com.mymed.controller.core.manager.storage.StorageManager {
 
    /**
     * Default Constructor: will create a ServiceManger on top of a Cassandra
     * Wrapper
     */
    public StorageManager() {
        this(new WrapperConfiguration(CONFIG_FILE));
    }

    /**
     * will create a ServiceManger on top of the WrapperType And use the specific
     * configuration file for the transport layer
     * 
     * @param conf
     *          The configuration of the transport layer
     * @throws InternalBackEndException
     */
    public StorageManager(final WrapperConfiguration conf) {
        super();
        wrapper = new CassandraWrapper(conf.getCassandraListenAddress(), conf.getThriftPort());
    }

}
