package com.mymed.model.data;

/**
 * 
 * @author lvanni
 * 
 */
public class MInteractionBean extends AbstractMBean {

	private String interactionID;
	private String applicationID;
	private String producerID;
	private String consumerID;
	// The interaction list ID
	private String complexInteractionID;
	private double feedback = 0;
	private long start;
	private long end;
	private int snooze = 0;

	@Override
	public String toString() {
		return "Interaction:\n" + super.toString();
	}

	/**
	 * @return the interactionID
	 */
	public String getInteractionID() {
		return interactionID;
	}

	/**
	 * @param interactionID
	 *            the interactionID to set
	 */
	public void setInteractionID(final String interactionID) {
		this.interactionID = interactionID;
	}

	/**
	 * @return the applicationID
	 */
	public String getApplicationID() {
		return applicationID;
	}

	/**
	 * @param applicationID
	 *            the applicationID to set
	 */
	public void setApplicationID(final String applicationID) {
		this.applicationID = applicationID;
	}

	/**
	 * @return the producerID
	 */
	public String getProducerID() {
		return producerID;
	}

	/**
	 * @param producerID
	 *            the producerID to set
	 */
	public void setProducerID(final String producerID) {
		this.producerID = producerID;
	}

	/**
	 * @return the consumerID
	 */
	public String getConsumerID() {
		return consumerID;
	}

	/**
	 * @param consumerID
	 *            the consumerID to set
	 */
	public void setConsumerID(final String consumerID) {
		this.consumerID = consumerID;
	}

	/**
	 * @return the complexInteractionID
	 */
	public String getComplexInteractionID() {
		return complexInteractionID;
	}

	/**
	 * @param complexInteractionID
	 *            the complexInteractionID to set
	 */
	public void setComplexInteractionID(final String complexInteractionID) {
		this.complexInteractionID = complexInteractionID;
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
}
