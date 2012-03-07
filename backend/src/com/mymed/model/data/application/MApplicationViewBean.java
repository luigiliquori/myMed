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

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    int result = 1;
    result = PRIME * result + (administrator == null ? 0 : administrator.hashCode());
    result = PRIME * result + (background == null ? 0 : background.hashCode());
    result = PRIME * result + (category == null ? 0 : category.hashCode());
    result = PRIME * result + (controller == null ? 0 : controller.hashCode());
    result = PRIME * result + (int) (dateOfCreation ^ dateOfCreation >>> 32);
    result = PRIME * result + (description == null ? 0 : description.hashCode());
    result = PRIME * result + (icon == null ? 0 : icon.hashCode());
    result = PRIME * result + (model == null ? 0 : model.hashCode());
    result = PRIME * result + (name == null ? 0 : name.hashCode());
    result = PRIME * result + (ontologies == null ? 0 : ontologies.hashCode());
    result = PRIME * result + (publisherList == null ? 0 : publisherList.hashCode());
    result = PRIME * result + (subscriberList == null ? 0 : subscriberList.hashCode());
    return result;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#equals(java.lang.Object)
   */
  @Override
  public boolean equals(final Object obj) {
    if (this == obj) {
      return true;
    }
    if (obj == null) {
      return false;
    }
    if (!(obj instanceof MApplicationViewBean)) {
      return false;
    }
    final MApplicationViewBean other = (MApplicationViewBean) obj;
    if (administrator == null) {
      if (other.administrator != null) {
        return false;
      }
    } else if (!administrator.equals(other.administrator)) {
      return false;
    }
    if (background == null) {
      if (other.background != null) {
        return false;
      }
    } else if (!background.equals(other.background)) {
      return false;
    }
    if (category == null) {
      if (other.category != null) {
        return false;
      }
    } else if (!category.equals(other.category)) {
      return false;
    }
    if (controller == null) {
      if (other.controller != null) {
        return false;
      }
    } else if (!controller.equals(other.controller)) {
      return false;
    }
    if (dateOfCreation != other.dateOfCreation) {
      return false;
    }
    if (description == null) {
      if (other.description != null) {
        return false;
      }
    } else if (!description.equals(other.description)) {
      return false;
    }
    if (icon == null) {
      if (other.icon != null) {
        return false;
      }
    } else if (!icon.equals(other.icon)) {
      return false;
    }
    if (model == null) {
      if (other.model != null) {
        return false;
      }
    } else if (!model.equals(other.model)) {
      return false;
    }
    if (name == null) {
      if (other.name != null) {
        return false;
      }
    } else if (!name.equals(other.name)) {
      return false;
    }
    if (ontologies == null) {
      if (other.ontologies != null) {
        return false;
      }
    } else if (!ontologies.equals(other.ontologies)) {
      return false;
    }
    if (publisherList == null) {
      if (other.publisherList != null) {
        return false;
      }
    } else if (!publisherList.equals(other.publisherList)) {
      return false;
    }
    if (subscriberList == null) {
      if (other.subscriberList != null) {
        return false;
      }
    } else if (!subscriberList.equals(other.subscriberList)) {
      return false;
    }
    return true;
  }
}
