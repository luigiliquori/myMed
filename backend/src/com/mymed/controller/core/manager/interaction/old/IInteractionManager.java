package com.mymed.controller.core.manager.interaction.old;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.interaction.MInteractionBean;

public interface IInteractionManager {

	/**
	 * 
	 * @param interaction
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	void create(MInteractionBean interaction) throws InternalBackEndException, IOBackEndException;

	/**
	 * 
	 * @param interactionID
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	MInteractionBean read(String interactionID) throws InternalBackEndException, IOBackEndException;

	/**
	 * 
	 * @param interaction
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	void update(MInteractionBean interaction) throws InternalBackEndException, IOBackEndException;

	/**
	 * 
	 * @param interactionID
	 * @throws InternalBackEndException
	 * @throws ServiceManagerException
	 */
	void delete(String interactionID) throws InternalBackEndException;
}
