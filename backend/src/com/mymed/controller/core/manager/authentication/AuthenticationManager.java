package com.mymed.controller.core.manager.authentication;

import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;

public class AuthenticationManager extends AbstractManager implements
		IAuthenticationManager {

	public AuthenticationManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public AuthenticationManager(final StorageManager storageManager)
			throws InternalBackEndException {
		super(storageManager);
	}

	/**
	 * @throws IOBackEndException
	 * @see IAuthenticationManager#create(MUserBean, MAuthenticationBean)
	 */
	@Override
	public MUserBean create(final MUserBean user,
			final MAuthenticationBean authentication)
			throws InternalBackEndException, IOBackEndException {

		final ProfileManager profileManager = new ProfileManager();
		profileManager.create(user);

		storageManager.insertSlice(CF_AUTHENTICATION, "login",
				authentication.getAttributeToMap());
		return user;
	}

	/**
	 * @see IAuthenticationManager#read(String, String)
	 */
	@Override
	public MUserBean read(final String login, final String password)
			throws InternalBackEndException, IOBackEndException {

		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		MAuthenticationBean authentication = new MAuthenticationBean();

		args = storageManager.selectAll(CF_AUTHENTICATION, login);

		authentication = (MAuthenticationBean) introspection(authentication,
				args);

		System.out.println(authentication);

		if (authentication.getPassword().equals(password)) {
			return new ProfileManager().read(authentication.getUser());
		} else {
			throw new IOBackEndException("Wrong password");
		}
	}

	/**
	 * @see IAuthenticationManager#update(MAuthenticationBean)
	 */
	@Override
	public void update(final MAuthenticationBean authentication)
			throws InternalBackEndException {
		// TODO Implement the update method witch use the wrapper updateColumn
		// method
	}

}
