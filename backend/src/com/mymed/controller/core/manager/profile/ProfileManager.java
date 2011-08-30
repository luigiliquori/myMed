package com.mymed.controller.core.manager.profile;

import java.io.UnsupportedEncodingException;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage an user profile
 * 
 * @author lvanni
 * 
 */
public class ProfileManager extends AbstractManager implements IProfileManager {

	public ProfileManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public ProfileManager(final IStorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	/**
	 * Setup a new user profile into the database
	 * 
	 * @param user
	 *            the user to insert into the database
	 * @throws IOBackEndException
	 */
	@Override
	public MUserBean create(final MUserBean user) throws InternalBackEndException, IOBackEndException {
		try {
			final Map<String, byte[]> args = user.getAttributeToMap();
			storageManager.insertSlice(CF_USER, new String(args.get("id"), "UTF8"), args);
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
	public MUserBean read(final String id) throws InternalBackEndException, IOBackEndException {
		final MUserBean user = new MUserBean();
		final Map<byte[], byte[]> args = storageManager.selectAll(CF_USER, id);

		return (MUserBean) introspection(user, args);
	}

	/**
	 * @throws IOBackEndException
	 * @see IProfileManager#update(MUserBean)
	 */
	@Override
	public void update(final MUserBean user) throws InternalBackEndException, IOBackEndException {
		create(user);
	}

	/**
	 * @see IProfileManager#delete(MUserBean)
	 */
	@Override
	public void delete(final String id) throws InternalBackEndException {
		storageManager.removeAll(CF_USER, id);
	}
}
