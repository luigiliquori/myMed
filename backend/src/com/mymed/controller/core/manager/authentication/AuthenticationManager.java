package com.mymed.controller.core.manager.authentication;

import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;
import com.mymed.model.data.MAuthenticationBean;
import com.mymed.model.data.MUserBean;

public class AuthenticationManager extends AbstractManager implements
		IAuthenticationManager {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public AuthenticationManager() throws InternalBackEndException {
		this(new StorageManager(WrapperType.CASSANDRA));
	}
	
	public AuthenticationManager(StorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	/* --------------------------------------------------------- */
	/* implements AuthenticationManager */
	/* --------------------------------------------------------- */
	/**
	 * @throws IOBackEndException 
	 * @see IAuthenticationManager#create(MUserBean, MAuthenticationBean)
	 */
	@Override
	public MUserBean create(MUserBean user, MAuthenticationBean authentication)
			throws InternalBackEndException, IOBackEndException {
		// create the user profile
		ProfileManager profileManager = new ProfileManager();
		profileManager.create(user);
		// add the authentication informations
		try {
			storageManager.insertSlice("Authentication", "login",
					authentication.getAttributeToMap());
			return user;
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(
					"create failed because of a WrapperException: "
							+ e.getMessage());
		}
	}

	/**
	 * @see IAuthenticationManager#read(String, String)
	 */
	@Override
	public MUserBean read(String login, String password)
			throws InternalBackEndException, IOBackEndException {
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		MAuthenticationBean authentication = new MAuthenticationBean();
		try {
			args = storageManager.selectAll("Authentication", login);
		} catch (ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"read failed because of a WrapperException: "
							+ e.getMessage());
		}
		authentication = (MAuthenticationBean) introspection(authentication,
				args);
		if(authentication.getPassword().equals(password)){
		return new ProfileManager().read(authentication.getMymedID());
		} else {
			throw new IOBackEndException("Wrong password");
		}
	}

	/**
	 * @see IAuthenticationManager#update(MAuthenticationBean)
	 */
	@Override
	public void update(MAuthenticationBean authentication)
			throws InternalBackEndException {
		// TODO Implement the update method witch use the wrapper updateColumn
		// method
	}

}
