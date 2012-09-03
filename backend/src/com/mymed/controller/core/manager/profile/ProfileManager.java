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
package com.mymed.controller.core.manager.profile;


import static com.mymed.utils.MiscUtils.encode;

import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage an user profile.
 *
 * @author lvanni
 */
public class ProfileManager extends AbstractManager implements IProfileManager {
    /**
     * The 'user' column family.
     */
    private static final String CF_USER = COLUMNS.get("column.cf.user");

    /**
     * The 'authentication' column family.
     */
    private static final String CF_AUTHENTICATION = COLUMNS.get("column.cf.authentication");

    /**
     * The 'id' field.
     */
    private static final String FIELD_ID = FIELDS.get("field.id");

    /**
     * The social network ID field.
     */
    private static final String SOCIAL_NETWORK_ID = GENERAL.get("general.social.network.id");

    /**
     * Default constructor.
     *
     * @throws InternalBackEndException
     */
    public ProfileManager() throws InternalBackEndException {
        this(new StorageManager());
    }

    public ProfileManager(final IStorageManager storageManager) throws InternalBackEndException {
        super(storageManager);
    }

    /**
     * Setup a new user profile into the database
     *
     * @param user
     *            the user to insert into the database
     * @throws IOBackEndException
     */
    @Override
    public final MUserBean create(final MUserBean user) throws InternalBackEndException, IOBackEndException {
        final Map<String, byte[]> args = user.getAttributeToMap();
        storageManager.insertSlice(CF_USER, com.mymed.utils.MConverter.byteArrayToString(args.get(FIELD_ID)), args);

        return user;
    }

    /**
     * @param id
     *            the id of the user
     * @return the User corresponding to the id
     * @throws InternalBackEndException
     * @throws IOBackEndException
     */
    @Override
    public final MUserBean read(final String id) throws InternalBackEndException, IOBackEndException {
        final Map<byte[], byte[]> args = storageManager.selectAll(CF_USER, id);

        if (args.isEmpty()) {
            LOGGER.info("User with FIELD_ID '{}' does not exists", id);
            throw new IOBackEndException("Profile does not exist!", ERROR_NOT_FOUND);
        }

        return (MUserBean) introspection(MUserBean.class, args);
    }

    /**
     * @throws IOBackEndException
     * @see IProfileManager#update(MUserBean)
     */
    @Override
    public final MUserBean update(final MUserBean user) throws InternalBackEndException, IOBackEndException {
        LOGGER.info("Updating user with FIELD_ID '{}'", user.getId());
        // create(user) will replace the current values of the user...
        return create(user);
    }
    
    public final void update(final String id, final String key, final String value) throws InternalBackEndException, IOBackEndException {
        LOGGER.info("Updating user {} with FIELDS '{}'", id, key+"->"+value);
        storageManager.insertColumn(CF_USER, id, key, encode(value));
    }

    /**
     * @throws IOBackEndException
     * @see IProfileManager#delete(MUserBean)
     */
    @Override
    public final void delete(final String id) throws InternalBackEndException, IOBackEndException {
        final MUserBean user = read(id);
        storageManager.removeAll(CF_USER, id);

        if (user.getSocialNetworkID().equals(SOCIAL_NETWORK_ID)) {
            storageManager.removeAll(CF_AUTHENTICATION, user.getLogin());
        }
    }
}
