package edu.lognet.core.protocols.p2p.exception;


/**
 * 
 * @author lvanni
 *
 */
public class NodeException extends Exception {
	private static final long serialVersionUID = 1L;

	private String message;
	
	/**
	 * Exception throws by the wrapper 
	 * @param message
	 */
	public NodeException(String message) {
		super(message);
		this.message = message;
	}
	
	@Override
	public void printStackTrace() {
		System.err.println(message);
	}
}
