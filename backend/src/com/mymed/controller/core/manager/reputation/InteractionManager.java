package com.mymed.controller.core.manager.reputation;

import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.reputation.MInteractionBean;

public class InteractionManager extends AbstractManager implements IInteractionManager {

	public InteractionManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public InteractionManager(final StorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	@Override
	public void create(final MInteractionBean interaction) throws InternalBackEndException, IOBackEndException {
		try {
			storageManager.insertSlice(CF_INTERACTION, interaction.getId(), interaction.getAttributeToMap());
		} catch (final ServiceManagerException e) {
			throw new InternalBackEndException("create failed because of a WrapperException: " + e.getMessage());
		}
	}

	@Override
	public MInteractionBean read(final String interactionID) throws InternalBackEndException, IOBackEndException {
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		final MInteractionBean interaction = new MInteractionBean();
		try {
			args = storageManager.selectAll(CF_INTERACTION, interactionID);
		} catch (final ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException("read failed because of a WrapperException: " + e.getMessage());
		}

		return (MInteractionBean) introspection(interaction, args);
	}

	@Override
	public void update(final MInteractionBean interaction) throws InternalBackEndException, IOBackEndException {
		create(interaction);
	}

	@Override
	public void delete(final String interactionID) throws InternalBackEndException, ServiceManagerException {
		storageManager.removeAll(CF_INTERACTION, interactionID);
	}
}
