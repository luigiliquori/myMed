package com.mymed.utils;

import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.application.DataBean;
import com.mymed.model.data.application.IndexBean;

/**
 * Utils methods for pubsub managers
 * 
 */

public class MatchMaking {

	/*
	 * preformat the received request
	 */

	public static LinkedHashMap<String, List<Index>> formatIndexes(
			List<IndexBean> data) {

		LinkedHashMap<String, List<Index>> map = new LinkedHashMap<String, List<Index>>();

		List<Index> li;

		for (final IndexBean d : data) {

			li = new ArrayList<Index>();

			switch (d.getType()) {
			case DATE:
				Long t1 = Long.parseLong(d.getValue().get(0));
				Long t2 = d.getValue().size() > 1 ? Long.parseLong(d.getValue()
						.get(1)) : t1;

				li.addAll(getDateRange(t1, t2));
				break;
			case FLOAT:
				String s1 = d.getValue().get(0);
				String s2 = d.getValue().size() > 1 ? d.getValue().get(1) : s1;
				li.addAll(getFloatRange(s1, s2));
				break;
			case KEYWORD:
				li.add(new Index(d.getValue().get(0), ""));
				break;
			case ENUM:
				for (String s : d.getValue()) {
					li.add(new Index(s, ""));
				}
				break;
			}

			map.put(d.getKey(), li);
		}
		return map;
	}

	static public List<Index> getPredicate(
			final LinkedHashMap<String, List<Index>> indexes,
			final int minLevel, final int maxLevel) {

		int n = indexes.size();
		final String keys[] = indexes.keySet().toArray(new String[] {});

		List<Index> result = new ArrayList<Index>();
		List<String> rows;

		CombiLine combiLine = new CombiLine(indexes);

		// Loop on number of predicates
		for (int k = minLevel; k <= maxLevel; k++) {

			// Loop on all possiblities for this number of predicates
			for (int i = (1 << k) - 1; (i >>> n) == 0; i = nextCombo(i)) {

				// Create one combi line

				// create emtry keyset
				rows = new ArrayList<String>();

				int mask = i;
				int j = 0;

				// Loop on DataBean
				while (mask > 0) {

					// Add it ?
					if ((mask & 1) == 1) {

						// Add one or several values to the combi line fot this
						// key

						rows.add(keys[j]);

					}
					mask >>= 1;
					j++;
				} // End of loop on data beans

				// Expand the current combi line
				result.addAll(combiLine.expand(rows));

			} // End of loop on possibilities for this number of predicates

		} // Loop on number of predicates

		return result;
	}

	/**
	 * Class that repsent one line of combinatory with potential list of values
	 * (instead of single value) Example: predicates: A="a", B="b|b2", "C="c"
	 * Gives the following combi lines: A=a A=a, B=[b, b2] B=[b, b2] C=c A=a,
	 * C=c C=c Which gives, after expansion : "Aa", "AaBb", "AaBb2", "BbCc",
	 * "Bb2Cc", "AaCc", "Cc"
	 */
	static private class CombiLine {

		private LinkedHashMap<String, List<Index>> map = new LinkedHashMap<String, List<Index>>();

		// map is global, expand() use it with a specific keySet

		public CombiLine(LinkedHashMap<String, List<Index>> map) {
			super();
			this.map = map;
		}

		// Add a value fot this key.
		/*
		 * public void put(String key, String val) { if
		 * (this.map.containsKey(key)) { this.map.get(key).add(val); } else {
		 * ArrayList<String> list = new ArrayList<String>(); list.add(val);
		 * this.map.put(key, list); } }
		 */

		// Expand a list of all predicates
		public List<Index> expand(final List<String> rows) {

			// Show state of combi before expanding
			// LOGGER.info("CombiLine: {}", this.map.toString());

			// List of keys, in their order of apparence
			// final String keys[] = this.map.keySet().toArray(new String[] {});

			// Buffer of all possibilities
			final List<Index> result = new ArrayList<Index>();

			// Dummy anynomous class for inner method
			new Object() {

				// Recursive method to generate predicates
				public void predicatesRec(Index prefix, int keyIdx) {

					// Reached end of combi line
					if (keyIdx == rows.size()) {
						result.add(prefix);
						return;
					}

					// Get key/vals
					String key = rows.get(keyIdx);
					List<Index> values = CombiLine.this.map.get(key);

					// Loop on values
					for (int i = 0; i < values.size(); i++) {
						// Recursive call. Add "keyval"
						this.predicatesRec(
								new Index(prefix.row + key + values.get(i).row,
										prefix.col + values.get(i).col),
								keyIdx + 1);
					}

				}

				// Init the recursion
			}.predicatesRec(new Index("", ""), 0);

			return result;

		} // End of #expand()

	} // End of class CombiLine

	
	
	
	// -----generic-utils-----------


	public static long parseLong(String s) {
		try {
			return Long.parseLong(s);
		} catch (NumberFormatException e) {
			throw new InternalBackEndException(e);
		}
	}
	
