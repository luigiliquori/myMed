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
package com.mymed.model.data.interaction;

import com.mymed.model.data.AbstractMBean;

/**
 * 
 * @author lvanni
 * 
 */
public final class MInteractionBean extends AbstractMBean {
	
	/* INTERACTION_ID */
	private String id;
	
	/* APPLICATION_ID */
	private String application;
	
	/* USER_ID */
	private String producer;
	
	/* USER_ID */
	private String consumer;
	
	private String predicate;
	private long start;
	private long end;
	private double feedback = -1;
	private String state;
	private int snooze;
	
	/* INTERACTION_LIST_ID */
	private String complexInteraction;
	
	/* Interaction State */
	public static final String PENDING_STATE = "pending";
	public static final String COMPLETED_STATE = "completed";

	/**
	 * Copy constructor.
	 * <p>
	 * Provide a clone of the passed MInteractionBean
	 * 
	 * @param toClone
	 *          the interaction bean to clone
	 */
	protected MInteractionBean(final MInteractionBean toClone) {
		super();

		id = toClone.getId();
		application = toClone.getApplication();
		producer = toClone.getProducer();
		consumer = toClone.getConsumer();
		start = toClone.getStart();
		end = toClone.getEnd();
		feedback = toClone.getFeedback();
		snooze = toClone.getSnooze();
		complexInteraction = toClone.getComplexInteraction();
	}

	/**
	 * Create a new empty MInteractionBean
	 */
	public MInteractionBean() {
		// Empty constructor, needed because of the copy constructor
		super();
	}

	@Override
	public MInteractionBean clone() {
		return new MInteractionBean(this);
	}

	@Override
	public String toString() {
		return "Interaction:\n" + super.toString();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		int result = 1;
		result = PRIME * result + (application == null ? 0 : application.hashCode());
		result = PRIME * result + (complexInteraction == null ? 0 : complexInteraction.hashCode());
		result = PRIME * result + (consumer == null ? 0 : consumer.hashCode());
		long temp;
		temp = Double.doubleToLongBits(feedback);
		result = PRIME * result + (int) (temp ^ temp >>> 32);
		result = PRIME * result + (id == null ? 0 : id.hashCode());
		result = PRIME * result + (producer == null ? 0 : producer.hashCode());
		return result;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#equals(java.lang.Object)
	 */
	@Override
	public boolean equals(final Object obj) {

		boolean equal = false;

		if (this == obj) {
			equal = true;
		} else if (obj instanceof MInteractionBean) {
			final MInteractionBean comparable = (MInteractionBean) obj;

			equal = true;

			if (application == null && comparable.getApplication() != null) {
				equal &= false;
			} else if (application != null && comparable.getApplication() != null) {
				equal &= application.equals(comparable.getApplication());
			}

			if (complexInteraction == null && comparable.getComplexInteraction() != null) {
				equal &= false;
			} else if (complexInteraction != null && comparable.getComplexInteraction() != null) {
				equal &= complexInteraction.equals(comparable.getComplexInteraction());
			}

			if (consumer == null && comparable.getConsumer() != null) {
				equal &= false;
			} else if (consumer != null && comparable.getConsumer() != null) {
				equal &= consumer.equals(comparable.getConsumer());
			}

			equal &= feedback == comparable.getFeedback();

			if (id == null && comparable.getId() != null) {
				equal &= false;
			} else if (id != null && comparable.getId() != null) {
				equal &= id.equals(comparable.getId());
			}

			if (producer == null && comparable.getProducer() != null) {
				equal &= false;
			} else if (producer != null && comparable.getProducer() != null) {
				equal &= producer.equals(comparable.getProducer());
			}
		}

		return equal;
	}

	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	/**
	 * @return the feedback associated with this interaction
	 */
	public double getFeedback() {
		return feedback;
	}

	/**
	 * @param feedback
	 *          the feedback to set for this interaction
	 */
	public void setFeedback(final double feedback) {
		this.feedback = feedback;
	}

	/**
	 * @return the start date of the interaction
	 */
	public long getStart() {
		return start;
	}

	/**
	 * @param start
	 *          the start date of the interaction
	 */
	public void setStart(final long start) {
		this.start = start;
	}

	/**
	 * @return the id that identifies the interaction
	 */
	public String getId() {
		return id;
	}

	/**
	 * @param id
	 *          the id to set for this interaction
	 */
	public void setId(final String id) {
		this.id = id;
	}

	/**
	 * @return the application used for the interaction
	 */
	public String getApplication() {
		return application;
	}

	/**
	 * @param application
	 *          the application used for the interaction
	 */
	public void setApplication(final String application) {
		this.application = application;
	}

	/**
	 * @return the producer who provided the interaction
	 */
	public String getProducer() {
		return producer;
	}

	/**
	 * @param producer
	 *          the producer who provides the interaction
	 */
	public void setProducer(final String producer) {
		this.producer = producer;
	}

	/**
	 * @return the consumer who used the interaction
	 */
	public String getConsumer() {
		return consumer;
	}

	/**
	 * @param consumer
	 *          the consumer who uses the interaction
	 */
	public void setConsumer(final String consumer) {
		this.consumer = consumer;
	}

	/**
	 * @return the end date of the interaction
	 */
	public long getEnd() {
		return end;
	}

	/**
	 * @param end
	 *          the end date of the interaction
	 */
	public void setEnd(final long end) {
		this.end = end;
	}

	/**
	 * @return the reminder of the interaction
	 */
	public int getSnooze() {
		return snooze;
	}

	/**
	 * @param snooze
	 *          the reminder for the interaction
	 */
	public void setSnooze(final int snooze) {
		this.snooze = snooze;
	}

	/**
	 * @return the complexInteraction
	 */
	public String getComplexInteraction() {
		return complexInteraction;
	}

	/**
	 * @param complexInteraction
	 *          the complexInteraction to set
	 */
	public void setComplexInteraction(final String complexInteraction) {
		this.complexInteraction = complexInteraction;
	}

	/**
	 * return the concatenated predicate
	 * @return predicate
	 */
	public String getPredicate() {
		return predicate;
	}
	
	/**
	 * Add the predicate to the interaction
	 * @param predicate
	 */
	public void setPredicate(String predicate) {
		this.predicate = predicate;
	}
	
	/**
	 * return the status of the Interaction (pending or completed)
	 * @return
	 */
	public String getState() {
		return state;
	}
	
	/**
	 * set the status of the interaction (pending or completed)
	 * @param status
	 */
	public void setState(String state) {
		this.state = state;
	}

}
