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
public class MyMedLogger {
	/**
	 * The name of the default info logger as defined in the file logback.xml
	 */
	private static final String INFO_LOG_NAME = "mymed.info.logger";

	/**
	 * The name of the default debug logger as defined in the file logback.xml
	 */
	private static final String DEBUG_LOG_NAME = "mymed.debug.logger";

	/**
	 * The default info logger to be used throughout all the managers
	 */
	protected static final Logger INFO_LOGGER = (Logger) LoggerFactory.getLogger(INFO_LOG_NAME);

	/**
	 * The default debug logger to be used throughout all the managers
	 */
	protected static final Logger DEBUG_LOGGER = (Logger) LoggerFactory.getLogger(DEBUG_LOG_NAME);
}
