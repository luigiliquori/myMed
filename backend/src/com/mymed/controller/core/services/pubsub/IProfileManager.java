package com.mymed.controller.core.services.pubsub;

import com.mymed.model.datastructure.User;

/**
 * Manage the profile of an user
 * @author lvanni
 *
 */
public interface IProfileManager {
	
	/**
	 * Setup a new user profile into the database
	 * @param user
	 * 			the user to insert into the database
	 */
	public void create(User user);
	
	/**
	 * @param id
	 *            the id of the user
	 * @return the User corresponding to the id
	 */
	public User read(String id);

	/**
	 * Update the profile of an user into the database
	 * @param user
	 * 		The profile updated to store
	 */
	public void update(User user);
	
	/**
	 * Delete an existing user
	 * @param user
	 * 		The user to delete
	 */
	public void delete(User user);
	
	/**
	 * Mymed authentication
	 */
	public void login();
}
