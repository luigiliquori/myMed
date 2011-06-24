package com.mymed.controller.core.manager.reputation;

import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;
import com.mymed.model.data.MInteractionBean;

public class InteractionManager extends AbstractManager implements
		IInteractionManager {

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public InteractionManager() throws InternalBackEndException {
		this(new StorageManager(WrapperType.CASSANDRA));
	}
	
	public InteractionManager(StorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	@Override
	public void create(MInteractionBean interaction)
			throws InternalBackEndException, IOBackEndException {
		try {
			storageManager.insertSlice("Interaction", interaction
					.getInteractionID(), interaction.getAttributeToMap());
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(
					"create failed because of a WrapperException: "
							+ e.getMessage());
		}
	}

	@Override
	public MInteractionBean read(String interactionID)
			throws InternalBackEndException, IOBackEndException {
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		MInteractionBean interaction = new MInteractionBean();
		try {
			args = storageManager.selectAll("Interaction", interactionID);
		} catch (ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"read failed because of a WrapperException: "
							+ e.getMessage());
		}

		return (MInteractionBean) introspection(interaction, args);
	}

	@Override
	public void update(MInteractionBean interaction)
			throws InternalBackEndException, IOBackEndException {
		create(interaction);
	}

	@Override
	public void delete(String interactionID) throws InternalBackEndException,
			ServiceManagerException {
		try {
			storageManager.removeAll("Interaction", interactionID);
		} catch (ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"delete failed because of a WrapperException: "
							+ e.getMessage());
		}
	}

}
