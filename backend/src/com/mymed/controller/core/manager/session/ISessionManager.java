package com.mymed.controller.core.manager.session;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.model.data.MSessionBean;

public interface ISessionManager {

	/**
	 * login
	 * @param mymedID
	 * @param ip
	 * @throws InternalBackEndException
	 */
	public void create(String mymedID, String ip) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * 
	 * @param mymedID
	 * @return
	 * @throws InternalBackEndException
	 */
	public MSessionBean read(String mymedID) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * 
	 * @param sesion
	 * @return
	 * @throws InternalBackEndException
	 */
	public void update(MSessionBean session) throws InternalBackEndException, IOBackEndException ;
	
	/**
	 * logout
	 * @param mymedID
	 * @throws InternalBackEndException
	 */
	public void delete(String mymedID)  throws InternalBackEndException, ServiceManagerException;
}
