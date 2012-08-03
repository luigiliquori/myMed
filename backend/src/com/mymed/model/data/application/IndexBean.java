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

import com.mymed.model.data.AbstractMBean;

/**
 * datum bean
 *   -> MDataBean + QueryBean
 */
public final class IndexBean extends AbstractMBean implements Comparable<IndexBean> {
	
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	
	private MOntologyID type;
	private String key;
	private ArrayList<String> value;
	

	public IndexBean(MOntologyID type, String key, ArrayList<String> value) {
		this.type = type;
		this.key = key;
		this.value = value;
		
	}
	
	public MOntologyID getType() {
		return type;
	}

	public void setType(MOntologyID type) {
		this.type = type;
	}

	public String getKey() {
		return key;
	}

	public void setKey(final String key) {
		this.key = key;
	}
	

	public ArrayList<String> getValue() {
		return value;
	}

	public void setValue(ArrayList<String> value) {
		this.value = value;
	}



	public String toString() {
		return key + value;

	}

	@Override
	public int compareTo(IndexBean o) {

		return this.key.compareTo(o.key);
	}

	@Override
	public int hashCode() {
		int result = 1;
		result = (PRIME * result) + String.valueOf(type).hashCode();
        result = (PRIME * result) + (key == null ? 0 : key.hashCode());
        result = (PRIME * result) + (value == null ? 0 : value.hashCode());
        return result;
	}

	@Override
	public boolean equals(Object obj) {
		if (this == obj) {
            return true;
        }
        if (obj == null) {
            return false;
        }
        if (!(obj instanceof IndexBean)) {
            return false;
        }
        final IndexBean other = (IndexBean) obj;
        if (key == null) {
            if (other.key != null) {
                return false;
            }
        } else if (!key.equals(other.key)) {
            return false;
        }
        return true;
	}

}