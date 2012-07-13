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
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.application.DataBean;
import com.mymed.model.data.user.MUserBean;

public interface IPubSubManager extends com.mymed.controller.core.manager.pubsub.IPubSubManager {

	
	/* new in v2 */
	
	/**
	 *  create indexes 
	 */
  
	public void create(
            String application, 
            final String predicate, 
            final String colprefix, 
            final String subPredicate,
    		final MUserBean publisher, 
    		final List<DataBean> dataList) 
    		        throws InternalBackEndException, IOBackEndException ;
	
	/**
	 *  
	 * create Data 
	 */
	
	public void create(
            String application, 
            final String subPredicate,
    		final List<DataBean> dataList) 
    		        throws InternalBackEndException, IOBackEndException ;
	
	/**
	 * subscribe v2
	 * 
	 */
	  void create(
	          String application, 
	          String predicate, 
	          String subscriber) 
	                  throws InternalBackEndException, IOBackEndException;
	
	/**
	 * 
	 * the extended read used in v2, for range queries
	 * reads over rows in predicate, and in the column slice [start-finish]
	 * 
	 */
	
	public Map<String, Map<String, String>> read(
            final String application, 
            final List<String> predicate, 
            final String start, 
            final String finish)
                    throws InternalBackEndException, IOBackEndException, UnsupportedEncodingException ;
	
	
	/**
	 * get details v2: return val modified
	 */
	List<DataBean> read_(String application, String predicate)
			throws InternalBackEndException, IOBackEndException;
	
	/**
	 * delete Data
	 */

	public void delete(
	        final String application, 
	        final String subPredicate)
			        throws InternalBackEndException, IOBackEndException ;
	
	
	
	public void sendEmailsToSubscribers(  
            String application,          
            String predicate,
            MUserBean publisher,
            List<DataBean> dataList);
	
}
