package com.mymed.controller.core.manager.mailtemplates;

import static com.mymed.controller.core.manager.mailtemplates.MailTemplateManager.BODY_TAG;
import static com.mymed.controller.core.manager.mailtemplates.MailTemplateManager.SUBJECT_TAG;
import static com.mymed.utils.MiscUtils.empty;

import java.io.InputStream;
import java.io.StringReader;
import java.io.StringWriter;
import java.util.Locale;
import java.util.Map;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;

import com.mymed.controller.core.exception.InternalBackEndException;

import freemarker.template.Configuration;
import freemarker.template.DefaultObjectWrapper;
import freemarker.template.Template;

/** Two templates (Freemarker templates) for the subject and the body */
public class MailTemplate {
    
    /** Default encoding for input files */
    static private final String INPUT_ENCODING = "utf8";   
    
    private String subject;
    private String body;
    
    /** Static Freemarker configuration */
    static private Configuration FREE_MARKER_CONF = new Configuration();
    static {
        FREE_MARKER_CONF.setEncoding(Locale.FRENCH, INPUT_ENCODING);
        FREE_MARKER_CONF.setObjectWrapper(new DefaultObjectWrapper());
    }
    
    // ---------------------------------------------------------------------------
    // Constructor
    // ---------------------------------------------------------------------------
    
    /** Creates a mail template from a XML file */
    public MailTemplate(InputStream is) {
        try {
            
            // Read XML
            DocumentBuilderFactory docBuilderFactory = DocumentBuilderFactory.newInstance();
            DocumentBuilder docBuilder = docBuilderFactory.newDocumentBuilder();
            Document doc = docBuilder.parse(is);
            
            // Root element 
            Element root = doc.getDocumentElement();
            
            // Get subject template
            Element subjectEl = (Element) root.getElementsByTagName(SUBJECT_TAG).item(0);
            this.subject = subjectEl.getTextContent();
            
            // Get body template
            Element bodyEl = (Element) root.getElementsByTagName(BODY_TAG).item(0);
            this.body = bodyEl.getTextContent();
            
            if (empty(this.subject)) {
                throw new InternalBackEndException("empty 'subject' in mail template");
            }
            if (empty(this.body)) {
                throw new InternalBackEndException("empty 'body' in mail template");
            }
            
            is.close();
            
        } catch (Exception e) {
            throw new InternalBackEndException(e, "Error when parsing mail template file");
        }   
    }
    
    public MailTemplate(
            String subject, 
            String body) 
    {
        this.subject = subject;
        this.body = body;
    }
    
    // ---------------------------------------------------------------------------
    // Getters / Setters
    // ---------------------------------------------------------------------------
    


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
            throw new InternalBackEndException(e);
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
