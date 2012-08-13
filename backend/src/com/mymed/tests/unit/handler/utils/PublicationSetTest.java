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
package com.mymed.tests.unit.handler.utils;
import static com.mymed.utils.PublicationSet.PREDICATE;
import static com.mymed.utils.PublicationSet.PUBLISHER_ID;
import static junit.framework.Assert.*;

import java.util.HashMap;
import java.util.Map;

import org.junit.Test;

import com.mymed.utils.PublicationSet;

/**
 *  Test {@link PublicationSet}
 */
public class PublicationSetTest {
    
    // ---------------------------------------------------------------------------
    // Attributes
    // ---------------------------------------------------------------------------
    
    private PublicationSet pubSet = new PublicationSet(); 
    
    // ---------------------------------------------------------------------------
    // Utils
    // ---------------------------------------------------------------------------
    
    private Map<String, String> map(String  ... args) {
        
        HashMap<String, String> map = new HashMap<String, String>();
        for (int i=0; i<args.length/2 ;i++) {
            map.put(args[2*i], args[2*i+1]);
        }
        return map;
    }
    

    @Test public void test1() {
        
        this.pubSet.add(map(
                PREDICATE, "pred1",
                PUBLISHER_ID, "pub1"));
        this.pubSet.add(map(
                PREDICATE, "pred1",
                PUBLISHER_ID, "pub2"));
        
        assertEquals(2, this.pubSet.size());  
    }
    
    @Test public void test12() {
        
        this.pubSet.add(map(
                PREDICATE, "pred1",
                PUBLISHER_ID, "pub1"));
        this.pubSet.add(map(
                PREDICATE, "pred2",
                PUBLISHER_ID, "pub1"));
        
        assertEquals(2, this.pubSet.size());  
    }
    
    @Test public void test2() {
        
        this.pubSet.add(map(
                PREDICATE, "pred1",
                PUBLISHER_ID, "pub1",
                "data", "data"));
        this.pubSet.add(map(
                PREDICATE, "pred1",
                PUBLISHER_ID, "pub1",
                "data", "data2"));
        
        assertEquals(1, this.pubSet.size());  
    }
    
    @Test public void test3() {
        
        this.pubSet.add(map(
                PREDICATE, "pred1",
                PUBLISHER_ID, "pub1",
                "data", "data"));
        this.pubSet.add(map(
                "data", "data2"));
        
        assertEquals(2, this.pubSet.size());  
    }
    
    @Test public void test4() {
        
        this.pubSet.add(map(
                "data", "data"));
        this.pubSet.add(map(
                "data", "data2"));
        
        assertEquals(1, this.pubSet.size());  
    }
    
    
}
