package com.mymed.model.data.storage;

/**
 * Bean that stores an expiring column.
 * 
 * @author iacopo
 *
 */
public class ExpColumnBean{
	private byte[] value;
	private long timestamp;
	private int timeToLive;
	
	public void setValue(byte[] value) {
		this.value = value;
	}
	public byte[] getValue() {
		return value;
	}
	public void setTimestamp(long timestamp) {
		this.timestamp = timestamp;
	}
	public long getTimestamp() {
		return timestamp;
	}
	public void setTimeToLive(int timeToLive) {
		this.timeToLive = timeToLive;
	}
	public int getTimeToLive() {
		return timeToLive;
	}
}