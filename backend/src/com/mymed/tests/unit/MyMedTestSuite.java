package com.mymed.tests.unit;

import org.junit.runner.RunWith;
import org.junit.runners.Suite;
import org.junit.runners.Suite.SuiteClasses;

@RunWith(Suite.class)
@SuiteClasses({StorageManagerTest.class, ProfileManagerTest.class, ReputationManagerTest.class,
        SessionManagerTest.class, InteractionManagerTest.class, AuthenticationManagerTest.class})
public class MyMedTestSuite {

}
