package com.mymed.controller.core.manager.session;

import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;
import com.mymed.model.data.MSessionBean;
import com.mymed.model.data.MUserBean;

public class SessionManager extends AbstractManager implements ISessionManager {
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public SessionManager() throws InternalBackEndException {
		super(new StorageManager(WrapperType.CASSANDRA));
	}

	/* --------------------------------------------------------- */
	/* implements IProfileManagement */
	/* --------------------------------------------------------- */
	/**
	 * @throws IOBackEndException 
	 * @see ISessionManager#create(String, String)
	 */
	public void create(String mymedID, String ip) throws InternalBackEndException, IOBackEndException {
		MSessionBean sessionBean = new MSessionBean();
		sessionBean.setIp(ip);
		sessionBean.setMymedID(mymedID);
		sessionBean.setCurrentApplications("");
		sessionBean.setP2P(false);
		sessionBean.setTimestamp(System.currentTimeMillis());
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
			sessionBean.setSessionID(sessionBean.getMymedID() + "_SESSION");
			storageManager.insertSlice("Session", "sessionID", sessionBean
					.getAttributeToMap());
			// LINK AVEC L'USER
			ProfileManager profileManager =  new ProfileManager();
			MUserBean user = profileManager.read(sessionBean.getMymedID());
			user.setSessionID(sessionBean.getSessionID());
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
	public MSessionBean read(String mymedID) throws InternalBackEndException, IOBackEndException {
		ProfileManager profileManager =  new ProfileManager();
		MUserBean user = profileManager.read(mymedID);
		Map<byte[], byte[]> args = new HashMap<byte[], byte[]>();
		MSessionBean session = new MSessionBean();
		try {
			args = storageManager.selectAll("Session", user.getSessionID());
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
	public void delete(String mymedID) throws InternalBackEndException, ServiceManagerException {
		// CREATION DE L'ID DE SESSION 
		storageManager.removeAll("Session", mymedID + "_SESSION");
	}

}
