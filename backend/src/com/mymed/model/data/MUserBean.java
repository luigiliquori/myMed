package com.mymed.model.data;

/**
 * This class represent an user profile
 * 
 * @author lvanni
 */
public class MUserBean extends AbstractMBean {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** User data structure */
	private String mymedID = null;			// Unique Identifier of the user in myMed
	private String socialNetworkID = null;			// ID of the user in the social network
	private String socialNetworkName = null;		// Name of the social network used by the user
	private String name = null;
	private String firstName = null;
	private String lastName = null;
	private String link = null;						// the profile manager url
	private String birthday = null;
	private String hometown = null;
	private String gender = null;
	private String email = null;
	private String profilePicture = null;
	private String buddyListID = null;	
	private String subscribtionListID = null;
	private String sessionID = null;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public MUserBean() {
		// TODO Auto-generated constructor stub
	}

	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	@Override
	public String toString() {
		return "User:\n" + super.toString();
	}

	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	public String getMymedID() {
		return mymedID;
	}

	public void setMymedID(String mymedID) {
		this.mymedID = mymedID;
	}

	public String getSocialNetworkID() {
		return socialNetworkID;
	}

	public void setSocialNetworkID(String socialNetworkID) {
		this.socialNetworkID = socialNetworkID;
	}

	public String getSocialNetworkName() {
		return socialNetworkName;
	}

	public void setSocialNetworkName(String socialNetworkName) {
		this.socialNetworkName = socialNetworkName;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getFirstName() {
		return firstName;
	}

	public void setFirstName(String firstName) {
		this.firstName = firstName;
	}

	public String getLastName() {
		return lastName;
	}

	public void setLastName(String lastName) {
		this.lastName = lastName;
	}

	public String getLink() {
		return link;
	}

	public void setLink(String link) {
		this.link = link;
	}

	public String getBirthday() {
		return birthday;
	}

	public void setBirthday(String birthday) {
		this.birthday = birthday;
	}

	public String getHometown() {
		return hometown;
	}

	public void setHometown(String hometown) {
		this.hometown = hometown;
	}

	public String getGender() {
		return gender;
	}

	public void setGender(String gender) {
		this.gender = gender;
	}

	public String getProfilePicture() {
		return profilePicture;
	}

	public void setProfilePicture(String profilePicture) {
		this.profilePicture = profilePicture;
	}

	public String getBuddyListID() {
		return buddyListID;
	}

	public void setBuddyListID(String buddyList) {
		this.buddyListID = buddyList;
	}

	public String getSubscribtionListID() {
		return subscribtionListID;
	}

	public void setSubscribtionListID(String subscribtionList) {
		this.subscribtionListID = subscribtionList;
	}

	public String getSessionID() {
		return sessionID;
	}

	public void setSessionID(String session) {
		this.sessionID = session;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}
}
