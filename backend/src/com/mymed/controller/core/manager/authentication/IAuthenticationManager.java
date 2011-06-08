package com.mymed.controller.core.manager.authentication;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.MAuthenticationBean;
import com.mymed.model.data.MUserBean;

public interface IAuthenticationManager {
	
	// create myMed user profile
	public MUserBean create(MUserBean user, MAuthenticationBean authentication) throws InternalBackEndException;
	
	// authentication
	public MUserBean read(String login, String password) throws InternalBackEndException;
	
	// update myMed user profile : Authentication Table (password)
	public void update(MAuthenticationBean authentication) throws InternalBackEndException;
}
