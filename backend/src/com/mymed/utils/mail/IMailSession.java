package com.mymed.utils.mail;

import javax.mail.Session;

/**
 * Simple interface necessary for accessing and retrieving the mail sessions.
 * <p>
 * When a new mail session is defined in Glassfish, a new singleton class has to be added and it has to implement this
 * interface. For an example of a class implementing this interface, see {@link SubscribeMailSession}.
 * 
 * @author Milo Casagrande
 */
public interface IMailSession {
    /**
     * Retrieves the session to be used for sending the email.
     * 
     * @return the session to be used for sending the email
     */
    Session get();
}
