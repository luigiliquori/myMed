package com.mymed.tests;

import static org.junit.Assert.assertArrayEquals;
import static org.junit.Assert.assertSame;
import static org.junit.Assert.assertTrue;
import static org.junit.Assert.fail;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.exception.ServiceManagerException;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.utils.MConverter;

/**
 * Test class for the {@link StorageManager}.
 * 
 * @author Milo Casagrande
 * 
 */
public class StorageManagerTest extends TestValues {

	private static final int INSERTS = 4;

	private static byte[] name;
	private static byte[] firstName;
	private static byte[] lastName;
	private static byte[] birthDate;

	private StorageManager storageManager;

	@BeforeClass
	public static void setUpOnce() {
		try {
			name = MConverter.stringToByteBuffer(NAME).array();
			firstName = MConverter.stringToByteBuffer(FIRST_NAME).array();
			lastName = MConverter.stringToByteBuffer(LAST_NAME).array();
		} catch (final InternalBackEndException ex) {
			fail(ex.getMessage());
		}

		CAL_INSTANCE.set(1971, 1, 1);
		birthDate = MConverter.longToByteBuffer(CAL_INSTANCE.getTimeInMillis()).array();
	}

	@Before
	public void setUp() throws InternalBackEndException {
		storageManager = new StorageManager(new WrapperConfiguration(new File(CONF_FILE)));
	}

	@After
	public void cleanUp() {
		storageManager = null;
	}

	/**
	 * Method used at the end of all the tests. Remove all the columns inserted
	 * 
	 * @throws InternalBackEndException
	 * @throws ServiceManagerException
	 */
	@AfterClass
	public static void end() throws InternalBackEndException, ServiceManagerException {
		final StorageManager manager = new StorageManager(new WrapperConfiguration(new File(CONF_FILE)));
		manager.removeAll(TABLE_NAME, KEY);
	}

	/**
	 * Perform an insert into a wrong table name.
	 * <p>
	 * The expected result is to throw an {@link InternalBackEndException}
	 * 
	 * @throws InternalBackEndException
	 */
	@Test(expected = InternalBackEndException.class)
	public void testInsertColumnWrong() throws InternalBackEndException {
		storageManager.insertColumn(WRONG_TABLE_NAME, KEY, COLUMN_NAME, name);
	}

