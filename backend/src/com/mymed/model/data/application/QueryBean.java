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
    
    private String valueStart;
    private String valueEnd;
    
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
    
    /*
     * a method to return all rows to be searched between value valueStart and valueFinish
     */
    public List<String> getValues(){
    	List<String> res = new ArrayList<String>();
    	
    	int ontId = Integer.parseInt(ontologyID);
    	
    	switch (ontId){
    	
    	case DATE:
    		
    		// must follows the US format 2012-06-20, after put hours and seconds
			int[] startvalues = parseDate(valueStart);
			int[] endvalues = parseDate(valueEnd);

			for (int i = startvalues[0]; i < endvalues[0]; i++) {
				for (int j = startvalues[1]; j < endvalues[1]; j++) {
					for (int k = startvalues[2]; k < endvalues[2]; k++) {
						res.add(i + "-" + j + "-" + k);
					}
				}
			}
    		
    		break;
    		
		case ENUM:
			int s = 0,
			e = 0;
			try {
				s = Integer.parseInt(valueStart);
				e = Integer.parseInt(valueEnd);
			} catch (NumberFormatException er) {}

			for (int i = s; i < e; i++) {
				res.add(String.valueOf(i));
			}
			break;
    	default:
    		return null;
    		
    	}
    	
    	return res;
    }
    
    private static int[] parseDate(String date) { // format yyyy-mm-dd
    	int[] values = new int[3];
    	String[] dateValues = date.split("-");
    	for (int i = 0; i < 3  && i< dateValues.length; i++) {
    		try {
    			values[i] = Integer.parseInt(dateValues[i]);
    		} catch (NumberFormatException e) {}
    	}
    	return values;
    }

    public String toString(){
		return key + valueStart;
    }

	public String getKey() {
		return key;
	}

	public void setKey(String key) {
		this.key = key;
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

	public String getOntologyID() {
		return ontologyID;
	}

	public void setOntologyID(String ontologyID) {
		this.ontologyID = ontologyID;
	}


}
