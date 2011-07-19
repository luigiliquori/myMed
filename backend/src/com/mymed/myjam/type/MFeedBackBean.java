package com.mymed.myjam.type;


/**
 * Contains the feedback for the reports.
 * @author iacopo
 *
 */
public class MFeedBackBean implements IMyJamType{
private final static  int maxGrade = 10;
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
@Override
public void validate() throws WrongFormatException {
	if (grade==null)
		throw new WrongFormatException(" Grade not set. ");
	if (grade<0 || grade>maxGrade)
		throw new WrongFormatException(" Grade out of bound. ");
}

}
