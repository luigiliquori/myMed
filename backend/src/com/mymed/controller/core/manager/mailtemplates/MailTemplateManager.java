package com.mymed.controller.core.manager.mailtemplates;

import static com.mymed.utils.MiscUtils.*;

import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;

/** Store mail templates in the backend */
public class MailTemplateManager extends AbstractManager {
    
    static private String DEFAULT_SUBJECT_RES = "mail_templates/default-subject-template.ftl";
    static private String DEFAULT_BODY_RES = "mail_templates/default-body-template.ftl";
    
    // ----------------------------------------------------------------
    // Constructors
    // ----------------------------------------------------------------
    
    public MailTemplateManager() {
        super(new StorageManager());
    }
    
    public MailTemplateManager(IStorageManager storageManager) {
        super(storageManager);
    }    
    
    // ----------------------------------------------------------------
    // Methods
    // ----------------------------------------------------------------  
    
    /**
     *  Get a template for a specific application, namespace and language.
     *  If none is found, returns the default one.
     */
    public MailTemplate getTemplate(
            String applicationID,
            String namespace,
            String language) 
    {
        
        // -- No template found => return default one
        {
            MailTemplate template = new MailTemplate();

            // Get subject and body template from resources
            template.setBody(resToStr(DEFAULT_BODY_RES));
            template.setSubject(resToStr(DEFAULT_SUBJECT_RES));

            return template;
        }
    }
    
}
