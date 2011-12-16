package com.mymed.model.data.user;

import com.mymed.model.data.AbstractMBean;

public class MPositionBean extends AbstractMBean {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** == Position ID*/
	private String userID;
	/** GPS information */
	private String latitude;
	private String longitude;
	
	/** Adress information  */
	private String city;
	private String zipCode;
	private String country;
	private String formattedAddress;
	
	

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Create a new empty MPositionBean
	 */
	public MPositionBean() {
		// Empty constructor, needed because of the copy constructor
		super();
	}

	/**
	 * Copy constructor.
	 * <p>
	 * Provide a clone of the passed MPositionBean
	 * 
	 * @param toClone
	 *            the position bean to clone
	 */
	protected MPositionBean(final MPositionBean toClone) {
		super();

		latitude = toClone.getLatitude();
		longitude = toClone.getLongitude();
		city = toClone.getCity();
		zipCode = toClone.getZipCode();
		country = toClone.getCountry();
		formattedAddress = toClone.getFormattedAddress();
	}
	
	@Override
	public MPositionBean clone() {
		return new MPositionBean(this);
	}

	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	/**
	 * @see java.lang.Object#equals()
	 */
	@Override
	public boolean equals(final Object object) {

		boolean equal = false;

		if (this == object) {
			equal = true;
		} else if (object instanceof MPositionBean) {
			final MPositionBean comparable = (MPositionBean) object;

			/*
			 * We compare only a subsets of the field to check that two
			 * MUserBean objects are the same. These should be values that are
			 * set for sure, and not null.
			 */
			equal = true;

			if (latitude == null && comparable.getLatitude() != null) {
				equal &= false;
			} else {
				equal &= latitude.equals(comparable.getLatitude());
			}

			if (longitude == null && comparable.getLongitude() != null) {
				equal &= false;
			} else {
				equal &= longitude.equals(comparable.getLongitude());
			}

		}

		return equal;
	}

	/**
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		int result = 1;
		return result;
	}

	@Override
	public String toString() {
		return "Position:\n" + super.toString();
	}

	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	public String getUserID() {
		return userID;
	}

	public void setUserID(String id) {
		this.userID = id;
	}
	
	public String getLatitude() {
		return latitude;
	}

	public void setLatitude(String latitude) {
		this.latitude = latitude;
	}

	public String getLongitude() {
		return longitude;
	}

	public void setLongitude(String longitude) {
		this.longitude = longitude;
	}

	public String getCity() {
		return city;
	}

	public void setCity(String city) {
		this.city = city;
	}

	public String getZipCode() {
		return zipCode;
	}

	public void setZipCode(String zipCode) {
		this.zipCode = zipCode;
	}

	public String getCountry() {
		return country;
	}

	public void setCountry(String country) {
		this.country = country;
	}

	public String getFormattedAddress() {
		return formattedAddress;
	}

	public void setFormattedAddress(String formattedAddress) {
		this.formattedAddress = formattedAddress;
	}
	
}
