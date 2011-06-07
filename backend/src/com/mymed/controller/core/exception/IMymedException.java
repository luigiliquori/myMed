package com.mymed.controller.core.exception;

/**
 * An myMed exception is used to convert the Exception message in a jSon format
 * for the frontend
 * 
 * @author lvanni
 */
public interface IMymedException {

	/**
	 * convert the Exception message in a jSon format for the frontend
	 * 
	 * @return the exception message converted
	 */
	public String getJsonException();

}
