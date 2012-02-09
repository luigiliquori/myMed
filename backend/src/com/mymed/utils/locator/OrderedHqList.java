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
