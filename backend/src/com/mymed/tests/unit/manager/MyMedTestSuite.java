package com.mymed.tests.unit.manager;

import org.junit.runner.RunWith;
import org.junit.runners.Suite;
import org.junit.runners.Suite.SuiteClasses;


@RunWith(Suite.class)
@SuiteClasses({StorageManagerTest.class, AuthenticationManagerTest.class, ProfileManagerTest.class,
    SessionManagerTest.class, InteractionManagerTest.class})
public class MyMedTestSuite {

}
