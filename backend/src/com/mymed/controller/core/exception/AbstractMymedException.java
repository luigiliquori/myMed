package com.mymed.controller.core.exception;

/**
 * An myMed exception is used to convert the Exception message in a jSon format
 * for the frontend
 * 
 * @author lvanni
 */
public abstract class AbstractMymedException extends Exception {

  /* --------------------------------------------------------- */
  /* Attributes */
  /* --------------------------------------------------------- */
  private static final long serialVersionUID = 1L;
  /** the status returned by the http server */
  private final int status;
  private final String message;

  /* --------------------------------------------------------- */
  /* Constructors */
  /* --------------------------------------------------------- */
  public AbstractMymedException(final int status, final String message) {
    this.status = status;
    this.message = message;
  }

  /* --------------------------------------------------------- */
  /* Public methods */
  /* --------------------------------------------------------- */
  public int getStatus() {
    return status;
  }

  @Override
  public String getMessage() {
    return message;
  }
}
