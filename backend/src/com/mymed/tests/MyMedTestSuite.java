package com.mymed.tests;

import org.junit.runner.RunWith;
import org.junit.runners.Suite;
import org.junit.runners.Suite.SuiteClasses;

@RunWith(Suite.class)
@SuiteClasses({ProfileManagerTest.class, ReputationManagerTest.class, SessionManagerTest.class,
        StorageManagerTest.class})
public class MyMedTestSuite {

}
