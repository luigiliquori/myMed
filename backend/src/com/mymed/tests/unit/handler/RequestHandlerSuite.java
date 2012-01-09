package com.mymed.tests.unit.handler;

import org.junit.runner.RunWith;
import org.junit.runners.Suite;
import org.junit.runners.Suite.SuiteClasses;

@RunWith(Suite.class)
@SuiteClasses({ProfileRequestHandlerTest.class, AuthenticationRequestHandlerTest.class, SessionRequestHandlerTest.class})
public class RequestHandlerSuite {

}
