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
package com.mymed.tests.stress;

import java.util.LinkedList;
import java.util.List;

import ch.qos.logback.classic.Logger;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.data.AbstractMBean;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MLogger;

/**
 * This is the class that implements the thread that is executed for the
 * Authentication test. Here we have only one thread since there is no method to
 * remove an authentication entry form the database
 * 
 * @author Milo Casagrande
 * 
 */
public class AuthenticationThread extends Thread {

  private final List<AbstractMBean[]> authList = new LinkedList<AbstractMBean[]>();
  private final Thread createAuthentication;

  private static final Logger LOGGER = MLogger.getLogger();

  public AuthenticationThread() {
    this(StressTestValues.NUMBER_OF_ELEMENTS);
  }

  public AuthenticationThread(final int maxElements) {
    super();

    final AuthenticationTest authTest = new AuthenticationTest(maxElements);

    createAuthentication = new Thread("createAuthentication") {
      @Override
      public void run() {
        LOGGER.info("Starting thread '{}'", getName());

        while (authList.isEmpty()) {
          final AbstractMBean[] beanArray = authTest.createAuthenticationBean();

          if (beanArray == null) {
            interrupt();
            break;
          }

          authList.add(beanArray);

          final MAuthenticationBean authBean = (MAuthenticationBean) beanArray[0];
          final MUserBean userBean = (MUserBean) beanArray[1];

          try {
            authTest.createAuthentication(userBean, authBean);
          } catch (final InternalBackEndException ex) {
            interrupt();
            LOGGER.info("Thread '{}' interrupted", getName());
            break;
          }

          ((LinkedList<AbstractMBean[]>) authList).pop();
        }

        LOGGER.info("Thread '{}' completed", getName());
      }
    };
  }

  @Override
  public void run() {
    createAuthentication.start();
  }
}
