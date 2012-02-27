package com.mymed.tests.stress;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;

/**
 * 
 * @author iacopo
 *
 */
public class GeoLocationStressTest {
    private static int numInsertions;
    private static int numSearches;
    private static int centerLat;
    private static int centerLon;
    private static int radius;


    public static void main(final String[] args) throws GeoLocationOutOfBoundException {
            GeoLocationThread geoLocationThread;
            boolean argsOk = false;

            try{
                    if (args.length==5){
                            numInsertions = Math.abs(Integer.parseInt(args[0]));
                            numSearches = Math.abs(Integer.parseInt(args[1]));
                            centerLat = Math.abs(Integer.parseInt(args[2]));
                            centerLon = Math.abs(Integer.parseInt(args[3]));
                            radius = Math.abs(Integer.parseInt(args[4]));
                            argsOk=true;
                    }
            }catch(NumberFormatException e){
                    e.printStackTrace();
            }

            if (argsOk) {
                    geoLocationThread = new GeoLocationThread(numInsertions,
                                    numSearches, centerLat,
                                    centerLon, radius);
            } else {
                    geoLocationThread = new GeoLocationThread();
            }
            geoLocationThread.start();
    }
}