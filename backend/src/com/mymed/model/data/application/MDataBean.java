package com.mymed.model.data.application;

import com.mymed.model.data.AbstractMBean;

/**
 * This class represent a Mymed application
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public final class MDataBean extends AbstractMBean {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private String key;
	private String value;
	private String ontologyID;


	/* --------------------------------------------------------- */
	/* extends AbstractMBean */
	/* --------------------------------------------------------- */
	@Override
	public boolean equals(final Object object) {
		final boolean returnValue = true;
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
	public String getKey() {
		return key;
	}

	public void setKey(String key) {
		this.key = key;
	}
	
	public String getValue() {
		return value;
	}

	public void setValue(String value) {
		this.value = value;
	}

	public String getOntologyID() {
		return ontologyID;
	}

	public void setOntologyID(String ontologyID) {
		this.ontologyID = ontologyID;
	}
}
