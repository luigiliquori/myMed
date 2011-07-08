package com.mymed.model.data;

/**
 * 
 * @author lvanni
 * 
 */
public class MInteractionBean extends AbstractMBean {

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

	@Override
	public String toString() {
		return "Interaction:\n" + super.toString();
	}

	/**
	 * @return the feedback
	 */
	public double getFeedback() {
		return feedback;
	}

	/**
	 * @param feedback
	 *            the feedback to set
	 */
	public void setFeedback(final double feedback) {
		this.feedback = feedback;
	}

	/**
	 * @return the start
	 */
	public long getStart() {
		return start;
	}

	/**
	 * @param start
	 *            the start to set
	 */
	public void setStart(final long start) {
		this.start = start;
	}

	public String getId() {
		return id;
	}

	public void setId(final String id) {
		this.id = id;
	}

	public String getApplication() {
		return application;
	}

	public void setApplication(final String application) {
		this.application = application;
	}

	public String getProducer() {
		return producer;
	}

	public void setProducer(final String producer) {
		this.producer = producer;
	}

	public String getConsumer() {
		return consumer;
	}

	public void setConsumer(final String consumer) {
		this.consumer = consumer;
	}

	/**
	 * @return the end
	 */
	public long getEnd() {
		return end;
	}

	/**
	 * @param end
	 *            the end to set
	 */
	public void setEnd(final long end) {
		this.end = end;
	}

	/**
	 * @return the snooze
	 */
	public int getSnooze() {
		return snooze;
	}

	/**
	 * @param snooze
	 *            the snooze to set
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
	 *            the complexInteraction to set
	 */
	public void setComplexInteraction(final String complexInteraction) {
		this.complexInteraction = complexInteraction;
	}
}
