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


import static com.mymed.utils.PubSub.getDateRange;
import static com.mymed.utils.PubSub.getFloatRange;

import java.util.ArrayList;
import java.util.List;

import com.mymed.model.data.AbstractMBean;

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
    
    private MOntologyID ontologyID;

    
    /*
     * a method to return all rows to be searched between value valueStart and valueFinish
     */
	public List<String> getRowIndexes(String key) {
		List<String> res = new ArrayList<String>();

		//int ontId = Integer.parseInt(ontologyID);

		switch (ontologyID) {

		case DATE:
			res.addAll(getDateRange(key, valueStart, valueEnd));
			break;
		case FLOAT:
			res.addAll(getFloatRange(key, valueStart, valueEnd));
			break;
		default: // default, no range queries done = exact matchmaking
			res.add(key + valueStart);
			break;
		}

		return res;
	}
	
	public MDataBean getStart(String key) {
		return new MDataBean(key, valueStart, ontologyID);
	}
	
	public MDataBean getEnd(String key) {
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

	public MOntologyID getOntologyID() {
		return ontologyID;
	}

	public void setOntologyID(MOntologyID ontologyID) {
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
