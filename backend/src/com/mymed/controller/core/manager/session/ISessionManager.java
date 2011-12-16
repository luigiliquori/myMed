package com.mymed.controller.core.manager.session;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.session.MSessionBean;

public interface ISessionManager {

	/**
	 * login
	 * 
	 * @param userID
	 * @param ip
	 * @throws InternalBackEndException
	 */
	@Deprecated
	void create(String userID, String ip) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * Login
	 * @param sessionBean
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public void create(final MSessionBean sessionBean) throws InternalBackEndException, IOBackEndException;

	/**
	 * 
	 * @param userID
	 * @return
	 * @throws InternalBackEndException
	 */
	MSessionBean read(String userID) throws InternalBackEndException, IOBackEndException;

	/**
	 * 
	 * @param sesion
	 * @return
	 * @throws InternalBackEndException
	 */
	void update(MSessionBean session) throws InternalBackEndException, IOBackEndException;

	/**
	 * logout
	 * 
	 * @param userID
	 * @throws InternalBackEndException
	 */
	void delete(String userID) throws InternalBackEndException, IOBackEndException;
}
