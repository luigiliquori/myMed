package com.mymed.utils.mail;

import javax.naming.Context;
import javax.naming.InitialContext;
import javax.naming.NamingException;

import ch.qos.logback.classic.Logger;

import com.mymed.utils.MLogger;

/**
 * Super class for the mail session that will handle the Java context retrieval.
 * 
 * @author Milo Casagrande
 */
public class MailSession {
    /**
     * The default logger for this class and its subclasses.
     */
    protected static final Logger LOGGER = MLogger.getLogger();

    /**
     * The default Java context.
     */
    private static final String DEFAULT_JAVA_CTX = "java:comp/env";

    /**
     * The default initial context.
     */
    private Context initialContext;

    /**
     * This Java environment context.
     */
    private Context environmetContext;

    /**
     * Default constructor.
     */
    public MailSession() {
        try {
            initialContext = new InitialContext();
            environmetContext = (Context) initialContext.lookup(DEFAULT_JAVA_CTX);
        } catch (final NamingException ex) {
            LOGGER.debug("Error retrieving default Java context for this session", ex); // NOPMD
        }
    }

    /**
     * Gets the environment context.
     * 
     * @return the default Java context
     */
    protected Context getContext() {
        return environmetContext;
    }
}
