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
package com.mymed.tests.unit.manager;

import static org.junit.Assert.assertEquals;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import org.junit.Test;

import com.mymed.controller.core.requesthandler.matchmaking.AbstractMatchMaking;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.application.MOntologyID;

/**
 * @author rjolivet
 * Test combinatory 
 */
public class MatchMakingTest {

    /**  Helper to create a string test */ 
    static private List<String> list(String ... args) {
        List<String> result = new ArrayList<String>();
        result.addAll(Arrays.asList(args));
        return result;
    }
    
    /** Helper to create Ontologies 
     * ontologies(
     *  "key", "value", KEYWORD,
     *  "key2", "value2", TEXT, ...
     * )*/ 
    static private List<MDataBean> ontologies(Object ... args) {
        ArrayList<MDataBean> result = new ArrayList<MDataBean>();
        
        for (int i=0; i< (args.length/3); i++) {
            String key = (String) args[3*i];
            String value = (String) args[3*i +1];
            MOntologyID ontID = (MOntologyID) args[3*i +2];
            result.add(new MDataBean(key, value, ontID));  
        }
        
        return result;
    }
    
    static private List<String> combi(List<MDataBean> predicates, int level) {  
        return AbstractMatchMaking.getPredicate(predicates, 1, level);
    }
    

    
    /** Test combinatory */
    @Test public void testCombiPredicate() {
        
        List<MDataBean> pred = ontologies(
                "A", "a", MOntologyID.TEXT,
                "B", "b", MOntologyID.ENUM, 
                "C", "c", MOntologyID.TEXT, 
                "D", "d", MOntologyID.TEXT 
                );
        
        List<String> combi = combi(pred, 4);
        System.out.println("Expanded combis :"+ combi.size()+" " +combi);
        assertEquals(
                list(
                        "Aa",
                        "Bb1", "Bb2",
                        "Cc1|c2",
                        "AaBb1", "AaBb2",
                        "AaCc1|c2",
                        "Bb1Cc1|c2", "Bb2Cc1|c2",
                        "AaBb1Cc1|c2", "AaBb2Cc1|c2"),
                combi);
        
    }
}
