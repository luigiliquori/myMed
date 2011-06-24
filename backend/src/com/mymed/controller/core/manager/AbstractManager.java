package com.mymed.controller.core.manager;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.util.Map;
import java.util.Map.Entry;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.AbstractMBean;

/**
 * 
 * @author lvanni
 * 
 */
public abstract class AbstractManager {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	protected IStorageManager storageManager;
	
	/* --------------------------------------------------------- */
	/* Constructor */
	/* --------------------------------------------------------- */
	public AbstractManager(IStorageManager storageManager) {
		this.storageManager = storageManager;
	}
	
	/* --------------------------------------------------------- */
	/* public method */
	/* --------------------------------------------------------- */
	/**
	 * Introspection
	 * @param mbean
	 * @param args
	 * @return
	 * @throws InternalBackEndException
	 */
	public AbstractMBean introspection(AbstractMBean mbean,
			Map<byte[], byte[]> args) throws InternalBackEndException {
		try {
			for (Entry<byte[], byte[]> arg : args.entrySet()) {
				try {
					Field f = mbean.getClass().getDeclaredField(
							new String(arg.getKey(), "UTF8"));
					String setterName = "set";
					setterName += f.getName().substring(0, 1).toUpperCase()
							+ f.getName().substring(1);
					Method m = mbean.getClass().getMethod(setterName,
							String.class);
					Object[] argument = new Object[1];
					argument[0] = new String(arg.getValue(), "UTF8");
					System.out.println("\nINFO: invoke:" + m.getName() + ", "
							+ argument[0]);
					m.invoke(mbean, argument);
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
		return mbean;
	}
	
	
}
