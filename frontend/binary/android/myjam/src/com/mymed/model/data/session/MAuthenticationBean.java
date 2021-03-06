/*
 * Copyright 2012 INRIA Licensed under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law
 * or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 */
package com.mymed.model.data.session;

import com.mymed.model.data.AbstractMBean;
import com.mymed.model.data.user.MUserBean;

/**
 * The authentication bean.
 * <p>
 * Used to store information about the log in user session.
 * 
 * @author lvanni
 */
public final class MAuthenticationBean extends AbstractMBean {
    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = 2486223705223910449L;

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

    /**
     * Create a new {@link MAuthenticationBean}
     */
    public MAuthenticationBean() {
        super();
    }

    /**
     * Copy constructor to implement a sane clone method
     * 
     * @param toClone
     *            the bean to clone
     */
    protected MAuthenticationBean(final MAuthenticationBean toClone) {
        super();

        login = toClone.getLogin();
        user = toClone.getUser();
        password = toClone.getPassword();
    }

    /*
     * (non-Javadoc)
     * @see java.lang.Object#clone()
     */
    @Override
    public MAuthenticationBean clone() {
        return new MAuthenticationBean(this);
    }

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
     * @see java.lang.Object#hashCode()
     */
    @Override
    public int hashCode() {
        int result = 1;
        result = (PRIME * result) + (login == null ? 0 : login.hashCode());
        result = (PRIME * result) + (password == null ? 0 : password.hashCode());
        result = (PRIME * result) + (user == null ? 0 : user.hashCode());
        return result;
    }

    /*
     * (non-Javadoc)
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

            if (((login == null) && (comparable.getLogin() != null))
                            || ((login != null) && (comparable.getLogin() == null))) {
                equal &= false;
            } else if ((login != null) && (comparable.getLogin() != null)) {
                equal &= login.equals(comparable.getLogin());
            }

            if (((password == null) && (comparable.getPassword() != null))
                            || ((password != null) && (comparable.getPassword() == null))) {
                equal &= false;
            } else if ((password != null) && (comparable.getPassword() != null)) {
                equal &= password.equals(comparable.getPassword());
            }

            if (((user == null) && (comparable.getUser() != null))
                            || ((user != null) && (comparable.getUser() == null))) {
                equal &= false;
            } else if ((user != null) && (comparable.getUser() != null)) {
                equal &= user.equals(comparable.getUser());
            }
        }

        return equal;
    }
}
