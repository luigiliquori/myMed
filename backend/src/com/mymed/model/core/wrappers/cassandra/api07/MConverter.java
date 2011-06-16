package com.mymed.model.core.wrappers.cassandra.api07;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.CharacterCodingException;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.List;

import com.mymed.controller.core.exception.InternalBackEndException;

public final class MConverter {

	/**
	 * The default charset to use
	 */
	private static final Charset CHARSET = Charset.forName("UTF-8");

	/**
	 * The size of a long in bytes
	 */
	private static final int LONG_SIZE = Long.SIZE / 8;

	/**
	 * The size of an int in bytes
	 */
	private static final int INT_SIZE = Integer.SIZE / 8;

	/**
	 * The size of a byte in bytes
	 */
	private static final int BYTE_SIZE = Byte.SIZE / 8;

	/**
	 * Private constructor to avoid warnings since class is all static, or we
	 * should implement a singleton
	 */
	private MConverter() {
	};

	/**
	 * Convert a string into a {@link ByteBuffer}
	 * 
	 * @param string
	 *            the string to convert
	 * @return the string converted into a {@link ByteBuffer}
	 * @throws InternalBackEndException
	 *             if the string is null, or when a wrong encoding is used
	 */
	public static ByteBuffer stringToByteBuffer(final String string) throws InternalBackEndException {

		if (string == null) {
			throw new InternalBackEndException("You need to provide a non-null value");
		}

		final CharBuffer charBuffer = CharBuffer.wrap(string);
		ByteBuffer returnBuffer = null;

		try {
			returnBuffer = CHARSET.newEncoder().encode(charBuffer);
		} catch (final CharacterCodingException ex) {
			throw new InternalBackEndException("Wrong encoding used in the message");
		}

		return returnBuffer;
	}

	/**
	 * Convert a list of strings in a list with the strings converted into
	 * {@link ByteBuffer}
	 * 
	 * @param list
	 *            the list with the strings to convert
	 * @return the list with the strings converted
	 * @throws InternalBackEndException
	 *             if one of the strings is null, or when a wrong encoding is
	 *             used
	 */
	public static List<ByteBuffer> stringToByteBuffer(final List<String> list) throws InternalBackEndException {

		final List<ByteBuffer> result = new ArrayList<ByteBuffer>(list.size());

		for (final String string : list) {
			result.add(stringToByteBuffer(string));
		}

		return result;
	}

	/**
	 * Convert a {@link ByteBuffer} back into a string
	 * 
	 * @param buffer
	 *            the {@link ByteBuffer} to convert
	 * @return the string
	 * @throws InternalBackEndException
	 *             if the buffer is null, or when a wrong encoding is used
	 */
	public static String byteBufferToString(final ByteBuffer buffer) throws InternalBackEndException {

		if (buffer == null) {
			throw new InternalBackEndException("You need to provide a non-null value");
		}

		String returnString = null;

		try {
			final CharBuffer charBuf = CHARSET.newDecoder().decode(buffer);
			returnString = charBuf.toString();
		} catch (final CharacterCodingException ex) {
			throw new InternalBackEndException("Wrong encoding used in the message");
		}

		return returnString;
	}

	/**
	 * Convert an integer value into a {@link ByteBuffer}
	 * 
	 * @param value
	 *            the integer to convert
	 * @return the ByteBuffer value
	 */
	public static ByteBuffer intToByteBuffer(final int value) {

		final ByteBuffer buffer = ByteBuffer.allocate(INT_SIZE);

		buffer.clear();
		buffer.putInt(value);
		buffer.compact();

		return buffer;
	}

	/**
	 * Convert an integer value into a {@link ByteBuffer}
	 * 
	 * @param value
	 *            the integer to convert
	 * @return the ByteBuffer value
	 */
	public static ByteBuffer intToByteBuffer(final Integer value) {
		return intToByteBuffer(value.intValue());
	}

