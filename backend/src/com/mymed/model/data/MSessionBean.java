package com.mymed.model.data;

public class MSessionBean extends AbstractMBean {
	
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private String sessionID;
	private String mymedID;
	private String currentApplications;
	private long timestamp;
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
		return "session:\n" + super.toString();
	}

	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	public String getSessionID() {
		return sessionID;
	}

	public void setSessionID(String sessionID) {
		this.sessionID = sessionID;
	}
	
	public String getMymedID() {
		return mymedID;
	}
	public void setMymedID(String mymedID) {
		this.mymedID = mymedID;
	}
	public String getCurrentApplications() {
		return currentApplications;
	}
	public void setCurrentApplications(String currentApplications) {
		this.currentApplications = currentApplications;
	}
	public long getTimestamp() {
		return timestamp;
	}
	public void setTimestamp(long timestamp) {
		this.timestamp = timestamp;
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
