package com.mymed.model.data.session;

import com.mymed.model.data.AbstractMBean;


public class MSessionBean extends AbstractMBean {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** SESSION_ID */
	private String id;
	/** USER_ID */
	private String user;
	/** APPLICATION_LIST_ID */
	private String currentApplications;
	private long timeout;
	private boolean isP2P;
	private String ip;
	private int port;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public MSessionBean() {
		// TODO Auto-generated constructor stub
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	@Override
	public String toString() {
		return "Session:\n" + super.toString();
	}
	
	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}

	public String getUser() {
		return user;
	}

	public void setUser(String user) {
		this.user = user;
	}

	public String getCurrentApplications() {
		return currentApplications;
	}
	
	public void setCurrentApplications(String currentApplications) {
		this.currentApplications = currentApplications;
	}
	public long getTimeout() {
		return timeout;
	}
	public void setTimeout(long timestamp) {
		this.timeout = timestamp;
	}
	public boolean isP2P() {
		return isP2P;
	}
	public void setP2P(boolean isP2P) {
		this.isP2P = isP2P;
	}
	public String getIp() {
		return ip;
	}
	public void setIp(String ip) {
		this.ip = ip;
	}
	public int getPort() {
		return port;
	}
	public void setPort(int port) {
		this.port = port;
	}
	
}
