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

	/*
	 * Used for the calculation of the hashCode()
	 */
	private static final int PRIME = 31;

	/** AUTHENTICATION_ID */
	private String login = "";
	/** USER_ID */
	private String user = "";
	/** sha256(string) */
	private String password = "";

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

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		int result = 1;
		result = PRIME * result + (login == null ? 0 : login.hashCode());
		result = PRIME * result + (password == null ? 0 : password.hashCode());
		result = PRIME * result + (user == null ? 0 : user.hashCode());
		return result;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#equals(java.lang.Object)
	 */
	@Override
	public boolean equals(final Object obj) {
		if (this == obj) {
			return true;
		}
		if (obj == null) {
			return false;
		}
		if (!(obj instanceof MAuthenticationBean)) {
			return false;
		}
		final MAuthenticationBean other = (MAuthenticationBean) obj;
		if (login == null) {
			if (other.login != null) {
				return false;
			}
		} else if (!login.equals(other.login)) {
			return false;
		}
		if (password == null) {
			if (other.password != null) {
				return false;
			}
		} else if (!password.equals(other.password)) {
			return false;
		}
		if (user == null) {
			if (other.user != null) {
				return false;
			}
		} else if (!user.equals(other.user)) {
			return false;
		}
		return true;
	}
}
