package com.mymed.model.data;

/**
 * 
 * @author lvanni
 *
 */
public class MAuthenticationBean extends AbstractMBean {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private String mymedID;
	private String login;
	private String password;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public MAuthenticationBean() {
		// TODO Auto-generated constructor stub
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	@Override
	public String toString() {
		return "Autentication:\n" + super.toString();
	}
	
	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	public String getMymedID() {
		return mymedID;
	}

	public void setMymedID(String mymedID) {
		this.mymedID = mymedID;
	}
	
	public String getLogin() {
		return login;
	}
	
	public void setLogin(String login) {
		this.login = login;
	}
	
	public String getPassword() {
		return password;
	}
	
	public void setPassword(String password) {
		this.password = password;
	}
	
	
}
