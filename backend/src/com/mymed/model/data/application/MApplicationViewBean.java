package com.mymed.model.data.application;

import com.mymed.model.data.AbstractMBean;

/**
 * This class represent an user profile
 * 
 * @author lvanni
 */
public final class MApplicationViewBean extends AbstractMBean {

	private String name;
	/** ID_CATEGORY */
	private String category;
	private long dateOfCreation;
	private String description;
	private String icon;
	private String background;
	/** USER_LIST_ID */
	private String publisherList;
	/** USER_LIST_ID */
	private String subscriberList;
	/** USER_ID */
	private String administrator;
	/** ONTOLOGY_LIST_ID */
	private String ontologies;
	/** APPLICATION_MODEL_ID */
	private String model;
	/** APPLICATION_CONTROLLER_ID */
	private String controller;

	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	@Override
	public boolean equals(final Object object) {
		final boolean returnValue = true;
		// TODO
		return returnValue;
	}


	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	public String getName() {
		return name;
	}

	public void setName(final String name) {
		this.name = name;
	}

	public String getCategory() {
		return category;
	}

	public void setCategory(final String category) {
		this.category = category;
	}

	public long getDateOfCreation() {
		return dateOfCreation;
	}

	public void setDateOfCreation(final long dateOfCreation) {
		this.dateOfCreation = dateOfCreation;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(final String description) {
		this.description = description;
	}

	public String getIcon() {
		return icon;
	}

	public void setIcon(final String icon) {
		this.icon = icon;
	}

	public String getBackground() {
		return background;
	}

	public void setBackground(final String background) {
		this.background = background;
	}

	public String getPublisherList() {
		return publisherList;
	}

	public void setPublisherList(final String publisherList) {
		this.publisherList = publisherList;
	}

	public String getSubscriberList() {
		return subscriberList;
	}

	public void setSubscriberList(final String subscriberList) {
		this.subscriberList = subscriberList;
	}

	public String getAdministrator() {
		return administrator;
	}

	public void setAdministrator(final String administrator) {
		this.administrator = administrator;
	}

	public String getOntologies() {
		return ontologies;
	}

	public void setOntologies(final String ontologies) {
		this.ontologies = ontologies;
	}

	public String getModel() {
		return model;
	}

	public void setModel(final String model) {
		this.model = model;
	}

	public String getController() {
		return controller;
	}

	public void setController(final String controller) {
		this.controller = controller;
	}
}
