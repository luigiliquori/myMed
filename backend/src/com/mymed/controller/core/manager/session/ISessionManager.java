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
import com.mymed.model.data.session.MSessionBean;

public interface ISessionManager {
  /**
   * login
   * 
   * @param userID
   * @param ip
   * @throws InternalBackEndException
   */
  @Deprecated
  void create(String userID, String ip) throws InternalBackEndException, IOBackEndException;

  /**
   * Login
   * 
   * @param sessionBean
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  void create(final MSessionBean sessionBean) throws InternalBackEndException, IOBackEndException;

  /**
   * 
   * @param userID
   * @return
   * @throws InternalBackEndException
   */
  MSessionBean read(String userID) throws InternalBackEndException, IOBackEndException;
  
  byte[] readSimple(final String sessionID) throws IOBackEndException, InternalBackEndException;

  /**
   * 
   * @param sesion
   * @return
   * @throws InternalBackEndException
   */
  void update(MSessionBean session) throws InternalBackEndException, IOBackEndException;
  
  void update(final String id, final Map<String, String> map) throws InternalBackEndException, IOBackEndException;

  /**
   * logout
   * 
   * @param userID
   * @throws InternalBackEndException
   */
  void delete(String userID) throws InternalBackEndException, IOBackEndException;

  String readSimpleUser(String sessionID) throws IOBackEndException,
		InternalBackEndException;
}
