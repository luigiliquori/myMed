package com.mymed.tests;

import org.junit.runner.RunWith;
import org.junit.runners.Suite;
import org.junit.runners.Suite.SuiteClasses;

@RunWith(Suite.class)
@SuiteClasses({StorageManagerTest.class, ProfileManagerTest.class, ReputationManagerTest.class,
        SessionManagerTest.class, InteractionManagerTest.class})
public class MyMedTestSuite {

}
