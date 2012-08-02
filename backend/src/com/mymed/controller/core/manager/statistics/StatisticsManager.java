package com.mymed.controller.core.manager.statistics;

import static com.mymed.utils.MiscUtils.encode;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;

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
		
		List<String> columns = new ArrayList<String>();
		columns.add(ALL_ARG);
		columns.add("" + year);
		columns.add("" + year + ++month);
		columns.add("" + year + month + day);
		
		List<String> superColumns = new ArrayList<String>();
		superColumns.add(ALL_ARG);
		superColumns.add(application);
		
		for (String column : columns) {
			for (String superColumn : superColumns) {
				Map<String, String> statistics = storageManager.selectSuperColumn(SC_STATICTICS, column, superColumn);
				int count = 0;
				if (!statistics.isEmpty()) {
					count = Integer.parseInt(statistics.get(method));
				}
				count++;
				storageManager.insertSuperColumn(SC_STATICTICS, column, superColumn, method, encode(count));
			}
		}
	}
}
