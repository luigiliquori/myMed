package com.mymed.controller.core.requesthandler.matchmaking;

import java.util.ArrayList;
import java.util.List;

import com.mymed.controller.core.requesthandler.AbstractRequestHandler;
import com.mymed.model.data.application.MDataBean;

/*
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
 */
public abstract class AbstractMatchMaking extends AbstractRequestHandler {
	
    /**
     * JSON 'predicate' attribute.
     */
    protected static final String JSON_NAMESPACE = JSON.get("json.namespace");
    
	/**
	 * JSON 'predicate' attribute.
	 */
    protected static final String JSON_PREDICATE = JSON.get("json.predicate");

	/**
	 * JSON 'predicate' attribute.
	 */
    protected static final String JSON_PREDICATE_LIST = JSON.get("json.predicate.list");

    
	/**
	 * 
	 * contructs all possible indexes between 1 and level from predicateListObject
	 * 
	 *  ex: [A, B, C] with level = 2 returns [A.toString(), B.toString(), C.toString(),
	 *  				 A.toString()+B.toString(), A.toString()+C.toString(), B.toString()+C.toString()]
	 * 
	 * 
	 * @param predicateListObject
	 * @param level
	 * @return List of StringBuffers
	 */
	protected List<StringBuffer> getPredicate(final List<MDataBean> predicateListObject, final int level) {

		List<StringBuffer> result = new ArrayList<StringBuffer>();
		int n = predicateListObject.size();
		StringBuffer bufferPredicate = new StringBuffer(150);
		
		for (int k=1; k<=level; k++) {
			for (long i = (1 << k) - 1; (i >>> n) == 0; i = nextCombo(i)) {
				bufferPredicate = new StringBuffer(150);
				int mask = (int) i;
				int j = 0;

				while (mask > 0) {
					if ((mask & 1) == 1) {
						bufferPredicate.append(predicateListObject.get(j).toString());
					}
					mask >>= 1;
					j++;
				}
				bufferPredicate.trimToSize();
				if (bufferPredicate.length() != 0) {
					result.add(bufferPredicate);
				}
			}
		}

		return result;
	}
	

    private long nextCombo(long n) {
    	// Gosper's hack, doesn't support level>= 64, there are other recursive functions to replace it without this limit
		
    	// moves to the next combination (of n's bits) with the same number of 1 bits
    	
		long u = n & (-n);
		long v = u + n;
		return v + (((v ^ n) / u) >> 2);
	}

}
