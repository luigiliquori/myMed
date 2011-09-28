package com.mymed.controller.core.manager.dht;

import java.io.UnsupportedEncodingException;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.utils.MLogger;

/**
 * 
 * @author lvanni
 * 
 */
public class DHTManager extends AbstractManager implements IDHTManager {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public DHTManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public DHTManager(final StorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	/* --------------------------------------------------------- */
	/* Put_Get */
	/* --------------------------------------------------------- */
	/**
	 * 
	 * @param key
	 * @param value
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	@Override
	public void put(final String key, final String value) throws IOBackEndException, InternalBackEndException {
		try {
			MLogger.getLog().info("Put value = {}", value);
			storageManager.put(key, value.getBytes("UTF8"));
		} catch (final UnsupportedEncodingException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}

	/**
	 * 
	 * @param key
	 * @return
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	@Override
	public String get(final String key) throws IOBackEndException, InternalBackEndException {
		try {
			final String value = new String(storageManager.get(key), "UTF8");
			MLogger.getLog().info("Get value = {}", value);
			return value;
		} catch (final UnsupportedEncodingException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}
}