	/**
	 * Insert a new key with some values into the table 'User'.
	 * <p>
	 * The expected result is the normal execution of the program
	 */
	@Test
	public void testInsertColumn() {
		try {
			storageManager.insertColumn(TABLE_NAME, KEY, COLUMN_NAME, name);
			storageManager.insertColumn(TABLE_NAME, KEY, COLUMN_FIRSTNAME, firstName);
			storageManager.insertColumn(TABLE_NAME, KEY, COLUMN_LASTNAME, lastName);
			storageManager.insertColumn(TABLE_NAME, KEY, COLUMN_BIRTHDATE, birthDate);
		} catch (final InternalBackEndException ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Remove all columns with the specified key
	 */
	@Test
	public void testRemoveAll() {
		try {
			storageManager.removeAll(TABLE_NAME, KEY);
			final Map<byte[], byte[]> column = storageManager.selectAll(TABLE_NAME, KEY);
			assertTrue("The number of columns after a removeAll is not 0", column.isEmpty());
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Perform an insert slice operation.
	 * <p>
	 * The expected behavior is the normal execution of the program
	 */
	@Test
	public void testInsertSlice() {
		try {
			final Map<String, byte[]> args = new HashMap<String, byte[]>();
			args.put(COLUMN_NAME, name);
			args.put(COLUMN_FIRSTNAME, firstName);
			args.put(COLUMN_LASTNAME, lastName);
			args.put(COLUMN_BIRTHDATE, birthDate);
			storageManager.insertSlice(TABLE_NAME, KEY, args);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Perform a selection of the values just inserted in the table 'User'.
	 * <p>
	 * The expected result is that the retrieved values equal the inserted ones
	 */
	@Test
	public void testSelectColumn() {
		try {
			byte[] returnValue = storageManager.selectColumn(TABLE_NAME, KEY, COLUMN_NAME);
			assertArrayEquals("The returned byte array (name) is not equal to the inserted one", name, returnValue);

			returnValue = storageManager.selectColumn(TABLE_NAME, KEY, COLUMN_FIRSTNAME);
			assertArrayEquals("The returned byte array (firstname) is not equal to the inserted one", firstName,
			        returnValue);

			returnValue = storageManager.selectColumn(TABLE_NAME, KEY, COLUMN_LASTNAME);
			assertArrayEquals("The returned byte array (lastname) is not equal to the inserted one", lastName,
			        returnValue);

			returnValue = storageManager.selectColumn(TABLE_NAME, KEY, COLUMN_BIRTHDATE);
			assertArrayEquals("The returned byte array (birthdate) is not equal to the inserted one", birthDate,
			        returnValue);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Perform a selection of the key just inserted, but in a wrong table.
	 * <p>
	 * The expected result is to throw an {@link InternalBackEndException}
	 * 
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	@Test(expected = InternalBackEndException.class)
	public void testSelectColumnWrong() throws InternalBackEndException, IOBackEndException {
		storageManager.selectColumn(WRONG_TABLE_NAME, KEY, COLUMN_NAME);
	}

	/**
	 * Perform a selection of a wrong key in the table 'User'.
	 * <p>
	 * The expected result is to throw an {@link IOBackEndException}
	 * 
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	@Test(expected = IOBackEndException.class)
	public void testSelectColumnWrong1() throws InternalBackEndException, IOBackEndException {
		storageManager.selectColumn(TABLE_NAME, WRONG_KEY, COLUMN_NAME);
	}

	/**
	 * Perform a selection of the key in the table 'User', but with a wrong
	 * column name.
	 * <p>
	 * The expected behavior is to throw an {@link IOBackEndException}
	 * 
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	@Test(expected = IOBackEndException.class)
	public void testSelectColumnWrong2() throws InternalBackEndException, IOBackEndException {
		storageManager.selectColumn(TABLE_NAME, KEY, WRONG_COLUMN_NAME);
	}

	/**
	 * Perform a {@code selectAll} and check that the number of retrieved values
	 * equals to the number of insertions
	 */
	@Test
	public void testSelectAll() {
		try {
			final Map<byte[], byte[]> columns = storageManager.selectAll(TABLE_NAME, KEY);
			assertSame("The number of retrived columns is not equal to the inserted one", INSERTS, columns.size());
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Perform a {@code selectRange} and check that the number of retrieved
	 * values equals to the one of the selected columns (in this case 2)
	 */
	@Test
	public void testSelectRange() {
		try {
			final List<String> columnNames = new ArrayList<String>(2);
			columnNames.add(COLUMN_NAME);
			columnNames.add(COLUMN_BIRTHDATE);

			final Map<byte[], byte[]> column = storageManager.selectRange(TABLE_NAME, KEY, columnNames);
			assertSame("The number of retrieved columns is wrong", 2, column.size());
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Perform a {@code selectRange} and check that the number of retrieved
	 * values equals to the one of the selected columns: in this case 1 since we
	 * ask for a column name that does not exists
	 */
	@Test
	public void testSelectRangeWrong() {
		try {
			final List<String> columnNames = new ArrayList<String>(2);
			columnNames.add(COLUMN_NAME);
			columnNames.add(WRONG_COLUMN_NAME);

			final Map<byte[], byte[]> column = storageManager.selectRange(TABLE_NAME, KEY, columnNames);
			assertSame("The number of retrieved columns is wrong", 1, column.size());
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Remove a column and check that the total columns is equals to the old
	 * number - 1
	 */
	@Test
	public void testRemoveColumn() {
		try {
			storageManager.removeColumn(TABLE_NAME, KEY, COLUMN_NAME);

			final Map<byte[], byte[]> column = storageManager.selectAll(TABLE_NAME, KEY);
			assertSame("The number of columns after removing one is not correct", INSERTS - 1, column.size());
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}
}
