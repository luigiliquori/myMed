package com.mymed.utils;

import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.List;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.application.DataBean;
import com.mymed.model.data.application.MOntologyID;

/**
 * Utils methods for pub sub managers
 * 
 * @author auburtic
 *
 */

public class PubSub {
	

	/**
	 * 
	 * generate all possible set of indexes from lists
	 * 
	 * [A, B, (c1, c2, c3)] ->
	 * [A], [B], [c1], [c2], [c3], [A,B], [A, c1] ...
	 * 
	 */
	
	public static List<List<Index>> generateIndexes(final List<Index> indexes,
			final List<List<Index>> enums) {

		List<List<Index>> result = new ArrayList<List<Index>>();
		int broadcastSize = (int) Math.pow(2, indexes.size());

		List<Index> cur;

		/*
		 * generate all combinatorics from indexes 2^indexes.size()
		 */

		for (int i = 0; i < broadcastSize; i++) {
			int mask = i;
			int j = 0;
			cur = new ArrayList<Index>();

			while (mask > 0) {
				if ((mask & 1) == 1) {
					cur.add(indexes.get(j));
				}
				mask >>= 1;
				j++;
			}
			if (indexes.size() != 0) {
				result.add(cur);
			}
		}

		/*
		 * multiply the Index generated with all enum possiblities, so
		 * 2^indexes.size() * (enum.get(0).size()+1) * (enum.get(1).size()+1)
		 * ...
		 * 
		 * note ENUMs are treated after other types, the find must comply to
		 * this order
		 */

		List<List<Index>> _result;

		for (List<Index> li : enums) {
			_result = new ArrayList<List<Index>>(result);
			for (Index i : li) {
				for (List<Index> r : _result) {
					List<Index> _r = new ArrayList<Index>(r);
					_r.add(i);
					result.add(_r);
				}
			}
		}

		/*
		 * remove empty item
		 */
		result.remove(new ArrayList<Index>());

		return result;
	}
    
	/**
	 * Its counter-part
	 * 
	 * generate all possible rows to search against given range of indexes
	 * 
	 * [(a1, a2, a3), (b1, b2), (c1, c2, c3)] ->
	 * [a1b1c1], [a1b1c2], [a1b1c3], [a1b2c1], ...
	 * 
	 */
    
    public static void generateRows(List<List<String>> li, int[] pos, int i, List<String> res) {
	    if (i == pos.length) {
	        StringBuilder b = new StringBuilder();
	        for (int j = 0 ; j < pos.length ; j++) {
	            b.append(li.get(j).get(pos[j]));
	        }
	        res.add(b.toString());
	    } else {
	        for (pos[i] = 0 ; pos[i] < li.get(i).size() ; pos[i]++) {
	        	generateRows(li, pos, i+1, res);
	        }
	    }
	}
    
    
    
    // ----message-getters----
    
    @SuppressWarnings("serial")
	public static List<List<String>> getRows(List<DataBean> data) {
		List<List<String>> res = new ArrayList<List<String>>();

		for (final DataBean d : data) {
			switch (d.getType()){
			case DATE:
				res.add(getDateRange(d.getKey(), Long.parseLong(d.getValue().get(0)), Long.parseLong(d.getValue().get(1))));
				break;
			case FLOAT:
				res.add(getFloatRange(d.getKey(), Float.parseFloat(d.getValue().get(0)), Float.parseFloat(d.getValue().get(1))));
				break;
			case ENUM:
				for (int k = 0; k < d.getValue().size(); k++) {
					d.getValue().set(k, d.getKey() + d.getValue().get(k));
				}
				res.add(d.getValue());
				break;
			case KEYWORD:
				res.add(new ArrayList<String>() {{ add(d.getKey() + d.getValue().get(0));}});
				break;
			}
		}
		return res;
	}
    public static List<List<String>> getRanges(List<DataBean> data) {
		List<List<String>> res = new ArrayList<List<String>>();

		for (final DataBean d : data) {
			switch (d.getType()) {
			case DATE:
			case FLOAT:
				List<String> l = new ArrayList<String>();
				l.add(d.getValue().get(0).toString());
				l.add(d.getValue().get(1).toString());
				res.add(l);
				break;
			}
		}
		return res;
	}
	
