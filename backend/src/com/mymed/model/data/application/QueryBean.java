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
    
    /*  Ontology ID list */
    
	public static final int KEYWORD = 0;
	public static final int GPS     = 1;
	public static final int ENUM    = 2;
	public static final int DATE    = 3;
	public static final int TEXT    = 4;
	public static final int PICTURE = 5;
	public static final int VIDEO   = 6;
	public static final int AUDIO   = 7;
	
	/* attr */
    
    private String key;
    
    private String groupStart;
    private String groupFinish;
    
    private String valueStart;
    private String valueFinish;
    
    private String ontologyID;

    /*
     * (non-Javadoc)
     * @see com.mymed.model.data.AbstractMBean#equals(java.lang.Object)
     */
    @Override
    public boolean equals(final Object object) {
        final boolean returnValue = true;
        // TODO
        return returnValue;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.model.data.AbstractMBean#hashCode()
     */
    @Override
    public int hashCode() {
        // TODO
        return 0;
    }
    
    // a method to return all groups between groupStart and groupEnd
    
    public List<String> getGroups(){
    	List<String> res = new ArrayList<String>();
    	
    	int ontId = Integer.parseInt(ontologyID);
    	
    	switch (ontId){
    	
    	case DATE:
    		String[] start = groupStart.split("-");
    		String[] end = groupStart.split("-");
    		for (int i=Integer.parseInt(start[0]); i<Integer.parseInt(end[0]); i++){
    			for (int j=Integer.parseInt(start[1]); j<Integer.parseInt(end[1]); j++){
    				for (int k=Integer.parseInt(start[2]); k<Integer.parseInt(end[2]); k++){
    					res.add(i+"-"+j+"-"+k);
    				}
    			}
    		}
    		
    		break;
    		
    	default:
    		int s = 0, f = 0;
            try {
            	s = Integer.parseInt(groupStart);
        		f = Integer.parseInt(groupFinish);
            } catch (NumberFormatException e) {
    			// TODO: handle exception
    		}
    		
			for (int i = s; i < f; i++) {
    			res.add(String.valueOf(i));
    		}
    		break;
    	}
    	
    	
    	return res;
    }
    
    public String toString(){
		return key + groupStart;
    }

	public String getKey() {
		return key;
	}

	public void setKey(String key) {
		this.key = key;
	}

	public String getGroupStart() {
		return groupStart;
	}

	public void setGroupStart(String groupStart) {
		this.groupStart = groupStart;
	}

	public String getGroupFinish() {
		return groupFinish;
	}

	public void setGroupFinish(String groupFinish) {
		this.groupFinish = groupFinish;
	}

	public String getValueStart() {
		return valueStart;
	}

	public void setValueStart(String valueStart) {
		this.valueStart = valueStart;
	}

	public String getValueFinish() {
		return valueFinish;
	}

	public void setValueFinish(String valueFinish) {
		this.valueFinish = valueFinish;
	}

	public String getOntologyID() {
		return ontologyID;
	}

	public void setOntologyID(String ontologyID) {
		this.ontologyID = ontologyID;
	}


}
