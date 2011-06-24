package com.mymed.controller.core.manager.profile;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.IStorageManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;
import com.mymed.model.data.MUserBean;

/**
 * Manage an user profile
 * 
 * @author lvanni
 * 
 */
public class ProfileManager extends AbstractManager implements IProfileManager {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ProfileManager() throws InternalBackEndException {
		this(new StorageManager(WrapperType.CASSANDRA));
	}
	
	public ProfileManager(IStorageManager storageManager) throws InternalBackEndException {
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
	public MUserBean create(MUserBean user) throws InternalBackEndException, IOBackEndException {
		try {
			Map<String, byte[]> args = user.getAttributeToMap();
			storageManager.insertSlice("User", new String(args.get("mymedID"), "UTF8"), args);
			// TODO update the user values!
			return user;
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(
					"create failed because of a WrapperException: "
							+ e.getMessage());
		} catch (UnsupportedEncodingException e) {
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
	public MUserBean read(String id) throws InternalBackEndException,
			IOBackEndException {
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		MUserBean user = new MUserBean();
		try {
			args = storageManager.selectAll("User", id);
		} catch (ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"read failed because of a WrapperException: "
							+ e.getMessage());
		}

		return (MUserBean) introspection(user, args);
	}

	/**
	 * @throws IOBackEndException 
	 * @see IProfileManager#update(MUserBean)
	 */
	public void update(MUserBean user) throws InternalBackEndException, IOBackEndException {
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
