package com.mymed.model.data;

public class MInteractionBean extends AbstractMBean {
	
	private String interactionID;
	private String applicationID;
	private String publisherID;
	private String subscriberID;
	private long end;
	private int snooze;
	
	public String getInteractionID() {
		return interactionID;
	}
	public void setInteractionID(String interactionID) {
		this.interactionID = interactionID;
	}
	public String getApplicationID() {
		return applicationID;
	}
	public void setApplicationID(String applicationID) {
		this.applicationID = applicationID;
	}
	public String getPublisherID() {
		return publisherID;
	}
	public void setPublisherID(String publisherID) {
		this.publisherID = publisherID;
	}
	public String getSubscriberID() {
		return subscriberID;
	}
	public void setSubscriberID(String subscriberID) {
		this.subscriberID = subscriberID;
	}
	public long getEnd() {
		return end;
	}
	public void setEnd(long end) {
		this.end = end;
	}
	public int getSnooze() {
		return snooze;
	}
	public void setSnooze(int snooze) {
		this.snooze = snooze;
	}
}
