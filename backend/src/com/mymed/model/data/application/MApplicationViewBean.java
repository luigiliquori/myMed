package com.mymed.model.data.application;

import com.mymed.model.data.AbstractMBean;

/**
 * This class represent an user profile
 * 
 * @author lvanni
 */
public final class MApplicationViewBean extends AbstractMBean {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private String  name;
	/** ID_CATEGORY */
	private String 	category;
	private long	dateOfCreation;
	private String	description;
	private String	icon;
	private String	background;
	/** USER_LIST_ID */
	private String	publisherList;
	/** USER_LIST_ID */
	private String	subscriberList;
	/** USER_ID */
	private String	administrator;
	/** ONTOLOGY_LIST_ID */
	private String	ontologies;
	/** APPLICATION_MODEL_ID */
	private String	model;
	/** APPLICATION_CONTROLLER_ID */
	private String	controller;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public MApplicationViewBean() {
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

	public String getCategory() {
		return category;
	}

	public void setCategory(String category) {
		this.category = category;
	}

	public long getDateOfCreation() {
		return dateOfCreation;
	}

	public void setDateOfCreation(long dateOfCreation) {
		this.dateOfCreation = dateOfCreation;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getIcon() {
		return icon;
	}

	public void setIcon(String icon) {
		this.icon = icon;
	}

	public String getBackground() {
		return background;
	}

	public void setBackground(String background) {
		this.background = background;
	}

	public String getPublisherList() {
		return publisherList;
	}

	public void setPublisherList(String publisherList) {
		this.publisherList = publisherList;
	}

	public String getSubscriberList() {
		return subscriberList;
	}

	public void setSubscriberList(String subscriberList) {
		this.subscriberList = subscriberList;
	}

	public String getAdministrator() {
		return administrator;
	}

	public void setAdministrator(String administrator) {
		this.administrator = administrator;
	}

	public String getOntologies() {
		return ontologies;
	}

	public void setOntologies(String ontologies) {
		this.ontologies = ontologies;
	}

	public String getModel() {
		return model;
	}

	public void setModel(String model) {
		this.model = model;
	}

	public String getController() {
		return controller;
	}

	public void setController(String controller) {
		this.controller = controller;
	}
}
