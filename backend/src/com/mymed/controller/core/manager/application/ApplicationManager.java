package com.mymed.controller.core.manager.application;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.profile.IProfileManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage an user profile
 * 
 * @author lvanni
 * 
 */
public class ApplicationManager extends AbstractManager implements
		IProfileManager {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ApplicationManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public ApplicationManager(final IStorageManager storageManager)
			throws InternalBackEndException {
		super(storageManager);
	}

	/* --------------------------------------------------------- */
	/* implements IProfileManagement */
	/* --------------------------------------------------------- */
	/**
	 * Setup a new user profile into the database
	 * 
	 * @param user
	 *            the user to insert into the database
	 * @throws IOBackEndException
	 */
	@Override
	public MUserBean create(final MUserBean user)
			throws InternalBackEndException, IOBackEndException {
		try {
			final Map<String, byte[]> args = user.getAttributeToMap();
			storageManager.insertSlice("User", new String(args.get("id"),
					"UTF8"), args);
			// TODO update the user values!
			return user;
		} catch (final UnsupportedEncodingException e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}

	/**
	 * @param id
	 *            the id of the user
	 * @return the User corresponding to the id
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	@Override
	public MUserBean read(final String id) throws InternalBackEndException,
			IOBackEndException {
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		final MUserBean user = new MUserBean();
		args = storageManager.selectAll("User", id);

		return (MUserBean) introspection(user, args);
	}

	/**
	 * @throws IOBackEndException
	 * @see IProfileManager#update(MUserBean)
	 */
	@Override
	public void update(final MUserBean user) throws InternalBackEndException,
			IOBackEndException {
		create(user);
		// TODO Implement the update method witch use the wrapper updateColumn
		// method
	}

	/**
	 * @see IProfileManager#delete(MUserBean)
	 */
	@Override
	public void delete(final String id) throws InternalBackEndException {
		storageManager.removeAll("User", id);
	}

}
