package com.mymed.controller.core.services;

import com.mymed.model.core.wrapper.Wrapper;
import com.mymed.model.datastructure.User;

/**
 * Manage all the request from the RequestHandler
 * @author lvanni
 *
 */
public class ServiceManager {
	
	/** DAO pattern */
	Wrapper wrapper;
	
	/**
	 * no-args constructor
	 */
	public ServiceManager() {
		this.wrapper = new Wrapper();
	}
	
	/**
	 * @see com.mymed.model.core.wrapper.Wrapper#getProfile(id)
	 * @param user
	 */
	public User getProfile(String id){
		return wrapper.getProfile(id);
	}
	
	/**
	 * @see com.mymed.model.core.wrapper.Wrapper#setProfile(User)
	 * @param user
	 */
	public void setProfile(User user) {
		wrapper.setProfile(user);
	}
}
