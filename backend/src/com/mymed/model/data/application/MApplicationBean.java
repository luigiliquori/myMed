package com.mymed.model.data.application;

import com.mymed.model.data.AbstractMBean;

/**
 * This class represent a Mymed application
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public final class MApplicationBean extends AbstractMBean {

	/**
	 * The APPLICATION_MODEL_ID
	 */
	private String model;

	/**
	 * The APPLICATION_VIEW_ID
	 */
	private String view;

	/**
	 * The APPLICATION_CONTROLLER_ID
	 */
	private String controller;

	/**
	 * @return the APPLICATION_MODEL_ID
	 */
	public String getModel() {
		return model;
	}

	/**
	 * @param model
	 *            the APPLICATION_MODEL_ID to set
	 */
	public void setModel(final String model) {
		this.model = model;
	}

	/**
	 * @return the APPLICATION_VIEW_ID
	 */
	public String getView() {
		return view;
	}

	/**
	 * @param view
	 *            the APPLICATION_VIEW_ID to set
	 */
	public void setView(final String view) {
		this.view = view;
	}

	/**
	 * @return the APPLICATION_CONTROLLER_ID
	 */
	public String getController() {
		return controller;
	}

	/**
	 * @param controller
	 *            the APPLICATION_CONTROLLER_ID to set
	 */
	public void setController(final String controller) {
		this.controller = controller;
	}

	@Override
	public boolean equals(final Object object) {
		final boolean returnValue = true;
		// TODO
		return returnValue;
	}

}
