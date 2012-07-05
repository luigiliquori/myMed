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
package com.mymed.controller.core.manager.pubsub.v2;

import static com.mymed.utils.PubSub.extractApplication;
import static com.mymed.utils.PubSub.extractNamespace;
import static java.util.Arrays.asList;

import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.TreeMap;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.mailtemplates.MailTemplate;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;
import static com.mymed.utils.MiscUtils.*;
import com.mymed.utils.MiscUtils;
import com.mymed.utils.mail.Mail;
import com.mymed.utils.mail.MailMessage;
import com.mymed.utils.mail.SubscribeMailSession;


/**
 * The pub/sub mechanism manager.
 * 
 * @author lvanni
 */
public class PubSubManager extends com.mymed.controller.core.manager.pubsub.PubSubManager implements IPubSubManager {

    final Map<String, byte[]> args;

    /**
     * Default constructor.
     * @throws InternalBackEndException 
     */
    public PubSubManager() 
            throws InternalBackEndException 
    {
        this(new StorageManager());
    }

    public PubSubManager(final IStorageManager storageManager) 
            throws InternalBackEndException 
    {
    	super(storageManager);
        args = new HashMap<String, byte[]>();
    }

    /**
     * Publish mechanism.
     * 
     * @see IPubSubManager#create(String, String, MUserBean)
     */
  
    /* v2 create index */
    public final void create(
            String application, 
            final String predicate, 
            final String colprefix, 
            final String subPredicate,
    		final MUserBean publisher, 
    		final List<MDataBean> predicateList,
    		final List<MDataBean> dataList) 
    		        throws InternalBackEndException, IOBackEndException 
    {
        args.clear();

        /*
         * Put all index data that enable to filter deeply 
         * @see read FindRequestHandler#READ
         *    , index filtering should be moved in this class soon, to free up handlers
         */

        for ( MDataBean item : predicateList) {
            if (item.getOntologyID() < 4 /*|| item.getOntologyID() == 8*/)
                args.put(item.getKey(), encode(item.getValue()));
        }

        args.put("predicate", encode(subPredicate)); // dataId pointer
        args.put("publisherID", encode(publisher.getId()));

        //---publisherName at the moment he published it, beware that name was possibly updated since
        args.put("publisherName", encode(publisher.getName()));

        storageManager.insertSuperSlice(
                SC_APPLICATION_CONTROLLER, 
                application + predicate, 
                colprefix + subPredicate + publisher.getId(), 
                args);


    }
    
    /* v2 create data */
    public final void create(
            String application, 
            final String subPredicate,
    		final String publisher, 
    		final List<MDataBean> dataList) 
    		        throws InternalBackEndException, IOBackEndException 
    {
    		args.clear();
    		
    		/*
    		 * stores data
    		 * 
    		 *  row -> supercolName dataKey, cols: {"key": dataKey, "value": dataValue, "ontologyID": dataoOntologyID}
    		 *  
    		 *  'd like to move to:
    		 *  row -> supercolName dataoOntologyID, cols: {dataKey1: dataValue1, dataKey2: dataValue2, ...}
    		 *  makes more sense
    		 */
    		
    		for ( MDataBean item : dataList) {
				args.put("key", encode(item.getKey()));
    			args.put("value", encode(item.getValue()));
    			args.put("ontologyID", encode(String.valueOf(item.getOntologyID())));
    			storageManager.insertSuperSlice(
    			        SC_DATA_LIST, 
    			        application + subPredicate + publisher,
    					item.getKey(), args);
    			args.clear();
    		}
    }
    
    @Override 
    public void create(
            final String application, 
            final String predicate, 
            final String subscriber)
    throws InternalBackEndException, IOBackEndException 
    {
        // STORE A NEW ENTRY IN THE UserList (SubscriberList)
        storageManager.insertColumn(
                CF_SUBSCRIBEES, 
                application + predicate, 
                subscriber, 
                encode(String.valueOf(System.currentTimeMillis())));
        storageManager.insertColumn(
                CF_SUBSCRIBERS, 
                application + subscriber, 
                predicate, 
                encode(String.valueOf(System.currentTimeMillis())));
    }
 
    
    public final Map<String, Map<String, String>> read(
            final String application, 
            final List<String> predicate, 
            final String start, 
            final String finish)
                    throws InternalBackEndException, IOBackEndException, UnsupportedEncodingException 
    {
    	final Map<String, Map<String, String>> resMap = new TreeMap<String, Map<String, String>>();
    	resMap.putAll(storageManager.multiSelectList(SC_APPLICATION_CONTROLLER, predicate, start, finish));
    	return resMap;
    }
    
