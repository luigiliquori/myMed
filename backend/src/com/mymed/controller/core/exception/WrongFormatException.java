package com.mymed.controller.core.exception;


/**
 * Returns an explicit server error 500.
 * 
 * @author iacopo
 *
 */
public class WrongFormatException extends AbstractMymedException {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;
	

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public WrongFormatException(final String message) {
		super(500, message);
	}
	
	public WrongFormatException(Exception e) {
		super(500, e.toString());
	}

}
