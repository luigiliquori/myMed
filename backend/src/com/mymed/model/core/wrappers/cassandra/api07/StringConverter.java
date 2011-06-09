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

public final class StringConverter {

	/**
	 * The default charset to use
	 */
	private static final Charset CHARSET = Charset.forName("UTF-8");

	/**
	 * Private constructor to avoid warnings since class is all static, or we
	 * should implement a singleton
	 */
	private StringConverter() {
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

	public static void main(final String[] args) throws IOException {

		final ArrayList<String> list = new ArrayList<String>();
		final File file = new File("/home/mcasagr/special-chars.txt");
		final FileReader fileReader = new FileReader(file);

		final BufferedReader reader = new BufferedReader(fileReader);

		String line = reader.readLine();

		while (line != null) {
			list.add(line);
			line = reader.readLine();
		}

		list.add(null);

		try {
			for (final String str : list) {
				final ByteBuffer bBuffer = StringConverter.stringToByteBuffer(str);
				final String result = StringConverter.byteBufferToString(bBuffer);

				System.err.println(bBuffer.toString());
				System.err.println(result);
			}
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}
}