    /* v2 deletes */
	public final void delete(
	        final String application, 
	        final String subPredicate)
			        throws InternalBackEndException, IOBackEndException 
	{
		storageManager.removeAll(SC_DATA_LIST, application + subPredicate);
	}
    
	@Override
    public final void delete(
            final String application, 
            final String predicate, 
            final String subPredicate,
    		final String publisherID) 
    		        throws InternalBackEndException, IOBackEndException 
    {
    	storageManager.removeSuperColumn(SC_APPLICATION_CONTROLLER, application + predicate,
    			subPredicate + publisherID);
    }
	
	@Override
    public final void delete(
            final String application, 
            final String user, 
            final String predicate)
                    throws InternalBackEndException, IOBackEndException 
    {
        // Remove subscriber member from subsribers list
        storageManager.removeColumn(CF_SUBSCRIBERS, application + user, predicate);
        // Remove subscriber member from predicates subscribed list
        storageManager.removeColumn(CF_SUBSCRIBEES, application + predicate, user);
    }
	
	
	
	
	public void sendEmails(  
            String application,          
            String predicate,
            MUserBean publisher,
            List<MDataBean> dataList) 
    {
        
        // Prepare a map of key=>val to represent the publication for the mail 
        HashMap<String, String> publicationMap = new HashMap<String, String>();
        {
            final List<MDataBean> ontologyList = new ArrayList<MDataBean>();
            
            // Add Data Beans 
            ontologyList.addAll(dataList);
            
            // Transform list of ontologies to map<String, String>
            for (MDataBean dataBean : ontologyList) {
                // Dont set empty values
                if (MiscUtils.empty(dataBean.getValue())) continue;

                publicationMap.put(dataBean.getKey(), dataBean.getValue());
            }
            
        }
        
        // Built list of recipients
        final List<MUserBean> recipients = new ArrayList<MUserBean>();
        {
            final Map<byte[], byte[]> subscribers = storageManager.selectAll(CF_SUBSCRIBEES, application + predicate);
            for (final Entry<byte[], byte[]> entry : subscribers.entrySet()) {
                MUserBean recipient = null;
                try {
                    recipient = profileManager.read(decode(entry.getKey()));
                } catch (IOBackEndException e) {
                } finally {
                	recipient = new MUserBean();
                	recipient.setEmail(decode(entry.getKey()).substring("MYMED_".length()));
                	recipient.setName(decode(entry.getKey()).substring("MYMED_".length()));
                	recipient.setLang("fr");
                }
                if (recipient != null) {
                    recipients.add(recipient);
                }
            }
        }
       
        // Split application ID
        String applicationID = extractApplication(application);
        String namespace = extractNamespace(application);
        
        // Prepare HashMap of object for FreeMarker template
        HashMap<String, Object> data = new HashMap<String, Object>(); 
        
        // Build URL of the application
        String url = getServerProtocol() + getServerURI() + "/";
        //if (applicationID != null) {
            // can we rename the folder myEuroCINAdmin in myEuroCIN_ADMIN to skip below hack
            // url += "/application/" + (applicationID.equals("myEuroCIN_ADMIN") ? "myEuroCINAdmin" : applicationID);
        //} 
        
        // Set data map
        data.put("base_url", url);
        data.put("application", applicationID);
        data.put("publication", publicationMap);
        data.put("publisher", publisher );

        // Loop on recipients
        for (MUserBean recipient : recipients) {
            
            // Update the current recipient in the data map
            data.put("recipient", recipient);
            
            // Get the prefered language of the user
            String language = recipient.getLang();
            
            // Get the mail template from the manager
            MailTemplate template = this.mailTemplateManager.getTemplate(
                    applicationID, 
                    namespace, 
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
