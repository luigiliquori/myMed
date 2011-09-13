package com.mymed.controller.core.manager.buddy;

import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.InternalBackEndException;

public interface IBuddiesManager {

	/**
	 * read the list
	 * 
	 * @param mymedID
	 * @return
	 * @throws InternalBackEndException
	 */
	List<Map<String, String>> read(String mymedID) throws InternalBackEndException;

	/**
	 * add new buddy
	 * 
	 * @param mymedID
	 * @param buddyID
	 * @throws InternalBackEndException
	 */
	void update(String mymedID, String buddyID) throws InternalBackEndException;

	/**
	 * remove a buddy from the buddyList
	 * 
	 * @param mymedID
	 * @param buddyID
	 * @throws InternalBackEndException
	 */
	void delete(String mymedID, String buddyID) throws InternalBackEndException;
}
