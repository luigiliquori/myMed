/*
 * Copyright 2012 INRIA Licensed under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law
 * or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 */
package com.mymed.model.data.application;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;
import com.mymed.controller.core.manager.storage.GeoLocStorageManager;
import com.mymed.model.data.AbstractMBean;
import com.mymed.utils.PubSub;
import com.mymed.utils.MConverter;
import com.mymed.utils.locator.Locator;

/**
 * This class represent a predicate bean
 * 
 * @author lvanni
 */
public final class QueryBean extends AbstractMBean {
    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = -2349418868650936378L;
	
	/* attr */
    
    private String valueStart;
    private String valueEnd;
    
    private int ontologyID;

    
    /*
     * a method to return all rows to be searched between value valueStart and valueFinish
     */
	public List<String> getRowIndexes(String key) {
		List<String> res = new ArrayList<String>();

		//int ontId = Integer.parseInt(ontologyID);

		switch (ontologyID) {

		case PubSub.DATE:
			res.addAll(PubSub.getDateRange(key, valueStart, valueEnd));
			break;
		case PubSub.ENUM:
			res.addAll(PubSub.getEnumRange(key, valueStart, valueEnd));
			break;
		default: // default, no range queries done = exact matchmaking
			res.add(key + valueStart);
			break;
		}

		return res;
	}
	
	public MDataBean toDataBeanStart(String key) {
		return new MDataBean(key, valueStart, ontologyID);
	}
	
	public MDataBean toDataBeanEnd(String key) {
		return new MDataBean(key, valueEnd, ontologyID);
	}

	public String getValueStart() {
		return valueStart;
	}

	public void setValueStart(String valueStart) {
		this.valueStart = valueStart;
	}

	public String getValueEnd() {
		return valueEnd;
	}

	public void setValueEnd(String valueEnd) {
		this.valueEnd = valueEnd;
	}

	public int getOntologyID() {
		return ontologyID;
	}

	public void setOntologyID(int ontologyID) {
		this.ontologyID = ontologyID;
	}

	@Override
	public int hashCode() {
		// TODO Auto-generated method stub
		return 0;
	}

	@Override
	public boolean equals(Object obj) {
		// TODO Auto-generated method stub
		return false;
	}


}