	public static int parseInt(String s) {
		try {
			return Integer.parseInt(s);
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
	 * DATE type get Range of rows to search between v1 and v2 (universal
	 * timestamps in seconds)
	 */

	public final static long interval = 60 * 60 * 24 * 7; // 1 week in s

	public static List<Index> getDateRange(long t1, long t2) {
		List<Index> res = new ArrayList<Index>();

		if (t1 != 0 && t2 != 0) {
			long curTime = t1 - t1 % interval; // init with first day at
												// 0h:00:00
			String t1str = padDate(t1);
			while (curTime <= t2) {
				res.add(new Index(curTime + "", t1str));
				curTime += interval;
			}
			if (res.size() > 1) {
				res.get(res.size() - 1).col = padDate(t2);
			}
		}

		return res;
	}

	/*
	 * FLOAT type integer part is indexed in rows et Range of rows to search
	 * between v1 and v2 [2.33 - 5.6] -> [2, 3, 4, 5]
	 */
	public static List<Index> getFloatRange(String t1, String t2) {
		List<Index> res = new ArrayList<Index>();
		String[] t1strs = t1.split("\\.");
		String[] t2strs = t2.split("\\.");

		int t1int = parseInt(t1strs[0]);
		int t2int = parseInt(t2strs[0]);

		String t1str = t1strs.length == 1 ? String.format("%04d", t1int)
				: String.format("%04d.%s", t1int, t1strs[1]);
		String t2str = t2strs.length == 1 ? String.format("%04d", t2int)
				: String.format("%04d.%s", t2int, t2strs[1]);

		for (int i = t1int; i <= t2int; i++) {
			res.add(new Index(i + "", t1str));
		}
		if (res.size() > 1) {
			res.get(res.size() - 1).col = t2str;
		}
		return res;
	}

	/**
	 * Pads float numbers to have a 2 characters integer part, for utf-8 sorting
	 */
	public static String padFloat(String... strings) {
		if (2 == strings.length) {
			return String.format("%04d.%s", parseInt(strings[0]),
					strings[1]);
		}
		return String.format("%04d", parseInt(strings[0]));
	}

	/** Pads date timestamps in seconds over 10 digits */
	public static String padDate(Long value) {
		return new DecimalFormat("0000000000").format(value);
	}

	public static List<List<String>> getRanges(List<IndexBean> data) {
		List<List<String>> res = new ArrayList<List<String>>();

		for (final IndexBean d : data) {
			switch (d.getType()) {
			case DATE:
			case FLOAT:
				List<String> l = new ArrayList<String>();
				l.add(d.getValue().get(0).toString());
				l.add( d.getValue().size() > 1
								? d.getValue().get(1).toString()
								: d.getValue().get(0).toString() );
				res.add(l);
				break;
			}
		}
		return res;
	}

	/**
	 * simple class to split which part of an index is in row, and which (if
	 * any) prefixed in column name
	 */
	public static class Index {

		public String row; /*
							 * part of an index appended in row name for
							 * Partitionning
							 */
		public String col; /* part prepended in col name for Sorting */

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
		
		public static List<String> getRows(List<Index> predicate) {
			List<String> l = new ArrayList<String>();
			for (Index i : predicate) {
				l.add(i.row);
			}
			return l;
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

	/** Get application from a prefix "applicationID<separator>namespace" */
	public static String extractApplication(String prefix, String separator) {
		return prefix.split(separator)[0];
	}

	/** Get application from a prefix "applicationID:namespace" */
	public static String extractApplication(String prefix) {
		return extractApplication(prefix, ":");
	}

	/**
	 * Get namespace (or null if none found) from a prefix
	 * "applicationID<separator>namespace"
	 */
	public static String extractNamespace(String prefix, String separator) {
		String[] parts = prefix.split(separator);
		return (parts.length == 2) ? parts[1] : null;
	}

	/**
	 * Get namespace (or null if none found) from a prefix
	 * "applicationID:namespace"
	 */
	public static String extractNamespace(String prefix) {
		return extractNamespace(prefix, ":");
	}

	/**
	 * Make a prefix with an aplpication and optionnal namespace
	 * "application<separator>namespace "
	 */
	public static String makePrefix(String application, String namespace,
			String separator) {
		return (namespace == null) ? application
				: (application + separator + namespace);
	}

	/**
	 * Make a prefix with an aplpication and optionnal namespace
	 * "application<separator>namespace "
	 */
	public static String makePrefix(String application, String namespace) {
		return makePrefix(application, namespace, ":");
	}

	static private int nextCombo(int n) {
		// Gosper's hack, Integer version (level must be < 32)
		// moves to the next combination (of n's bits) with the same number of 1
		// bits
		if (n == 0) 
			return Integer.MAX_VALUE;

		int u = n & (-n);
		int v = u + n;
		return v + (((v ^ n) / u) >> 2);
	}

}
