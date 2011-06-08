package com.mymed.controller.core.manager.session;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.MSessionBean;

public interface ISessionManager {

	// login
	public void create(String mymedID, String ip) throws InternalBackEndException;
	
	public MSessionBean read(String mymedID) throws InternalBackEndException;
	
	public MSessionBean update(MSessionBean sesion) throws InternalBackEndException;
	
	//logout
	public void delete(String mymedID) throws InternalBackEndException;
}
