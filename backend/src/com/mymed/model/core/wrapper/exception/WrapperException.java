package com.mymed.model.core.wrapper.exception;


/**
 * 
 * @author lvanni
 *
 */
public class WrapperException extends Exception {
	private static final long serialVersionUID = 1L;

	/**
	 * Exception throws by the wrapper 
	 * @param message
	 */
	public WrapperException(String message) {
		super(message);
	}
	
}
