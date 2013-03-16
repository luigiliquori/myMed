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

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.user.MPositionBean;

public interface IPositionManager {
  /**
   * 
   * @param user
   * @return
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  MPositionBean create(final MPositionBean user) throws InternalBackEndException, IOBackEndException;

  /**
   * 
   * @param userID
   * @return
   * @throws InternalBackEndException
   */
  MPositionBean read(String userID) throws InternalBackEndException, IOBackEndException;

  /**
   * 
   * @param position
   * @return
   * @throws InternalBackEndException
   */
  void update(final MPositionBean position) throws InternalBackEndException, IOBackEndException;
}
