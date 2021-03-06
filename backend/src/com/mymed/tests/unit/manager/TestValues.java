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
package com.mymed.tests.unit.manager;

import java.math.BigInteger;
import java.nio.charset.Charset;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.util.Calendar;

import com.mymed.utils.MLogger;

/**
 * Simple class to hold static string values valid for all the tests
 * 
 * @author Milo Casagrande
 */
public class TestValues {
  protected static final String CONF_FILE = "conf/config.xml";
  protected static final String NAME = "username";
  protected static final String FIRST_NAME = "John";
  protected static final String LAST_NAME = "Carter";
  protected static final String FIRST_NAME_2 = "Tars";
  protected static final String LAST_NAME_2 = "Tarkas";
  protected static final String USER_TABLE = "User";
  protected static final String WRONG_USER_TABLE = "Users";
  protected static final String USER_ID = "userKey";
  protected static final String WRONG_USER_ID = "keyUser";
  protected static final String COLUMN_NAME = "name";
  protected static final String COLUMN_FIRSTNAME = "firstName";
  protected static final String COLUMN_LASTNAME = "lastName";
  protected static final String COLUMN_BIRTHDATE = "birthday";
  protected static final String WRONG_COLUMN_NAME = "wrong_name";

  protected static final String LOGIN = "usertest1";
  protected static final String LOGIN_2 = "usertest2";
  protected static final String EMAIL = "testUser@example.net";
  protected static final String EMAIL_2 = "newEmail@example.net";
  protected static final String LINK = "http://www.example.net";
  protected static final String HOMETOWN = "123456789.123454";
  protected static final String GENDER = "female";
  protected static final String BUDDY_LST_ID = "buddylist1";
  protected static final String SUBSCRIPTION_LST_ID = "subscription1";
  protected static final String REPUTATION_ID = "reputation1";
  protected static final String SESSION_ID = USER_ID + "_SESSION";
  protected static final String INTERACTION_LST_ID = "interaction1";
  protected static final String ACCESS_TOKEN = Long.toString(System.currentTimeMillis());

  protected static final String INTERACTION_ID = "interaction1";
  protected static final String APPLICATION_ID = "application1";
  protected static final String PRODUCER_ID = "producerKey";
  protected static final String CONSUMER_ID = "consumerKey";

  // GeoLocation manager values
  protected static final String ITEM_TYPE = "item1";
  protected static final int LATITUDE = 472000;
  protected static final int LONGITUDE = 155000;
  protected static final int EXPIRING_TIME = 0;
  protected static final int RADIUS = 50000;
  protected static final String GEO_VALUE = "simplevalue";

  protected static final String IP = "138.126.23.2"; // NOPMD

  protected static final Calendar CAL_INSTANCE = Calendar.getInstance();

  /**
   * Random instance to keep around.
   */
  private static final SecureRandom RANDOM = new SecureRandom();

  /**
   * Get the string representation of a RANDOM casual generated password
   * 
   * @return the SHA-256 in hex format of a RANDOM string
   */
  protected static String getRandomPwd() {
    final StringBuffer hex = new StringBuffer(100);

    try {
      final String randString = new BigInteger(130, RANDOM).toString(32);
      final MessageDigest digest = MessageDigest.getInstance("SHA-256");

      digest.update(randString.getBytes(Charset.forName("UTF-8")));
      final byte[] mdbytes = digest.digest();

      for (final byte b : mdbytes) {
        hex.append(Integer.toHexString(0xFF & b));
      }

      hex.trimToSize();
    } catch (final NoSuchAlgorithmException ex) {
      MLogger.getLogger().debug("Random password generator failed", ex.getCause());
    }

    return hex.toString();
  }
}
