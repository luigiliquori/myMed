package com.mymed.model.datastructure;

/**
 * Represent an myMed user
 * @author lvanni
 */
public class User {
	
	/** User data structure */
	private String id;
	private String name;
	private String gender;
	private String locale;
	private String updated_time;
	private String profile;
	private String profile_picture;
	private String social_network;
	
	/**
	 * no-args constructor
	 */
	public User() {}
	
	@Override
	public String toString() {
		String value = "User:";
		value += "\tid: " + id + "\n";
		value += "\tname: " + name + "\n";
		value += "\tgender: " + gender + "\n";
		value += "\tlocale: " + locale + "\n";
		value += "\tupdated_time: " + updated_time + "\n";
		value += "\tprofile: " + profile + "\n";
		value += "\tprofile_picture: " + profile_picture + "\n";
		value += "\tsocial_network: " + social_network + "\n";
		return value;
	}
	
	/* --------------------------------------------------------- */
	/*                  GETTER AND SETTER                        */
	/* --------------------------------------------------------- */
	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getGender() {
		return gender;
	}

	public void setGender(String gender) {
		this.gender = gender;
	}

	public String getLocale() {
		return locale;
	}

	public void setLocale(String locale) {
		this.locale = locale;
	}

	public String getUpdated_time() {
		return updated_time;
	}

	public void setUpdated_time(String updatedTime) {
		updated_time = updatedTime;
	}

	public String getProfile() {
		return profile;
	}

	public void setProfile(String profile) {
		this.profile = profile;
	}

	public String getProfile_picture() {
		return profile_picture;
	}

	public void setProfile_picture(String profilePicture) {
		profile_picture = profilePicture;
	}

	public String getSocial_network() {
		return social_network;
	}

	public void setSocial_network(String socialNetwork) {
		this.social_network = socialNetwork;
	}

}
