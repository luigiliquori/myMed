package com.mymed.controller.core.manager.reputation;

import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.reputation.MInteractionBean;

/**
 * Manage the reputation of a user
 * 
 * @author lvanni
 * @author Milo Casagrande
 * 
 */
public class InteractionManager extends AbstractManager implements IInteractionManager {

	public InteractionManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public InteractionManager(final StorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	@Override
	public void create(final MInteractionBean interaction) throws InternalBackEndException, IOBackEndException {
		storageManager.insertSlice(CF_INTERACTION, interaction.getId(), interaction.getAttributeToMap());
	}

	@Override
	public MInteractionBean read(final String interactionID) throws InternalBackEndException, IOBackEndException {
		final MInteractionBean interaction = new MInteractionBean();
		final Map<byte[], byte[]> args = storageManager.selectAll(CF_INTERACTION, interactionID);

		return (MInteractionBean) introspection(interaction, args);
	}

	@Override
	public void update(final MInteractionBean interaction) throws InternalBackEndException, IOBackEndException {
		create(interaction);
	}

	@Override
	public void delete(final String interactionID) throws InternalBackEndException {
		storageManager.removeAll(CF_INTERACTION, interactionID);
	}
}
