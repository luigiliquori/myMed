package com.mymed.controller.core.manager.reputation.old;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.reputation.MReputationBean;

public interface IReputationManager {

	/**
	 *
	 * @param reputation
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	void create(MReputationBean reputation, String id)
			throws InternalBackEndException, IOBackEndException;

	/**
	 * Get the reputation of a publisher related to a given service
	 *
	 * @param producerID
	 * @param consumerID
	 * @param applicationID
	 * @return
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	MReputationBean read(String producerID, String consumerID,
			String applicationID) throws InternalBackEndException,
			IOBackEndException;

	/**
	 * Update the reputation of a publisher related to a given service
	 *
	 * @param session
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public void update(MReputationBean reputation, String id)
			throws InternalBackEndException, IOBackEndException;
}
