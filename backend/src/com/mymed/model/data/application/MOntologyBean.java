/*
 * Copyright 2012 INRIA 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
package com.mymed.model.data.application;

import com.mymed.model.data.AbstractMBean;

/**
 * This class represent an user profile
 * 
 * @author lvanni
 */
public final class MOntologyBean extends AbstractMBean {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private String name;
	private String type;
	private boolean isPredicate;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public MOntologyBean() {
		// TODO Auto-generated constructor stub
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	@Override
	public boolean equals(final Object object) {
		boolean returnValue = true;
		// TODO
		return returnValue;
	}

	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}

	public boolean isPredicate() {
		return isPredicate;
	}

	public void setPredicate(boolean isPredicate) {
		this.isPredicate = isPredicate;
	}
}
