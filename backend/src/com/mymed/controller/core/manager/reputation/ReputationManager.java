package com.mymed.controller.core.manager.reputation;

import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;
import com.mymed.model.core.wrappers.cassandra.api07.MConverter;
import com.mymed.model.data.MInteractionBean;
import com.mymed.model.data.MReputationBean;

public class ReputationManager extends AbstractManager implements IReputationManager {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ReputationManager() throws InternalBackEndException {
		super(new StorageManager(WrapperType.CASSANDRA));
	}

	/* --------------------------------------------------------- */
	/* implements ReputationManager */
	/* --------------------------------------------------------- */

	// TODO the consumerID is not used, refactor the args to use "user_id"
	@Override
	public MReputationBean read(final String producerID, final String consumerID, final String applicationID)
	        throws InternalBackEndException, IOBackEndException {
		final MReputationBean reputationBean = new MReputationBean();
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		try {
			args = storageManager.selectAll("Reputation", producerID + applicationID);
		} catch (final ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException("read failed because of a WrapperException: " + e.getMessage());
		}
		return (MReputationBean) introspection(reputationBean, args);
	}

	@Override
	public void update(final MInteractionBean interaction, final double feedback) throws InternalBackEndException,
	        IOBackEndException {
		// REMINDER:
		// THE REPUTATION VALUE IS THE LAST GIVEN FEEDBACK

		try {
			storageManager.insertColumn("Reputation", interaction.getProducerID() + interaction.getApplicationID(),
			        "value", MConverter.doubleToByteBuffer(feedback).array());
		} catch (final ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException("read failed because of a WrapperException: " + e.getMessage());
		}
	}
}
