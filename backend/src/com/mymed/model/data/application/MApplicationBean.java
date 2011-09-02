package com.mymed.model.data.application;

import com.mymed.model.data.AbstractMBean;

/**
 * This class represent a Mymed application
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public final class MApplicationBean extends AbstractMBean {

	// Used for the calculation of the hashCode()
	private static final int PRIME = 31;

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

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		int result = 1;
		result = PRIME * result + (controller == null ? 0 : controller.hashCode());
		result = PRIME * result + (model == null ? 0 : model.hashCode());
		result = PRIME * result + (view == null ? 0 : view.hashCode());
		return result;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#equals()
	 */
	@Override
	public boolean equals(final Object object) {
		boolean equal = false;

		if (this == object) {
			equal = true;
		} else if (object instanceof MApplicationBean) {
			final MApplicationBean comparable = (MApplicationBean) object;
			equal = true;

			if (model == null && comparable.getModel() != null) {
				equal &= false;
			} else {
				equal &= model.equals(comparable.getModel());
			}

			if (view == null && comparable.getView() != null) {
				equal &= false;
			} else {
				equal &= view.equals(comparable.getView());
			}

			if (controller == null && comparable.getController() != null) {
				equal &= false;
			} else {
				equal &= controller.equals(comparable.getController());
			}
		}

		return equal;
	}
}
