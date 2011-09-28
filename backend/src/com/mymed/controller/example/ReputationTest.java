package com.mymed.controller.example;

import java.io.File;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.manager.application.InteractionManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.reputation.old.ReputationManager;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.data.reputation.MInteractionBean;
import com.mymed.model.data.reputation.MReputationBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MConverter;

public class ReputationTest {

	private static MUserBean producer1;
	private static MUserBean consumer1;

	static {
		consumer1 = new MUserBean();
		consumer1.setName("goofy");
		consumer1.setId("cons1");

		producer1 = new MUserBean();
		producer1.setName("paperone");
		producer1.setId("prod1");
	}

	private static String applicationID = "myMountain";

	public static void main(final String[] args) {

		try {
			final WrapperConfiguration config = new WrapperConfiguration(new File("/local/mymed/backend/conf/config.xml"));

			final IStorageManager storageManager = new StorageManager(config);

			final ProfileManager profileManager = new ProfileManager(storageManager);
			profileManager.create(producer1);
			profileManager.create(consumer1);

			final ReputationManager repManager = new ReputationManager();

			final Map<String, byte[]> arguments = new HashMap<String, byte[]>();
			arguments.put("value", MConverter.doubleToByteBuffer(0.5).array());
			arguments.put("nbRaters", MConverter.intToByteBuffer(1).array());

			storageManager.insertSlice("Reputation", producer1.getId() + applicationID, arguments);

			MReputationBean repBean = repManager.read(producer1.getId(), consumer1.getId(), applicationID);

			System.out.println("Reputation of " + producer1.getName() + " is: " + repBean.getValue());

			final InteractionManager intManager = new InteractionManager();
			// IDEA: implement a reset method in the bean that clears all the
			// fields? So that we can reuse the same object
			final MInteractionBean intBean = new MInteractionBean();
			intBean.setApplication(applicationID);
			intBean.setConsumer(consumer1.getId());
			intBean.setProducer(producer1.getId());
			intBean.setId("int1");

			final long end = System.currentTimeMillis() + 1000000;

			intBean.setEnd(end);

			System.err.println("\nCreating the interaction...");
			intManager.create(intBean);

			// final InteractionListManager interactionListManager = new
			// InteractionListManager();
			// interactionListManager.update(intBean);

			System.err.println("Setting new reputation value...");
			repManager.update(repBean, producer1.getId() + applicationID);

			repBean = repManager.read(producer1.getId(), consumer1.getId(), applicationID);
			System.out.println("\nThe new reputation of " + producer1.getName() + " is: " + repBean.getValue());
		} catch (final Exception ex) {
			ex.printStackTrace();
		}
	}
}
