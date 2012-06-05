/*
 * Copyright 2012 POLITO 
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
package com.mymed.utils.locator;

import java.util.LinkedList;
import java.util.ListIterator;

/**
 * LinkedList which permits the ordered insertion of {@link HqWrapper} instances.
 * 
 * @author iacopo
 *
 */
public class OrderedHqList extends LinkedList<HqWrapper>{	
	
	private static final long serialVersionUID = 6342688947679245550L;
	
	/**
	 * Adds an {@link HqWrapper} instance at the list ordered by metric.
	 * 
	 * @param hq HqWrapper instance to be inserted.
	 * @return
	 */
	public int addOrdered(HqWrapper hq){
		ListIterator<HqWrapper> listIt = this.listIterator();
		
		while (listIt.hasNext()){
			HqWrapper currQi = listIt.next();
			if (hq.getMetric()<currQi.getMetric()){
				listIt.previous();
				listIt.add(hq);
				return listIt.previousIndex();
			}
		}
		listIt.add(hq);
		return listIt.previousIndex();
	}

	

}
