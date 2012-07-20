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

import com.mymed.model.data.AbstractMBean;

/**
 * datum bean
 *   -> MDataBean + QueryBean
 * @param <T>
 * 
 * @param <T>
 */
public final class SimpleDataBean extends AbstractMBean {
	
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	
	private String type;
	private String key;
	private String value;
	
    public SimpleDataBean() {
        super();
    }
    

	public DataBean toDataBean(){

		return new DataBean(MOntologyID.fromInt(Integer.parseInt(type)), key, value);
    }
	
	public SimpleDataBean(String type, String key, String value) {
		this.type = type;
		this.key = key;
		this.value = value;
		
	}
	
	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}

	public String getKey() {
		return key;
	}

	public void setKey(final String key) {
		this.key = key;
	}
	

	public String getValue() {
		return value;
	}

	public void setValue(String value) {
		this.value = value;
	}

	public String toString() {
		return key + value;

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
