package com.mymed.utils;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.LinkedHashMap;
import java.util.List;

import com.beoui.geocell.GeocellManager;
import com.beoui.geocell.model.BoundingBox;
import com.beoui.geocell.model.Point;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.application.DataBean;

/**
 * Utils methods for pubsub managers
 * 
 */

public class MatchMakingv2{

	/*
	 * preformat the received request
	 */

	public static LinkedHashMap<String, List<String>> format(
			List<DataBean> indexes) {

		LinkedHashMap<String, List<String>> keywords = new LinkedHashMap<String, List<String>>();
		
		for (final DataBean entry : indexes) {
			
			String[] vals = entry.getValue().split("\\|");
			
			if (vals.length == 0 || vals.length == 1 && vals[0].equals(""))
				continue;
			
			switch (entry.getType()){

			default: case ENUM:
				keywords.put(entry.getKey(), Arrays.asList(vals));
				break;
				
			case GPS:
				if (vals.length == 2 || vals.length == 3) { // create

					double lat = parseDouble(vals[vals.length-2]);
					double lon = parseDouble(vals[vals.length-1]);
					Point p = new Point(lat, lon);

					// Generates the list of GeoCells
					List<String> cells = GeocellManager.generateGeoCell(p);
					
					if (vals.length == 3 && "".equals(vals[0]))
						cells.add(0, vals[0]);
					
					keywords.put(entry.getKey(), cells);
					
				} else if (vals.length == 4 || vals.length == 5) { // search

					double latS = parseDouble(vals[vals.length-4]);
					double lonW = parseDouble(vals[vals.length-3]);
					double latN = parseDouble(vals[vals.length-2]);
					double lonE = parseDouble(vals[vals.length-1]);

					// Transform this to a bounding box
					BoundingBox bb = new BoundingBox(latN, lonE, latS, lonW);

					// Calculate the geocells list to be used in the queries
					List<String> cells = GeocellManager
							.bestBboxSearchCells(bb, null);
					
					if (vals.length == 5 && "".equals(vals[0]))
						cells.add(0, vals[0]);
					
					keywords.put(entry.getKey(), cells);
				}
			}
		}
		return keywords;
	}

//	static public List<List<String>> getPredicate(
//			final LinkedHashMap<String, List<String>> indexes) {
//
//		List<List<String>> result = new ArrayList<List<String>>();
//
//		CombiLine combiLine = new CombiLine(indexes);
//	
//		result.addAll(combiLine.expand());
//
//		return result;
//	}

	/**
	 * Class that repsent one line of combinatory with potential list of values
	 * (instead of single value) Example: predicates: A="a", B="b|b2", "C="c"
	 * Gives the following combi lines: A=a A=a, B=[b, b2] B=[b, b2] C=c A=a,
	 * C=c C=c Which gives, after expansion : "Aa", "AaBb", "AaBb2", "BbCc",
	 * "Bb2Cc", "AaCc", "Cc"
	 */
	public static class CombiLine {

		private LinkedHashMap<String, List<String>> map = new LinkedHashMap<String, List<String>>();
		
		final List<String> result = new ArrayList<String>();

		public CombiLine(LinkedHashMap<String, List<String>> map) {
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
		public List<String> expand() {

			// Show state of combi before expanding
			// LOGGER.info("CombiLine: {}", this.map.toString());

			// List of keys
			final String keys[] = this.map.keySet().toArray(new String[] {});

			// Dummy anynomous class for inner method
			new Object() {

				// Recursive method to generate predicates
				public void predicatesRec(String prefix, int keyIdx) {

					// Reached end of combi line
					if (keyIdx == keys.length) {
						result.add(prefix);
						return;
					}

					// Get key/vals
					String key = keys[keyIdx];
					List<String> values = CombiLine.this.map.get(key);

					// Loop on values
					for (int i = 0; i < values.size(); i++) {
						// Recursive call. Add "keyval"
						
						this.predicatesRec( prefix + ("".equals(values.get(i))?"":key+values.get(i)), keyIdx + 1);
					}

				}

				// Init the recursion
			}.predicatesRec("", 0);

			return result;

		} // End of #expand()

	} // End of class CombiLine
	
	
	
	public static double parseDouble(String s) {
		try {
			return Double.parseDouble(s);
		} catch (NumberFormatException e) {
			throw new InternalBackEndException(e);
		}
	}
	
	
	public static List<String> prefix(String s, List<String> l){
		for (int i=0; i<l.size(); i++){
			l.set(i, s + l.get(i));
		}
		return l;
	}
	
	
}
