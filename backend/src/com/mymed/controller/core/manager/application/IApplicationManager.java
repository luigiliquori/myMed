package com.mymed.controller.core.manager.application;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.user.MUserBean;

/**
 * Manage the profile of an user
 * 
 * @author lvanni
 * 
 */
public interface IApplicationManager {

	void create(MUserBean user) throws InternalBackEndException, IOBackEndException;

	MUserBean read(String id) throws InternalBackEndException, IOBackEndException;

	void update(MUserBean user) throws InternalBackEndException, IOBackEndException;

	void delete(String id) throws InternalBackEndException;
}
