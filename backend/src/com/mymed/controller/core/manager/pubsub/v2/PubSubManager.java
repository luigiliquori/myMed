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

import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.TreeMap;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.pubsub.v2.IPubSubManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MConverter;
import com.mymed.utils.mail.Mail;
import com.mymed.utils.mail.MailMessage;
import com.mymed.utils.mail.SubscribeMailSession;

/**
 * The pub/sub mechanism manager.
 * 
 * @author lvanni
 */
public class PubSubManager extends AbstractManager implements IPubSubManager {

    protected static final String APPLICATION_CONTROLLER_ID = "applicationControllerID";
    /**
     * The default publish prefix.
     */
    protected static final String PUBLISHER_PREFIX = "PUBLISH_";

    /**
     * The default subscribe prefix.
     */
    protected static final String SUBSCRIBER_PREFIX = "SUBSCRIBE_";
    protected static final String MEMBER_LIST_KEY = "memberList";
    protected static final String DATA_ARG = "data";
    protected static final String BEGIN_ARG = "begin";
    protected static final String END_ARG = "end";

    /**
     * The application controller super column.
     */
    private static final String SC_APPLICATION_CONTROLLER = COLUMNS.get("column.sc.application.controller");

    /**
     * The data list super column.
     */
    private static final String SC_DATA_LIST = COLUMNS.get("column.sc.data.list");

    /**
     * The subscribees (users subscribed to a predicate) column family.
     */
    private static final String CF_SUBSCRIBEES = COLUMNS.get("column.cf.subscribees");
    
    /**
    * The subscribers (predicates subscribed by a user) column family.
    */
   private static final String CF_SUBSCRIBERS = COLUMNS.get("column.cf.subscribers");

    final ProfileManager profileManager;
    
    final Map<String, byte[]> args;
    
    /**
     * Default constructor.
     * @throws InternalBackEndException 
     */
    public PubSubManager() throws InternalBackEndException {
        this(new StorageManager());
    }

    public PubSubManager(final IStorageManager storageManager) throws InternalBackEndException {
        super(storageManager);
        profileManager = new ProfileManager(storageManager);
        args = new HashMap<String, byte[]>();
    }

    /**
     * Publish mechanism.
     * 
     * @see IPubSubManager#create(String, String, MUserBean)
     */
    
    
    /* v2 creates */
    
    public final void createIndex(String application, final String predicate, final String colprefix, final String subPredicate,
    		final MUserBean publisher, final List<MDataBean> dataList) throws InternalBackEndException, IOBackEndException {
    	try {

    		args.clear();
    		
    		/*
    		 * Put all index data that enable to filter deeply 
    		 * @see read FindRequestHandler#READ
    		 *    , index filtering should be moved in this class soon, to free up handlers
    		 */
   		
	   		for ( MDataBean item : dataList) {
	   			if (item.getOntologyID() < 4 /*|| item.getOntologyID() == 8*/)
	   				args.put(item.getKey(), item.getValue().getBytes(ENCODING));
	   		}
	   		
	   		args.put("predicate", subPredicate.getBytes(ENCODING)); // dataId pointer
	   		args.put("publisherID", publisher.getId().getBytes(ENCODING));

	   		//---publisherName at the moment he published it, beware that name was possibly updated since
	   		args.put("publisherName", publisher.getName().getBytes(ENCODING));

    		storageManager.insertSuperSlice(SC_APPLICATION_CONTROLLER, application + predicate, colprefix + subPredicate
    				+ publisher.getId(), args);

    	} catch (final UnsupportedEncodingException e) {
    		LOGGER.debug(ERROR_ENCODING, ENCODING, e);
    		throw new InternalBackEndException(e.getMessage()); // NOPMD
    	}
    }
    
