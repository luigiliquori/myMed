package com.mymed.controller.core.manager.reputation;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.model.data.reputation.MInteractionBean;

public interface IInteractionManager {

	/**
	 * 
	 * @param interaction
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public void create(MInteractionBean interaction) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * 
	 * @param interactionID
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public MInteractionBean read(String interactionID) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * 
	 * @param interaction
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public void update(MInteractionBean interaction) throws InternalBackEndException, IOBackEndException ;
	
	/**
	 * 
	 * @param interactionID
	 * @throws InternalBackEndException
	 * @throws ServiceManagerException
	 */
	public void delete(String interactionID)  throws InternalBackEndException, ServiceManagerException;
}
