package com.mymed.model.core.data.dht;

/**
 * 
 * @author lvanni
 *
 */
public abstract class AbstractDHT implements IDHT{
	
	/** address of the node */
	protected String address;
	/** port number for the transport layer */
	protected int port;
	
	/**
	 * Default Constructor
	 * @param address
	 * @param port
	 */
	public AbstractDHT(String address, int port) {
		this.address = address;
		this.port = port;
	}
	
	/* --------------------------------------------------------- */
	/*                  GETTER AND SETTER                        */
	/* --------------------------------------------------------- */
	public String getAddress() {
		return address;
	}

	public void setAddress(String address) {
		this.address = address;
	}

	public int getPort() {
		return port;
	}

	public void setPort(int port) {
		this.port = port;
	}

}
