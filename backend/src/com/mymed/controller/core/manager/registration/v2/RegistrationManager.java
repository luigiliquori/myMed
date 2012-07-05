package com.mymed.controller.core.manager.registration.v2;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import com.google.gson.Gson;
import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.authentication.AuthenticationManager;
import com.mymed.controller.core.manager.authentication.IAuthenticationManager;
import com.mymed.controller.core.manager.pubsub.v2.IPubSubManager;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.HashFunction;
import com.mymed.utils.PubSub;

/**
 * Handles the user registrations in myMed.
 * 
 */

public class RegistrationManager extends com.mymed.controller.core.manager.registration.RegistrationManager {
    /**
     * The general name of the application responsible for registering a user.
     */
    private static final String APP_NAME = GENERAL.get("general.social.network.app");

    /**
     * The 'user' field.
     */
    private static final String FIELD_USER = FIELDS.get("field.user");

    /**
     * The 'authentication' field.
     */
    private static final String FIELD_AUTHENTICATION = FIELDS.get("field.authentication");

    /**
     * The 'key' field.
     */
    private static final String FIELD_KEY = FIELDS.get("field.key");

    /**
     * The 'value' field.
     */
    private static final String FIELD_VALUE = FIELDS.get("field.value");

    /**
     * The default ontology id.
     */
    private static final int ONTOLOGY_ID = 0;

    private final IPubSubManager pubSubManager;
    private final IAuthenticationManager authenticationManager;
    private final Gson gson;
    
    public RegistrationManager() throws InternalBackEndException {
        this(new StorageManager());
    }

    /**
     * Default constructor.
     * 
     * @param storageManager
     * @throws InternalBackEndException
     */
    public RegistrationManager(final IStorageManager storageManager) throws InternalBackEndException {
        super(storageManager);

        pubSubManager = new PubSubManager();
        authenticationManager = new AuthenticationManager();
        gson = new Gson();
    }
    @Override
    public void create(
            final MUserBean user, 
            final MAuthenticationBean authentication, 
            final String application)
    throws AbstractMymedException
    {
        // PUBLISH A NEW REGISTATION PENDING TASK
        final List<MDataBean> dataList = new ArrayList<MDataBean>();
        try {
			final MDataBean dataUser = new MDataBean(FIELD_USER,
					gson.toJson(user), ONTOLOGY_ID);

			final MDataBean dataAuthentication = new MDataBean(FIELD_AUTHENTICATION, 
					gson.toJson(authentication), ONTOLOGY_ID);

            dataList.add(dataUser);
            dataList.add(dataAuthentication);
        } catch (final JsonSyntaxException e) {
            throw new InternalBackEndException("User/Authentication jSon format is not valid");
        }

        // We use the APP_NAME as the epsilon for the hash function
        final HashFunction hashFunc = new HashFunction(APP_NAME);
        final String accessToken = hashFunc.SHA1ToString(user.getLogin() + System.currentTimeMillis());
        
        /* pub account infos */
        pubSubManager.create(APP_NAME, accessToken, "", dataList);
        
        /* sub to confirmation mail */
        pubSubManager.create(APP_NAME, accessToken + "conf", user.getId());
        
        dataList.clear();
        final StringBuilder contentBuilder = new StringBuilder(250);
        // TODO add internationalization support
        contentBuilder.append("Bienvenu sur myMed.<br /><br />Pour finaliser votre inscription allez sur <a href='");
        String address = getServerProtocol() + getServerURI();
        if (application != null) {
        	address += "/application/" + application;
        } 
        address += "?registration=ok&accessToken=" + accessToken;
        contentBuilder.append(address);
        contentBuilder.append("'>"+address+"</a>");

        contentBuilder.trimToSize();
        
        
        MDataBean mailContent = new MDataBean("myMed", contentBuilder.toString(), PubSub.TEXT);
        dataList.add(mailContent);
        MUserBean publisher = new MUserBean();
        publisher.setName("myMed");
        
        /* trigger confirmation mail send with its content  */        
        pubSubManager.sendEmails(APP_NAME, accessToken + "conf", publisher, dataList);
        

        /*final MailMessage message = new MailMessage();
        message.setSubject("Bienvenu sur myMed");
        message.setRecipient(user.getEmail());
        message.setText(contentBuilder.toString());

        final Mail mail = new Mail(message, SubscribeMailSession.getInstance());
        mail.send();*/
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.controller.core.manager.registration.IRegistrationManager#read(java.lang.String)
     */
    @Override
    public void read(final String accessToken) throws AbstractMymedException {
        // Retrieve the user profile
        final List<Map<String, String>> list = pubSubManager.read(APP_NAME, accessToken, ""); //read temporary data used for pending registration
        if (list.size() == 0){
        	LOGGER.debug("account registration not found");
            throw new InternalBackEndException("account registration not found");
    	}
        MUserBean userBean = null;
        MAuthenticationBean authenticationBean = null;

        try {
            for (final Map<String, String> dataEntry : list) {
                if (dataEntry.get(FIELD_KEY).equals(FIELD_USER)) {
                    userBean = gson.fromJson(dataEntry.get(FIELD_VALUE), MUserBean.class);
                } else if (dataEntry.get(FIELD_KEY).equals(FIELD_AUTHENTICATION)) {
                    authenticationBean = gson.fromJson(dataEntry.get(FIELD_VALUE), MAuthenticationBean.class);
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
            pubSubManager.delete(APP_NAME, accessToken);
        } else {
        	LOGGER.debug("account creation failed");
        }
    }
}
