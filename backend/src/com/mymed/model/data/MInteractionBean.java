package com.mymed.model.data;

public class MInteractionBean extends AbstractMBean {

	private String interactionID;
	private String applicationID;
	private String producerID;
	private String consumerID;
	private long end;
	private int snooze;

	public String getInteractionID() {
		return interactionID;
	}
	public void setInteractionID(final String interactionID) {
		this.interactionID = interactionID;
	}
	public String getApplicationID() {
		return applicationID;
	}
	public void setApplicationID(final String applicationID) {
		this.applicationID = applicationID;
	}
	public String getProducerID() {
		return producerID;
	}
	public void setProducerID(final String producerID) {
		this.producerID = producerID;
	}
	public String getConsumerID() {
		return consumerID;
	}
	public void setConsumerID(final String consumerID) {
		this.consumerID = consumerID;
	}
	public long getEnd() {
		return end;
	}
	public void setEnd(final long end) {
		this.end = end;
	}
	public int getSnooze() {
		return snooze;
	}
	public void setSnooze(final int snooze) {
		this.snooze = snooze;
	}
}
