package com.mymed.controller.core.manager.authentication;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
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
public class AuthenticationManager extends AbstractManager implements
		IAuthenticationManager {

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
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

		try {
			read(authentication.getLogin(), authentication.getPassword());
		} catch (IOBackEndException e) { 
			if(e.getStatus() == 404) { // only if the user does not exist
				storageManager.insertSlice(CF_AUTHENTICATION, "login",
						authentication.getAttributeToMap());
				try {
					final Map<String, byte[]> authMap = authentication
							.getAttributeToMap();
					storageManager.insertSlice(CF_AUTHENTICATION,
							new String(authMap.get("login"), "UTF8"), authMap);
				} catch (final UnsupportedEncodingException ex) {
					throw new InternalBackEndException(ex.getMessage());
				}
				return user;
			}
		}
		throw new IOBackEndException("The login already exist!", 409);
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

		if (authentication.getLogin().equals("")) {
			throw new IOBackEndException("the login does not exist!", 404);
		} else if (!authentication.getPassword().equals(password)) {
			throw new IOBackEndException("Wrong password", 403);
		}
		return new ProfileManager().read(authentication.getUser());
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
