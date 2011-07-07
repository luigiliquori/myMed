package com.mymed.model.data;

/**
 * This class represent an user profile
 * 
 * @author lvanni
 */
public final class MUserBean extends AbstractMBean {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
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
	public boolean equals(final Object object) {

		boolean returnValue = true;

		if (!(object instanceof MUserBean)) {
			returnValue = false;
		} else {
			final MUserBean comparable = (MUserBean) object;

			/*
			 * We compare only a subsets of the field to check that two
			 * MUserBean objects are the same
			 */
			returnValue &= getBirthday() == comparable.getBirthday();
			returnValue &= getEmail().equals(comparable.getEmail());
			returnValue &= getFirstName().equals(comparable.getFirstName());
			returnValue &= getGender().equals(comparable.getGender());
			returnValue &= getLastName().equals(comparable.getLastName());
			returnValue &= getId().equals(comparable.getId());
			returnValue &= getName().equals(comparable.getName());
		}

		return returnValue;
	}


	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
	@Override
	public String toString() {
		return "User:\n" + super.toString();
	}

	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}

	public String getLogin() {
		return login;
	}

	public void setLogin(String login) {
		this.login = login;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
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

	public long getBirthday() {
		return birthday;
	}

	public void setBirthday(long birthday) {
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

	public long getLastConnection() {
		return lastConnection;
	}

	public void setLastConnection(long lastConnection) {
		this.lastConnection = lastConnection;
	}

	public String getBuddyList() {
		return buddyList;
	}

	public void setBuddyList(String buddyList) {
		this.buddyList = buddyList;
	}

	public String getSubscribtionList() {
		return subscribtionList;
	}

	public void setSubscribtionList(String subscribtionList) {
		this.subscribtionList = subscribtionList;
	}

	public String getReputation() {
		return reputation;
	}

	public void setReputation(String reputation) {
		this.reputation = reputation;
	}

	public String getSession() {
		return session;
	}

	public void setSession(String session) {
		this.session = session;
	}

	public String getInteractionList() {
		return interactionList;
	}

	public void setInteractionList(String interactionList) {
		this.interactionList = interactionList;
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
}
