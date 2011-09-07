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
	private String message;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public IOBackEndException(Exception ex, int status) {
		super(ex, status);
		this.message = ex.toString();
	}
	
	public IOBackEndException(Exception ex) {
		this(ex, 404);
	}

	public IOBackEndException(String message, int status) {
		super(message, status);
		this.message = message;
	}
	
	public IOBackEndException(String message) {
		this(message, 404);
	}
	
	/* --------------------------------------------------------- */
	/* extends AbstractMymedException */
	/* --------------------------------------------------------- */
	public String getJsonException() {
		String res = "{\n" + "\"error\": {\n"
				+ "\"type\": \"IOBackEndException\",\n"
				+ "\"status\":  \"" + status + "\",\n"
				+ "\"message\": \"" + message + "\"\n" + "}\n" + "}";
		return res;
	}
}
