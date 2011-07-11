package com.mymed.utils;

import java.lang.reflect.Constructor;
import java.lang.reflect.Method;
import java.lang.reflect.Type;
import java.nio.ByteBuffer;
import java.util.EnumSet;

/**
 * Enum type to store information about the class types. Information are stored
 * as &lt;Class, Primitive type&gt;; for an {@code int} it is: &lt;Integer,
 * int&gt;.
 * <p>
 * {@link String} is stored as &lt;String, String&gt;.
 * 
 * @author Milo Casagrande
 * 
 */
public enum ClassType {

	BYTE(Byte.class, byte.class),
	BOOL(Boolean.class, boolean.class),
	CHAR(Character.class, char.class),
	DOUBLE(Double.class, double.class),
	FLOAT(Float.class, float.class),
	INT(Integer.class, int.class),
	LONG(Long.class, long.class),
	STRING(String.class, String.class),
	SHORT(Short.class, short.class);

	private static final String TO_BYTE_BUFFER = "ToByteBuffer";
	private static final String BYTE_BUFFER_TO = "byteBufferTo";

	private final Class<?> objectClass;
	private final Class<?> primitiveType;

	/**
	 * Simple constructor
	 * 
	 * @param objectClass
	 * @param primitiveType
	 */
	private ClassType(final Class<?> objectClass, final Class<?> primitiveType) {
		this.objectClass = objectClass;
		this.primitiveType = primitiveType;
	}

	/**
	 * @return the object class
	 */
	public Class<?> getObjectClass() {
		return objectClass;
	}

	/**
	 * @return the primitive type
	 */
	public Class<?> getPrimitiveType() {
		return primitiveType;
	}

	/**
	 * Infer the class type of the given {@link Object}
	 * 
	 * @param object
	 *            the {@link Object} to infer the {@link Class} of
	 * @return the ClassType enumeration type
	 */
	public static ClassType inferType(final Object object) {
		return inferTypeGeneric(object.getClass().getCanonicalName());
	}

	/**
	 * Infer the class type of the given, unknown, class
	 * 
	 * @param classType
	 *            the class to infer its {@link Class}
	 * @return the ClassType enumeration type
	 */
	public static ClassType inferTpye(final Class<?> classType) {
		return inferTypeGeneric(classType.getCanonicalName());
	}

	/**
	 * Infer the class type of the given, unknown, class
	 * 
	 * @param type
	 *            the type to infer its {@link Class}
	 * @return the ClassType enumeration type
	 */
	public static ClassType inferType(final Type type) {
		return inferTypeGeneric(type.toString());
	}

	private static ClassType inferTypeGeneric(final String className) {
		String localClassName = className;

		if (className.contains("class")) {
			localClassName = className.split(" ")[1];
		}

		ClassType classType = null;

		for (final ClassType type : EnumSet.range(ClassType.BYTE, ClassType.STRING)) {

			if (localClassName.equals(type.getObjectClass().getCanonicalName())
			        || localClassName.equals(type.getPrimitiveType().getSimpleName())) {
				classType = type;
				break;
			}
		}

		return classType;
	}

	/**
	 * Create an abject of the specified ClassType type
	 * 
	 * @param classType
	 *            the type of the object to create
	 * @param arg
	 *            the byte array with the value of the object to create
	 * @return a new object
	 */
	public static Object objectFromClassType(final ClassType classType, final byte[] arg) {
		Object returnObject = null;

		try {
			final Constructor<?> cons = classType.getObjectClass().getDeclaredConstructor(classType.getPrimitiveType());

			final String methodName = BYTE_BUFFER_TO + classType.getObjectClass().getSimpleName();
			final Method method = MConverter.class.getMethod(methodName, ByteBuffer.class);

			final Object initArg = method.invoke(null, ByteBuffer.wrap(arg));

			returnObject = cons.newInstance(initArg);
		} catch (final Exception ex) {
			// TODO logger!
			ex.printStackTrace();
		}

		return returnObject;
	}

	/**
	 * Convert an object of the specified ClassType into a byte array
	 * 
	 * @param classType
	 *            the ClassType with the type of the object
	 * @param object
	 *            the object to convert
	 * @return the byte array that represents the object
	 */
	public static byte[] classTypeToByteArray(final ClassType classType, final Object object) {
		final String methodName = classType.getPrimitiveType().getSimpleName().toLowerCase() + TO_BYTE_BUFFER;
		ByteBuffer returnBuffer = null;

		try {
			final Method method = MConverter.class.getMethod(methodName, classType.getPrimitiveType());
			returnBuffer = (ByteBuffer) method.invoke(null, object);
		} catch (final Exception ex) {
			// TODO logger
			ex.printStackTrace();
		}

		return returnBuffer.array();
	}

	@Override
	public String toString() {
		final StringBuilder stringBuilder = new StringBuilder(45);

		stringBuilder.append("Object Class: " + getObjectClass().getCanonicalName());
		stringBuilder.append("\nNative Type : " + getPrimitiveType().getSimpleName());

		stringBuilder.trimToSize();

		return stringBuilder.toString();
	}
}
