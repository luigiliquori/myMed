package com.mymed.controller.core.exception;

/**
 * Use to return an explicit server Error 404 to the frontend
 * 
 * @author lvanni
 * 
 */
public class IOBackEndException extends AbstractMymedException {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public IOBackEndException(String message, int status) {
		super(status, message);
	}
}
