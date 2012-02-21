/*
 * Copyright 2012 INRIA Licensed under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law
 * or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 */
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
     * Generated serial ID.
     */
    private static final long serialVersionUID = 6239230388911379598L;

    /*
     * (non-Javadoc)
     * @see java.lang.Object#hashCode()
     */
    @Override
    public int hashCode() {
        int result = 1;
        result = (PRIME * result) + (controller == null ? 0 : controller.hashCode());
        result = (PRIME * result) + (model == null ? 0 : model.hashCode());
        result = (PRIME * result) + (view == null ? 0 : view.hashCode());
        return result;
    }

    /*
     * (non-Javadoc)
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

            if ((model == null) && (comparable.getModel() != null)) {
                equal &= false;
            } else if ((model != null) && (comparable.getModel() != null)) {
                equal &= model.equals(comparable.getModel());
            }

            if ((view == null) && (comparable.getView() != null)) {
                equal &= false;
            } else if ((view != null) && (comparable.getView() != null)) {
                equal &= view.equals(comparable.getView());
            }

            if ((controller == null) && (comparable.getController() != null)) {
                equal &= false;
            } else if ((controller != null) && (comparable.getController() != null)) {
                equal &= controller.equals(comparable.getController());
            }
        }

        return equal;
    }

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
}
