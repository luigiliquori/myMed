package com.mymed.controller.core.manager.statistics;

import static com.mymed.utils.MiscUtils.encode;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.utils.MConverter;

public class StatisticsManager extends AbstractManager {

	protected static final String ALL_ARG = "all";
	public static final String PUBLISH_ARG = "pub";
	
	/**
	 * The application controller super column.
	 */
	protected static final String SC_STATICTICS = COLUMNS.get("column.sc.statistics");

	/**
	 * Default constructor.
	 * @throws InternalBackEndException 
	 */
	public StatisticsManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public StatisticsManager(IStorageManager storageManager) {
		super(storageManager);
	}

	/**
	 * update the Statistics CF
	 * @param application, 
	 * 				ID of the application
	 * @param method, 
	 * 				pub/sub/find_ok/fin_ko/delete
	 */
	public void update(String application, String method) {

		Calendar now 	= Calendar.getInstance();
		String year 	= (now.get(Calendar.YEAR)) + "";
		String month 	= (now.get(Calendar.MONTH) + 1) + "";
		String day 		= (now.get(Calendar.DAY_OF_MONTH)) + "";

		List<String> keys = new ArrayList<String>();
		keys.add(application 	+ method);
		keys.add(ALL_ARG		+ method);
		keys.add(application 	+ ALL_ARG);
		keys.add(ALL_ARG 		+ ALL_ARG);

		for (String key : keys) {
			int countYear = 0;
			int countMonth = 0;
			int countDay = 0;

			// read
			final List<Map<String, String>> statisticsStringList = new ArrayList<Map<String, String>>();
			final List<Map<byte[], byte[]>> statisticsByteList = storageManager.selectList(SC_STATICTICS, key);
			for (final Map<byte[], byte[]> set : statisticsByteList) {
				final Map<String, String> resMap = new HashMap<String, String>();
				for (final Entry<byte[], byte[]> entry : set.entrySet()) {
					resMap.put(MConverter.byteArrayToString(entry.getKey()),
							MConverter.byteArrayToString(entry.getValue()));
				}
				statisticsStringList.add(resMap);
			}
			for(Map<String, String> statistics : statisticsStringList) {
				
				if(statistics.containsKey(year)) {
					countYear = Integer.parseInt(statistics.get(year));
				} else if (statistics.containsKey(month)) {
					countMonth = Integer.parseInt(statistics.get(month));
				} else if (statistics.containsKey(day)) {
					countDay = Integer.parseInt(statistics.get(day));
				}
			}
			
			// update
			final Map<String, byte[]> argsAll = new HashMap<String, byte[]>();
			final Map<String, byte[]> argsYears = new HashMap<String, byte[]>();
			final Map<String, byte[]> argsMonth = new HashMap<String, byte[]>();
			
			argsAll.put(year, encode(++countYear));
			argsYears.put(month, encode(++countMonth));
			argsMonth.put(day, encode(++countDay));
			
			System.out.println("\n countYear = " + countYear);
			System.out.println("\n countMonth = " + countMonth);
			System.out.println("\n countDay = " + countDay);
			
			storageManager.insertSuperSlice(SC_STATICTICS, key, ALL_ARG, argsAll);
			storageManager.insertSuperSlice(SC_STATICTICS, key, year, argsYears);
			storageManager.insertSuperSlice(SC_STATICTICS, key, month, argsMonth);
		}
	}
	
	public Map<String, Integer> read(String application, String year, String month, String day) {
		Map<String, Integer> res = new HashMap<String, Integer>();
		
		return res;
	}
}
