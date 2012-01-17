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
package com.mymed.utils;

import org.slf4j.LoggerFactory;

import ch.qos.logback.classic.Logger;

/**
 * Class to hold the logger facilities for the mymed backend.
 * 
 * @author Milo Casagrande
 * 
 */
public final class MLogger {
  private static final MLogger INSTANCE = new MLogger();

  // The name of the default mymed backend logger as defined in logback.xml
  private static final String DEFAULT_LOG_NAME = "mymed.backend.logger";

  private static final Logger BACKEND_LOGGER = (Logger) LoggerFactory.getLogger(DEFAULT_LOG_NAME);

  // Private constructor since all methods are static and we have a singleton
  private MLogger() {
  }

  /**
   * Retrieve the MLogger instance to be used as a singleton
   * 
   * @return the MLogger instance
   */
  public static MLogger getInstance() {
    return INSTANCE;
  }

  /**
   * Get the default mymed backend logger
   * 
   * @return the default logger
   */
  @Deprecated
  public static Logger getLog() {
    return MLogger.BACKEND_LOGGER;
  }

  /**
   * Get the 'info' level logger of mymed
   * 
   * @return the info level logger
   */
  @Deprecated
  public static Logger getInfoLog() {
    return MLogger.BACKEND_LOGGER;
  }

  /**
   * Get the 'debug' level logger of mymed
   * 
   * @return the debug level logger
   */
  @Deprecated
  public static Logger getDebugLog() {
    return MLogger.BACKEND_LOGGER;
  }

  /**
   * Get the default mymed backend logger
   * 
   * @return the default logger
   */
  public static Logger getLogger() {
    return MLogger.BACKEND_LOGGER;
  }
}
