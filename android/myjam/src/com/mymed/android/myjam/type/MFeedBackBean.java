package com.mymed.android.myjam.type;

/**
 * Contains the feedback for the reports.
 * @author iacopo
 *
 */
public class MFeedBackBean extends AbstractMBean{
private String userId = null;
private Integer grade = null;

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

}
