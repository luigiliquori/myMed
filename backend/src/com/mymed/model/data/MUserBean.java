package com.mymed.model.data;

/**
 * This class represent an user profile
 * 
 * @author lvanni
 */
public final class MUserBean extends AbstractMBean {

	/** USER_ID */
	private String id = null;
	/** AUTHENTICATION_ID */
	private String login = null;
	private String email = null;
	private String name = null;
	private String firstName = null;
	private String lastName = null;
	private String link = null;
	private long birthday;
	private String hometown = null;
	private String gender = null;
	private long lastConnection;
	/** USER_LIST_ID */
	private String buddyList = null;
	/** APPLICATION_LIST_ID */
	private String subscribtionList = null;
	/** REPUTATION_ID */
	private String reputation = null;
	/** SESSION_ID || null */
	private String session = null;
	/** INTERACTION_LIST_ID */
	private String interactionList = null;
	private String socialNetworkID = null;
	private String socialNetworkName = null;

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#equals()
	 */
	@Override
	public boolean equals(final Object object) {

		boolean returnValue = true;

		if (object instanceof MUserBean) {
			final MUserBean comparable = (MUserBean) object;

			/*
			 * We compare only a subsets of the field to check that two
			 * MUserBean objects are the same. These should be values that are
			 * set for sure, and not null.
			 */
			returnValue &= getEmail().equals(comparable.getEmail());
			returnValue &= getFirstName().equals(comparable.getFirstName());
			returnValue &= getLastName().equals(comparable.getLastName());
			returnValue &= getId().equals(comparable.getId());
			returnValue &= getName().equals(comparable.getName());
		} else {
			returnValue = false;
		}

		return returnValue;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		final int prime = 31;
		int result = 1;

		result = prime * result + (int) (birthday ^ birthday >>> 32);
		result = prime * result + (email == null ? 0 : email.hashCode());
		result = prime * result + (firstName == null ? 0 : firstName.hashCode());
		result = prime * result + (gender == null ? 0 : gender.hashCode());
		result = prime * result + (id == null ? 0 : id.hashCode());
		result = prime * result + (lastName == null ? 0 : lastName.hashCode());
		result = prime * result + (name == null ? 0 : name.hashCode());

		return result;
	}

	@Override
	public String toString() {
		return "User:\n" + super.toString();
	}

	/**
	 * @return the id
	 */
	public String getId() {
		return id;
	}

	/**
	 * @param id
	 *            the id to set
	 */
	public void setId(final String id) {
		this.id = id;
	}

	/**
	 * @return the login
	 */
	public String getLogin() {
		return login;
	}

	/**
	 * @param login
	 *            the login to set
	 */
	public void setLogin(final String login) {
		this.login = login;
	}

	/**
	 * @return the email
	 */
	public String getEmail() {
		return email;
	}

	/**
	 * @param email
	 *            the email to set
	 */
	public void setEmail(final String email) {
		this.email = email;
	}

	/**
	 * @return the name
	 */
	public String getName() {
		return name;
	}

	/**
	 * @param name
	 *            the name to set
	 */
	public void setName(final String name) {
		this.name = name;
	}

	/**
	 * @return the firstName
	 */
	public String getFirstName() {
		return firstName;
	}

	/**
	 * @param firstName
	 *            the firstName to set
	 */
	public void setFirstName(final String firstName) {
		this.firstName = firstName;
	}

	/**
	 * @return the lastName
	 */
	public String getLastName() {
		return lastName;
	}

	/**
	 * @param lastName
	 *            the lastName to set
	 */
	public void setLastName(final String lastName) {
		this.lastName = lastName;
	}

	/**
	 * @return the link
	 */
	public String getLink() {
		return link;
	}

	/**
	 * @param link
	 *            the link to set
	 */
	public void setLink(final String link) {
		this.link = link;
	}

	/**
	 * @return the birthday
	 */
	public long getBirthday() {
		return birthday;
	}

	/**
	 * @param birthday
	 *            the birthday to set
	 */
	public void setBirthday(final long birthday) {
		this.birthday = birthday;
	}

	/**
	 * @return the hometown
	 */
	public String getHometown() {
		return hometown;
	}

	/**
	 * @param hometown
	 *            the hometown to set
	 */
	public void setHometown(final String hometown) {
		this.hometown = hometown;
	}

	/**
	 * @return the gender
	 */
	public String getGender() {
		return gender;
	}

	/**
	 * @param gender
	 *            the gender to set
	 */
	public void setGender(final String gender) {
		this.gender = gender;
	}

	/**
	 * @return the lastConnection
	 */
	public long getLastConnection() {
		return lastConnection;
	}

	/**
	 * @param lastConnection
	 *            the lastConnection to set
	 */
	public void setLastConnection(final long lastConnection) {
		this.lastConnection = lastConnection;
	}

	/**
	 * @return the buddyList
	 */
	public String getBuddyList() {
		return buddyList;
	}

	/**
	 * @param buddyList
	 *            the buddyList to set
	 */
	public void setBuddyList(final String buddyList) {
		this.buddyList = buddyList;
	}

	/**
	 * @return the subscriptionList
	 */
	public String getSubscribtionList() {
		return subscribtionList;
	}

	/**
	 * @param subscribtionList
	 *            the subscriptionList to set
	 */
	public void setSubscribtionList(final String subscribtionList) {
		this.subscribtionList = subscribtionList;
	}

	/**
	 * @return the reputation
	 */
	public String getReputation() {
		return reputation;
	}

	/**
	 * @param reputation
	 *            the reputation to set
	 */
	public void setReputation(final String reputation) {
		this.reputation = reputation;
	}

	/**
	 * @return the session
	 */
	public String getSession() {
		return session;
	}

	/**
	 * @param session
	 *            the session to set
	 */
	public void setSession(final String session) {
		this.session = session;
	}

	/**
	 * @return the interactionList
	 */
	public String getInteractionList() {
		return interactionList;
	}

	/**
	 * @param interactionList
	 *            the interactionList to set
	 */
	public void setInteractionList(final String interactionList) {
		this.interactionList = interactionList;
	}

	/**
	 * @return the socialNetworkID
	 */
	public String getSocialNetworkID() {
		return socialNetworkID;
	}

	/**
	 * @param socialNetworkID
	 *            the socialNetworkID to set
	 */
	public void setSocialNetworkID(final String socialNetworkID) {
		this.socialNetworkID = socialNetworkID;
	}

	/**
	 * @return the socialNetworkName
	 */
	public String getSocialNetworkName() {
		return socialNetworkName;
	}

	/**
	 * @param socialNetworkName
	 *            the socialNetworkName to set
	 */
	public void setSocialNetworkName(final String socialNetworkName) {
		this.socialNetworkName = socialNetworkName;
	}
}
