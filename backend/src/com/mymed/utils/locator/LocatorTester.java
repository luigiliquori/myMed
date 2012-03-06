package com.mymed.utils.locator;

import java.util.Random;

import ch.qos.logback.classic.Logger;

import com.mymed.controller.core.exception.GeoLocationOutOfBoundException;
import com.mymed.utils.MLogger;

/**
 * 
 * @author iacopo
 *
 */
public class LocatorTester {
        private static String TAG = "LocatorTester";
        private static final Logger LOGGER = MLogger.getLogger();
        private static long SEED = 19580427;
        private static int NUM_SAMPLES = 100000;
        private static int RADIUS = 50000;
        private final int maxLat = (int) (Math.toDegrees(Location.MAX_LAT)*1E6);
        private final int minLat = (int) (Math.toDegrees(Location.MIN_LAT)*1E6);
        private final int minLon = (int) (Math.toDegrees(Location.MAX_LON)*1E6);
        private final int maxLon = (int) (Math.toDegrees(Location.MIN_LON)*1E6);

        private Random rand;
        private long cumulantTime;
        private int numTotal;
        private long minTime;
        private long maxTime;

        public LocatorTester(){
                rand = new Random(SEED);
                cumulantTime=0;
                numTotal=0;
                maxTime=0;
                minTime=Long.MAX_VALUE;
        }


        public static void main(String[] args){
                if (args.length==2){
                        try{
                                NUM_SAMPLES = Math.abs(Integer.parseInt(args[0]));
                                RADIUS = Math.abs(Integer.parseInt(args[0]));
                        }catch(NumberFormatException e){
                            LOGGER.debug(TAG+"error: {}", e.toString());
                    }
            }
            LocatorTester locTester = new LocatorTester();
            Location loc;

            try {
                    for(int i=0;i<NUM_SAMPLES;i++){
                            loc = locTester.getRandomLocation();
                            long startnow = System.currentTimeMillis();
                            Locator.getCoveringLocationId(loc.getLatitude(), loc.getLongitude(), RADIUS);
                            long endnow = System.currentTimeMillis();
                            locTester.addSample(endnow-startnow);
                            System.out.println("Execution time:"+ String.valueOf(endnow-startnow));
                    }
                    LOGGER.info(TAG+" Average execution time: {}\n" +
                                    "       Number of samples: {}\n" +
                                    "       Radius [m]: {}",
                                    new Object[]{locTester.getAvgTime(),String.valueOf(NUM_SAMPLES),String.valueOf(RADIUS)});
            } catch (GeoLocationOutOfBoundException e) {
                    LOGGER.debug(TAG+"error: {}", e.getMessage());
            }
    }

    public Location getRandomLocation() throws GeoLocationOutOfBoundException{
            int latitude =(int) (minLat + (maxLat-minLat)*rand.nextDouble());
            int longitude =(int) (minLon + (maxLon-minLon)*rand.nextDouble());
            return new Location(latitude,longitude);
    }


    public void addSample(long duration){
            this.cumulantTime+=duration;
            this.numTotal++;
            if (duration>this.maxTime) this.maxTime = duration;
            if (duration<this.minTime) this.minTime = duration;
    }

    public int getAvgTime(){
            return numTotal==0?0:(int) (cumulantTime*1.0/numTotal);
    }
}