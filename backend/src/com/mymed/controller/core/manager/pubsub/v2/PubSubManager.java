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
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.TreeMap;

import com.google.gson.Gson;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.mailtemplates.MailTemplateManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.pubsub.IPubSubManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;
import static com.mymed.utils.MiscUtils.*;

/**
 * The pub/sub mechanism manager.
 * 
 * @author lvanni
 */
public class PubSubManager extends com.mymed.controller.core.manager.pubsub.PubSubManager {

    final Map<String, byte[]> args;
    final MailTemplateManager mailTemplateManager;
    
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
        profileManager = new ProfileManager(storageManager);
        mailTemplateManager = new MailTemplateManager(storageManager);
        args = new HashMap<String, byte[]>();
    }

    /**
     * Publish mechanism.
     * 
     * @see IPubSubManager#create(String, String, MUserBean)
     */
  
    /* v2 creates */
    public final void createIndex(
            String application, 
            final String predicate, 
            final String colprefix, 
            final String subPredicate,
    		final MUserBean publisher, 
    		final List<MDataBean> dataList,
    		final List<MDataBean> predicateList) 
    		        throws InternalBackEndException, IOBackEndException 
    {
        args.clear();

        /*
         * Put all index data that enable to filter deeply 
         * @see read FindRequestHandler#READ
         *    , index filtering should be moved in this class soon, to free up handlers
         */

        for ( MDataBean item : dataList) {
            if (item.getOntologyID() < 4 /*|| item.getOntologyID() == 8*/)
                args.put(item.getKey(), encode(item.getValue()));
        }

        args.put("predicate", encode(subPredicate)); // dataId pointer
        if (predicateList != null) {
            Gson gson = new Gson();
            args.put("predicateListJson", encode(gson.toJson(predicateList)));
        }
        args.put("publisherID", encode(publisher.getId()));

        //---publisherName at the moment he published it, beware that name was possibly updated since
        args.put("publisherName", encode(publisher.getName()));

        storageManager.insertSuperSlice(
                SC_APPLICATION_CONTROLLER, 
                application + predicate, 
                colprefix + subPredicate + publisher.getId(), 
                args);


    }
    
    public final void createData(
            String application, 
            final String subPredicate,
    		final MUserBean publisher, 
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
    			        application + subPredicate + publisher.getId(),
    					item.getKey(), args);
    			args.clear();
    		}
    }
    
    public final void createMail(
            String application, 
            final String predicate, 
            final MUserBean publisher, 
            final List<MDataBean> dataList)
                    throws InternalBackEndException, IOBackEndException 
    {
    	this.sendEmails(
    	        application, 
    	        publisher,
    	        dataList, 
    	        null, 
    	        predicate);
    }  
 

    /* 
     * Changed the signature => rename to readIndex
     */
    public final Map<String, Map<String, String>> readIndex(
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
	public final void deleteData(
	        final String application, 
	        final String subPredicate,
			final String publisherID)
			        throws InternalBackEndException, IOBackEndException 
	{
		storageManager.removeAll(SC_DATA_LIST, application + subPredicate + publisherID);
	}
    
    public final void deleteIndex(
            final String application, 
            final String predicate, 
            final String subPredicate,
    		final String publisherID) 
    		        throws InternalBackEndException, IOBackEndException 
    {
    	storageManager.removeSuperColumn(SC_APPLICATION_CONTROLLER, application + predicate,
    			subPredicate + publisherID);
    }

    public final void deleteSubscription(
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
	
}
