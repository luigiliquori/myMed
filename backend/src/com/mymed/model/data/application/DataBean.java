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

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.AbstractMBean;

/**
 * This class represent a dato ontology bean
 * 
 */
public final class DataBean extends AbstractMBean implements Comparable<DataBean>{
	

	/**
     * Generated serial ID.
     */
    private static final long serialVersionUID = 6788844723133324991L;
    private String key;
    private String value;
    private MOntologyID type;
    
    public DataBean(String key, String value, MOntologyID type) {
		this.key = key;
		this.value = value;
		this.type = type;
	}
    
    public String getKey() {
        return key;
    }

    public void setKey(final String key) {
        this.key = key;
    }

    public String getValue() {
        if (value == null) throw new InternalBackEndException("Null value for ontology '%s'", key);
        return value;
    }

    public void setValue(final String value) {
        this.value = value;
    }

    public MOntologyID getType() {
        return type;
    }

    public void setType(final MOntologyID type) {
        this.type = type;
    }
    
    public String toString() {
    	return key+value;
    }

    /*
     * (non-Javadoc)
     * @see java.lang.Object#hashCode()
     */
    @Override
    public int hashCode() {
        int result = 1;
        result = (PRIME * result) + (key == null ? 0 : key.hashCode());
        result = (PRIME * result) + (value == null ? 0 : value.hashCode());
        result = (PRIME * result) + String.valueOf(type).hashCode();
        return result;
    }

    /*
     * (non-Javadoc)
     * @see java.lang.Object#equals(java.lang.Object)
     */
    @Override
    public boolean equals(final Object obj) {
        if (this == obj) {
            return true;
        }
        if (obj == null) {
            return false;
        }
        if (!(obj instanceof DataBean)) {
            return false;
        }
        final DataBean other = (DataBean) obj;
        if (key == null) {
            if (other.key != null) {
                return false;
            }
        } else if (!key.equals(other.key)) {
            return false;
        }
        if (type != other.type){
        	return false;
        }
        if (value == null) {
            if (other.value != null) {
                return false;
            }
        } else if (!value.equals(other.value)) {
            return false;
        }
        return true;
    }

	@Override
	public int compareTo(DataBean o) {
		
		return this.key.compareTo(o.key);
	}

}
