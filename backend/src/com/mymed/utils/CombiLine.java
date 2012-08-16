package com.mymed.utils;

import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;

/**
 * Class that repsent one line of combinatory with potential list of values
 * (instead of single value) Example: predicates: A="a", B="b|b2", "C="c"
 * Gives the following combi lines: A=a A=a, B=[b, b2] B=[b, b2] C=c A=a,
 * C=c C=c Which gives, after expansion : "Aa", "AaBb", "AaBb2", "BbCc",
 * "Bb2Cc", "AaCc", "Cc"
 * 
 * @author raphael
 */

public class CombiLine {

	private LinkedHashMap<String, List<String>> map = new LinkedHashMap<String, List<String>>();
	
	final List<String> result = new ArrayList<String>();
	
	private int lengthMax;

	public CombiLine(LinkedHashMap<String, List<String>> map) {
		super();
		this.map = map;
		this.lengthMax = map.size();
	}
	
	public CombiLine(LinkedHashMap<String, List<String>> map, String lengthMaxStr) {
		super();
		this.map = map;
		this.lengthMax = MatchMakingv2.parseInt(lengthMaxStr);
	}

	// Add a value fot this key.
	/*
	 * public void put(String key, String val) { if
	 * (this.map.containsKey(key)) { this.map.get(key).add(val); } else {
	 * ArrayList<String> list = new ArrayList<String>(); list.add(val);
	 * this.map.put(key, list); } }
	 */
	
	// Expand a list of all predicates
	public List<String> expand() {

		// Show state of combi before expanding
		// LOGGER.info("CombiLine: {}", this.map.toString());

		// List of keys
		final String keys[] = this.map.keySet().toArray(new String[] {});

		// Dummy anynomous class for inner method
		new Object() {

			// Recursive method to generate predicates
			public void predicatesRec(String prefix, int keyIdx, int count) {

				// Reached end of combi line
				if (keyIdx == keys.length || count -1 == CombiLine.this.lengthMax) {
					result.add(prefix);
					return;
				}

				// Get key/vals
				String key = keys[keyIdx];
				List<String> values = CombiLine.this.map.get(key);

				// Loop on values
				for (int i = 0; i < values.size(); i++) {
					// Recursive call. Add "keyval"
					
					if ("".equals(values.get(i)))
						this.predicatesRec( prefix, keyIdx + 1, count);
					else
						this.predicatesRec( prefix + key + values.get(i), keyIdx + 1, ++count);
					
				}

			}

			// Init the recursion
		}.predicatesRec("", 0, 0);

		return result;

	} // End of #expand()

}
