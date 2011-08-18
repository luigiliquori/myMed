package com.mymed.controller.core.exception;

/**
 * An myMed exception is used to convert the Exception message in a jSon format
 * for the frontend
 * 
 * @author lvanni
 */
public abstract class AbstractMymedException extends Exception {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;
	/** the status returned by the http server */
	protected int status;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public AbstractMymedException(Exception ex, int status) {
		super(ex);
		this.status = status;
	}

	public AbstractMymedException(String message, int status) {
		super(message);
		this.status = status;
	}

	
	public abstract String getJsonException();

	/* --------------------------------------------------------- */
	/* GETTER & SETTER */
	/* --------------------------------------------------------- */
	
	/**
	 * 
	 * @return the status code server
	 */
	public int getStatus() {
		return status;
	}

	/**
	 * Set the status server
	 * @param status
	 * @return
	 */
	public int setStatus(int status) {
		return this.status = status;
	}
}
