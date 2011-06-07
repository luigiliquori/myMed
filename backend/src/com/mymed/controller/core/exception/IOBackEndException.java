package com.mymed.controller.core.exception;

/**
 * Use to return an explicit server Error 404 to the frontend
 * 
 * @author lvanni
 * 
 */
public class IOBackEndException extends Exception implements IMymedException {
	private static final long serialVersionUID = 1L;

	private String message;

	/**
	 * Exception throws by the wrapper
	 * 
	 * @param message
	 */
	public IOBackEndException(String message) {
		super(message);
		this.message = message;
	}

	/**
	 * see {@link IMymedException#getJsonException()}
	 */
	public String getJsonException() {
		String res = "{\n" + "\"error\": {\n"
				+ "\"type\": \"IOBackEndException\",\n" + "\"message\": \""
				+ message + "\"\n" + "}\n" + "}";
		return res;
	}
}
