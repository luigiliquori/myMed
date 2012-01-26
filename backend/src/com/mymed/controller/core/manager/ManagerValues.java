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
package com.mymed.controller.core.manager;

import ch.qos.logback.classic.Logger;

import com.mymed.utils.MLogger;

/**
 * Simple class to hold generic strings that can be used with all the managers.
 * We hold the names of the columns families and of the super columns.
 * <p>
 * It contains also the log facilities for all the managers.
 * 
 * @author Milo Casagrande
 * 
 */
public class ManagerValues {
  // Column families
  /**
   * The 'Application' column family
   */
  protected static final String CF_APPLICATION = "Application";

  /**
   * The 'ApplicationView' column family
   */
  protected static final String CF_APPLICATION_VIEW = "ApplicationView";

  /**
   * The 'Authentication' column family
   */
  protected static final String CF_AUTHENTICATION = "Authentication";

  /**
   * The 'Interaction' column family
   */
  protected static final String CF_INTERACTION = "Interaction";

  /**
   * The 'Ontology' column family
   */
  protected static final String CF_ONTOLOGY = "Ontology";

  /**
   * The 'Reputation' column family
   */
  protected static final String CF_REPUTATION = "Reputation";

  /**
   * The 'Session' column family
   */
  protected static final String CF_SESSION = "Session";

  /**
   * The 'User' column family
   */
  protected static final String CF_USER = "User";

  /**
   * The 'Position' column family
   */
  protected static final String CF_POSITION = "Position";

  // Super columns
  /**
   * The 'ApplicationController' super column
   */
  protected static final String SC_APPLICATION_CONTROLLER = "ApplicationController";

  /**
   * The 'ApplicationList' super column
   */
  protected static final String SC_APPLICATION_LIST = "ApplicationList";

  /**
   * The 'ApplicationModel' super column
   */
  protected static final String SC_APPLICATION_MODEL = "ApplicationModel";

  /**
   * The 'OntologyList' super column
   */
  protected static final String SC_ONTOLOGY_LIST = "OntologyList";

  /**
   * The 'DataList' super column
   */
  protected static final String SC_DATA_LIST = "DataList";

  /**
   * The 'UserList' super column
   */
  protected static final String SC_USER_LIST = "UserList";

  /**
   * The 'InteractionList' super column
   */
  protected static final String SC_INTERACTION_LIST = "InteractionList";

  /**
   * The 'RaterList' super column
   */
  protected static final String SC_RATER_LIST = "RaterList";

  // Logger
  /**
   * The default logger for the backend managers
   */
  protected static final Logger LOGGER = MLogger.getLogger();

  // Encoding
  /**
   * The default encoding
   */
  protected static final String ENCODING = "UTF-8";
}