	public static List<Index> getIndexes(List<DataBean> data) {
		List<Index> res = new ArrayList<Index>();

		for (final DataBean d : data) {
			switch (d.getType()) {
			case DATE:
				Long t = Long.parseLong(d.getValue().get(0));
				res.add(new Index(d.getKey() + (t - t % interval), padDate(t)));
				break;
			case FLOAT:
				res.add(new Index(d.getKey() + d.getValue().get(0).split("\\.")[0], padFloat(d.getValue().get(0))));
				break;
			case KEYWORD:
				res.add(new Index(d.getKey() + d.getValue().get(0), ""));
				break;
			}
		}
		return res;
	}
	
	public static List<List<Index>> getIndexesEnums(List<DataBean> data) {
		List<List<Index>> res = new ArrayList<List<Index>>();
		List<Index> en;
		for (final DataBean d : data) {
			switch (d.getType()) {
			case ENUM:
				en = new ArrayList<Index>();
				for (String s : d.getValue()) {
					en.add(new Index(d.getKey() + s, ""));
				}
				res.add(en);
				break;
			}
		}
		return res;
	}
    
	public static List<DataBean> subList(List<DataBean> data, MOntologyID type) {
		List<DataBean> res = new ArrayList<DataBean>();
		for (final DataBean d : data) {
			if (d.getType() == type) {
				res.add(d);
			}
		}
		return res;
	}
    
    
    
    //-----generic-utils-----------
    
	public static long parseLong(String s) {
		try {
			return Long.parseLong(s);
		} catch (NumberFormatException e) {
			throw new InternalBackEndException(e);
		}
	}
	
	public static float parseFloat(String s) {
		try {
			return Float.parseFloat(s);
		} catch (NumberFormatException e) {
			throw new InternalBackEndException(e);
		}
	}
	
	/*
     * DATE type
     *    get Range of rows to search between v1 and v2 
     *    (universal timestamps in seconds)
     */
	
	public final static long interval =  60 * 60 * 24 * 7; // 1 week in s

	public static List<String> getDateRange(String key, long v1, long v2){
		List<String> res = new ArrayList<String>();

		if (v1 != 0 && v2 != 0){
	    	long curTime = v1 - v1 % interval; //init with first day at 0h:00:00
	    	while (curTime <= v2) {
	    		res.add(key + curTime);
	    		curTime += interval;
	    	}
		}
		
		return res;
	}
    
    
   /*
    * FLOAT type
    *    integer part is indexed in rows
    *    et Range of rows to search between v1 and v2 
    * [2.33 - 5.6] -> [2, 3, 4, 5]
    */
    public static List<String> getFloatRange(String key, Float v1, Float v2){
    	List<String> res = new ArrayList<String>();  	
		int i1 = v1.intValue();
		int i2 = v2.intValue();
		for (int i = i1; i <= i2; i++) {
			res.add(key + String.valueOf(i));
		}
		return res;
    }
	
    /** Pads float numbers to have a 2 characters integer part, for utf-8 sorting */
   	public static String padFloat(String value){
	    String[] p = value.split("\\.");
	    if (2 == p.length){
	        return String.format("%04d.%s", Integer.parseInt(p[0]), p[1]);
	    }
	    return String.format("%04d", Integer.parseInt(p[0]));
   	}
   	
   	/** Pads date timestamps in seconds over 10 digits  */
   	public static String padDate(Long value){
   		return new DecimalFormat("0000000000").format(value);
   	}
 
   	/**  simple class to split which part of an index is in row, and which (if any) prefixed in column name */
	public static class Index {

		String row; /* part of an index appended in row name for Partitionning */
		String col; /* part prepended in col name for Sorting */

		public Index(String row, String col) {
			this.row = row;
			this.col = col;
		}

		public static String joinRows(List<Index> predicate) {
			String s = "";
			for (Index i : predicate) {
				s += i.row;
			}
			return s;
		}

		public static String joinCols(List<Index> predicate) {
			String s = "";
			for (Index i : predicate) {
				s += i.col.length() > 0 ? i.col + "+" : "";
			}
			return s;
		}

		public String toString() {
			return row + ":" + col;
		}
	}  
	
	public static String join(List<DataBean> data) {
		StringBuffer str = new StringBuffer();
		for (DataBean d : data) {
			str.append(d.toString());
		}
		return str.toString();
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
