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
package com.mymed.utils;

import java.util.Comparator;
import java.util.Map;
import java.util.TreeSet;

/**
 *  A custom TreeSet that stores publications (in form of Map<String String>) 
 *  and avoids duplicates by implementing a comparator, checking for publisherID and predicate  
 *  @author rjolivet
 */
public class PublicationSet extends TreeSet<Map<String, String>>{

    private static final long serialVersionUID = 2625595960186153295L;

    static public final String PUBLISHER_ID = "publisherID";
    static public final String PREDICATE = "predicate";
    
    // ---------------------------------------------------------------------------
    // Comparator
    // ---------------------------------------------------------------------------
    
    static class PublicationComparator implements Comparator<Map<String, String>> {

        /** generate an id made of <predicate>:<publisherID> */
        private String getID(Map<String, String> publication) {
            String predicate = publication.get(PREDICATE);
            String publisherID = publication.get(PUBLISHER_ID);
            return 
                    ((predicate == null) ? "" : predicate) + ":" + 
                    ((publisherID == null) ? "" : publisherID);
        }
        
        @Override public int compare(
                Map<String, String> pub1, 
                Map<String, String> pub2) 
        {
            return this.getID(pub1).compareTo(this.getID(pub2));
        }
        
    }
    
    // ---------------------------------------------------------------------------
    // Constructor
    // ---------------------------------------------------------------------------
    
    /** Initialize the set with a custom constructor */
    public PublicationSet() {
        super(new PublicationComparator());
    }
    
}
