package com.mymed.android.myjam.exception;

/**
 * 
 * @author iacopo
 *
 */
public class InternalClientException extends AbstractMymedException{
	private static final long serialVersionUID = 1L;

	/**
	 * @param message
	 */
	public InternalClientException(Exception ex) {
		super(400,ex.toString());
	}
	
	/**
	 * @param message
	 */
	public InternalClientException(String message) {
		super(400,message);
	}
}
