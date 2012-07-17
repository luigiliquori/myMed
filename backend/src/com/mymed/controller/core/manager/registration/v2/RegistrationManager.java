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

package com.mymed.controller.core.manager.registration.v2;

import static com.mymed.model.data.application.MOntologyID.TEXT;
import static com.mymed.utils.MiscUtils.singleton;
import static java.util.Arrays.asList;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.commons.lang.NotImplementedException;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.authentication.AuthenticationManager;
import com.mymed.controller.core.manager.authentication.IAuthenticationManager;
import com.mymed.controller.core.manager.mailtemplates.MailTemplate;
import com.mymed.controller.core.manager.mailtemplates.MailTemplateManager;
import com.mymed.controller.core.manager.pubsub.v2.IPubSubManager;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
import com.mymed.controller.core.manager.registration.IRegistrationManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.application.DataBean;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.HashFunction;
import com.mymed.utils.mail.Mail;
import com.mymed.utils.mail.MailMessage;
import com.mymed.utils.mail.SubscribeMailSession;


/**
 * The manager for the authentication bean
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public class RegistrationManager extends AbstractManager implements IRegistrationManager {

    /**
     * The 'user' field.
     */
    private static final String FIELD_USER = FIELDS.get("field.user");

    /**
     * The 'authentication' field.
     */
    private static final String FIELD_AUTHENTICATION = FIELDS.get("field.authentication");
    
    private final IPubSubManager pubSubManager;
    private final IAuthenticationManager authenticationManager;
    protected MailTemplateManager mailTemplateManager;
    private final Gson gson;

    /**
     * Default constructor.
     * 
     * @throws InternalBackEndException
     */
    public RegistrationManager() throws InternalBackEndException {
        this(new StorageManager());
    }

    public RegistrationManager(final IStorageManager storageManager) throws InternalBackEndException {
        super(storageManager);
        pubSubManager = new PubSubManager();
        authenticationManager = new AuthenticationManager();
        mailTemplateManager = new MailTemplateManager(storageManager);
        gson = new GsonBuilder().serializeNulls().create();
    }

	@Override
    /**
     * v2
     */
    public void create(
            final MUserBean user, 
            final MAuthenticationBean authentication, 
            final String application)
    throws AbstractMymedException
    {
        // PUBLISH A NEW REGISTATION PENDING TASK
        final List<DataBean> dataList = new ArrayList<DataBean>();
        try {
			final DataBean dataUser = new DataBean(TEXT, FIELD_USER,
					singleton(gson.toJson(user)));

			final DataBean dataAuthentication = new DataBean(TEXT,
					FIELD_AUTHENTICATION,
					singleton(gson.toJson(authentication)));
			
            dataList.add(dataUser);
            dataList.add(dataAuthentication);
        } catch (final JsonSyntaxException e) {
            throw new InternalBackEndException("User/Authentication jSon format is not valid");
        }

        // We use the APP_NAME as the epsilon for the hash function
        final HashFunction hashFunc = new HashFunction(application);
        final String accessToken = hashFunc.SHA1ToString(user.getLogin() + System.currentTimeMillis());
        
        /* pub account infos */

        pubSubManager.create(application + ":pendingAccount", accessToken, dataList);

        /* send confirmation mail   */  
        sendRegistrationEmail( application, user, accessToken);

        
    }
    
	@Override
	public void read(String accessToken) throws AbstractMymedException {

		throw new NotImplementedException();	
	}

    /**
     * v2
     * (non-Javadoc)
     * @see com.mymed.controller.core.manager.registration.IRegistrationManager#read(java.lang.String)
     */
   
    public void read( final String application, final String accessToken) throws AbstractMymedException {
        // Retrieve the user profile, temporary data used for pending registration
        final List<DataBean> list = pubSubManager.read(application + ":pendingAccount", accessToken);
        if (list.size() == 0){
            throw new InternalBackEndException("account registration not found");
    	}
        MUserBean userBean = null;
        MAuthenticationBean authenticationBean = null;

        try {
            for (final DataBean dataEntry : list) {
                if (dataEntry.getKey().equals(FIELD_USER)) {
                    userBean = gson.fromJson(dataEntry.getValue().get(0), MUserBean.class);
                } else if (dataEntry.getKey().equals(FIELD_AUTHENTICATION)) {
                    authenticationBean = gson.fromJson(dataEntry.getValue().get(0), MAuthenticationBean.class);
                }
            }
        } catch (final JsonSyntaxException e) {
            LOGGER.debug("JSON string is not valid", e);
            throw new InternalBackEndException("User/Authentication jSon format is not valid"); // NOPMD
        }

        // register the new user
        if ((userBean != null) && (authenticationBean != null)) {
        	authenticationManager.create(userBean, authenticationBean);
            // delete pending tasks
			pubSubManager.delete(application + ":pendingAccount", accessToken);	
			
        } else {
        	LOGGER.debug("account creation failed");
        }
    }
    
    /** send registration mail, uses myMed#registration-XX.ftl.xml template */
    public void sendRegistrationEmail( String application, MUserBean recipient,  String accessToken ) {
        
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
        data.put("application", application);
        data.put("accessToken", accessToken);

            
        // Update the current recipient in the data map
        data.put("recipient", recipient);
        
        // Get the prefered language of the user
        String language = recipient.getLang();
        
        // Get the mail template from the manager
        MailTemplate template = this.mailTemplateManager.getTemplate(
                "myMed", 
                "registration", 
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
        
        LOGGER.info(String.format("Mail sent to '%s' with title '%s' for registration", 
                recipient.getEmail(), 
                subject));

    }



}
