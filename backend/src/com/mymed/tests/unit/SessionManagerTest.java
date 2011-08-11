package com.mymed.tests.unit;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;
import static org.junit.Assert.fail;

import java.util.Map;

import org.junit.Test;

import com.mymed.model.data.session.MSessionBean;

/**
 * Test class for the {@link SessionManager}.
 * 
 * @author Milo Casagrande
 */
public class SessionManagerTest extends GeneralTest {

	@Test
	public void testCreateSession() {
		try {
			profileManager.create(userBean);
			sessionManager.create(USER_ID, IP);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	@Test
	public void testReadSession() {
		try {
			final MSessionBean readSession = sessionManager.read(USER_ID);
			assertEquals("The sessions beans are not the same\n", sessionBean, readSession);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	@Test
	public void testUpdateSession() {
		try {
			sessionBean.setP2P(true);

			sessionManager.update(sessionBean);
			final MSessionBean readSession = sessionManager.read(USER_ID);
			assertTrue("The session bean has not been updated correctly", readSession.isP2P());
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	@Test
	public void testDeleteSession() {
		try {
			sessionManager.delete(USER_ID);
			final Map<byte[], byte[]> column = storageManager.selectAll("Session", USER_ID + "_SESSION");
			assertTrue("The number of columns after a delete is not 0", column.isEmpty());
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}
}
