package com.mymed.controller.core.manager.authentication;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;

/**
 * The manager for the authentication bean
 * 
 * @author lvanni
 * @author Milo Casagrande
 * 
 */
public class AuthenticationManager extends AbstractManager implements IAuthenticationManager {

	public AuthenticationManager() throws InternalBackEndException {
		this(new StorageManager());
	}

	public AuthenticationManager(final StorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	/**
	 * @throws IOBackEndException
	 * @see IAuthenticationManager#create(MUserBean, MAuthenticationBean)
	 */
	@Override
	public MUserBean create(final MUserBean user, final MAuthenticationBean authentication)
	        throws InternalBackEndException, IOBackEndException {

		final ProfileManager profileManager = new ProfileManager();
		profileManager.create(user);

		try {
			final Map<String, byte[]> authMap = authentication.getAttributeToMap();
			storageManager.insertSlice(CF_AUTHENTICATION, new String(authMap.get("login"), "UTF8"), authMap);
		} catch (final ServiceManagerException ex) {
			throw new InternalBackEndException("create failed because of a WrapperException: " + ex.getMessage());
		} catch (final UnsupportedEncodingException ex) {
			throw new InternalBackEndException(ex.getMessage());
		}

		return user;
	}

	/**
	 * @see IAuthenticationManager#read(String, String)
	 */
	@Override
	public MUserBean read(final String login, final String password) throws InternalBackEndException,
	        IOBackEndException {

		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		MAuthenticationBean authentication = new MAuthenticationBean();

		try {
			args = storageManager.selectAll(CF_AUTHENTICATION, login);
		} catch (final ServiceManagerException e) {
			// TODO use logger
			e.printStackTrace();
			throw new InternalBackEndException("read failed because of a WrapperException: " + e.getMessage());
		}

		authentication = (MAuthenticationBean) introspection(authentication, args);

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
	public void update(final MAuthenticationBean authentication) throws InternalBackEndException {
		// TODO Implement the update method witch use the wrapper updateColumn
		// method
	}
}
