package com.mymed.controller.core.exception;


/**
 * Use to return an explicit server Error 500 to the frontend
 * 
 * @author lvanni
 * 
 */
public class InternalBackEndException extends AbstractMymedException {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;
	

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public InternalBackEndException(final String message) {
		super(500, message);
	}
	
	public InternalBackEndException(Exception e) {
		super(500, e.toString());
	}

}
