package com.mymed.controller.core.manager.mailtemplates;

import java.io.StringReader;
import java.io.StringWriter;
import java.util.Map;

import com.mymed.controller.core.exception.InternalBackEndException;

import freemarker.template.Configuration;
import freemarker.template.DefaultObjectWrapper;
import freemarker.template.Template;

/** Two templates (Freemarker templates) for the subject and the body */
public class MailTemplate {
    
    private String subject;
    private String body;
    
    static private Configuration FREE_MARKER_CONF = new Configuration();
    static {
        FREE_MARKER_CONF.setObjectWrapper(new DefaultObjectWrapper());
    }
    
    // -- Getters / Setters
    
    /**
     * @return The subject template 
     */
    public String getSubject() {
        return subject;
    }
    /**
     * @param subject The subject template 
     */
    public void setSubject(String subject) {
        this.subject = subject;
    }
    
    /**
     * @return the body template
     */
    public String getBody() {
        return body;
    }
    

    
    /**
     * @param body the body template
     */
    public void setBody(String body) {
        this.body = body;
    }
    
    
    // Template processing
    
    /** Private util to render a template */
    private String renderTemplate(
            String template, 
            Map<String, Object> data) 
    {
        StringWriter out = new StringWriter();
        try {
            Template tmpl = new Template(
                    "mailTemplate", 
                    new StringReader(template), 
                    FREE_MARKER_CONF);
            tmpl.process(data, out);
            return out.toString();
        } catch (Exception e) {
            throw new InternalBackEndException(e.getMessage());
        }
        
    }
    
    /** Process the subject template against some data */ 
    public String renderSubject(Map<String, Object> data) {
        return this.renderTemplate(this.subject, data);
    }
    
    /** Process the subject template against some data */ 
    public String renderBody(Map<String, Object> data) {
        return this.renderTemplate(this.body, data);
    }
    
}
