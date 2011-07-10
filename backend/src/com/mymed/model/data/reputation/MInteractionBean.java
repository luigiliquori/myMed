package com.mymed.model.data.reputation;

import com.mymed.model.data.AbstractMBean;

/**
 * 
 * @author lvanni
 *
 */
public class MInteractionBean extends AbstractMBean {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** INTERACTION_ID */
	private String id;
	/** APPLICATION_ID */
	private String application;
	/** USER_ID */
	private String producer;
	/** USER_ID */
	private String consumer;
	private long start;
	private long end;
	private double feedback;
	private int snooze;
	/** INTERACTION_LIST_ID */
	private String complexInteraction;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public MInteractionBean() {
		// TODO Auto-generated constructor stub
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	@Override
	public String toString() {
		return "Interaction:\n" + super.toString();
	}

	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}

	public String getApplication() {
		return application;
	}

	public void setApplication(String application) {
		this.application = application;
	}

	public String getProducer() {
		return producer;
	}

	public void setProducer(String producer) {
		this.producer = producer;
	}

	public String getConsumer() {
		return consumer;
	}

	public void setConsumer(String consumer) {
		this.consumer = consumer;
	}

	public long getStart() {
		return start;
	}

	public void setStart(long start) {
		this.start = start;
	}

	public long getEnd() {
		return end;
	}

	public void setEnd(long end) {
		this.end = end;
	}

	public double getFeedback() {
		return feedback;
	}

	public void setFeedback(double feedback) {
		this.feedback = feedback;
	}

	public int getSnooze() {
		return snooze;
	}

	public void setSnooze(int snooze) {
		this.snooze = snooze;
	}

	public String getComplexInteraction() {
		return complexInteraction;
	}

	public void setComplexInteraction(String complexInteraction) {
		this.complexInteraction = complexInteraction;
	}
}
