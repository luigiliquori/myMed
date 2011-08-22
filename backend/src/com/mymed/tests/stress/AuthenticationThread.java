package com.mymed.tests.stress;

import java.util.LinkedList;

import com.mymed.model.data.AbstractMBean;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;

public class AuthenticationThread extends Thread {

	private final LinkedList<AbstractMBean[]> authList = new LinkedList<AbstractMBean[]>();
	private final AuthenticationTest authTest = new AuthenticationTest();
	private final Thread createAuthentication;

	public AuthenticationThread() {
		super();

		createAuthentication = new Thread() {
			@Override
			public void run() {
				System.err.println("Starting the create authentication thread...");

				while (authList.isEmpty()) {
					final AbstractMBean[] beanArray = authTest.createAuthenticationBean();

					if (beanArray == null) {
						break;
					}

					authList.add(beanArray);

					final MAuthenticationBean authBean = (MAuthenticationBean) beanArray[0];
					final MUserBean userBean = (MUserBean) beanArray[1];

					try {
						authTest.createAuthentication(userBean, authBean);
					} catch (final Exception ex) {
						ex.printStackTrace();
						interrupt();
						break;
					}

					authList.pop();
				}
			}
		};
	}

	@Override
	public void run() {
		createAuthentication.start();
	}
}
