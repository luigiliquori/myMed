package com.mymed.controller.core.services.requesthandler.exception;

import java.io.IOException;

/**
 * 
 * @author lvanni
 *
 */
public class IOBackEndException extends IOException {
	private static final long serialVersionUID = 1L;

	/**
	 * Exception throws by the wrapper 
	 * @param message
	 */
	public IOBackEndException(String message) {
		super(message);
	}
	
}
