package com.mymed.myjam.type;

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
	private Integer grade = null;
	private Integer timestamp = null;

	public void setGrade(Integer grade) {
		this.grade = grade;
	}
	public Integer getGrade() {
		return grade;
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

}
