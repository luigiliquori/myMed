package com.mymed.android.myjam.type;


/**TODO
 * 
 * @author iacopo
 *
 */
public class MFeedBackBean implements IMyJamType{
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
	// TODO Auto-generated method stub
	
}
}
