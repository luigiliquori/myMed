package com.mymed.utils;

import org.slf4j.LoggerFactory;

import ch.qos.logback.classic.Logger;

/**
 * Class to hold the logger facilities for mymed.
 * 
 * 
 * @author Milo Casagrande
 * 
 */
public final class MLogger {
  /*
   * The private instance for the MLogger class to implement the singleton
   */
  private static final MLogger INSTANCE = new MLogger();

  /*
   * The name of the default info logger as defined in the file logback.xml
   */
  private static final String INFO_LOG_NAME = "mymed.info.logger";

  /*
   * The name of the default debug logger as defined in the file logback.xml
   */
  private static final String DEBUG_LOG_NAME = "mymed.debug.logger";

  /*
   * The default info logger to be used throughout all the managers
   */
  private static final Logger INFO_LOGGER = (Logger) LoggerFactory.getLogger(INFO_LOG_NAME);

  /*
   * The default debug logger to be used throughout all the managers
   */
  private static final Logger DEBUG_LOGGER = (Logger) LoggerFactory.getLogger(DEBUG_LOG_NAME);

  /*
   * Private constructor since all methods are static and we have a singleton
   */
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
   * Get the default mymed logger, in this case an 'info' level logger
   * 
   * @return the default logger
   */
  @Deprecated
  public static Logger getLog() {
    return INFO_LOGGER;
  }

  /**
   * Get the 'info' level logger of mymed
   * 
   * @return the info level logger
   */
  @Deprecated
  public static Logger getInfoLog() {
    return INFO_LOGGER;
  }

  /**
   * Get the 'debug' level logger of mymed
   * 
   * @return the debug level logger
   */
  @Deprecated
  public static Logger getDebugLog() {
    return DEBUG_LOGGER;
  }

  /**
   * @see ch.qos.logback.classic.Logger#info(String)
   * @param msg
   *          the message to log
   */
  public static void info(final String msg) {
    INFO_LOGGER.info(msg);
  }

  /**
   * @see ch.qos.logback.classic.Logger#info(String, Object)
   * @param format
   *          the format of the string to log
   * @param arg
   *          the argument to log
   */
  public static void info(final String format, final Object arg) {
    INFO_LOGGER.info(format, arg);
  }

  /**
   * @see ch.qos.logback.classic.Logger#info(String, Object, Object)
   * @param format
   *          the format of the string to log
   * @param arg1
   *          the argument to log
   * @param arg2
   *          the argument to log
   */
  public static void info(final String format, final Object arg1, final Object arg2) {
    INFO_LOGGER.info(format, arg1, arg2);
  }

  /**
   * @see ch.qos.logback.classic.Logger#warn(String)
   * @param msg
   *          the message to log
   */
  public static void warn(final String msg) {
    INFO_LOGGER.warn(msg);
  }

  /**
   * @see ch.qos.logback.classic.Logger#debug(String)
   * @param msg
   *          the message to log
   */
  public static void debug(final String msg) {
    DEBUG_LOGGER.debug(msg);
  }

  /**
   * @see ch.qos.logback.classic.Logger#debug(String, Throwable)
   * @param msg
   *          the message to log
   * @param cause
   *          the potential exception
   */
  public static void debug(final String msg, final Throwable cause) {
    DEBUG_LOGGER.debug(msg, cause);
  }

  /**
   * @see ch.qos.logback.classic.Logger#debug(String, Object, Object)
   * @param format
   *          the format of the string to log
   * @param arg1
   *          the argument to log
   * @param arg2
   *          the argument to log
   */
  public static void debug(final String format, final Object arg1, final Object arg2) {
    DEBUG_LOGGER.debug(format, arg1, arg2);
  }
}
