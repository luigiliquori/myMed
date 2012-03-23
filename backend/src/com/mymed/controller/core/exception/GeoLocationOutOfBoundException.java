package com.mymed.controller.core.exception;

public class GeoLocationOutOfBoundException extends Exception {

  /**
   * Generated serial version.
   */
  private static final long serialVersionUID = 151036190872073038L;

  /**
   * Thrown when the location of the user is out from the area defined in
   * GeoLocation class.
   * 
   * @param message
   */
  public GeoLocationOutOfBoundException(final String message) {
    super(message);
  }
}
