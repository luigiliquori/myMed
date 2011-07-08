package com.mymed.controller.core.manager.session;

import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.data.MSessionBean;
import com.mymed.model.data.MUserBean;

public class SessionManager extends AbstractManager implements ISessionManager {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public SessionManager() throws InternalBackEndException {
		this(new StorageManager());
	}
	
	public SessionManager(StorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}

	/* --------------------------------------------------------- */
	/* implements IProfileManagement */
	/* --------------------------------------------------------- */
	/**
	 * @throws IOBackEndException 
	 * @see ISessionManager#create(String, String)
	 */
	public void create(String userID, String ip) throws InternalBackEndException, IOBackEndException {
		MSessionBean sessionBean = new MSessionBean();
		sessionBean.setIp(ip);
		sessionBean.setUser(userID);
		sessionBean.setCurrentApplications("");
		sessionBean.setP2P(false);
		sessionBean.setTimeout(System.currentTimeMillis());
		create(sessionBean);
	}
	
	/**
	 * @throws IOBackEndException 
	 * @see ISessionManager#create(String, String)
	 */
	public void create(MSessionBean sessionBean)
			throws InternalBackEndException, IOBackEndException {
		try {
			// CREATION DE L'ID DE SESSION 
			sessionBean.setId(sessionBean.getUser() + "_SESSION");
			storageManager.insertSlice("Session", "sessionID", sessionBean
					.getAttributeToMap());
			// LINK AVEC L'USER
			ProfileManager profileManager =  new ProfileManager();
			MUserBean user = profileManager.read(sessionBean.getUser());
			user.setSession(sessionBean.getId());
			profileManager.update(user);
		} catch (ServiceManagerException e) {
			throw new InternalBackEndException(
					"create failed because of a WrapperException: "
							+ e.getMessage());
		}
	}

	/**
	 * @throws IOBackEndException 
	 * @see ISessionManager#read(String)
	 */
	public MSessionBean read(String userID) throws InternalBackEndException, IOBackEndException {
		ProfileManager profileManager =  new ProfileManager();
		MUserBean user = profileManager.read(userID);
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		MSessionBean session = new MSessionBean();
		try {
			args = storageManager.selectAll("Session", user.getSession());
		} catch (ServiceManagerException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"read failed because of a WrapperException: "
							+ e.getMessage());
		}

		return (MSessionBean) introspection(session, args);
	}

	/**
	 * @throws IOBackEndException 
	 * @see ISessionManager#update(MSessionBean)
	 */
	public void update(MSessionBean session)
			throws InternalBackEndException, IOBackEndException {
		create(session);
	}
	
	/**
	 * @throws ServiceManagerException 
	 * @see ISessionManager#delete(String)
	 */
	public void delete(String userID) throws InternalBackEndException {
		storageManager.removeAll("Session", userID + "_SESSION");
	}

}
