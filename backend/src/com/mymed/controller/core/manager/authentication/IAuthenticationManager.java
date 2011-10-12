package com.mymed.controller.core.manager.authentication;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;

public interface IAuthenticationManager {

	/**
	 * create myMed user profile
	 * 
	 * @param user
	 * @param authentication
	 * @return
	 * @throws InternalBackEndException
	 */
	MUserBean create(MUserBean user, MAuthenticationBean authentication) throws InternalBackEndException,
	        IOBackEndException;

	/**
	 * authentication
	 * 
	 * @param login
	 * @param password
	 * @return
	 * @throws InternalBackEndException
	 */
	MUserBean read(String login, String password) throws InternalBackEndException, IOBackEndException;

	/**
	 * update myMed user profile : Authentication Table (password)
	 * 
	 * @param authentication
	 * @throws InternalBackEndException
	 */
	void update(String id, MAuthenticationBean authentication) throws InternalBackEndException, IOBackEndException;
}
