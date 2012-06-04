package com.mymed.myjam.locator;

import java.util.LinkedList;
import java.util.ListIterator;

public class OrderedHqList extends LinkedList<HqWrapper>{	
	
	/**
	 * 
	 */
	private static final long serialVersionUID = 6342688947679245550L;
	
	/*
	 * The complexity is of the search is O(n) and of the insertion O(1), using an ArrayList we have 
	 * O(log(n)) (dicotomic),
	 * but the ordered insertion is linear O(n).
	 * If index is null, throws an exception.
	 */
	public int addOrdered(HqWrapper index){
		ListIterator<HqWrapper> listIt = this.listIterator();
		
		while (listIt.hasNext()){
			HqWrapper currQi = listIt.next();
			if (index.getMetric()<currQi.getMetric()){
				listIt.previous();
				listIt.add(index);
				return listIt.previousIndex();
			}
		}
		listIt.add(index);
		return listIt.previousIndex();
	}

	

}
