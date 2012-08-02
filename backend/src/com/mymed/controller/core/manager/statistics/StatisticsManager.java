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

		Calendar now = Calendar.getInstance();
		int year = now.get(Calendar.YEAR);
		int month = now.get(Calendar.MONTH);
		int day = now.get(Calendar.DAY_OF_MONTH);

		List<String> keys = new ArrayList<String>();
		keys.add(ALL_ARG);
		keys.add("" + year);
		keys.add("" + year + ++month);
		keys.add("" + year + month + day);

		for (String key : keys) {
			int count = 0;

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
				if(statistics.containsValue(application)) {
					count = Integer.parseInt(statistics.get(method));
				}
			}

			count++;
			System.out.println("*********** COUNT : " + count);

			// update
			final Map<String, byte[]> args = new HashMap<String, byte[]>();
			args.put("application", encode(application));
			args.put(method, encode(count));
			storageManager.insertSuperSlice(SC_STATICTICS, key, application, args);
			args.put("application", encode(ALL_ARG));
			storageManager.insertSuperSlice(SC_STATICTICS, key, ALL_ARG, args);

		}
	}
}
