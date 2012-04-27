package com.mymed.utils.mail;

import java.net.URL;
import java.util.List;

import javax.activation.DataHandler;
import javax.activation.DataSource;
import javax.activation.FileDataSource;
import javax.mail.BodyPart;
import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.Multipart;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.AddressException;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeBodyPart;
import javax.mail.internet.MimeMessage;
import javax.mail.internet.MimeMultipart;

import ch.qos.logback.classic.Logger;

import com.mymed.utils.MLogger;

/**
 * Sends {@link MailMessage} message with a Glassfish JavaMail sessions defined in a class that implements
 * {@link IMailSession} and extends {@link MailSession} (i.e. {@link SubscribeMailSession}).
 * <p>
 * If the {@link MailMessage} has mail attachments, these will be added to the email message.
 * 
 * @author Milo Casagrande
 */
public final class Mail {
    /**
     * The default logger.
     */
    private static final Logger LOGGER = MLogger.getLogger();

    /**
     * This is the message that we create.
     */
    private final MailMessage message;

    /**
     * This is the mailSession that we want to use. There might be different JavaMail mailSession defined in Glassfish,
     * in order to send emails with the different email address.
     */
    private final IMailSession mailSession;

    /**
     * Creates a new mail message to be sent.
     * 
     * @param message
     *            the {@link MailMessage} to be sent
     * @param mailSession
     *            the {@link IMailSession} to use
     */
    public Mail(final MailMessage message, final IMailSession mailSession) {
        this.message = message;
        this.mailSession = mailSession;
    }

    /**
     * Sends the email to the recipients.
     * <p>
     * A single message is sent for each recipients in the recipients list.
     */
    public void send() {
        final Session session = mailSession.get();

        if (session != null) {
            final String from = session.getProperty("mail.from");
            final Message mail = new MimeMessage(session);

            try {
                mail.setFrom(new InternetAddress(from));
                mail.setSubject(message.getSubject());

                // Create a multi part body so that we can add attachments
                final BodyPart mailBodyPart = new MimeBodyPart();
                mailBodyPart.setContent(message.getText(), message.getMymeType());

                final Multipart multiPart = new MimeMultipart();
                multiPart.addBodyPart(mailBodyPart);

                if (!message.getAttachments().isEmpty()) {
                    addAttachments(message.getAttachments(), multiPart);
                }

                mail.setContent(multiPart);

                for (final String recipient : message.getRecipients()) {
                    // TODO add thread to not block on the sending phase with many different emails
                    try {
                        mail.setRecipients(Message.RecipientType.TO, InternetAddress.parse(recipient));
                        Transport.send(mail);
                    } catch (final AddressException ex) {
                        LOGGER.debug("Problem with the recipient address '{}'", recipient, ex); // NOPMD
                    }
                }
            } catch (final AddressException ex) {
                LOGGER.debug("Problem with the sender address '{}'", from, ex); // NOPMD
            } catch (final MessagingException ex) {
                LOGGER.debug("Problem creating or sending the email message", ex); // NOPMD
            }
        }
    }

    /**
     * Adds the attachments to the email message.
     * 
     * @param attachments
     *            the list of attachments
     * @param multiPart
     *            the {@link Multipart} of this email
     * @throws MessagingException
     */
    private void addAttachments(final List<String> attachments, final Multipart multiPart) throws MessagingException {
        for (final String attachment : attachments) {
            final MimeBodyPart bodyPart = new MimeBodyPart();
            final URL resourceUrl = this.getClass().getClassLoader().getResource(attachment);

            // FileDataSource does MIME detection
            final DataSource dataSource = new FileDataSource(resourceUrl.getPath());

            bodyPart.setDataHandler(new DataHandler(dataSource));
            // Since we store only the name of the file in the MailMessage object, we use that
            bodyPart.setFileName(attachment);

            multiPart.addBodyPart(bodyPart);
        }
    }
}
