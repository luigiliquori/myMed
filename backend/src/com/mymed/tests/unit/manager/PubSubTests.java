package com.mymed.tests.unit.manager;

import static com.mymed.tests.unit.manager.PubSubTests.getDateRange;
import static com.mymed.tests.unit.manager.PubSubTests.getFloatRange;
import static com.mymed.tests.unit.manager.PubSubTests.interval;
import static com.mymed.tests.unit.manager.PubSubTests.padDate;
import static com.mymed.tests.unit.manager.PubSubTests.padFloat;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.fail;

import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import org.junit.Test;

import com.mymed.model.data.session.MSessionBean;
import com.mymed.tests.unit.manager.PubSubTests.Index;
import com.mymed.utils.GsonUtils;

public class PubSubTests {
	
	/**
	 * Utils
	 */
	
	public final static long interval =  60 * 60 * 24; // 1 day in s
	
	public static List<String> getDateRange(String key, long t1, long t2){
		List<String> res = new ArrayList<String>();

		if (t1 != 0 && t2 != 0){
	    	long curTime = t1 - t1 % interval; //init with first day at 0h:00:00
	    	while (curTime <= t2) {
	    		res.add(key + curTime);
	    		curTime += interval;
	    	}
		}
		
		return res;
	}
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
	public static String padFloat(float value){
		return new DecimalFormat("00." + String.valueOf(value).replaceAll(".", "#")).format(value);
	}
	
	/** Pads date timestamps in seconds over 10 digits  */
	public static String padDate(long value){
		return new DecimalFormat("0000000000").format(value);
	}
    
	/**  simple class to split which part of an index is in row, and which (if any) prefixed in column name */
	public static class Index {

		String row; /* part of a String Index appended in row name */
		String col; /* part prepended in col name */

