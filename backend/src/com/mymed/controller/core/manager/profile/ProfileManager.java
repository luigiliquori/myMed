package com.mymed.controller.core.manager.profile;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;
import com.mymed.model.data.MAuthenticationBean;
import com.mymed.model.data.MUserBean;

/**
 * Manage an user profile
 * 
 * @author lvanni
 * 
 */
public class ProfileManager extends AbstractManager implements IProfileManager {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private StorageManager storageManager;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ProfileManager() throws InternalBackEndException {
		this.storageManager = new StorageManager(WrapperType.CASSANDRA);
	}

	/* --------------------------------------------------------- */
	/* implements IProfileManagement */
	/* --------------------------------------------------------- */
	/**
	 * Setup a new user profile into the database
	 * 
	 * @param user
	 *            the user to insert into the database
	 */
	public MUserBean create(MUserBean user) throws InternalBackEndException {
		try {
			storageManager.insertSlice("Users", "mymedID", user
					.getAttributeToMap());
			// TODO update the user values!
			return user;
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(
					"create failed because of a WrapperException: "
							+ e.getMessage());
		}
	}

	/**
	 * @param id
	 *            the id of the user
	 * @return the User corresponding to the id
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public MUserBean read(String id) throws InternalBackEndException,
			IOBackEndException {
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		MUserBean user = new MUserBean();
		try {
			args = storageManager.selectAll("Users", id);
		} catch (ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"read failed because of a WrapperException: "
							+ e.getMessage());
		}

		return (MUserBean) introspection(user, args);
	}

	/**
	 * @see IProfileManager#update(MUserBean)
	 */
	public void update(MUserBean user) throws InternalBackEndException {
		create(user);
		// TODO Implement the update method witch use the wrapper updateColumn
		// method
	}

	/**
	 * @see IProfileManager#delete(MUserBean)
	 */
	public void delete(String mymedID) throws InternalBackEndException {
		try {
			storageManager.removeAll("User", mymedID);
		} catch (ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"delete failed because of a WrapperException: "
							+ e.getMessage());
		}
	}

}
