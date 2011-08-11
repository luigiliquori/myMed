package com.mymed.android.myjam.type;

/**
 * Class used to exchange located reports, without details. 
 * @author iacopo
 *
 */
public class MSearchReportBean extends AbstractMBean{
	private String reportId;
	private Integer latitude;
	private Integer longitude;
	private String reportType;
	private Integer distance;
	private Long date;
	
public MSearchReportBean(){};

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

public long getDate() {
	return date;
}

public void setDate(Long date) {
	this.date = date;
}

public Integer getDistance() {
	return distance;
}

public void setDistance(Integer distance) {
	this.distance = distance;
}

}

