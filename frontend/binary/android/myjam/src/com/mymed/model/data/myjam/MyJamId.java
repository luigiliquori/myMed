/*
 * Copyright 2012 POLITO 
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
package com.mymed.model.data.myjam;

import java.io.UnsupportedEncodingException;
import java.nio.BufferUnderflowException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.CharacterCodingException;
import java.nio.charset.Charset;

/**
 * Class used to identify a report.
 * @author iacopo
 *
 */
public class MyJamId {
	public final static char REPORT_ID = 'r';
	public final static char UPDATE_ID = 'u';
	public final static char FEEDBACK_ID = 'f';
	private long timestamp;
	private String userId;
	private char type;
	
	private static String CHARSET_NAME = "UTF8";
	private static Charset CHARSET = Charset.forName(CHARSET_NAME);
	private static short LONG_BYTESIZE = 8;
	private static short CHAR_BYTESIZE = 2;
	private static char SEPARATOR_CHAR = ':';
	
	/**
	 * Public constructor.
	 * @param timestamp
	 * @param userId
	 * @throws IllegalArgumentException
	 */
	public MyJamId(char type,long timestamp,String userId) throws IllegalArgumentException{
		this.type = type;
		this.timestamp = timestamp;  //Removed check
		this.userId = userId;
	}
	
	/**
	 * Returns a String representation of the Id
	 */
	@Override
	public String toString(){
		return type+userId+SEPARATOR_CHAR+String.valueOf(timestamp);		
	}
	/**
	 * Return a ByteBuffer representation of the ReportId
	 * @return 
	 * @throws UnsupportedEncodingException 
	 */
	public ByteBuffer ReportIdAsByteBuffer() throws UnsupportedEncodingException{
		byte[] userIdBB = userId.getBytes(CHARSET_NAME);
		int size = userIdBB.length;
		size += LONG_BYTESIZE;
		size += CHAR_BYTESIZE;
		
		ByteBuffer reportIdBB = ByteBuffer.allocate(size);
		reportIdBB.clear();
		reportIdBB.putChar(type);
		reportIdBB.putLong(timestamp);
		reportIdBB.put(userIdBB);
		reportIdBB.compact();
		return reportIdBB;		
	}
	
	/**
	 * Parse the ByteBuffer argument and returns a ReportId object
	 * @return ReportIdObject
	 */
	public static MyJamId parseByteBuffer(ByteBuffer arg0) throws WrongFormatException{
		try{
			ByteBuffer tmp = arg0.slice();
			char type = tmp.getChar();
			long timestamp = tmp.getLong();
			final CharBuffer charBuf = CHARSET.newDecoder().decode(tmp);
			String userId = charBuf.toString();
			return new MyJamId(checkType(type),checkTimestamp(timestamp),
					checkUserId(userId));
		}catch(CharacterCodingException e){
			throw new WrongFormatException("ReportId: Character decoding error.");	
		}catch(BufferUnderflowException e){
			throw new WrongFormatException("ReportId: BufferUnderflow.");
		}catch(Exception e){
			throw new WrongFormatException("ReportId: Parsing error occurred. "+e.getLocalizedMessage());
		}
	}
	
	/**
	 * Parse the ByteBuffer argument and returns a ReportId object
	 * @return ReportIdObject
	 */
	public static MyJamId parseString(String arg0) throws WrongFormatException{
		try{
			char type = arg0.charAt(0);
			int sepIndex = arg0.lastIndexOf(SEPARATOR_CHAR);
			String userId = arg0.substring(1,sepIndex);
			long timestamp = Long.parseLong(arg0.substring(sepIndex+1, arg0.length()));
			return new MyJamId(checkType(type),checkTimestamp(timestamp),
					checkUserId(userId));
		}catch(NumberFormatException e){
			throw new WrongFormatException("ReportId: Parsing error.");	
		}catch(BufferUnderflowException e){
			throw new WrongFormatException("ReportId: BufferUnderflow.");
		}catch(Exception e){
			throw new WrongFormatException("ReportId: Parsing error occurred. "+e.getLocalizedMessage());
		}
	}
	
	/*
	 * Getters
	 * 
	 */
	public String getUserId() {
		return userId;
	}
	public long getTimestamp() {
		return timestamp;
	}
	
	public char getType(){
		return type;
	}
	
	/**
	 * Checks if the timestamp is valid.
	 * @param timestamp
	 * @return
	 * @throws RuntimeException
	 */
	private static long checkTimestamp(long timestamp) throws WrongFormatException{
		if (timestamp<0)
			throw new WrongFormatException("Timestamp cannot be negative.");
		return timestamp;
	}
	//TODO Check
	/**
	 * Checks if the userId is valid.
	 */
	private static String checkUserId(String userId) throws WrongFormatException{
		if (userId==null || userId.length()<=0)
			throw new WrongFormatException("UserId malformed.");
		return userId;
	}
	
	private static char checkType(char type)  throws WrongFormatException {
		switch (type){
			case REPORT_ID:
			case UPDATE_ID:
			case FEEDBACK_ID:
				return type;
			default:
				throw new WrongFormatException("Wrong type.");
		}
	}

}
