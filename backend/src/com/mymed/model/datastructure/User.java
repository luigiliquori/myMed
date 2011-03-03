package com.mymed.model.datastructure;

/**
 * This class represent an user profile
 * @author lvanni
 */
public class User {

	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	/** User data structure */
	private String id;
	private String name;
	private String gender;
	private String locale;
	private String updated_time;
	private String profile;
	private String profile_picture;
	private String social_network;
	// for mymed profile
	private String email;
	private String password;
	
	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
	/**
	 * no-args constructor
	 */
	public User() { }

	/**
	 * Constructor for a mymed profile
	 * @param id
	 * @param name
	 * @param gender
	 * @param locale
	 * @param updated_time
	 * @param profile
	 * @param profile_picture
	 * @param social_network
	 * @param email
	 * @param password
	 */
	public User(String id, String name, String gender, String locale,
			String updated_time, String profile, String profile_picture,
			String social_network, String email, String password) {
		this.id = id;
		this.name = name;
		this.gender = gender;
		this.locale = locale;
		this.updated_time = updated_time;
		this.profile = profile;
		this.profile_picture = profile_picture;
		this.social_network = social_network;
		this.email = email;
		this.password = password;
	}
	
	/**
	 * Constructor for a no-mymed profile
	 * @param id
	 * @param name
	 * @param gender
	 * @param locale
	 * @param updated_time
	 * @param profile
	 * @param profile_picture
	 * @param social_network
	 */
	public User(String id, String name, String gender, String locale,
			String updated_time, String profile, String profile_picture,
			String social_network) {
		this(id, name, gender, locale, updated_time, profile, profile_picture, social_network, profile_picture, social_network);
		this.email = "";
		this.password = "";
	}

	/* --------------------------------------------------------- */
	/*                      Override methods                     */
	/* --------------------------------------------------------- */
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
		if (social_network.equals("myMed") && !id.equals("visiteur")) {
			value += "\temail: " + email + "\n";
			value += "\tpassword: " + password + "\n";
		}
		return value;
	}

	/* --------------------------------------------------------- */
	/* 						GETTER AND SETTER 					 */
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

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

}
