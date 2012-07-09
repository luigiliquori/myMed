package com.mymed.utils;

import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.application.MDataBean;

/**
 * Utils methods for pub sub managers
 * 
 * @author auburtic
 *
 */

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
	public static final int FLOAT   = -1;


	private final static long interval =  60 * 60 * 24; // 1 day in s
	
	public static final int maxNumColumns = 10000; // arbitrary max number of cols read in a slice, to overrides 100 by default
	
	/**
	 * 
	 * contructs all possible set of indexes with level terms from predicateList
	 * 
	 *  ex: [A, B, C] with level = 2 returns [A, B], [A, C], [B, C]
	 * 
	 * 
	 * @param predicateMap
	 * @param level
	 * @return List of StringBuffers
	 */
	
	public static List<List<Index>> getIndex(final List<MDataBean> predicateList, final int level) {

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
						long t = parseLong(d.getValue());
						indexes.add(new Index(d.getKey() + (t - t % interval), d.getValue()));
					} else if ( d.getOntologyID() == FLOAT ){
						Float value = new Float(d.getValue());
						indexes.add(new Index(d.getKey()+value.intValue(), pad(value)));
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

	/*
	 * class to distinguish which part of an ontology will be indexed in rows,
	 *    in pubsub v1 ontology.value is in row (appended to ontology.key) and nothing is in column
	 *    in pubsub v2 it depends on ontology.ontologyID
	 */
	public static class Index{ 
		
		String row; /* part of a String Index appended in row name */
		String col; /* part prepended in col name */
		
		/*
		 * for ENUM (multiple) we need to use list of rows and
		 *  constructRows(indexlistrows, new int[indexlistrows.size()], 0, rows)
		 */
		
		Index(String row, String col) {
			this.row = row;
			this.col = col;
		}
		
		public static String joinRows(List<Index> predicate){
			String s="";
	        for (Index i : predicate){
				s += i.row;
	        }
	        return s;
		}
		
		public static String joinCols(List<Index> predicate){
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
    
    public static List<MDataBean> getIndexData(List<MDataBean> data){
    	List<MDataBean> predicate = new ArrayList<MDataBean>();
        for (MDataBean d : data){
        	if (d.getOntologyID() < TEXT) {
        		predicate.add(d);
        	}
        }
        return predicate;
	}
    
    public static String join(List<MDataBean> data){
    	StringBuffer str = new StringBuffer();
        for (MDataBean d : data){
        	str.append(d.toString());
        }
        return str.toString();
	}
    
    
    /**
     * DATE ontology ID
     *    get Range of rows to search between t1 and t2 
     *    (universal timestamps in seconds)
     */

    public static List<String> getDateRange(String key, String t1, String t2){
    	List<String> res = new ArrayList<String>();
    	long startTime = parseLong(t1);
    	long endTime = parseLong(t2);

		if (startTime != 0 && endTime != 0){
	    	long curTime = startTime - startTime % interval; //init with first day at 0h:00:00
	    	while (curTime <= endTime) {
	    		res.add(key + curTime);
	    		curTime += interval;
	    	}
		}
    	
		return res;
    }
    
    
   /*
    * FLOAT ontology ID
    *    integer part is indexed in rows
    *    if there is a floating part the value is prefixed in column to allow it's auto sorting
    * [2.33 - 5.6] -> [2, 3, 4, 5]
    */
    public static List<String> getFloatRange(String key, String v1, String v2){
    	List<String> res = new ArrayList<String>();
    	
		int i1 = new Float(v1).intValue();
		int i2 = new Float(v2).intValue();
		for (int i = i1; i <= i2; i++) {
			res.add(key + String.valueOf(i));
		}
		return res;
    }
    
    
    
    
    
    
    //-----------------
    
    private static long nextCombo(long n) {
    	// Gosper's hack, doesn't support level>= 64, there are other recursive functions to replace it without this limit
		
    	// moves to the next combination (of n's bits) with the same number of 1 bits
    	
		long u = n & (-n);
		long v = u + n;
		return v + (((v ^ n) / u) >> 2);
	}
    
	public static long parseLong(String s) {
		try {
			return Long.parseLong(s);
		} catch (NumberFormatException e) {
			throw new InternalBackEndException(e);
		}
	}
	
	/** Pads float numbers to have a 2 characters integer part, for utf-8 sorting */
	public static String pad(float value){
		return new DecimalFormat("00." + String.valueOf(value).replaceAll(".", "#")).format(value);
	}
    	    
    
    /** Get application from a prefix "applicationID<separator>namespace"  */
    public static String extractApplication(String prefix, String separator) {
        return prefix.split(separator)[0];
    }
    
    /** Get application from a prefix "applicationID:namespace"  */
    public static String extractApplication(String prefix) {
        return extractApplication(prefix, ":");
    }
    
    /** Get namespace (or null if none found) from a prefix "applicationID<separator>namespace"  */
    public static String extractNamespace(String prefix, String separator) {
        String[] parts = prefix.split(separator);
        return (parts.length == 2) ? parts[1] : null;
    }
    
    /** Get namespace (or null if none found) from a prefix "applicationID:namespace"  */
    public static String extractNamespace(String prefix) {
        return extractNamespace(prefix, ":");
    }
    
    /** Make a prefix with an aplpication and optionnal namespace "application<separator>namespace "*/
    public static String makePrefix(String application, String namespace, String separator) {
        return (namespace == null) ? application : (application + separator + namespace);
    }
    
    /** Make a prefix with an aplpication and optionnal namespace "application<separator>namespace "*/
    public static String makePrefix(String application, String namespace) {
        return makePrefix(application, namespace, ":");
    }
	

}
