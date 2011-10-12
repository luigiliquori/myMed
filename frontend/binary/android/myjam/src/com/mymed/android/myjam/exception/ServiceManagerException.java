package com.mymed.android.myjam.exception;


/**
 * 
 * @author lvanni
 *
 */
public class ServiceManagerException extends Exception {
	private static final long serialVersionUID = 1L;

	/**
	 * Exception throws by the wrapper 
	 * @param message
	 */
	public ServiceManagerException(String message) {
		super(message);
	}
	
}