    public final void createData(String application, final String subPredicate,
    		final MUserBean publisher, final List<MDataBean> dataList) throws InternalBackEndException,
    		IOBackEndException {
    	try {

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
				args.put("key", item.getKey().getBytes(ENCODING));
    			args.put("value", item.getValue().getBytes(ENCODING));
    			args.put("ontologyID", String.valueOf(item.getOntologyID()).getBytes(ENCODING));
    			storageManager.insertSuperSlice(SC_DATA_LIST, application + subPredicate + publisher.getId(),
    					item.getKey(), args);
    			args.clear();
    		}

    	} catch (final UnsupportedEncodingException e) {
    		LOGGER.debug(ERROR_ENCODING, ENCODING, e);
    		throw new InternalBackEndException(e.getMessage()); // NOPMD
    	}
    }
    
    public final void createMail(String application, final String predicate, final MUserBean publisher, final List<MDataBean> dataList)
    		throws InternalBackEndException, IOBackEndException {
    	
    	// SEND A MAIL TO THE SUBSCRIBERS

    	final List<String> recipients = new ArrayList<String>();

		final Map<byte[], byte[]> subscribers = storageManager.selectAll(CF_SUBSCRIBEES, application + predicate);
		for (final Entry<byte[], byte[]> entry : subscribers.entrySet()) {
			final String key = Charset.forName(ENCODING).decode(ByteBuffer.wrap(entry.getKey())).toString();
			//final String val = Charset.forName(ENCODING).decode(ByteBuffer.wrap(entry.getValue())).toString();
        	MUserBean recipient = null;
        	try {
        		recipient = profileManager.read(key);
        	} catch (IOBackEndException e){}
        	if (recipient != null)	
        		recipients.add(recipient.getEmail());
            LOGGER.info("____subscription sent for: "+recipient.getEmail());
		}


		// Format the mail
		// TODO move this somewhere else and handle translation of this email!
		if (!recipients.isEmpty()) {
	
			final StringBuilder mailContent = new StringBuilder(400);
			mailContent.append("Bonjour,<br/>De nouvelles informations sont arriv&eacute;es sur votre plateforme myMed.<br/>Application Concern&eacute;e: ");
			mailContent.append(application);
			mailContent.append("<br/>Predicate:<br/>");
			
			for ( MDataBean item : dataList) {
				mailContent.append("&nbsp;&nbsp;-");
				mailContent.append(item.getKey());
				mailContent.append(": ");
				mailContent.append(item.getValue());
				mailContent.append("<br/>");
			}

			mailContent.append("<br/><br/>------<br/>L'&eacute;quipe myMed<br/><br/>");
			mailContent.append("Cliquez <a href='");

			String address = getServerProtocol() + getServerURI();
			if (application != null) {
				// can we rename the folder myEuroCINAdmin in myEuroCIN_ADMIN to skip below hack
				address += "/application/" + (application.equals("myEuroCIN_ADMIN")?"myEuroCINAdmin":application);
			} 
			address += "?predicate=" + predicate + "&application="
					+ application + "&userID=" + publisher.getId();
			mailContent.append(address);
			mailContent.append("'>ici</a> si vous souhaitez vraiment vous d&eacute;sabonner");

			mailContent.trimToSize();

			final MailMessage message = new MailMessage();
			message.setSubject("myMed subscribe info: " + application);
			message.setRecipients(recipients);
			message.setText(mailContent.toString());

			final Mail mail = new Mail(message, SubscribeMailSession.getInstance());
			mail.send();
		}
    }

    /* @TODO rename it to createSubscription */
    public final void create(final String application, final String predicate, final MUserBean subscriber)
                    throws InternalBackEndException, IOBackEndException {
        try {

            // STORE A NEW ENTRY IN THE UserList (SubscriberList)
            storageManager.insertColumn(CF_SUBSCRIBEES, application + predicate, subscriber.getId(), 
            		String.valueOf(System.currentTimeMillis()).getBytes(ENCODING));
            storageManager.insertColumn(CF_SUBSCRIBERS, application + subscriber.getId(), predicate, 
            		String.valueOf(System.currentTimeMillis()).getBytes(ENCODING));

        } catch (final UnsupportedEncodingException e) {
            LOGGER.debug(ERROR_ENCODING, ENCODING, e);
            throw new InternalBackEndException(e.getMessage()); // NOPMD
        }
    }

    /*
     * The find mechanism.
     * @see com.mymed.controller.core.manager.pubsub.IPubSubManager#read(java.lang.String, java.lang.String)
     */
    public final List<Map<String, String>> read(final String application, final String predicate)
                    throws InternalBackEndException, IOBackEndException {
    	
        final List<Map<String, String>> resList = new ArrayList<Map<String, String>>();
        final List<Map<byte[], byte[]>> subPredicateListMap = storageManager.selectList(SC_APPLICATION_CONTROLLER,
                        application + predicate);

        for (final Map<byte[], byte[]> set : subPredicateListMap) {
            if (set.size() > 3) { // do not return the memberList
                final Map<String, String> resMap = new HashMap<String, String>();
                for (final Entry<byte[], byte[]> entry : set.entrySet()) {
                    resMap.put(MConverter.byteArrayToString(entry.getKey()),
                                    MConverter.byteArrayToString(entry.getValue()));
                }

                resList.add(resMap);
            }
        }

        return resList;
    }
    
 
    
    public final List<Map<String, String>> read(final String application, final String predicate, final String start, final int count, final Boolean reversed)
                    throws InternalBackEndException, IOBackEndException, UnsupportedEncodingException {
        final List<Map<String, String>> resList = new ArrayList<Map<String, String>>();
        final List<Map<byte[], byte[]>> subPredicateListMap = storageManager.selectList(SC_APPLICATION_CONTROLLER,
                        application + predicate, start, count, reversed);

        for (final Map<byte[], byte[]> set : subPredicateListMap) {
            if (set.size() > 3) { // do not return the memberList
                final Map<String, String> resMap = new HashMap<String, String>();
                for (final Entry<byte[], byte[]> entry : set.entrySet()) {
                    resMap.put(MConverter.byteArrayToString(entry.getKey()),
                                    MConverter.byteArrayToString(entry.getValue()));
                }

                resList.add(resMap);
            }
        }

        return resList;
    }
    
    
    /* v2 reads */

    
    /* 
     * read RESULTS (indexes found)
     *  @TODO rename to readIndex
     */
    public final Map<String, Map<String, String>> read(final String application, final List<String> predicate, final String start, final String finish)
    		throws InternalBackEndException, IOBackEndException, UnsupportedEncodingException {
    	
    	final Map<String, Map<String, String>> resMap = new TreeMap<String, Map<String, String>>();
	
    	resMap.putAll(storageManager.multiSelectList(SC_APPLICATION_CONTROLLER, predicate, start, finish));

    	return resMap;
    }


    /*
     * read DATA
     * 
     * @see com.mymed.controller.core.manager.pubsub.IPubSubManager#read(java.lang.String, java.lang.String,
     * java.lang.String)
     * @TODO rename to readData
     */
    
    public final List<Map<String, String>> read(final String application, final String predicate, final String userID)
                    throws InternalBackEndException, IOBackEndException {

        final List<Map<byte[], byte[]>> list = storageManager
                        .selectList(SC_DATA_LIST, application + predicate + userID);
        final List<Map<String, String>> resList = new ArrayList<Map<String, String>>();
        for (final Map<byte[], byte[]> set : list) {
            final Map<String, String> resMap = new HashMap<String, String>();
            for (final Entry<byte[], byte[]> entry : set.entrySet()) {
                final String key = Charset.forName(ENCODING).decode(ByteBuffer.wrap(entry.getKey())).toString();
                final String value = Charset.forName(ENCODING).decode(ByteBuffer.wrap(entry.getValue())).toString();
                resMap.put(key, value);
            }

            resList.add(resMap);
        }

        return resList;
    }

    /*
     * read SUBSCRIPTIONS
     * @see com.mymed.controller.core.manager.pubsub.IPubSubManager#read(java.lang.String)
     * @TODO rename to readSubscription
     */

    public final Map<String, String> read(final String appuserid) throws InternalBackEndException, IOBackEndException {

        final Map<String, String> res = new HashMap<String, String>();
        final Map<byte[], byte[]> predicates = storageManager.selectAll(CF_SUBSCRIBERS, appuserid);
        for (final Entry<byte[], byte[]> entry : predicates.entrySet()) {
        	final String key = Charset.forName(ENCODING).decode(ByteBuffer.wrap(entry.getKey())).toString();
            final String val = Charset.forName(ENCODING).decode(ByteBuffer.wrap(entry.getValue())).toString();
            res.put(key, val);
            LOGGER.info("__"+appuserid +" is subscribed to "+ key);
        }

        return res;
    }

    
    /* v2 deletes */
    
	public final void deleteData(final String application, final String subPredicate,
			final String publisherID) throws InternalBackEndException, IOBackEndException {

		storageManager.removeAll(SC_DATA_LIST, application + subPredicate + publisherID);

	}
    
    public final void deleteIndex(final String application, final String predicate, final String subPredicate,
    		final String publisherID) throws InternalBackEndException, IOBackEndException {

    	storageManager.removeSuperColumn(SC_APPLICATION_CONTROLLER, application + predicate,
    			subPredicate + publisherID);
    }

    public final void deleteSubscription(final String application, final String user, final String predicate)
                    throws InternalBackEndException, IOBackEndException {
        // Remove subscriber member from subsribers list
        storageManager.removeColumn(CF_SUBSCRIBERS, application + user, predicate);
        // Remove subscriber member from predicates subscribed list
        storageManager.removeColumn(CF_SUBSCRIBEES, application + predicate, user);
    }
	
}
