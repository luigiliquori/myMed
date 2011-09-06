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
	private static final Logger INFO_LOGGER = (Logger) LoggerFactory.getLogger(INFO_LOG_NAME);

	/**
	 * The default debug logger to be used throughout all the managers
	 */
	private static final Logger DEBUG_LOGGER = (Logger) LoggerFactory.getLogger(DEBUG_LOG_NAME);

	/*
	 * Private constructor since all methods are static, or we have to provide a
	 * singleton
	 */
	private MLogger() {
	}

	/**
	 * Get the default mymed logger, in this case an 'info' level logger
	 * 
	 * @return the default logger
	 */
	public static Logger getLog() {
		return INFO_LOGGER;
	}

	/**
	 * Get the 'info' level logger of mymed
	 * 
	 * @return the info level logger
	 */
	public static Logger getInfoLog() {
		return INFO_LOGGER;
	}

	/**
	 * Get the 'debug' level logger of mymed
	 * 
	 * @return the debug level logger
	 */
	public static Logger getDebugLog() {
		return DEBUG_LOGGER;
	}
}
