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
package com.mymed.controller.core.manager.pubsub;

import java.io.UnsupportedEncodingException;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage the profile of an user
 * 
 * @author lvanni
 * 
 */
public interface IPubSubManager {

  /**
   * Insert a new Predicate in the dataBase - PUBLISH.
   * 
   * @param application
   *          the application responsible for this predicate
   * @param publisher
   *          the user who insert the new predicate
   */
  void create(String application, String predicate, String subPredicate, MUserBean publisher, List<MDataBean> dataList, String predicateListJson)
      throws InternalBackEndException, IOBackEndException;

  /**
   * Insert a new Predicate in the dataBase - SUBSCRIBE.
   * 
   * @param application
   * @param predicate
   * @param publisher
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  void create(String application, String predicate, MUserBean publisher) throws InternalBackEndException,
      IOBackEndException;

  /**
   * @param application
   *          the application responsible for this predicate
   * @param predicate
   *          the predicateID to read
   * @return the list of the publisher who provide this predicate
   */
  List<Map<String, String>> read(String application, String predicate) throws InternalBackEndException,
      IOBackEndException;
  
  /**
   * @param application
   *          the application responsible for this predicate
   * @param predicate
   *          the predicateID to read
   * @param start
   *           starting column
   *  @param count
   *           nb of columns
   *  @param reversed
   *           order         
   * @return the list of the publisher who provide this predicate
   */
  List<Map<String, String>> read(String application, String predicate, String start, int count, Boolean reversed) throws InternalBackEndException,
      IOBackEndException, UnsupportedEncodingException;

  /**
   * the extended read used in v2, for range queries
   *  reads over rows in predicate, and in the column slice [start-finish]
   * 
   * @param application
   * @param predicate
   * @param start
   * @param finish
   * @return
   * @throws InternalBackEndException
   * @throws IOBackEndException
   * @throws UnsupportedEncodingException
   */
  
  Map<String, Map<String, String>> read(final String application, final List<String> predicate, final String start, final String finish)
  		throws InternalBackEndException, IOBackEndException, UnsupportedEncodingException;
  /**
   * Get the DataList Entry related to application + predicate + userID.
   * 
   * @param application
   * @param predicate
   * @param userID
   * @return A DataList Entry
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  List<Map<String, String>> read(String application, String predicate, String userID) throws InternalBackEndException,
      IOBackEndException;
  
  
  /**
   * Get the Subscriptions Entry related to application + userID
   *
   * @param application
   * @param userID
   * @return A List of predicates
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  
  Map<String, String> read(String user) throws InternalBackEndException,
	IOBackEndException;

  /**
   * Delete an existing predicate
   * 
   * @param application
   *          the application responsible for this predicate
   * @param predicate
   *          The predicate to delete
   */
  void delete(String application, String predicate, String subPredicate, String publisherID)  throws InternalBackEndException, IOBackEndException;
  
  /**
   * Delete an existing predicate in Subscribers CF, you seem to like multiple functions with same names and different arguments so here's a new one
   * 
   * @param application
   *          the application responsible for this predicate
   * @param user
   *          the user that has an ongoing subscription
   * @param predicate
   *          The predicate subscription pattern to delete from the row
   */
  void delete(String application, String user, String predicate)  throws InternalBackEndException, IOBackEndException;

}
