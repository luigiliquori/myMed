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
package com.mymed.controller.core.manager.position;

import java.io.UnsupportedEncodingException;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.user.MPositionBean;

/**
 * Manage the session of a user
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public class PositionManager extends AbstractManager implements IPositionManager {

  private static final String CF_POSITION = COLUMNS.get("column.cf.position");
  private static final String FIELD_USER_ID = FIELDS.get("field.user.id");

  public PositionManager() throws InternalBackEndException {
    this(new StorageManager());
  }

  public PositionManager(final StorageManager storageManager) throws InternalBackEndException {
    super(storageManager);
  }

  /**
   * Setup a new user profile into the database
   * 
   * @param user
   *          the user to insert into the database
   * @throws IOBackEndException
   */
  @Override
  public MPositionBean create(final MPositionBean position) throws InternalBackEndException, IOBackEndException {
    try {
      final Map<String, byte[]> args = position.getAttributeToMap();
      storageManager.insertSlice(CF_POSITION, new String(args.get(FIELD_USER_ID), ENCODING), args);

      return position;
    } catch (final UnsupportedEncodingException e) {
      LOGGER.info(ERROR_ENCODING, ENCODING);
      LOGGER.debug(ERROR_ENCODING, ENCODING, e);

      throw new InternalBackEndException(e.toString());
    }
  }

  /**
   * @throws IOBackEndException
   * @see IPositionManager#read(String)
   */
  @Override
  public MPositionBean read(final String userID) throws InternalBackEndException, IOBackEndException {
    final Map<byte[], byte[]> args = storageManager.selectAll(CF_POSITION, userID);
    if (args.isEmpty()) {
      LOGGER.info("User with ID '{}' does not exists", userID);
      throw new IOBackEndException("position does not exist!", 404);
    }

    return (MPositionBean) introspection(MPositionBean.class, args);
  }

  /**
   * @throws IOBackEndException
   * @see IPositionManager#update(MPositionBean)
   */
  @Override
  public void update(final MPositionBean position) throws InternalBackEndException, IOBackEndException {
    create(position);
  }
}
