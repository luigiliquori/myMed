/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.mymed.controller.core.manager.reputation.primary_key;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

import java.util.ArrayList;

import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;

import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedAppId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedAppUserId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedUserId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.ReputationRole;

/**
 *
 * @author peter
 */
public class RepIdTest {

    public RepIdTest() {
    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    @Before
    public void setUp() {
    }

    @After
    public void tearDown() {
    }

    /**
     * Test of MakeAppSpecificRepId method, of class RepId.
     */
    @Test
    public void testAppId() {
        System.out.println("testAppId");
        String appId = "1";
        String appUniqueId = "foo";
        String expPrimaryResult = "01|1|foo";
        String appIdString = "01|1";
        MymedAppId aid = new MymedAppId(appId, appUniqueId);
        assertEquals(expPrimaryResult, aid.getPrimaryId());
        ArrayList ids = aid.getEntityIds();
        assertEquals(2, ids.size());
        assertTrue(ids.contains(appIdString));
    }

    @Test
    public void testUserId() {
        System.out.println("testUserId");
        String userId = "Daenerys";
        String expPrimaryResult = "02|Daenerys";
        MymedUserId uid = new MymedUserId(userId);
        assertEquals(expPrimaryResult, uid.getPrimaryId());
        ArrayList ids = uid.getEntityIds();
        assertEquals(1, ids.size());
    }

    @Test
    public void testAppUserId() {
        System.out.println("testAppUserId");
        String appId = "87";
        String userId = "Daenerys";
        String expPrimaryResult = "00|87|Daenerys";
        String expAppId = "01|87";
        String expUserId = "02|Daenerys";
        MymedAppUserId auid = new MymedAppUserId(appId, userId);
        assertEquals(expPrimaryResult, auid.getPrimaryId());
        ArrayList ids = auid.getEntityIds();
        assertEquals(3, ids.size());
        assertTrue(ids.contains(expAppId));
        assertTrue(ids.contains(expUserId));
    }

    @Test
    public void testAppUserIdWithRole() {
        System.out.println("testAppUserIdWithRole");
        String appId = "87";
        String userId = "Daenerys";
        String expPrimaryResult = "00|87|Daenerys|c";
        String expAppId = "01|87";
        String expUserId = "02|Daenerys";
        String expAppUserId = "00|87|Daenerys";
        MymedAppUserId auid = new MymedAppUserId(appId, userId, ReputationRole.Consumer);
        assertEquals(expPrimaryResult, auid.getPrimaryId());
        ArrayList ids = auid.getEntityIds();
        assertEquals(4, ids.size());
        assertTrue(ids.contains(expAppId));
        assertTrue(ids.contains(expUserId));
        assertTrue(ids.contains(expAppUserId));

        // again, with producer
        expPrimaryResult = "00|87|Daenerys|p";
        auid = new MymedAppUserId(appId, userId, ReputationRole.Producer);
        assertEquals(expPrimaryResult, auid.getPrimaryId());
        ids = auid.getEntityIds();
        assertEquals(4, ids.size());
        assertTrue(ids.contains(expAppId));
        assertTrue(ids.contains(expUserId));
        assertTrue(ids.contains(expAppUserId));
    }

    @Test
    public void testAppUserIdWithRoleAppSpecific() {
        System.out.println("testAppUserIdWithRoleAppSpecific");
        String appId = "87";
        String userId = "Daenerys";
        ReputationRole role = ReputationRole.Producer;
        String userSpecific = "IT-EN";
        String expPrimaryResult = "00|87|Daenerys|p|IT-EN";
        String expAppUserRoleId = "00|87|Daenerys|p";
        String expAppId = "01|87";
        String expUserId = "02|Daenerys";
        String expAppUserId = "00|87|Daenerys";
        MymedAppUserId auid = new MymedAppUserId(appId, userId, role, userSpecific);
        assertEquals(expPrimaryResult, auid.getPrimaryId());
        ArrayList ids = auid.getEntityIds();
        assertEquals(5, ids.size());
        assertTrue(ids.contains(expAppId));
        assertTrue(ids.contains(expUserId));
        assertTrue(ids.contains(expAppUserId));
        assertTrue(ids.contains(expAppUserRoleId));

    }

}