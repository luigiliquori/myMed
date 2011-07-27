package com.mymed.android.myjam.exception;

/**
 * 
 * @author iacopo
 *
 */
public class InternalClientException extends Exception 
implements IMymedException{
	private static final long serialVersionUID = 1L;

	private String message;

	/**
	 * @param message
	 */
	public InternalClientException(Exception ex) {
		super(ex);
		this.message = ex.toString();
	}
	
	/**
	 * @param message
	 */
	public InternalClientException(String message) {
		super(message);
		this.message = message;
	}
	
	
	/**
	 * see {@link IMymedException#getJsonException()}
	 */
	public String getJsonException() {
		String res = "{\n" + "\"error\": {\n"
				+ "\"type\": \"InternalBackEndException\",\n"
				+ "\"message\": \"" + message + "\"\n" + "}\n" + "}";
		return res;
	}
}
