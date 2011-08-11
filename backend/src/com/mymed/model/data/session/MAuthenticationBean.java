package com.mymed.model.data.session;

import com.mymed.model.data.AbstractMBean;
import com.mymed.model.data.user.MUserBean;

/**
 * The authentication bean.
 * <p>
 * Used to store information about the log in user session.
 * 
 * @author lvanni
 * 
 */
public class MAuthenticationBean extends AbstractMBean {

	/** AUTHENTICATION_ID */
	private String login;
	/** USER_ID */
	private String user;
	/** sha256(string) */
	private String password;

	@Override
	public String toString() {
		return "Autentication:\n" + super.toString();
	}

	/**
	 * @return the AUTHENTICATION_ID
	 */
	public String getLogin() {
		return login;
	}

	/**
	 * @param login
	 *            the AUTHENTICATION_ID
	 */
	public void setLogin(final String login) {
		this.login = login;
	}

	/**
	 * @return the ID of the authenticated user
	 */
	public String getUser() {
		return user;
	}

	/**
	 * @param user
	 *            the ID of the user from {@link MUserBean}
	 */
	public void setUser(final String user) {
		this.user = user;
	}

	/**
	 * @return the password SHA-256'ed
	 */
	public String getPassword() {
		return password;
	}

	/**
	 * @param password
	 *            the SHA-256 conversion of the password
	 */
	public void setPassword(final String password) {
		this.password = password;
	}
}
