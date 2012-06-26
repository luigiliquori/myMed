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
package com.mymed.controller.core.manager.pubsub;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Type;
import java.nio.ByteBuffer;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collection;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.TreeMap;

import org.apache.cassandra.cli.CliParser.newColumnFamily_return;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.mailtemplates.MailTemplate;
import com.mymed.controller.core.manager.mailtemplates.MailTemplateManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MConverter;
import com.mymed.utils.mail.Mail;
import com.mymed.utils.mail.MailMessage;
import com.mymed.utils.mail.SubscribeMailSession;
import static com.mymed.utils.PubSub.*;
import static com.mymed.utils.MiscUtils.*; 
import static java.util.Arrays.asList;

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
     * The application model super column.
     */
    private static final String SC_APPLICATION_MODEL = COLUMNS.get("column.sc.application.model");

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

    /**
     * The 'user' column family.
     */
    private static final String CF_USER = COLUMNS.get("column.cf.user");

    final ProfileManager profileManager;
    final MailTemplateManager mailTemplateManager;

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
        mailTemplateManager = new MailTemplateManager(storageManager);
    }

    /**
     * Publish mechanism.
     * 
     * @see IPubSubManager#create(String, String, MUserBean)
     */
    @Override public final void create(
            String prefix, 
            final String predicate, 
            final String subPredicate,
            final MUserBean publisher, 
            final List<MDataBean> dataList, 
            final String predicateListJson) throws InternalBackEndException, IOBackEndException 
    {
        // STORE THE PUBLISHER
        final Map<String, byte[]> args = new HashMap<String, byte[]>();
        args.put("publisherList", encode(PUBLISHER_PREFIX + prefix + subPredicate));
        args.put("predicate", encode(subPredicate));
        storageManager.insertSuperSlice(SC_APPLICATION_CONTROLLER, prefix + predicate, MEMBER_LIST_KEY, args);

        // STORE VALUES RELATED TO THE PREDICATE
        String data = "";
        String begin = Long.toString(System.currentTimeMillis());
        String end = "";

        for (final MDataBean item : dataList) {
            if (item.getKey().equals(DATA_ARG)) {
                data = item.getValue();
            } else if (item.getKey().equals(BEGIN_ARG)) {
                begin = item.getValue();
            } else if (item.getKey().equals(END_ARG)) {
                end = item.getValue();
            }
        }
        args.clear();
        args.put("predicate", encode(subPredicate));
        if(predicateListJson != null) {
            args.put("predicateListJson", encode(predicateListJson));
        }
        args.put("begin", encode(begin));
        args.put("end", encode(end));
        args.put("publisherID", encode(publisher.getId()));

        //---should be removed as they are not updated along with profile ...
        args.put("publisherName", encode(publisher.getName()));
        args.put("publisherProfilePicture",
                encode(publisher.getProfilePicture() == null ? "" : publisher.getProfilePicture()));
        args.put("publisherReputation",
                encode(publisher.getReputation() == null ? "" : publisher.getReputation()));

        args.put("data", encode(data));
        storageManager.insertSuperSlice(SC_APPLICATION_CONTROLLER, prefix + predicate, subPredicate
                + publisher.getId(), args);

        // STORE A NEW ENTRY IN THE ApplicationModel (use to retrieve all the
        // predicate of a given application)
        args.clear();
        args.put(APPLICATION_CONTROLLER_ID, encode(prefix + predicate));
        storageManager.insertSuperSlice(SC_APPLICATION_MODEL, prefix, predicate, args);

        // STORE THE DATAs
        args.clear();
        for (final MDataBean item : dataList) {
            args.put("key", encode(item.getKey()));
            args.put("value", encode(item.getValue()));
            args.put("ontologyID", encode(item.getOntologyID()));
            storageManager.insertSuperSlice(SC_DATA_LIST, prefix + subPredicate + publisher.getId(),
                    item.getKey(), args);
            args.clear();
        }
        
        // Prepare a map of key=>val to represent the publication for the mail 
        HashMap<String, String> publicationMap = new HashMap<String, String>();
        {
            Gson gson = new Gson();
            
            // Transform predicateListJson to list of ontologies (XXX Should have been done by the caller) 
            final Type dataType = new TypeToken<List<MDataBean>>() {}.getType();
            final List<MDataBean> ontologyList = gson.fromJson(predicateListJson, dataType);
            ontologyList.addAll(dataList);

            // Trasform list of ontologies to map<String, String>
            for (MDataBean dataBean : ontologyList) {
                publicationMap.put(dataBean.getKey(), dataBean.getValue());
            }
            
        }
        
        // Built list of recipients
        final List<MUserBean> recipients = new ArrayList<MUserBean>();
        {
            final Map<byte[], byte[]> subscribers = storageManager.selectAll(CF_SUBSCRIBEES, prefix + predicate);
            for (final Entry<byte[], byte[]> entry : subscribers.entrySet()) {
                recipients.add(profileManager.read(decode(entry.getKey())));
            }
        }
       
        // Split application ID
        String applicationID = extractApplication(prefix);
        String namespace = extractNamespace(prefix); 
         
        // Send emails
        this.sendEmails(  
                publisher, 
                recipients, 
                publicationMap,
                applicationID,
                namespace,
                predicate);
    }
    
    /** 
     *  Send emails to the recipents, using the approriate template
     *  @param publisher User publishing this object
     *  @param applicationID ID of the application
     *  @param namespace Namespace, may be null. Equivalent to a "table name" 
     *  @param publication Object published (Data + Predicate) 
     *  @param recipients List of recipients 
     */
    private void sendEmails(     
            MUserBean publisher,
            Collection<MUserBean> recipients, 
            Map<String ,String> publication,
            String applicationID,
            String namespace,
            String predicate) 
    {
        
        LOGGER.info("Sending emails");
        
        // Prepare HashMap of object for FreeMarker template
        HashMap<String, Object> data = new HashMap<String, Object>(); 
        
        // Build URL of the application
        String url = getServerProtocol() + getServerURI();
        if (applicationID != null) {
            // can we rename the folder myEuroCINAdmin in myEuroCIN_ADMIN to skip below hack
            url += "/application/" + (applicationID.equals("myEuroCIN_ADMIN") ? "myEuroCINAdmin" : applicationID);
        } 
        
        // Set data map
        data.put("base_url", url);
        data.put("application", applicationID);
        data.put("publisher", publisher);
        data.put("post", publication);

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

    /**
     * The subscribe mechanism.
     * 
     * @see IPubSubManager#create(String, String, MUserBean)
     */
    @Override
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

    /**
     * The find mechanism.
     * @see com.mymed.controller.core.manager.pubsub.IPubSubManager#read(java.lang.String, java.lang.String)
     */
    @Override
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


    /**
     * The find mechanism.
     * @see com.mymed.controller.core.manager.pubsub.IPubSubManager#read(String, String, String, int, Boolean)
     */
    @Override
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

    /**
     * The find mechanism.
     * @see com.mymed.controller.core.manager.pubsub.IPubSubManager#read(String, String, String)
     */
    public final TreeMap<String, Map<String, String>> read(final String application, final List<String> predicate, final String start, final String finish)
            throws InternalBackEndException, IOBackEndException, UnsupportedEncodingException {

        final TreeMap<String, Map<String, String>> resMap = new TreeMap<String, Map<String, String>>();

        resMap.putAll(storageManager.multiSelectList(SC_APPLICATION_CONTROLLER, predicate, start, finish));

        return resMap;
    }


    /**
     * The find mechanism: get more details.
     * @see com.mymed.controller.core.manager.pubsub.IPubSubManager#read(java.lang.String, java.lang.String,
     * java.lang.String)
     */
    @Override
    public final List<Map<String, String>> read(final String application, final String predicate, final String userID)
            throws InternalBackEndException, IOBackEndException {

        final List<Map<byte[], byte[]>> list = storageManager
                .selectList(SC_DATA_LIST, application + predicate + userID);
        final List<Map<String, String>> resList = new ArrayList<Map<String, String>>();
        for (final Map<byte[], byte[]> set : list) {
            final Map<String, String> resMap = new HashMap<String, String>();
            for (final Entry<byte[], byte[]> entry : set.entrySet()) {
                final String key = decode(entry.getKey());
                final String value = decode(entry.getValue());
                resMap.put(key, value);
            }

            resList.add(resMap);
        }

        return resList;
    }

    /**
     * The find mechanism.
     * @see com.mymed.controller.core.manager.pubsub.IPubSubManager#read(java.lang.String)
     */
    @Override
    public final Map<String, String> read(final String appuserid) throws InternalBackEndException, IOBackEndException {

        final Map<String, String> res = new HashMap<String, String>();
        final Map<byte[], byte[]> predicates = storageManager.selectAll(CF_SUBSCRIBERS, appuserid);
        for (final Entry<byte[], byte[]> entry : predicates.entrySet()) {
            final String key = decode(entry.getKey());
            final String val = decode(entry.getValue());
            res.put(key, val);
            LOGGER.info("__"+appuserid +" is subscribed to "+ key);
        }

        return res;
    }

    /**
     * @see IPubSubManager#delete(String * 3 + MUserBean)
     */
    @Override
    public final void delete(final String application, final String predicate, final String subPredicate,
            final String publisherID) throws InternalBackEndException, IOBackEndException {
        // Remove publisher member
        storageManager.removeAll(CF_SUBSCRIBEES, application + predicate);
        // Remove the 1st level of data
        storageManager.removeSuperColumn(SC_APPLICATION_CONTROLLER, application + predicate,
                subPredicate + publisherID);
        // Remove the 2nd level of data
        storageManager.removeAll(SC_DATA_LIST, application + subPredicate + publisherID);
        // Remove app model entry
        // storageManager.removeSuperColumn(SC_APPLICATION_MODEL, application, predicate + publisher.getId());
    }

    public final void deleteIndex(final String application, final String predicate, final String subPredicate,
            final String publisherID) throws InternalBackEndException, IOBackEndException {

        storageManager.removeSuperColumn(SC_APPLICATION_CONTROLLER, application + predicate,
                subPredicate + publisherID);
    }

    /**
     * @see IPubSubManager#delete(String * 3)
     */
    @Override
    public final void delete(final String application, final String user, final String predicate)
            throws InternalBackEndException, IOBackEndException {
        // Remove subscriber member from subsribers list
        storageManager.removeColumn(CF_SUBSCRIBERS, application + user, predicate);
        // Remove subscriber member from predicates subscribed list
        storageManager.removeColumn(CF_SUBSCRIBEES, application + predicate, user);
    }
}
