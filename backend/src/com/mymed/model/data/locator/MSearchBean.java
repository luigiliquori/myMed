package com.mymed.model.data.locator;

import com.mymed.model.data.AbstractMBean;

/**
 * Class used to exchange located reports, without details. 
 * @author iacopo
 *
 */
public class MSearchBean extends AbstractMBean{
	private String id;
	private Long locationId;
	private Integer latitude;
	private Integer longitude;
	private String value;
	private Integer distance;
	private Long date;
	private Long expirationDate;
	
public MSearchBean(){};

/**Getter and Setter*/
public void setId(String id) {
	this.id = id;
}

public String getId() {
	return id;
}

public Long getLocationId() {
	return locationId;
}

public void setLocationId(Long locationId) {
	this.locationId = locationId;
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

public Integer getDistance() {
	return distance;
}

public void setDistance(Integer distance) {
	this.distance = distance;
}

public void setValue(String value) {
	this.value = value;
}

public String getValue() {
	return value;
}

public long getDate() {
	return date;
}

public void setDate(Long date) {
	this.date = date;
}

public Long getExpirationDate() {
	return expirationDate;
}

public void setExpirationDate(Long expirationDate) {
	this.expirationDate = expirationDate;
}

}

