package com.mymed.tests;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.fail;

import org.junit.Test;

import com.mymed.controller.core.manager.reputation.ReputationManager;
import com.mymed.model.data.reputation.MReputationBean;

/**
 * Test class for the {@link ReputationManager}
 * 
 * @author Milo Casagrande
 * 
 */
public class ReputationManagerTest extends GeneralTest {

	private final double feedback = 2;

	/**
	 * Perform an update operation
	 */
	@Test
	public void testUpdateReputation() {
		try {
			reputationManager.update(interactionBean, feedback);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	/**
	 * Read the reputation back from the database
	 */
	@Test
	public void testReadReputation() {
		try {
			final MReputationBean beanRead = reputationManager.read(PRODUCER_ID, CONSUMER_ID, APPLICATION_ID);
			assertEquals("The reputation beans are not the same\n", feedback, beanRead.getValue(), 0);
		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}
}
