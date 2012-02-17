package com.mymed.tests.stress;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;

/**
 * 
 * @author iacopo
 *
 */
public class GeoLocationStressTest {

	public static void main(final String[] args) throws GeoLocationOutOfBoundException {
		GeoLocationThread geoLocationThread;

		if (args.length == 0) {
			geoLocationThread = new GeoLocationThread();
		} else {
			geoLocationThread = new GeoLocationThread(Math.abs(Integer.parseInt(args[0])), 
					Math.abs(Integer.parseInt(args[1])), Math.abs(Integer.parseInt(args[2])), 
					Math.abs(Integer.parseInt(args[3])), Math.abs(Integer.parseInt(args[4])));
		}
		geoLocationThread.start();
	}
}
