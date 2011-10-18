package com.mymed.controller.core.exception;

import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.requesthandler.message.JsonMessage;

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
	private int status;
	private String message;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public AbstractMymedException(int status, final String message) {
		this.status = status;
		this.message = message;
	}

	/* --------------------------------------------------------- */
	/* Public methods */
	/* --------------------------------------------------------- */
	public int getStatus(){
		return this.status;
	}
	
	public String getMessage(){
		return this.message;
	}
}
