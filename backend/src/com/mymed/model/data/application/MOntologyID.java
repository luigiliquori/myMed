/**
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
 * 
 */
package com.mymed.model.data.application;

/**
 *  Ontology IDs
 *  @author rjolivet
 *  
 *  @ToDO rename to OntologyType
 *  ontologID -> unique hash for ontologies
 */
public enum MOntologyID {
    
    KEYWORD(0),
    GPS(1),
    ENUM(2),
    DATE(3),
    TEXT(4),
    PICTURE(5),    
    VIDEO(6),
    AUDIO(7),
    FLOAT(-1),
    DATA(8); //Type for data that are present in Index List, but are neither index nor data (unhack "data" key)
    
    private int value;
    
    private MOntologyID(int value) {
        this.value = value;
    }

    private MOntologyID() {
    	this(0);
	}
    
    private MOntologyID(String value) {
    	this(Integer.parseInt(value));
	}


	public int getValue() {
        return value;
    }
    
    public static MOntologyID fromInt(int value) {
        for(MOntologyID id : MOntologyID.values()) {
            if (id.value == value) return id;
        }
        return null;
    }
   
}
