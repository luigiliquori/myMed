package com.mymed.controller.core.services.manager.pubsub;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;

import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;
import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;
import com.mymed.model.core.wrapper.Wrapper;
import com.mymed.model.core.wrapper.exception.WrapperException;
import com.mymed.model.data.MUserBean;

/**
 * Manage an user profile
 * 
 * @author lvanni
 * 
 */
public class ProfileManager implements IProfileManager {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** DAO pattern */
	private Wrapper wrapper;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ProfileManager() throws InternalBackEndException {
		this.wrapper = new Wrapper(ClientType.CASSANDRA);
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
			wrapper.insertInto("Users", "mymedID", user.getAttributeToMap());
			// TODO update the user values!
			return user;
		} catch (WrapperException e) {
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
			args = wrapper.selectAll("Users", id);
		} catch (WrapperException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"read failed because of a WrapperException: "
							+ e.getMessage());
		}

		// Introspection:
		try {
			for (Entry<byte[], byte[]> arg : args.entrySet()) {
				try {
					Field f = user.getClass().getDeclaredField(
							new String(arg.getKey(), "UTF8"));
					String setterName = "set";
					setterName += f.getName().substring(0, 1).toUpperCase()
							+ f.getName().substring(1);
					Method m = user.getClass().getMethod(setterName,
							String.class);
					Object[] argument = new Object[1];
					argument[0] = new String(arg.getValue(), "UTF8");
					System.out.println("\nINFO: invoke:" + m.getName() + ", "
							+ argument[0]);
					m.invoke(user, argument);
				} catch (NoSuchFieldException e) {
					System.out.println("\nWARNING: "
							+ new String(arg.getKey(), "UTF8")
							+ " is not an MUserBean Field");
				}
			}
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"Introspection failed with a UnsupportedEncodingException");
		} catch (SecurityException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"Introspection failed with a SecurityException");
		} catch (IllegalArgumentException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"Introspection failed with a IllegalArgumentException");
		} catch (NoSuchMethodException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"Introspection failed with a NoSuchMethodException");
		} catch (IllegalAccessException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"Introspection failed with a IllegalAccessException");
		} catch (InvocationTargetException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"Introspection failed with a InvocationTargetException");
		}
		return user;
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
			wrapper.removeAll("User", mymedID);
		} catch (WrapperException e) {
			e.printStackTrace();
			throw new InternalBackEndException(
					"delete failed because of a WrapperException: "
							+ e.getMessage());
		}
	}

}
