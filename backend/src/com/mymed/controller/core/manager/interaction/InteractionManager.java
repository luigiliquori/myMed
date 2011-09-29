package com.mymed.controller.core.manager.interaction;

import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.reputation.reputation_manager.ReputationManager;
import com.mymed.controller.core.manager.reputation.reputation_manager.VerdictManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.reputation.MInteractionBean;

/**
 * Manage the reputation of a user
 * 
 * @author lvanni
 * @author Milo Casagrande
 * 
 */
public class InteractionManager extends AbstractManager implements
		IInteractionManager {

	private VerdictManager verdictManager;

	public InteractionManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public InteractionManager(final StorageManager storageManager)
			throws InternalBackEndException {
		super(storageManager);
		this.verdictManager = new VerdictManager(storageManager.getWrapper());
	}

	@Override
	public void create(final MInteractionBean interaction)
			throws InternalBackEndException, IOBackEndException {
//		try { // Verify the interaction does not exist
//			storageManager.selectColumn(CF_INTERACTION, interaction.getId(),
//					"id");
//		} catch (IOBackEndException e) {
			// INTERACTION CREATION
			storageManager.insertSlice(CF_INTERACTION, interaction.getId(),
					interaction.getAttributeToMap());
			if (interaction.getFeedback() != -1) {
				// REPUTATION UPDATE
				if (!verdictManager.updateReputation(interaction
						.getApplication(), interaction.getConsumer(), false,
						interaction.getProducer(), interaction.getFeedback())) {
					throw new InternalBackEndException("Reputation update: doesn't work!");
				}
			}
			return;
//		}
//		throw new IOBackEndException("Interaction already exist!");
	}

	@Override
	public MInteractionBean read(final String interactionID)
			throws InternalBackEndException, IOBackEndException {
		final MInteractionBean interaction = new MInteractionBean();
		final Map<byte[], byte[]> args = storageManager.selectAll(
				CF_INTERACTION, interactionID);

		return (MInteractionBean) introspection(interaction, args);
	}

	@Override
	public void update(final MInteractionBean interaction)
			throws InternalBackEndException, IOBackEndException {
		create(interaction);
	}

	@Override
	public void delete(final String interactionID)
			throws InternalBackEndException {
		storageManager.removeAll(CF_INTERACTION, interactionID);
	}
}
