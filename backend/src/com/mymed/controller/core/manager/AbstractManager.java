package com.mymed.controller.core.manager;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.util.Map;
import java.util.Map.Entry;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.storage.IMyJamStorageManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.model.data.AbstractMBean;
import com.mymed.utils.ClassType;

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
	protected IMyJamStorageManager myJamStorageManager;

	/* --------------------------------------------------------- */
	/* Constructor */
	/* --------------------------------------------------------- */
	public AbstractManager(final IStorageManager storageManager) {
		this.storageManager = storageManager;
	}
	
	public AbstractManager(final IMyJamStorageManager storageManager) {
		this.myJamStorageManager = storageManager;
	}

	/* --------------------------------------------------------- */
	/* public method */
	/* --------------------------------------------------------- */
	/**
	 * Introspection
	 * 
	 * @param mbean
	 * @param args
	 * @return
	 * @throws InternalBackEndException
	 */
	public AbstractMBean introspection(final AbstractMBean mbean, final Map<byte[], byte[]> args)
	        throws InternalBackEndException {
		try {
			for (final Entry<byte[], byte[]> arg : args.entrySet()) {
				try {
					final Field field = mbean.getClass().getDeclaredField(new String(arg.getKey(), "UTF8"));
					final ClassType classType = ClassType.inferType(field.getGenericType());

					final StringBuilder setterName = new StringBuilder(20);
					setterName.append("set");
					setterName.append(field.getName().substring(0, 1).toUpperCase());
					setterName.append(field.getName().substring(1));

					setterName.trimToSize();

					final Method method = mbean.getClass().getMethod(setterName.toString(),
					        classType.getPrimitiveType());

					final Object argument = ClassType.objectFromClassType(classType, arg.getValue());

					method.invoke(mbean, argument);
				} catch (final NoSuchFieldException e) {
					System.out.println("\nWARNING: " + new String(arg.getKey(), "UTF8") + " is not an MUserBean Field");
				}
			}
		} catch (final Exception ex) {
			throw new InternalBackEndException(ex);
		}
		return mbean;
	}
}
