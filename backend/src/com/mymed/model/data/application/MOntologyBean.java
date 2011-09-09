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
	
	@Override
	public void update(AbstractMBean mBean) {
		// TODO Auto-generated method stub
		
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
