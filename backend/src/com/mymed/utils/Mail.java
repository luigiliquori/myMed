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

import java.util.Properties;

import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.PasswordAuthentication;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.AddressException;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;

/**
 * Class to provide mail functionalities to the backend
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public class Mail {

    private final Properties props;
    private final String from, to, object, content;

    public Mail(final String from, final String to, final String object, final String content) throws AddressException,
                    MessagingException {
        props = new Properties();
        props.put("mail.smtp.host", "smtp.gmail.com");
        props.put("mail.smtp.socketFactory.port", "465");
        props.put("mail.smtp.socketFactory.class", "javax.net.ssl.SSLSocketFactory");
        props.put("mail.smtp.auth", "true");
        props.put("mail.smtp.port", "465");

        this.from = from;
        this.to = to;
        this.object = object;
        this.content = content;

        sendMail();
    }

    private void sendMail() throws AddressException, MessagingException {
        final Mail.MailAuthenticator authenticator = new Mail.MailAuthenticator();
        final Session session = Session.getInstance(props, authenticator);

        final Message message = new MimeMessage(session);
        message.setFrom(new InternetAddress(from));
        message.setRecipients(Message.RecipientType.TO, InternetAddress.parse(to));
        message.setSubject(object);
        message.setText(content);

        Transport.send(message);
    }

    /**
     * Inner class used to avoid strong referencing
     * 
     * @author Milo Casagrande
     */
    private static final class MailAuthenticator extends javax.mail.Authenticator {
        @Override
        protected PasswordAuthentication getPasswordAuthentication() {
            return new PasswordAuthentication("mymed.subscribe", "myalcotra");
        }
    }
}
