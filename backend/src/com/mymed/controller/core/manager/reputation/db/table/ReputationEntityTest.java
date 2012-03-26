/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.mymed.controller.core.manager.reputation.db.table;

import com.mymed.controller.core.manager.reputation.db.facade.TransactionManager;
import java.util.Collection;
import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;
import static org.junit.Assert.*;

/**
 *
 * @author peter
 */
public class ReputationEntityTest {

    public ReputationEntityTest() {
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
     * Test of persist method, of class ReputationEntity.
     */
    @Test
    public void testReadAndWrite() throws Exception {
        System.out.println("persist");

        TransactionManager.getInstance().truncate("ReputationEntity");
        TransactionManager.getInstance().createTransaction();
        ReputationEntity instance = null;
        for (int i = 0; i < 10; i++) {
            instance = new ReputationEntity("uid_" + i);
            instance.setReputation(0.1 * i);
            instance.persist();
        }
        TransactionManager.getInstance().commit();
        // TODO NB without commit doesn't work, because they all point to the same object!
        for (int i = 0; i < 10; i++) {
            ReputationEntity i2 = instance.get("uid_" + i);
            System.out.println("id: " + i2.getUid() + " rep: " + i2.getReputation());
        }
        ReputationEntity i2 = instance.get("uid_3");
        assertEquals(0.3,i2.getReputation(),0.01);

        Collection<ReputationEntity> all = instance.getAll();
        assertEquals(10, all.size());
    }


}