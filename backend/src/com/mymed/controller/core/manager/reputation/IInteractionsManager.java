package com.mymed.controller.core.manager.reputation;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.model.data.MInteractionBean;

public interface IInteractionsManager {

	/**
	 * read the complete list of interactions
	 * @param interactionID
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public MInteractionBean read(String interactionsID) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * Add the interaction to the list
	 * @param interaction
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public void update(MInteractionBean interaction) throws InternalBackEndException, IOBackEndException ;
	
	/**
	 * Remove the interaction from the list
	 * @param interactionID
	 * @throws InternalBackEndException
	 * @throws ServiceManagerException
	 */
	public void delete(String interactionID)  throws InternalBackEndException, ServiceManagerException;
}
