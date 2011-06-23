package com.mymed.controller.core.manager.reputation;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.MInteractionBean;
import com.mymed.model.data.MReputationBean;

public interface IReputationManager {

	/**
	 * Get the reputation of a publisher related to a given service
	 * @param publisherID
	 * @param subscriberID
	 * @param serviceID
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public MReputationBean read(String publisherID, String subscriberID, String serviceID) throws InternalBackEndException, IOBackEndException;
	
	/**
	 * Update the reputation of a publisher related to a given service
	 * @param session
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public void update(MInteractionBean interaction, double feedback) throws InternalBackEndException, IOBackEndException ;
}