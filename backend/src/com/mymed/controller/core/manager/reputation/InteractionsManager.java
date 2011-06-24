package com.mymed.controller.core.manager.reputation;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;
import com.mymed.model.data.MInteractionBean;

public class InteractionsManager extends AbstractManager implements IInteractionsManager {

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public InteractionsManager() throws InternalBackEndException {
		super(new StorageManager(WrapperType.CASSANDRA));
	}

	@Override
	public MInteractionBean read(final String interactionsID) throws InternalBackEndException, IOBackEndException {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public void update(final MInteractionBean interaction) throws InternalBackEndException, IOBackEndException {
		// TODO Auto-generated method stub

	}

	@Override
	public void delete(final String interactionID) throws InternalBackEndException, ServiceManagerException {
		// TODO Auto-generated method stub

	}

}
