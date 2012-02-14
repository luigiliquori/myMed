package com.mymed.model.data.id;

import java.nio.BufferUnderflowException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.CharacterCodingException;
import java.nio.charset.Charset;

import com.mymed.controller.core.exception.WrongFormatException;

/**
 * Class used to identify a report.
 * 
 * Format of the identifier: -Textual representation: <char><userId>_<timestamp>
 * 
 * -Raw bytes representation: <char(2 Bytes)><timestamp(8 Bytes)><userId(1 Byte
 * for each character)>
 * 
 * @author iacopo
 * 
 */
public class MyMedId {
  private final long timestamp;
  private final String userId;
  private final char type;

  private static final Charset CHARSET = Charset.forName("UTF8");
  private static final int LONG_BYTESIZE = 8;
  private static final int CHAR_BYTESIZE = 2;
  private static final char SEPARATOR_CHAR = '_';

  /**
   * Public constructor.
   * 
   * @param timestamp
   * @param userId
   * @throws IllegalArgumentException
   */
  public MyMedId(final char type, final long timestamp, final String userId) throws IllegalArgumentException {
    this.type = type;
    this.timestamp = timestamp; // Removed check
    this.userId = userId;
  }

  /**
   * Returns a String representation of the Id
   */
  @Override
  public String toString() {
    final StringBuffer buffer = new StringBuffer(150);
    buffer.append(type);
    buffer.append(userId);
    buffer.append(SEPARATOR_CHAR);
    buffer.append(timestamp);

    buffer.trimToSize();
    return buffer.toString();
  }

  /**
   * Return a ByteBuffer representation of the ReportId
   * 
   * @return
   */
  public ByteBuffer AsByteBuffer() {
    final byte[] userIdBB = userId.getBytes(CHARSET);
    int size = userIdBB.length;
    size += LONG_BYTESIZE;
    size += CHAR_BYTESIZE;

    final ByteBuffer reportIdBB = ByteBuffer.allocate(size);
    reportIdBB.clear();
    reportIdBB.putChar(type);
    reportIdBB.putLong(timestamp);
    reportIdBB.put(userIdBB);
    reportIdBB.compact();
    return reportIdBB;
  }

  /**
   * Parse the ByteBuffer argument and returns a ReportId object
   * 
   * @return ReportIdObject
   */
  public static MyMedId parseByteBuffer(final ByteBuffer arg0) throws WrongFormatException {
    try {
      final ByteBuffer tmp = arg0.slice();
      final char type = tmp.getChar();
      final long timestamp = tmp.getLong();
      final CharBuffer charBuf = CHARSET.newDecoder().decode(tmp);
      final String userId = charBuf.toString();
      return new MyMedId(type, checkTimestamp(timestamp), checkUserId(userId));
    } catch (final CharacterCodingException e) {
      throw new WrongFormatException("ReportId: Character decoding error.");
    } catch (final BufferUnderflowException e) {
      throw new WrongFormatException("ReportId: BufferUnderflow.");
    } catch (final Exception e) {
      throw new WrongFormatException("ReportId: Parsing error occurred. " + e.getLocalizedMessage());
    }
  }

  /**
   * Parse the ByteBuffer argument and returns a ReportId object
   * 
   * @return ReportIdObject
   */
  public static MyMedId parseString(final String arg0) throws WrongFormatException {
    try {
      final char type = arg0.charAt(0);
      final int sepIndex = arg0.lastIndexOf(SEPARATOR_CHAR);
      final String userId = arg0.substring(1, sepIndex);
      final long timestamp = Long.parseLong(arg0.substring(sepIndex + 1, arg0.length()));
      return new MyMedId(type, checkTimestamp(timestamp), checkUserId(userId));
    } catch (final NumberFormatException e) {
      throw new WrongFormatException("ReportId: Parsing error.");
    } catch (final BufferUnderflowException e) {
      throw new WrongFormatException("ReportId: BufferUnderflow.");
    } catch (final Exception e) {
      throw new WrongFormatException("ReportId: Parsing error occurred. " + e.getLocalizedMessage());
    }
  }

  /*
   * Getters
   */
  public String getUserId() {
    return userId;
  }

  public long getTimestamp() {
    return timestamp;
  }

  public char getType() {
    return type;
  }

  /**
   * Checks if the timestamp is valid.
   * 
   * @param timestamp
   * @return
   * @throws RuntimeException
   */
  private static long checkTimestamp(final long timestamp) throws WrongFormatException {
    if (timestamp < 0) {
      throw new WrongFormatException("Timestamp cannot be negative.");
    }
    return timestamp;
  }

  // TODO Check
  /**
   * Checks if the userId is valid.
   */
  private static String checkUserId(final String userId) throws WrongFormatException {
    if (userId == null || userId.length() <= 0) {
      throw new WrongFormatException("UserId malformed.");
    }
    return userId;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    final int prime = 31;
    int result = 1;
    result = prime * result + (int) (timestamp ^ timestamp >>> 32);
    result = prime * result + type;
    result = prime * result + (userId == null ? 0 : userId.hashCode());
    return result;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#equals(java.lang.Object)
   */
  @Override
  public boolean equals(final Object obj) {

    boolean equal = false;

    if (this == obj) {
      equal = true;
    } else if (obj instanceof MyMedId) {
      final MyMedId comparable = (MyMedId) obj;

      equal = true;

      if (userId == null && comparable.getUserId() != null || userId != null && comparable.getUserId() == null) {
        equal &= false;
      } else if (userId != null && comparable.getUserId() != null) {
        equal &= userId.equals(comparable.getUserId());
      }

      equal &= timestamp == comparable.getTimestamp();
      equal &= type == comparable.getType();
    }

    return equal;
  }
}
