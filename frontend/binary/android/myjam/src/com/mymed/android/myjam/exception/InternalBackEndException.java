package com.mymed.android.myjam.exception;

/**
 * Use to return an explicit server Error 500 to the frontend
 * 
 * @author lvanni
 * 
 */
public class InternalBackEndException extends Exception implements
		IMymedException {
	private static final long serialVersionUID = 1L;

	private String message;

	/**
	 * Exception throws by the wrapper
	 * 
	 * @param message
	 */
	public InternalBackEndException(Exception ex) {
		super(ex);
		this.message = ex.toString();
	}
	
	/**
	 * 
	 * @param message
	 */
	public InternalBackEndException(String message) {
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