		Index(String row, String col) {
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

    public static List<List<Index>> constructIndexes(
			final List<Index> indexes, final List<List<Index>> enums) {

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
		 * multiply the Index generated with all enum possiblities, 
		 * so 2^indexes.size() * (enum.get(0).size()+1) * (enum.get(1).size()+1) ...
		 * 
		 * note ENUMs are treated after other types, the find must comply to this order
		 */
		
		List<List<Index>> _result;
		
		for (List<Index> li : enums){
			_result = new ArrayList<List<Index>>(result);
			for (Index i : li){
				for (List<Index> r : _result){
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
    
    public static void constructRows(List<List<String>> li, int[] pos, int i, List<String> res) {
	    if (i == pos.length) {
	        StringBuilder b = new StringBuilder();
	        for (int j = 0 ; j < pos.length ; j++) {
	            b.append(li.get(j).get(pos[j]));
	        }
	        res.add(b.toString());
	    } else {
	        for (pos[i] = 0 ; pos[i] < li.get(i).size() ; pos[i]++) {
	        	constructRows(li, pos, i+1, res);
	        }
	    }
	}
    
	@Test
	public void testConstructPub() {
		
		String postJson = "{\"test\":12,\"ENUM\":{\"enum\":[1341783296,1341973211, \"toto\"], \"autreEnum\":[\"titi\",\"tonton\", \"tata\"]},\"DATE\":{\"date\":999999999},\"FLOAT\":{\"rate\":18.4},\"KEYWORD\":{\"europe\":\"paris\",\"type\":\"métier1\"}}";

    	Post p =  GsonUtils.gson.fromJson( postJson , Post.class);
		p.init();
    	System.out.println(p);
    	
    	List<List<Index>> lli = constructIndexes(p.getIndexes(), p.getEnums());
    	
    	for (List<Index> li : lli){
    		System.out.println(li);
    	}
    	System.out.println(" nb of rows to Post: "+lli.size());
    	
		assertEquals("nb of rows is ok\n", lli.size(), (int) Math.pow(2, 4)*4*4-1);
						/* 2^ raw indexes size * (enum size+1) * (otherenumsize +1) - empty  */

	}

	@Test
	public void testConstructFind() {
		
		String reqJson = "{\"test\":12,\"ENUM\":{\"enum\":[1341783296,\"toto\"], \"autreEnum\":[\"titi\",\"tonton\", \"tata\"]},\"FLOAT\":{\"rate\":[4,18.4]},\"KEYWORD\":{\"europe\":\"\",\"type\":\"métier1\"}}";
    	Req r =  GsonUtils.gson.fromJson( reqJson , Req.class);
    	r.init();
    	List<List<String>> queryRows = r.getRows();
    	
    	System.out.println();
    	for (List<String> li : queryRows){
    		System.out.println(li);
    	}
    	
    	List<String> rows = new ArrayList<String>();
    	constructRows(queryRows, new int[queryRows.size()], 0, rows);
    	System.out.println();
    	System.out.println(rows);
    	System.out.println(" nb of rows to Req: "+rows.size());
    	System.out.println(r.getCols());
    	System.out.println(" nb of cols range to slice: "+r.getCols().size());
    	
		assertEquals(" nb of rows to find is ok\n", rows.size(), 90);
			/* (18-4+1=15)rate slice range * (3)enum range * (2)enum range   */
		
		assertEquals(" nb of cols to find between range limits is ok\n", r.getCols().size(), 1);

	}
	
	/**
	 * @param args
	 */
	public static void main(String args[]) {
		
	}
	
}






class Post extends Message<String, List<String>, Long, Float>{

	/**
	 * List of pure match Indexes (! ENUM)
	 * @return
	 */
	public List<Index> getIndexes() {
		List<Index> res = new ArrayList<Index>();

		for (final Entry<String, Long> e : DATE.entrySet()) {
			long t = e.getValue();
			res.add(new Index(e.getKey() + (t - t % interval), padDate(t)));
		}

		for (final Entry<String, Float> e : FLOAT.entrySet()) {
			res.add(new Index(e.getKey() + e.getValue().intValue(), padFloat(e.getValue())));
		}

		for (final Entry<String, String> e : KEYWORD.entrySet()) {
			res.add(new Index(e.getKey() + e.getValue(), ""));
		}

		return res;
	}

	/**
	 * List of enums categories
	 * @return
	 */
	public List<List<Index>> getEnums() {
		List<List<Index>> res = new ArrayList<List<Index>>();
		
		List<Index> en;
		for (final Entry<String, List<String>> e : ENUM.entrySet()) {
			en = new ArrayList<Index>();
			for (String s : e.getValue()) {
				en.add(new Index(e.getKey() + s, ""));
			}
			res.add(en);
		}
		
		return res;
	}
	
}


class Req extends Message<String, List<String>, List<Long>, List<Float>>{

	/**
	 * List of composite rows
	 * @return
	 */
	public List<List<String>> getRows() {
		List<List<String>> res = new ArrayList<List<String>>();

		for (final Entry<String, List<Long>> e : DATE.entrySet()) {
			res.add(getDateRange(e.getKey(), e.getValue().get(0), e.getValue().get(1)));
		}

		for (final Entry<String, List<Float>> e : FLOAT.entrySet()) {
			res.add(getFloatRange(e.getKey(), e.getValue().get(0), e.getValue().get(1)));
		}

		for (final Entry<String, List<String>> e : ENUM.entrySet()) {
			for (int k = 0; k < e.getValue().size(); k++) {
				e.getValue().set(k, e.getKey() + e.getValue().get(k));
			}
			res.add(e.getValue());
		}
		for (final Entry<String, String> e : KEYWORD.entrySet()) {
			res.add(new ArrayList<String>() {{ add(e.getKey() + e.getValue());}});
		}

		return res;
	}
	
	/**
	 * List of [start, end] pairs for DATE, FLOAT and GPS types
	 * @return
	 */
	
	public List<List<String>> getCols() {
		List<List<String>> res = new ArrayList<List<String>>();

		for (final Entry<String, List<Long>> e : DATE.entrySet()) {
			List<String> l = new ArrayList<String>();
			l.add(e.getValue().get(0).toString());
			l.add(e.getValue().get(1).toString());
			res.add(l);
		}

		for (final Entry<String, List<Float>> e : FLOAT.entrySet()) {
			List<String> l = new ArrayList<String>();
			l.add(e.getValue().get(0).toString());
			l.add(e.getValue().get(1).toString());
			res.add(l);
		}

		return res;
	}
	
}


abstract class Message<K, E, D, F>{
	int code;
	String application;
	
	Map<String, K> KEYWORD;
	Map<String, E> ENUM;
	Map<String, D> DATE;
	Map<String, F> FLOAT;
	
	public void init() {
		if (DATE == null)
			DATE = new HashMap<String, D>();
		if (KEYWORD == null)
			KEYWORD = new HashMap<String, K>();
		if (FLOAT == null)
			FLOAT = new HashMap<String, F>();
		if (ENUM == null)
			ENUM = new HashMap<String, E>();
	}
	
	public String toString(){
		
		return   code
				+"\n"
				+application
				+"\n"
				+"DATE:"+DATE.toString()
				+"\n"
				+"FLOAT:"+FLOAT.toString()
				+"\n"
				+"KEYWORD:"+KEYWORD.toString()
				+"\n"
				+"ENUM:"+ENUM.toString();
		
	}
	
}
