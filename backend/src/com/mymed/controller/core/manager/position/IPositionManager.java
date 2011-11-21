package com.mymed.controller.core.manager.position;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.user.MPositionBean;

public interface IPositionManager {

	/**
	 * 
	 * @param user
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public MPositionBean create(final MPositionBean user) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * 
	 * @param userID
	 * @return
	 * @throws InternalBackEndException
	 */
	MPositionBean read(String userID) throws InternalBackEndException, IOBackEndException;

	/**
	 * 
	 * @param position
	 * @return
	 * @throws InternalBackEndException
	 */
	public void update(final MPositionBean position) throws InternalBackEndException, IOBackEndException;

}
