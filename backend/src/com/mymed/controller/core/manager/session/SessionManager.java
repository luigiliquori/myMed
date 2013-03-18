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
package com.mymed.controller.core.manager.session;

import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.session.MSessionBean;

/**
 * Manage the session of a user
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public class SessionManager extends AbstractManager implements ISessionManager {

  private static final String CF_SESSION = COLUMNS.get("column.cf.session");
  private static final String SESSION_SUFFIX = GENERAL.get("general.session.suffix");

  public SessionManager() throws InternalBackEndException {
    this(new StorageManager());
  }

  public SessionManager(final IStorageManager storageManager) throws InternalBackEndException {
    super(storageManager);
  }

  /**
   * @throws IOBackEndException
   * @see ISessionManager#create(String, String)
   */
  @Override
  @Deprecated
  public void create(final String userID, final String ip) throws InternalBackEndException, IOBackEndException {
    final MSessionBean sessionBean = new MSessionBean();
    sessionBean.setId(userID + SESSION_SUFFIX);
    sessionBean.setIp(ip);
    sessionBean.setUser(userID);
    sessionBean.setCurrentApplications("");
    sessionBean.setP2P(false);
    sessionBean.setTimeout(System.currentTimeMillis());
    create(sessionBean);
  }

  /**
   * @throws IOBackEndException
   * @see ISessionManager#create(String, String)
   */
  @Override
  public void create(final MSessionBean sessionBean) throws InternalBackEndException, IOBackEndException {

    if (sessionBean.getId() == null) {
      throw new InternalBackEndException("The session id is null!");
    }

    LOGGER.info("Creating new session with ID '{}' for user '{}'", sessionBean.getId(), sessionBean.getUser());
    
    storageManager.insertSlice(CF_SESSION, sessionBean.getId(), sessionBean.getAttributeToMap());

  }

  /**
   * @throws InternalBackEndException
   * @throws IOBackEndException
   * @see ISessionManager#read(String)
   */
  @Override
  public MSessionBean read(final String sessionID) throws IOBackEndException, InternalBackEndException {
	  
    final Map<byte[], byte[]> args = storageManager.selectAll(CF_SESSION, sessionID);
    if(args.size() == 0) {
    	throw new InternalBackEndException("Session unknown!");
    }
    final MSessionBean session = (MSessionBean) introspection(MSessionBean.class, args);
    
    if (session.isExpired()) {
      throw new IOBackEndException("Session expired!", 404);
      // why not deleting it then?
    }

    return session;
  }
  
  @Override
  public byte[] readSimple(final String sessionID) throws IOBackEndException, InternalBackEndException {
	  return storageManager.selectColumn(CF_SESSION, sessionID, "id");
  }

  @Override
  public String readSimpleUser(final String sessionID) throws IOBackEndException, InternalBackEndException {
	  return storageManager.selectColumnStr(CF_SESSION, sessionID, "user");
  }

  /**
   * @throws IOBackEndException
   * @see ISessionManager#update(MSessionBean)
   */
  @Override
  public void update(final MSessionBean session) throws InternalBackEndException, IOBackEndException {
    create(session);
  }
  
  public final void update(final String id, final Map<String, String> map) throws InternalBackEndException, IOBackEndException {
  	
      storageManager.insertSliceStr(CF_SESSION, id, 60*60*24, map); // 1 day session, might decrease or increase that after
  }

  /**
   * @throws IOBackEndException
   * @throws ServiceManagerException
   * @see ISessionManager#delete(String)
   */
  @Override
  public void delete(final String sessionID) throws InternalBackEndException, IOBackEndException {
    LOGGER.info("Deleting session for user with ID: {}", sessionID);
    final MSessionBean session = read(sessionID);
    session.setExpired(true);
    update(session);

    // Removed after 10 days--> WHERE???
    storageManager.removeAll(CF_SESSION, sessionID + SESSION_SUFFIX);
  }
}
