package com.mymed.utils;

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
	
	
	public static double parseDouble(String s) {
		try {
			return Double.parseDouble(s);
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
	
	
	public static List<String> prefix(String s, List<String> l){
		for (int i=0; i<l.size(); i++){
			l.set(i, s + l.get(i));
		}
		return l;
	}
	
	
}
