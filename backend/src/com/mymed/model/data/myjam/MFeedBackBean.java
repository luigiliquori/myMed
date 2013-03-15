package com.mymed.model.data.myjam;

import java.io.Serializable;

import com.mymed.model.data.AbstractMBean;

/**
 * Contains the feedback for the reports.
 * @author iacopo
 *
 */
public class MFeedBackBean extends AbstractMBean implements Serializable{
	/**
	 * Identifier used for serialization purposes.
	 */
	private static final long serialVersionUID = 3889004040399952200L;

	private String userId = null;
	private Integer value = null;
	//TODO Not used at the moment.
	private Integer timestamp = null;

	public void setValue(Integer value) {
		this.value = value;
	}
	public Integer getValue() {
		return value;
	}
	public void setUserId(String userId) {
		this.userId = userId;
	}
	public String getUserId() {
		return userId;
	}
	public Integer getTimestamp() {
		return timestamp;
	}
	public void setTimestamp(Integer timestamp) {
		this.timestamp = timestamp;
	}
	
	@Override
	public int hashCode() {
		// TODO Auto-generated method stub
		return 0;
	}
	@Override
	public boolean equals(Object obj) {
		// TODO Auto-generated method stub
		return false;
	}

}
