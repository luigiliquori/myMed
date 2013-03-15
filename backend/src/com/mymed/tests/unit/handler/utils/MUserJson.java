/*
 * Copyright 2012 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package com.mymed.tests.unit.handler.utils;

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
public enum MUserJson implements IJson {
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

  /*
   * (non-Javadoc)
   * 
   * @see com.mymed.tests.unit.handler.utils.IJson#get()
   */
  @Override
  public String get() {
    return element;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Enum#toString()
   */
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
      if (element.get().equals(str)) {
        isValid = true;
        break;
      }
    }

    return isValid;
  }
}
