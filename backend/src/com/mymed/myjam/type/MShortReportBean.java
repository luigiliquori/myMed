package com.mymed.myjam.type;

import com.mymed.model.data.AbstractMBean;
import com.mymed.myjam.type.MyJamTypes.ReportType;


/**
 * Class used to exchange located reports, without details. 
 * @author iacopo
 *
 */
public class MShortReportBean extends AbstractMBean implements IMyJamType{
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



/** Validator */
@Override
public void validate() throws WrongFormatException {
	if (ReportType.valueOf(this.reportType)==null)
		throw new WrongFormatException(" Wrong report type. ");
	ReportId.parseString(reportId);
}

}

