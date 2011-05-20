package com.mymed.controller.core.services.manager.pubsub;

import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;
import com.mymed.model.data.MUserBean;

/**
 * Manage the profile of an user
 * 
 * @author lvanni
 * 
 */
public interface IProfileManager {

	/**
	 * Setup a new user profile into the database
	 * 
	 * @param user
	 *            the user to insert into the database
	 * @return true if the user if the user is created, false otherwise
	 */
	public MUserBean create(MUserBean user) throws InternalBackEndException;

	/**
	 * @param id
	 *            the id of the user
	 * @return the User corresponding to the id
	 */
	public MUserBean read(String id) throws InternalBackEndException, IOBackEndException;

	/**
	 * Update the profile of an user into the database
	 * 
	 * @param user
	 *            The profile updated to store
	 */
	public void update(MUserBean user) throws InternalBackEndException;

	/**
	 * Delete an existing user
	 * 
	 * @param user
	 *            The user to delete
	 */
	public void delete(String key) throws InternalBackEndException;
}
