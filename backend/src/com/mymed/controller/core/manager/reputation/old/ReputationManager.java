package com.mymed.controller.core.manager.reputation.old;

import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.reputation.MReputationBean;

/**
 * Manage the reputation of a user
 * 
 * @author lvanni
 * @author Milo Casagrande
 * 
 */
public class ReputationManager extends AbstractManager implements IReputationManager {

	public ReputationManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public ReputationManager(final StorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	@Override
	public void create(MReputationBean reputation, String id)
			throws InternalBackEndException, IOBackEndException {
		final Map<String, byte[]> args = reputation.getAttributeToMap();
		storageManager.insertSlice(CF_REPUTATION, id, args);
	}

	@Override
	public MReputationBean read(final String producerID, final String consumerID, final String applicationID)
			throws InternalBackEndException, IOBackEndException {
		MReputationBean reputationBean = new MReputationBean();
		Map<byte[], byte[]> args;
		args = storageManager.selectAll(CF_REPUTATION, applicationID + producerID);
		reputationBean = (MReputationBean) introspection(reputationBean, args);
		if(reputationBean.getRaterList() == null) {	// The reputation of the user is not yet initialized
			reputationBean.setValue(0.5);									// init reputation value
			reputationBean.setNbInteraction(1);								// init nbInteraction
			reputationBean.setRaterList(applicationID+producerID);			// init rater list
			reputationBean.setInteractionList(applicationID+producerID);	// init interaction list
			create(reputationBean, applicationID+producerID); 				// create the reputation
		}
		return reputationBean;
	}

	@Override
	public void update(MReputationBean reputation, String id) throws InternalBackEndException,
	IOBackEndException {
		create(reputation, id);
	}
}
