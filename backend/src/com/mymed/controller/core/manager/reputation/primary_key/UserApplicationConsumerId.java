/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.primary_key;

import java.nio.ByteBuffer;

import com.mymed.utils.MConverter;

/**
 * 
 * @author piccolo
 */
public class UserApplicationConsumerId {
  private final String userId;
  private final String applicationId;

  public UserApplicationConsumerId(final String userId, final String applicationId) {
    this.userId = userId;
    this.applicationId = applicationId;
  }

  public String getApplicationId() {
    return applicationId;
  }

  public String getUserId() {
    return userId;
  }

  @Override
  public String toString() {
    return userId + PKConstants.SEPARATOR_CHAR + applicationId;
  }

  public ByteBuffer getUserApplicationConsumerIdAsByteBuffer() {
    return ByteBuffer.wrap(toString().getBytes());
  }

  public static UserApplicationConsumerId parseByteBuffer(final ByteBuffer arg0) throws Exception {
    return parseString(MConverter.byteBufferToString(arg0));
  }

  public static UserApplicationConsumerId parseString(final String arg0) throws Exception {
    final String userId = arg0.substring(0, arg0.indexOf(PKConstants.SEPARATOR_CHAR));
    final String applicationId = arg0.substring(arg0.indexOf(PKConstants.SEPARATOR_CHAR)
        + PKConstants.SEPARATOR_CHAR.length());
    return new UserApplicationConsumerId(userId, applicationId);
  }
}
