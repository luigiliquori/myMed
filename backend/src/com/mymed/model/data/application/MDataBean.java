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
 * This class represent a dato ontology bean
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public final class MDataBean extends AbstractMBean {
	

    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = 6788844723133324991L;
    private String key;
    private String value;
    private String ontologyID;

    public String getKey() {
        return key;
    }

    public void setKey(final String key) {
        this.key = key;
    }

    public String getValue() {
        return value;
    }

    public void setValue(final String value) {
        this.value = value;
    }

    public String getOntologyID() {
        return ontologyID;
    }

    public void setOntologyID(final String ontologyID) {
        this.ontologyID = ontologyID;
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
        result = (PRIME * result) + (ontologyID == null ? 0 : ontologyID.hashCode());
        result = (PRIME * result) + (value == null ? 0 : value.hashCode());
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
        if (!(obj instanceof MDataBean)) {
            return false;
        }
        final MDataBean other = (MDataBean) obj;
        if (key == null) {
            if (other.key != null) {
                return false;
            }
        } else if (!key.equals(other.key)) {
            return false;
        }
        if (ontologyID == null) {
            if (other.ontologyID != null) {
                return false;
            }
        } else if (!ontologyID.equals(other.ontologyID)) {
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
}
