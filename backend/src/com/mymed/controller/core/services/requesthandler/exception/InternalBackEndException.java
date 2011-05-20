package com.mymed.controller.core.services.requesthandler.exception;


/**
 * 
 * @author lvanni
 *
 */
public class InternalBackEndException extends Exception {
	private static final long serialVersionUID = 1L;

	/**
	 * Exception throws by the wrapper 
	 * @param message
	 */
	public InternalBackEndException(String message) {
		super(message);
	}
	
}
