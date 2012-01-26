/*
 * Copyright 2012 INRIA 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
package com.mymed.utils;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.BufferOverflowException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.CharacterCodingException;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.List;

import com.mymed.controller.core.exception.InternalBackEndException;

/**
 * Convenience class to convert to and from {@link ByteBuffer} for the most used
 * data types.
 * 
 * @author Milo Casagrande
 * 
 */
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
	 * The size of a double in bytes
	 */
	private static final int DOUBLE_SIZE = Double.SIZE / 8;

	/**
	 * The size of an int in bytes
	 */
	private static final int INT_SIZE = Integer.SIZE / 8;

	/**
	 * The size of a byte in bytes
	 */
	private static final int BYTE_SIZE = Byte.SIZE / 8;

	private static final byte[] trueBuffer = new byte[] {0, 0, 0, 1};
	private static final byte[] falseBuffer = new byte[] {0, 0, 0, 0};

	/**
	 * Private constructor to avoid warnings since class is all static, or we
	 * should implement a singleton
	 */
	private MConverter() {
	};

	/**
	 * Convert a string into a {@link ByteBuffer}. If the {@link String} object
	 * passed is null, it is treated as an empty string
	 * 
	 * @param string
	 *            the string to convert
	 * @return the string converted into a {@link ByteBuffer}
	 * @throws InternalBackEndException
	 *             when a wrong encoding is used
	 */
	public static ByteBuffer stringToByteBuffer(final String string) throws InternalBackEndException {

		String localString = string;

		if (string == null) {
			localString = "";
		}

		final CharBuffer charBuffer = CharBuffer.wrap(localString.trim());
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
		CharBuffer charBuf = null;

		try {
			charBuf = CHARSET.newDecoder().decode(buffer);
			returnString = charBuf.toString();
		} catch (final CharacterCodingException ex) {
			throw new InternalBackEndException("Wrong encoding used in the message");
		}

		// Clean up the empty spaces, looks like in the conversion from
		// ByteBuffer to String some spaces might slip in
		return returnString.trim();
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
	public static int byteBufferToInteger(final ByteBuffer buffer) {

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

	/**
	 * Convert a double value into a {@link ByteBuffer}
	 * 
	 * @param value
	 *            the double to convert
	 * @return the ByteBuffer value
	 */
	public static ByteBuffer doubleToByteBuffer(final double value) {

		final ByteBuffer buffer = ByteBuffer.allocate(DOUBLE_SIZE);

		buffer.clear();
		buffer.putDouble(value);
		buffer.compact();

		return buffer;
	}

	/**
	 * Convert a double value into a {@link ByteBuffer}
	 * 
	 * @param value
	 *            the double to convert
	 * @return the ByteBuffer value
	 */
	public static ByteBuffer doubleToByteBuffer(final Double value) {
		return doubleToByteBuffer(value.doubleValue());
	}

	/**
	 * Convert a {@link ByteBuffer} into an double
	 * <p>
	 * No assumptions are made on the value stored in the ByteBuffer, it has to
	 * be a double
	 * 
	 * @param buffer
	 *            the ByteBuffer that holds the double value
	 * @return the long value
	 */
	public static double byteBufferToDouble(final ByteBuffer buffer) {

		buffer.compact();
		buffer.clear();

		return buffer.getDouble();
	}

	/**
	 * Convert a boolean value into a fictitious {@link ByteBuffer}
	 * 
	 * @param value
	 *            the boolean to convert
	 * @return a {@link ByteBuffer} representation of the boolean value
	 */
	public static ByteBuffer booleanToByteBuffer(final boolean value) {

		ByteBuffer buffer = ByteBuffer.allocate(INT_SIZE);

		if (value) {
			buffer = ByteBuffer.wrap(trueBuffer);
		} else {
			buffer = ByteBuffer.wrap(falseBuffer);
		}

		buffer.clear();
		buffer.compact();

		return buffer;
	}

	/**
	 * Convert a boolean value contained in a ByteBuffer into a boolean
	 * 
	 * @param buffer
	 *            the buffer with the boolean value
	 * @return the boolean value
	 */
	public static boolean byteBufferToBoolean(final ByteBuffer buffer) {

		boolean returnValue = false;

		byte[] booleanBuffer = new byte[4];
		booleanBuffer = buffer.array();

		if (booleanBuffer[3] == 1) {
			returnValue = true;
		}

		return returnValue;
	}
	
	/**
	 * Given an InputStream reads the bytes as UTF8 chars and return a 
	 * String.
	 * @param is Input stream.
	 * @param length Length of the stream in bytes.
	 * @return The string
	 * @throws InternalBackEndException Format is not correct or the length less then the real wrong.
	 */
	public static String convertStreamToString(InputStream is,int length) throws InternalBackEndException {
		try {
			if (length>0){
				ByteBuffer byteBuff = ByteBuffer.allocate(length);
				int currByte;
				while ((currByte=is.read()) != -1) {
					byteBuff.put((byte) currByte);
				}
				byteBuff.compact();
				return com.mymed.utils.MConverter.byteBufferToString(byteBuff);
			}else{
				BufferedReader buffRead = new BufferedReader(new InputStreamReader(is,Charset.forName("UTF-8")));
				StringBuilder sb = new StringBuilder();
				String line;
				while ((line = buffRead.readLine()) != null) {
					sb.append(line + "\n");
				}
				return sb.toString();
			}
		} catch (IOException e) {
			throw new InternalBackEndException("Wrong content");
		} catch (BufferOverflowException e){
			throw new InternalBackEndException("Wrong length");
		}finally {
			try {
				is.close();             
			} catch (IOException e) {
				throw new InternalBackEndException("Error closing the stream.");
			}
		}
	}
}
