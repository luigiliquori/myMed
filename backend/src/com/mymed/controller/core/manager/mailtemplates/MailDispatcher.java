package com.mymed.controller.core.manager.mailtemplates;

import static java.util.Arrays.asList;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.mail.Mail;
import com.mymed.utils.mail.MailMessage;
import com.mymed.utils.mail.SubscribeMailSession;

public class MailDispatcher extends AbstractManager implements Runnable {
	
	/**
     * The subscribees (users subscribed to a predicate) column family.
     */
    protected static final String CF_SUBSCRIBEES = COLUMNS.get("column.cf.subscribees");
	
	List<String> predicates;
	
	String application, namespace;
	
	HashMap<String, Object> data;
	
	protected ProfileManager profileManager;
    protected MailTemplateManager mailTemplateManager;
	
	public MailDispatcher() 
            throws InternalBackEndException 
    {
        this(new StorageManager());
    }

    public MailDispatcher(final IStorageManager storageManager) 
            throws InternalBackEndException 
    {
    	super(storageManager);
        profileManager = new ProfileManager(storageManager);
        mailTemplateManager = new MailTemplateManager(storageManager);
    }
    
    
    public MailDispatcher(
		final String application,
		final String namespace,
		final List<String> predicates,
		final Map<String, String> details,
		final MUserBean publisher) 
            throws InternalBackEndException 
    {
    	this();
    	data = new HashMap<String, Object>();

        this.application = application;
        this.namespace = namespace;
        this.predicates = predicates;
        
        // Set data map
        data.put("base_url", getServerProtocol()+getServerURI()+"/");
        data.put("application", application);
        data.put("publication", details);
        data.put("publisher", publisher );
    }
	
	
	//find subscribers for each predicate and alert them (mail)
	@Override
	public void run() {
		
		for (String predicate : predicates) {
			
			final List<MUserBean> recipients = new ArrayList<MUserBean>();
	        {
	            final Map<String, String> subscribers = storageManager.selectAllStr(CF_SUBSCRIBEES, predicate);
	            for (final Entry<String, String> entry : subscribers.entrySet()) {
	                MUserBean recipient = null;
	                try {
	                    recipient = profileManager.read(entry.getKey());
	                } catch (IOBackEndException e) {}
	                if (recipient != null) {
	                    recipients.add(recipient);
	                }
	            }
	        }
	        
	        // Loop on recipients
	        for (MUserBean recipient : recipients) {
	            
	            // Update the current recipient in the data map
	            data.put("recipient", recipient);
	            
	            // Get the prefered language of the user
	            String language = recipient.getLang();
	            
	            // Get the mail template from the manager
	            
	            MailTemplate template = this.mailTemplateManager.getTemplate(
	                    this.application, 
	                    this.namespace, 
	                    language);
	            
	            // Render the template
	            String subject = template.renderSubject(data);
	            String body = template.renderBody(data);
	            
	            // Create the mail
	            final MailMessage message = new MailMessage();
	            message.setSubject(subject);
	            message.setRecipients(asList(recipient.getEmail()));
	            message.setText(body);

	            // Send it
	            final Mail mail = new Mail(
	                    message, 
	                    SubscribeMailSession.getInstance());
	            mail.send();
	            
	            LOGGER.info(String.format("Mail sent to '%s' with title '%s' for predicate '%s'", 
	                    recipient.getEmail(), 
	                    subject,
	                    predicate));
	            
	        } // End of loop on recipients 
			
		}
		
	}

}
