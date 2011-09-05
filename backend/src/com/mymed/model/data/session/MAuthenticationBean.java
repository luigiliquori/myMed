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
	public boolean equals(final Object object) {

		boolean equal = false;

		if (this == object) {
			equal = true;
		} else if (object instanceof MAuthenticationBean) {
			final MAuthenticationBean comparable = (MAuthenticationBean) object;

			equal = true;

			if (login == null && comparable.getLogin() != null) {
				equal &= false;
			} else {
				equal &= login.equals(comparable.getLogin());
			}

			if (password == null && comparable.getPassword() != null) {
				equal &= false;
			} else {
				equal &= password.equals(comparable.getPassword());
			}

			if (user == null && comparable.getUser() != null) {
				equal &= false;
			} else {
				equal &= user.equals(comparable.getUser());
			}
		}

		return equal;
	}
}
