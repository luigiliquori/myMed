package com.mymed.tests.unit.handler;

import java.util.EnumSet;

import com.mymed.model.data.user.MUserBean;

/**
 * This is the enum class that stores all the fields of a User JSON response.
 * <p>
 * The fields in here are the ones that can be found in the {@link MUserBean}
 * <p>
 * The format of the JSON message should be:<br>
 * <code>
   user {
    "id": String,
    "email": String,
    "name": String,
    "firstName": String,
    ...
  }
 * </code>
 * 
 * @author Milo Casagrande
 * 
 */
public enum MUserJson {
  ID("id"),
  LOGIN("login"),
  EMAIL("email"),
  NAME("name"),
  FIRSTNAME("firstName"),
  LASTNAME("lastName"),
  LINK("link"),
  BIRTHDAY("birthday"),
  HOMETOWN("hometown"),
  GENDER("gender"),
  PRIVATE("private"),
  LASTCONNECTION("lastConnection"),
  PROFILEPICTURE("profilePicture"),
  BUDDYLIST("buddyList"),
  SUBSCRIPTIONLIST("subscriptionList"),
  REPUTATION("reputation"),
  SESSION("session"),
  INTERACTIONLIST("interactionList"),
  SOCIALNETWORKID("socialNetworkID"),
  SOCIALNETWORKNAME("socialNetworkName");

  private String element;

  private MUserJson(final String element) {
    this.element = element;
  }

  @Override
  public String toString() {
    return element;
  }

  /**
   * Check if JSON element is valid for the JSON format
   * 
   * @param str
   *          the element to check
   * @return true if the element is valid, false otherwise
   */
  public static boolean isValidElement(final String str) {
    boolean isValid = false;

    for (final MUserJson element : EnumSet.allOf(MUserJson.class)) {
      if (element.toString().equals(str)) {
        isValid = true;
        break;
      }
    }

    return isValid;
  }
}
