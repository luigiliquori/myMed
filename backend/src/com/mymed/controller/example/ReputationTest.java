package com.mymed.controller.example;

import java.io.File;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.manager.IStorageManager;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.reputation.InteractionManager;
import com.mymed.controller.core.manager.reputation.InteractionsManager;
import com.mymed.controller.core.manager.reputation.ReputationManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.MConverter;
import com.mymed.model.data.MInteractionBean;
import com.mymed.model.data.MReputationBean;
import com.mymed.model.data.MUserBean;

public class ReputationTest {

	private static MUserBean producer1;
	private static MUserBean consumer1;

	static {
		consumer1 = new MUserBean();
		consumer1.setName("goofy");
		consumer1.setMymedID("cons1");

		producer1 = new MUserBean();
		producer1.setName("paperone");
		producer1.setMymedID("prod1");
	}

	private static String applicationID = "myMountain";

	public static void main(final String[] args) {
		try {
			final WrapperConfiguration config = new WrapperConfiguration(new File(
			        "/Users/lvanni/Documents/workspace/mymed/backend/conf/config.xml"));

			final IStorageManager storageManager = new StorageManager(config);

			final ProfileManager profileManager = new ProfileManager();
			profileManager.create(producer1);
			profileManager.create(consumer1);

			final ReputationManager repManager = new ReputationManager();

			final Map<String, byte[]> arguments = new HashMap<String, byte[]>();
			arguments.put("value", MConverter.doubleToByteBuffer(0.5).array());
			arguments.put("nbRaters", MConverter.intToByteBuffer(1).array());

			storageManager.insertSlice("Reputation", producer1.getMymedID() + applicationID, arguments);

			MReputationBean repBean = repManager.read(producer1.getMymedID(), consumer1.getMymedID(), applicationID);

			System.out.println("Reputation of " + producer1.getName() + " is: " + repBean.getValue());

			final InteractionManager intManager = new InteractionManager();
			// IDEA: implement a reset method in the bean that clears all the
			// fields? So that we can reuse the same object
			final MInteractionBean intBean = new MInteractionBean();
			intBean.setApplicationID(applicationID);
			intBean.setConsumerID(consumer1.getMymedID());
			intBean.setProducerID(producer1.getMymedID());
			intBean.setInteractionID("int1");

			final long end = System.currentTimeMillis() + 1000000;

			intBean.setEnd(end);

			System.err.println("\nCreating the interaction...");
			intManager.create(intBean);

			final InteractionsManager interactionListManager = new InteractionsManager();
			interactionListManager.update(intBean);

			System.err.println("Setting new reputation value...");
			repManager.update(intBean, 0.6);

			repBean = repManager.read(producer1.getMymedID(), consumer1.getMymedID(), applicationID);
			System.out.println("\nThe new reputation of " + producer1.getName() + " is: " + repBean.getValue());
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}
}
