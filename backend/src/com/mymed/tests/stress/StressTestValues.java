package com.mymed.tests.stress;

import java.io.UnsupportedEncodingException;
import java.math.BigInteger;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

/**
 * Simple class to hold values useful for all the stress tests
 * 
 * @author Milo Casagrande
 * 
 */
public class StressTestValues {
	/**
	 * The number of elements we want to insert
	 */
	protected static final int NUMBER_OF_ELEMENTS = 100;
	protected static final Calendar CAL_INSTANCE = Calendar.getInstance();
	protected static final String CONF_FILE = "conf/config.xml";

	protected static final String USR_LIST_ID = "usr_list_id_%d";
	protected static final String SUB_LIST_ID = "sub_list_id_%d";
	protected static final String FIRST_NAME = "first_name_%d";
	protected static final String EMAIL = "first_name_%s@example.org";
	protected static final String USR_ID = "usr_id_%d";
	protected static final String LOGIN = "usr_login_%d";
	protected static final String NAME = "usr_name_%d";
	protected static final String SESSION = "usr_session_%d";

	protected static final String IP_ADDRESS = "127.0.0.%d";

	protected static final String AUTH_ID = "usr_auth_id_%d";

	/**
	 * Random instance to keep around.
	 */
	protected static final SecureRandom random = new SecureRandom();

	private static final String FEMALE = "female";
	private static final String MALE = "male";

	/**
	 * 1899-01-01 00:00
	 */
	private static final long START_DATE = -2240528400000L;

	/**
	 * 2999-12-31 23:59
	 */
	private static final long END_DATE = 32503676380082L;

	/**
	 * Create a random date between January 1 1899, and December 31 3000.
	 * 
	 * @return the random date
	 */
	protected static String getRandomDate() {
		final long randDate = START_DATE + (long) (Math.random() * (END_DATE - START_DATE + 1));

		final Date date = new Date(randDate);
		final SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");

		return sdf.format(date.getTime());
	}

	/**
	 * @return a random chosen user gender
	 */
	protected static String getRandomGender() {
		final boolean rand = random.nextBoolean();

		if (rand) {
			return FEMALE;
		} else {
			return MALE;
		}
	}

	/**
	 * Get the string representation of a random casual generated password
	 * 
	 * @return the SHA-256 in hex format of a random string
	 * @throws NoSuchAlgorithmException
	 * @throws UnsupportedEncodingException
	 */
	protected static String getRandomPwd() throws NoSuchAlgorithmException, UnsupportedEncodingException {
		final String randString = new BigInteger(130, random).toString(32);

		final MessageDigest md = MessageDigest.getInstance("SHA-256");

		md.update(randString.getBytes("UTF-8"));
		final byte[] mdbytes = md.digest();

		final StringBuffer hex = new StringBuffer(100);
		for (final byte b : mdbytes) {
			hex.append(Integer.toHexString(0xFF & b));
		}

		hex.trimToSize();

		return hex.toString();
	}
}
