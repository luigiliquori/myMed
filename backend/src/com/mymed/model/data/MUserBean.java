package com.mymed.model.data;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Field;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.InternalBackEndException;

/**
 * This class represent an user profile
 * 
 * @author lvanni
 */
public class MUserBean extends AbstractMBean{

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
	private String profilePicture = null;
	private String buddyList = null;	
	private String subscribtionList = null;
	private String reputation = null;
	private String session = null;
	private String transactionList = null;
	private String email = null;
	private String password = null;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * no-args constructor
	 */
	public MUserBean() {
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * @throws InternalBackEndException 
	 * @see com.mymed.model.data.AbstractMBean#getAttributeToMap()
	 */
	public Map<String, byte[]> getAttributeToMap() throws InternalBackEndException {
		Map<String, byte[]> args = new HashMap<String, byte[]>();
		for (Field f : this.getClass().getDeclaredFields()) {
			try {
				if (f.get(this) instanceof String){
					args.put(f.getName(), ((String) f.get(this)).getBytes("UTF8"));
				}
			} catch (UnsupportedEncodingException e) {
				throw new InternalBackEndException("getAttribueToMap failed!: Introspection error");
			} catch (IllegalArgumentException e) {
				throw new InternalBackEndException("getAttribueToMap failed!: Introspection error");
			} catch (IllegalAccessException e) {
				throw new InternalBackEndException("getAttribueToMap failed!: Introspection error");
			}
		}
		return args;
	}

	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	public String toString() {
		String value = "User:\n";
		for (Field f : this.getClass().getDeclaredFields()) {
			try {
				if (f.get(this) instanceof String){
					value += "\t" + f.getName() + " : " + (String) f.get(this) + "\n";
				} else {
					value += "\t" + f.getName() + " : " + f.get(this) + "\n";
				}
			} catch (IllegalArgumentException e) {
				e.printStackTrace();
			} catch (IllegalAccessException e) {
				e.printStackTrace();
			}
		}
		return value;
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

	public String getTransactionList() {
		return transactionList;
	}

	public void setTransactionList(String transactionList) {
		this.transactionList = transactionList;
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
