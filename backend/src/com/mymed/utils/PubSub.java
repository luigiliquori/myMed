package com.mymed.utils;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import org.apache.commons.lang.time.DateFormatUtils;

import com.mymed.model.data.application.MDataBean;

public class PubSub {
	
	/*  Ontology ID list */
    
	public static final int KEYWORD = 0;
	public static final int GPS     = 1;
	public static final int ENUM    = 2;
	public static final int DATE    = 3;
	public static final int TEXT    = 4;
	public static final int PICTURE = 5;
	public static final int VIDEO   = 6;
	public static final int AUDIO   = 7;
	/**
	 * 
	 * contructs all possible set of indexes with level terms from predicateMap
	 * 
	 *  ex: [A, B, C] with level = 2 returns [A, B], [A, C], [B, C]
	 * 
	 * 
	 * @param predicateMap
	 * @param level
	 * @return List of StringBuffers
	 */
	
	public static List<List<Index>> getPredicate(final List<MDataBean> predicateList, final int level) {

		List<List<Index>> result = new ArrayList<List<Index>>();
		int n = predicateList.size();
		
		List<Index> indexes;

		for (long i = (1 << level) - 1; (i >>> n) == 0; i = nextCombo(i)) {
			int mask = (int) i;
			int j = 0;
			indexes = new ArrayList<Index>();
			
			while (mask > 0) {
				if ((mask & 1) == 1) {
					MDataBean d = predicateList.get(j);
					//int ontID = parseInt(d.getOntologyID());
					
					if ( d.getOntologyID() == DATE ){
						indexes.add( new Index(d.getKey()+d.getValue().substring(0, 10), d.getValue()));
					} else if ( d.getOntologyID() == ENUM ){
						indexes.add(new Index(d.getKey()+d.getValue().substring(0, d.getValue().indexOf(".")), d.getValue()));
					} else{
						indexes.add(new Index(d.getKey()+d.getValue(), ""));
					}
				}
				mask >>= 1;
				j++;
			}

			if (indexes.size() != 0) {
				result.add(indexes);
			}
		}

		return result;
	}

	
	public static class Index{ /* simple class to distinguish which part an ontology will be indexed in rows, when not all, the other part must be prefixed in col*/
		
		String row; /* part of a String Index appended in row name */
		String col; /* part prepended in col name */
		
		Index(String row, String col) {
			this.row = row;
			this.col = col;
		}
		
		public static String toRowString(List<Index> predicate){
			String s="";
	        for (Index i : predicate){
				s += i.row;
	        }
	        return s;
		}
		
		public static String toColString(List<Index> predicate){
	        String s="";
	        for (Index i : predicate){
	        	if (i.col.length() != 0){
	        		s += i.col + "+";
	        	}
	        }
	        return s;
	    }
	}	
	

    public static void constructRows(List<List<String>> data, int[] pos, int n, List<String> res) {
	    if (n == pos.length) {
	        StringBuilder b = new StringBuilder();
	        for (int i = 0 ; i != pos.length ; i++) {
	            b.append(data.get(i).get(pos[i]));
	        }
	        res.add(b.toString());
	    } else {
	        for (pos[n] = 0 ; pos[n] != data.get(n).size() ; pos[n]++) {
	        	constructRows(data, pos, n+1, res);
	        }
	    }
	}
    
    public static boolean isPredicate(Map<String, Map<String, String>> map, String key, String value) {
		for (Entry<String, Map<String, String>> el : map.entrySet()) {
			if (value.equals(el.getValue().get(key))) {
				return true;
			}
		}
		return false;
	}
	
    /*
     * DATE
     *    yyyy-mm-dd is indexed in rows
     *    if there is hours, minutes.. this value is prefixed in column to allow it's auto sorting
     * 
     */
    
    public static List<String> getDateRange(String key, String t1, String t2){
    	List<String> res = new ArrayList<String>();
    	DateFormat formatter = new SimpleDateFormat("yyyy-MM-dd");
    	Date startDate = null, endDate = null;
		try {
			startDate = (Date)formatter.parse(t1);
			endDate = (Date)formatter.parse(t2);
		} catch (ParseException e) {} 
		if (startDate != null && endDate != null){
			long interval = 1000 * 60 * 60 * 24; // 1 hour in millis
	    	long endTime =endDate.getTime() ; // create your endtime here, possibly using Calendar or Date
	    	long curTime = startDate.getTime();
	    	while (curTime <= endTime) {
	    		Date d  = new Date(curTime);
	    		res.add(key + DateFormatUtils.format(d, "yyyy-MM-dd"));
	    		curTime += interval;
	    	}
		}
    	
		return res;
    }
    
    
   /*
    * ENUM
    *    integer part is indexed in rows
    *    if there is a floating part the value is prefixed in column to allow it's auto sorting
    * [2.33 - 5.6] -> [2, 3, 4, 5]
    */
    public static List<String> getEnumRange(String key, String v1, String v2){
    	List<String> res = new ArrayList<String>();
		int a = parseInt(v1.split("\\.")[0]);
		int b = parseInt(v2.split("\\.")[0]);
		for (int i = a; i <= b; i++) {
			res.add(key + String.valueOf(i));
		}
		return res;
    }
    
    
    
    
    
    
    //-----------------
    
    public static int[] parseDate(String date) { // format yyyy-mm-dd
    	int[] values = new int[3];
    	String[] dateValues = date.split("-");
    	for (int i = 0; i < 3  && i< dateValues.length; i++) {
    		values[i] = parseInt(dateValues[i]);
    	}
    	return values;
    }
    
    public static int parseInt(String s){
    	int i = 0;
    	try {
			i = Integer.parseInt(s);
		} catch (NumberFormatException e) {} 
    	return i;
    }
    
    private static long nextCombo(long n) {
    	// Gosper's hack, doesn't support level>= 64, there are other recursive functions to replace it without this limit
		
    	// moves to the next combination (of n's bits) with the same number of 1 bits
    	
		long u = n & (-n);
		long v = u + n;
		return v + (((v ^ n) / u) >> 2);
	}
    
    /** Get application from a prefix "applicationID:namespace"  */
    public static String extractApplication(String prefix) {
        return prefix.split(":")[0];
    }
    
    /** Get namespace (or null if none found) from a prefix "applicationID:namespace"  */
    public static String extractNamespace(String prefix) {
        String[] parts = prefix.split(":");
        return (parts.length == 2) ? parts[1] : null;
    }
    
    /** Make a prefix with an aplpication and optionnal namespace "application:namespace "*/
    public static String makePrefix(String application, String namespace) {
        return (namespace == null) ? application : (application + ':' + namespace);
    }
	

}
