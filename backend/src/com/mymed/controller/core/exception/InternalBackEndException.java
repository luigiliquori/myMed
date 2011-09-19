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
	private final String message;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public InternalBackEndException(final Exception ex) {
		super(ex, 500);
		message = ex.toString();
	}

	public InternalBackEndException(final String message) {
		super(message, 500);
		this.message = message;
	}

	public InternalBackEndException(final String message, final Throwable cause) {
		super(message, cause, 500);
		this.message = message;
	}

	/* --------------------------------------------------------- */
	/* extends AbstractMymedException */
	/* --------------------------------------------------------- */
	/**
	 * see {@link AbstractMymedException#getJsonException()}
	 */
	@Override
	public String getJsonException() {
		final String res = "{\n" + "\"error\": {\n" + "\"type\": \"InternalBackEndException\",\n" + "\"status\":  \""
		        + status + "\",\n" + "\"message\": \"" + message + "\"\n" + "}\n" + "}";
		return res;
	}
}
