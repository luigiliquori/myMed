package com.mymed.controller.core.manager.reputation;

import java.nio.ByteBuffer;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;
import com.mymed.model.data.MInteractionBean;
import com.mymed.model.data.MReputationBean;

public class ReputationManager extends AbstractManager implements
		IReputationManager {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ReputationManager() throws InternalBackEndException {
		super(new StorageManager(WrapperType.CASSANDRA));
	}

	/* --------------------------------------------------------- */
	/* implements ReputationManager */
	/* --------------------------------------------------------- */

	@Override
	public MReputationBean read(String publisherID, String subscriberID,
			String serviceID) throws InternalBackEndException,
			IOBackEndException {
		MReputationBean reputationBean = new MReputationBean();
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		try {
			args = storageManager.selectAll("Reputation", publisherID
					+ serviceID);
		} catch (ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"read failed because of a WrapperException: "
							+ e.getMessage());
		}
		return (MReputationBean) introspection(reputationBean, args);
	}

	@Override
	public void update(MInteractionBean interaction, double feedback)
			throws InternalBackEndException, IOBackEndException {
		// REMINDER:
		// THE REPUTATION VALUE IS THE LAST GIVEN FEEDBACK
		final ByteBuffer buffer = ByteBuffer.allocate(Double.SIZE / 8);
		buffer.clear();
		buffer.putDouble(feedback);
		buffer.compact();
		try {
			storageManager.insertColumn("Reputation", interaction
					.getPublisherID()
					+ interaction.getApplicationID(), "value", buffer.array());
		} catch (ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"read failed because of a WrapperException: "
							+ e.getMessage());
		}
	}
}
