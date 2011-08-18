package com.mymed.controller.core.exception;

/**
 * Use to return an explicit server Error 500 to the frontend
 * 
 * @author lvanni
 * 
 */
public class InternalBackEndException extends AbstractMymedException {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;
	private String message;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public InternalBackEndException(Exception ex) {
		super(ex, 500);
		this.message = ex.toString();
	}

	public InternalBackEndException(String message) {
		super(message, 500);
		this.message = message;
	}

	/* --------------------------------------------------------- */
	/* extends AbstractMymedException */
	/* --------------------------------------------------------- */
	/**
	 * see {@link AbstractMymedException#getJsonException()}
	 */
	public String getJsonException() {
		String res = "{\n" + "\"error\": {\n"
				+ "\"type\": \"InternalBackEndException\",\n"
				+ "\"status\":  \"" + status + "\",\n"
				+ "\"message\": \"" + message + "\"\n" + "}\n" + "}";
		return res;
	}
}
