package com.mymed.tests;

import java.util.Calendar;

/**
 * Simple class to hold static string values valid for all the manager tests
 * 
 * @author Milo Casagrande
 */
public class TestValues {
	protected static final String CONF_FILE = "/local/mymed/backend/conf/config-new.xml";
	protected static final String NAME = "username";
	protected static final String FIRST_NAME = "First Name";
	protected static final String LAST_NAME = "Last Name";
	protected static final String TABLE_NAME = "User";
	protected static final String WRONG_TABLE_NAME = "Users";
	protected static final String KEY = "user1";
	protected static final String WRONG_KEY = "1user";
	protected static final String COLUMN_NAME = "name";
	protected static final String COLUMN_FIRSTNAME = "firstName";
	protected static final String COLUMN_LASTNAME = "lastName";
	protected static final String COLUMN_BIRTHDATE = "birthday";
	protected static final String WRONG_COLUMN_NAME = "name1";

	protected static final String LOGIN = "usertest1";
	protected static final String EMAIL = "testUser@example.net";
	protected static final String LINK = "http://www.example.net";
	protected static final String HOMETOWN = "123456789.123454";
	protected static final String GENDER = "female";
	protected static final String BUDDY_LST_ID = "buddylist1";
	protected static final String SUBSCRIPTION_LST_ID = "subscription1";
	protected static final String REPUTATION_ID = "reputation1";
	protected static final String SESSION_ID = "session1";
	protected static final String INTERACTION_LST_ID = "interaction1";

	protected static final String INTERACTION_ID = "interaction1";
	protected static final String APPLICATION_ID = "application1";
	protected static final String PRODUCER_ID = "producerKey";
	protected static final String CONSUMER_ID = "consumerKey";

	protected static final Calendar CAL_INSTANCE = Calendar.getInstance();
}
