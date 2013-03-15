package com.mymed.controller.core.exception;

/**
 * Returns an explicit server error 500.
 * 
 * @author iacopo
 * 
 */
public class WrongFormatException extends AbstractMymedException {

  /**
   * Generated serial version.
   */
  private static final long serialVersionUID = 1187514541829456061L;

  public WrongFormatException(final String message) {
    super(500, message);
  }

  public WrongFormatException(final Exception e) {
    super(500, e.toString());
  }
}
