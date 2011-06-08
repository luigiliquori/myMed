package com.mymed.controller.core.manager.buddy;

import java.util.List;
import java.util.Map;

import com.mymed.controller.core.exception.InternalBackEndException;

public interface IBuddyManager {

	// read the list
	/*
	 * buddyList = [{ "name" : String, "mymedID" : "ID"}]
	 */
	public List<Map<String, String>> read(String mymedID) throws InternalBackEndException;
	
	// add new buddy
	public void update(String mymedID, String buddyID) throws InternalBackEndException;
	
	// remove a buddy from the buddyList
	public void delete(String mymedID, String buddyID) throws InternalBackEndException;
}
