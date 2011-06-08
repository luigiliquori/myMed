package com.mymed.controller.core.manager.authentication;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.MAuthenticationBean;
import com.mymed.model.data.MUserBean;

public class AuthenticationManager implements IAuthenticationManager{

	@Override
	public MUserBean create(MUserBean user, MAuthenticationBean authentication)
			throws InternalBackEndException {
		// TODO Auto-generated method stub
		// CALL ProfileManager.create(MUserBean user);
		return null;
	}

	@Override
	public MUserBean read(String login, String password)
			throws InternalBackEndException {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public void update(MAuthenticationBean authentication)
			throws InternalBackEndException {
		// TODO Auto-generated method stub
		
	}

}