	/**
	 * Convert a {@link ByteBuffer} into an integer
	 * <p>
	 * No assumptions are made on the value stored in the ByteBuffer, it has to
	 * be an integer
	 * 
	 * @param buffer
	 *            the ByteBuffer that holds the integer value
	 * @return the integer value
	 */
	public static int byteBufferToInt(final ByteBuffer buffer) {

		buffer.compact();
		buffer.clear();

		return buffer.getInt();
	}

	/**
	 * Convert a long value into a {@link ByteBuffer}
	 * 
	 * @param value
	 *            the long to convert
	 * @return the ByteBuffer value
	 */
	public static ByteBuffer longToByteBuffer(final long value) {

		final ByteBuffer buffer = ByteBuffer.allocate(LONG_SIZE);

		buffer.clear();
		buffer.putLong(value);
		buffer.compact();

		return buffer;
	}

	/**
	 * Convert a long value into a {@link ByteBuffer}
	 * 
	 * @param value
	 *            the long to convert
	 * @return the ByteBuffer value
	 */
	public static ByteBuffer longToByteBuffer(final Long value) {
		return longToByteBuffer(value.longValue());
	}

	/**
	 * Convert a {@link ByteBuffer} into an long
	 * <p>
	 * No assumptions are made on the value stored in the ByteBuffer, it has to
	 * be a long
	 * 
	 * @param buffer
	 *            the ByteBuffer that holds the long value
	 * @return the long value
	 */
	public static long byteBufferToLong(final ByteBuffer buffer) {

		buffer.compact();
		buffer.clear();

		return buffer.getLong();
	}

	/**
	 * Convert a byte value into a {@link ByteBuffer}
	 * 
	 * @param value
	 *            the byte value to convert
	 * @return the ByteBuffer value
	 */
	public static ByteBuffer byteToByteBuffer(final byte value) {

		final ByteBuffer buffer = ByteBuffer.allocate(BYTE_SIZE);

		buffer.clear();
		buffer.put(value);
		buffer.compact();

		return buffer;
	}

	/**
	 * Convert a byte value into a {@link ByteBuffer}
	 * 
	 * @param value
	 *            the byte value to convert
	 * @return the ByteBuffer value
	 */
	public static ByteBuffer byteToByteBuffer(final Byte value) {
		return byteToByteBuffer(value.byteValue());
	}

	/**
	 * Convert a {@link ByteBuffer} into a byte
	 * <p>
	 * No assumptions are made on the value stored in the ByteBuffer, it has to
	 * be a byte
	 * 
	 * @param buffer
	 *            the ByteBuffer that holds the byte value
	 * @return the byte value
	 */
	public static byte byteBufferToByte(final ByteBuffer buffer) {

		buffer.compact();
		buffer.clear();

		return buffer.get();
	}

	public static void main(final String[] args) throws IOException {

		final ArrayList<Integer> intList = new ArrayList<Integer>();
		intList.add(Integer.MAX_VALUE);
		intList.add(Integer.MIN_VALUE);
		intList.add(Integer.valueOf(123458));

		for (final Integer integer : intList) {
			final ByteBuffer buf = MConverter.intToByteBuffer(integer);
			final int conv = MConverter.byteBufferToInt(buf);
			System.err.println(conv);
		}

		final ArrayList<Long> longList = new ArrayList<Long>();
		longList.add(Long.MAX_VALUE);
		longList.add(Long.MIN_VALUE);
		longList.add(Long.valueOf(12354687));

		for (final Long i : longList) {
			final ByteBuffer b = MConverter.longToByteBuffer(i);
			final long c = MConverter.byteBufferToLong(b);
			System.err.println(c);
		}

		final ArrayList<String> list = new ArrayList<String>();
		final File file = new File("/home/mcasagr/special-chars.txt");
		final FileReader fileReader = new FileReader(file);

		final BufferedReader reader = new BufferedReader(fileReader);

		String line = reader.readLine();

		while (line != null) {
			list.add(line);
			line = reader.readLine();
		}

		// Add a null string to test
		// list.add(null);

		try {
			for (final String str : list) {
				final ByteBuffer bBuffer = MConverter.stringToByteBuffer(str);
				final String result = MConverter.byteBufferToString(bBuffer);

				System.err.println(bBuffer.toString());
				System.err.println(result);
			}
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}
}
