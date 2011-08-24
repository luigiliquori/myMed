package com.mymed.controller.core.manager.application;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage an user profile
 * 
 * @author lvanni
 * 
 */
public class ApplicationManager extends AbstractManager implements
		IApplicationManager {
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ApplicationManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public ApplicationManager(final IStorageManager storageManager)
			throws InternalBackEndException {
		super(storageManager);
	}

	@Override
	public void create(MUserBean user) throws InternalBackEndException,
			IOBackEndException {
		// TODO Auto-generated method stub
		
	}

	@Override
	public MUserBean read(String id) throws InternalBackEndException,
			IOBackEndException {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public void update(MUserBean user) throws InternalBackEndException,
			IOBackEndException {
		// TODO Auto-generated method stub
		
	}

	@Override
	public void delete(String id) throws InternalBackEndException {
		// TODO Auto-generated method stub
		
	}

	

}
