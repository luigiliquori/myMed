package com.mymed.android.myjam.type;

/**
 * Class used to exchange located reports, without details. 
 * @author iacopo
 *
 */
public class MShortReportBean extends AbstractMBean{
	private String reportId;
	private Integer latitude;
	private Integer longitude;
	private String reportType;
	
public MShortReportBean(){};

/**Getter and Setter*/
public void setReportId(String reportId) {
	this.reportId = reportId;
}

public String getReportId() {
	return reportId;
}

public void setLatitude(Integer latitude) {
	this.latitude = latitude;
}

public Integer getLatitude() {
	return latitude;
}

public void setLongitude(Integer longitude) {
	this.longitude = longitude;
}

public Integer getLongitude() {
	return longitude;
}

public void setReportType(String reportType) {
	this.reportType = reportType;
}

public String getReportType() {
	return reportType;
}

}

