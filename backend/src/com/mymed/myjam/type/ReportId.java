package com.mymed.myjam.type;

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
public class ReportId {
	private long timestamp;
	private String userId;
	
	private static Charset CHARSET = Charset.forName("UTF8");
	private static short LONG_BYTESIZE = 8;
	private static char separationChar = '.';
	
	/**
	 * Public constructor.
	 * @param timestamp
	 * @param userId
	 * @throws IllegalArgumentException
	 */
	public ReportId(long timestamp,String userId) throws IllegalArgumentException{
		this.timestamp = timestamp;  //Removed check
		this.userId = userId;
	}
	
	/**
	 * Returns a String representation of the Id
	 */
	@Override
	public String toString(){
		return userId+separationChar+String.valueOf(timestamp);		
	}
	
	@Override
	public boolean equals(Object o){
		ReportId obj = null;
		try{
			obj = (ReportId) o;
			if (obj.getTimestamp() == this.getTimestamp() && obj.getUserId().equals(this.getUserId()))
				return true;
			else
				return false;	
		}catch(Exception e){
			return false;
		}
	}
	/**
	 * Return a ByteBuffer representation of the ReportId
	 * @return 
	 */
	public ByteBuffer ReportIdAsByteBuffer(){
		byte[] userIdBB = userId.getBytes(CHARSET);
		int size = userIdBB.length;
		size += LONG_BYTESIZE;
		
		ByteBuffer reportIdBB = ByteBuffer.allocate(size);
		reportIdBB.clear();
		reportIdBB.putLong(timestamp);
		reportIdBB.put(userIdBB);
		reportIdBB.compact();
		return reportIdBB;		
	}
	
	/**
	 * Parse the ByteBuffer argument and returns a ReportId object
	 * @return ReportIdObject
	 */
	public static ReportId parseByteBuffer(ByteBuffer arg0) throws WrongFormatException{
		try{
			ByteBuffer tmp = arg0.slice();
			long timestamp = tmp.getLong();
			final CharBuffer charBuf = CHARSET.newDecoder().decode(tmp);
			String userId = charBuf.toString();
			return new ReportId(checkTimestamp(timestamp),
					checkUserId(userId));
		}catch(CharacterCodingException e){
			throw new WrongFormatException("ReportId: Character decoding error.");	
		}catch(BufferUnderflowException e){
			throw new WrongFormatException("ReportId: BufferUnderflow.");
		}catch(Exception e){
			throw new WrongFormatException("ReportId: Pursing error occurred. "+e.getLocalizedMessage());
		}
	}
	
	/**
	 * Parse the ByteBuffer argument and returns a ReportId object
	 * @return ReportIdObject
	 */
	public static ReportId parseString(String arg0) throws WrongFormatException{
		try{
			int sepIndex = arg0.indexOf(separationChar);
			String userId = arg0.substring(0,sepIndex);
			long timestamp = Long.parseLong(arg0.substring(sepIndex+1, arg0.length()));
			return new ReportId(checkTimestamp(timestamp),
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
			throw new WrongFormatException("UserId malformed");
		return userId;
	}

}
