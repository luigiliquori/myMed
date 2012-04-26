package com.mymed.utils.mail;

import javax.mail.Session;
import javax.naming.NamingException;

/**
 * This class stores the necessary mail session for sending subscribe-type email.
 * <p>
 * It is a singleton, and the instance is retrieved via the {@link #getInstance()} method.
 * 
 * @author Milo Casagrande
 */
public class SubscribeMailSession extends MailSession implements IMailSession {
    /**
     * The name of the mail session as define in Glassfish.
     */
    private static final String MAIL_SESSION_SUBSCRIBE = "mail/mymed.subscribe";

    /**
     * This is the real session.
     */
    private Session session;

    /**
     * The singleton instance.
     */
    private static final SubscribeMailSession INSTANCE = new SubscribeMailSession();

    /**
     * Private constructor for singleton instance.
     */
    private SubscribeMailSession() {
        super();
        try {
            session = (Session) getContext().lookup(MAIL_SESSION_SUBSCRIBE);
        } catch (final NamingException ex) {
            LOGGER.debug("Error retrieving the '{}' JavaMail session", MAIL_SESSION_SUBSCRIBE, ex); // NOPMD
        }
    }

    /**
     * Retrieves the singleton instance of this class.
     * 
     * @return the instance of the email session
     */
    public static SubscribeMailSession getInstance() {
        return INSTANCE;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.utils.mail.IMailSession#get()
     */
    @Override
    public Session get() {
        return session;
    }
}
